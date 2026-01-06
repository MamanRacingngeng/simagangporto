<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermohonanMagang;
use App\Models\Dokumen;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CacheHelper;

class MagangController extends Controller
{
    /**
     * Sequence 2: Pengajuan Permohonan Magang
     * User -> Web: Buka halaman pengajuan magang
     * Web -> MagangController: Request form pengajuan
     * MagangController -> DB: Ambil data user
     * DB --> MagangController: Data ditemukan
     * MagangController --> Web: Kirim form pengajuan
     */
    public function showFormPengajuan()
    {
        $user = Auth::user();
        
        // Ambil data user dari database
        $userData = User::findOrFail($user->id);
        
        // Ambil data kuota dan jadwal (sesuai ERD: tidak ada field aktif)
        $kuota = KuotaMagang::all();
        $jadwal = JadwalMagang::all();
        
        // Cek apakah sudah ada permohonan dan dokumen
        $permohonan = PermohonanMagang::where('user_id', $user->id)->first();
        $dokumen = Dokumen::where('user_id', $user->id)->first();
        
        return view('magang.form_pengajuan', compact('userData', 'kuota', 'jadwal', 'permohonan', 'dokumen'));
    }

    /**
     * Sequence 2: Pengajuan Permohonan Magang (lanjutan)
     * User -> Web: Isi data + Upload dokumen
     * Web -> MagangController: Kirim data & dokumen
     * MagangController -> DB: Simpan permohonan
     * DB --> MagangController: OK
     * MagangController --> Web: Status = "Diajukan"
     * Web --> User: Pengajuan berhasil
     */
    public function storePengajuan(Request $request)
    {
        $request->validate([
            // Sesuai ERD: dokumen_id required (one-to-one dengan Dokumen)
            'dokumen_id' => 'required|exists:dokumen,id',
        ]);

        $user = Auth::user();

        // Cek apakah user bisa mendaftar (satu akun hanya 1 divisi, kecuali masa berlaku habis dan ditolak)
        $cekDaftar = PermohonanMagang::cekBisaDaftar($user->id);
        
        if (!$cekDaftar['bisa_daftar']) {
            return back()->withErrors([
                'error' => $cekDaftar['alasan']
            ])->withInput();
        }

        // Validasi dokumen lengkap
        $dokumen = Dokumen::findOrFail($request->dokumen_id);
        if (empty($dokumen->cv) || empty($dokumen->surat_pengantar) || empty($dokumen->proposal)) {
            return back()->withErrors([
                'error' => 'Dokumen belum lengkap. Pastikan CV, Surat Pengantar, dan Proposal sudah diunggah sebelum mengajukan permohonan.'
            ])->withInput();
        }

        try {
            // Simpan permohonan magang sesuai ERD
            $permohonan = PermohonanMagang::create([
                'user_id' => $user->id,
                'dokumen_id' => $request->dokumen_id, // Sesuai ERD: dokumen_id (FK)
                'tanggal_pengajuan' => now()->toDateString(), // Sesuai ERD: tanggal_pengajuan (date)
                'status' => 'Diajukan', // Sesuai ERD: status default "Diajukan"
            ]);
            
            // Jika ada kuota_id di request, hubungkan ke permohonan dan simpan backup
            if ($request->has('kuota_id')) {
                $permohonan->kuotaMagang()->attach($request->kuota_id);
                
                // Simpan backup informasi kuota/jadwal untuk riwayat meskipun kuota sudah dihapus
                $kuota = KuotaMagang::find($request->kuota_id);
                if ($kuota) {
                    // Cari jadwal yang sesuai dengan periode dan posisi
                    $jadwal = JadwalMagang::where('periode', $kuota->periode)
                        ->where(function($q) use ($kuota) {
                            $posisi = $kuota->posisi ?? 'Umum';
                            $q->where('posisi', $posisi)
                              ->orWhere(function($q2) use ($posisi) {
                                  $q2->whereNull('posisi')->where('posisi', 'Umum');
                              });
                        })
                        ->first();
                    
                    // Jika tidak ditemukan dengan posisi, coba cari hanya dengan periode
                    if (!$jadwal) {
                        $jadwal = JadwalMagang::where('periode', $kuota->periode)->first();
                    }
                    
                    $permohonan->update([
                        'periode_backup' => $kuota->periode,
                        'posisi_backup' => $kuota->posisi ?? 'Umum',
                        'tgl_mulai_backup' => $jadwal ? $jadwal->tgl_mulai : null,
                        'tgl_selesai_backup' => $jadwal ? $jadwal->tgl_selesai : null,
                    ]);
                }
            }
            
            // Cache will be cleared by Observer, but clear user cache too
            CacheHelper::clearUserCache($user->id);

            return redirect()->route('riwayat.lamaran')
                ->with('success', 'Permohonan magang berhasil diajukan. Status: Diajukan. Lihat detail di riwayat lamaran Anda.');
        } catch (\Exception $e) {
            \Log::error('Error storing pengajuan: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan permohonan. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.'
            ])->withInput();
        }
    }
}
