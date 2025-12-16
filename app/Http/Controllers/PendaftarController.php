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
use Illuminate\Support\Facades\Log;

class PendaftarController extends Controller
{
    // Mengisi Data Diri
    public function isiDataDiri()
    {
        $user = Auth::user();
        return view('pendaftar.isi_data_diri', compact('user'));
    }

    public function updateDataDiri(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255', // Sesuai ERD: nama bukan name
            'no_telepon' => 'nullable|string',
            'instansi' => 'nullable|string', // Sesuai ERD: instansi (bukan universitas/jurusan terpisah)
        ]);

        $user = Auth::user();
        $user->update($request->only([
            'nama', // Sesuai ERD: nama bukan name
            'no_telepon',
            'instansi', // Sesuai ERD: instansi
        ]));

        return back()->with('success', 'Data diri berhasil diperbarui.');
    }

    // Unggah Dokumen
    public function unggahDokumen()
    {
        $user = Auth::user();
        $dokumen = Dokumen::where('user_id', $user->id)->get();
        $permohonan = PermohonanMagang::where('user_id', $user->id)->first();

        return view('pendaftar.unggah_dokumen', compact('dokumen', 'permohonan'));
    }

    /**
     * Activity Diagram - Pendaftaran Magang
     * User mengunggah dokumen (CV, Surat Pengantar, Proposal)
     */
    public function storeDokumen(Request $request)
    {
        try {
            // Activity Diagram: User mengunggah dokumen (CV, Surat Pengantar, Proposal)
            $request->validate([
                'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'surat_pengantar' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'proposal' => 'required|file|mimes:pdf,doc,docx|max:5120',
            ], [
                'cv.required' => 'CV wajib diunggah.',
                'surat_pengantar.required' => 'Surat Pengantar wajib diunggah.',
                'proposal.required' => 'Proposal wajib diunggah.',
                '*.mimes' => 'File harus berformat PDF, DOC, atau DOCX.',
                '*.max' => 'Ukuran file maksimal 5MB.',
            ]);

            $user = Auth::user();
        
        // Hapus dokumen lama jika ada
        $dokumenLama = Dokumen::where('user_id', $user->id)->first();
        if ($dokumenLama) {
            if ($dokumenLama->cv && Storage::disk('public')->exists($dokumenLama->cv)) {
                Storage::disk('public')->delete($dokumenLama->cv);
            }
            if ($dokumenLama->surat_pengantar && Storage::disk('public')->exists($dokumenLama->surat_pengantar)) {
                Storage::disk('public')->delete($dokumenLama->surat_pengantar);
            }
            if ($dokumenLama->proposal && Storage::disk('public')->exists($dokumenLama->proposal)) {
                Storage::disk('public')->delete($dokumenLama->proposal);
            }
            $dokumenLama->delete();
        }

        $data = [
            'user_id' => $user->id,
            'tanggal_upload' => now()->toDateString(),
        ];

        // Upload CV
        $file = $request->file('cv');
        $filename = 'cv_' . time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('dokumen', $filename, 'public');
        $data['cv'] = $path;

        // Upload Surat Pengantar
        $file = $request->file('surat_pengantar');
        $filename = 'surat_pengantar_' . time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('dokumen', $filename, 'public');
        $data['surat_pengantar'] = $path;

        // Upload Proposal
        $file = $request->file('proposal');
        $filename = 'proposal_' . time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('dokumen', $filename, 'public');
        $data['proposal'] = $path;

        $dokumen = Dokumen::create($data);
        
        // Cek apakah sudah ada permohonan yang terhubung dengan dokumen ini
        $permohonanAda = PermohonanMagang::where('user_id', $user->id)
            ->where('dokumen_id', $dokumen->id)
            ->exists();
        
        if (!$permohonanAda) {
            // Jika belum ada permohonan, cek apakah ada permohonan tanpa dokumen yang bisa dihubungkan
            $permohonanTanpaDokumen = PermohonanMagang::where('user_id', $user->id)
                ->whereNull('dokumen_id')
                ->first();
            
            if ($permohonanTanpaDokumen) {
                // Hubungkan dokumen ke permohonan yang sudah ada
                $permohonanTanpaDokumen->update(['dokumen_id' => $dokumen->id]);
            }
        }

            // Setelah dokumen diunggah, set flag untuk menyembunyikan draft sementara
            // Draft akan muncul lagi setelah halaman di-refresh
            return redirect()->route('lamaran')
                ->with('success', 'Dokumen (CV, Surat Pengantar, Proposal) berhasil diunggah.')
                ->with('dokumen_baru_diunggah', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing dokumen: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengunggah dokumen. Pastikan file tidak corrupt dan ukurannya tidak melebihi 5MB per file. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function deleteDokumen($id)
    {
        $dokumen = Dokumen::where('user_id', Auth::id())->findOrFail($id);
        
        // Hapus file jika ada
        if ($dokumen->cv && Storage::disk('public')->exists($dokumen->cv)) {
            Storage::disk('public')->delete($dokumen->cv);
        }
        if ($dokumen->surat_pengantar && Storage::disk('public')->exists($dokumen->surat_pengantar)) {
            Storage::disk('public')->delete($dokumen->surat_pengantar);
        }
        if ($dokumen->proposal && Storage::disk('public')->exists($dokumen->proposal)) {
            Storage::disk('public')->delete($dokumen->proposal);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    // Hapus dokumen per field (CV, Surat Pengantar, atau Proposal)
    public function deleteDokumenField($id, $field)
    {
        try {
            $dokumen = Dokumen::where('user_id', Auth::id())->findOrFail($id);
            
            // Validasi field yang diizinkan
            $allowedFields = ['cv', 'surat_pengantar', 'proposal'];
            if (!in_array($field, $allowedFields)) {
                return back()->withErrors(['error' => 'Field dokumen tidak valid.']);
            }
            
            // Hapus file dari storage jika ada
            if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                Storage::disk('public')->delete($dokumen->$field);
            }
            
            // Update field menjadi null
            $dokumen->update([$field => null]);
            
            // Jika semua dokumen sudah dihapus, hapus record dokumen
            if (empty($dokumen->cv) && empty($dokumen->surat_pengantar) && empty($dokumen->proposal)) {
                $dokumen->delete();
                return redirect()->route('lamaran')
                    ->with('success', 'Semua dokumen telah dihapus.');
            }
            
            $fieldNames = [
                'cv' => 'CV (Curriculum Vitae)',
                'surat_pengantar' => 'Surat Pengantar',
                'proposal' => 'Proposal'
            ];
            
            return redirect()->route('lamaran')
                ->with('success', $fieldNames[$field] . ' berhasil dihapus. Anda dapat mengunggah ulang dokumen ini.');
        } catch (\Exception $e) {
            Log::error('Error deleting dokumen field: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus dokumen. Silakan coba lagi.'
            ]);
        }
    }

    // Ajukan Permohonan Magang
    public function ajukanPermohonanMagang()
    {
        $user = Auth::user();
        $kuota = KuotaMagang::all(); // Sesuai ERD: tidak ada field aktif
        $jadwal = JadwalMagang::all(); // Sesuai ERD: tidak ada field aktif
        $permohonan = PermohonanMagang::where('user_id', $user->id)->first();
        $dokumen = Dokumen::where('user_id', $user->id)->first();

        return view('pendaftar.ajukan_permohonan_magang', compact('kuota', 'jadwal', 'permohonan', 'dokumen'));
    }

    /**
     * Activity Diagram - Pendaftaran Magang
     * User mengirim permohonan magang
     * Decision: Dokumen valid?
     *   - Ya: Sistem menyimpan permohonan → Status = "Diajukan"
     *   - Tidak: Sistem meminta user memperbaiki dokumen
     */
    public function storePermohonanMagang(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user bisa mendaftar (satu akun hanya 1 divisi, kecuali masa berlaku habis dan ditolak)
        $cekDaftar = PermohonanMagang::cekBisaDaftar($user->id);
        
        if (!$cekDaftar['bisa_daftar']) {
            return back()->withErrors(['error' => $cekDaftar['alasan']])->withInput();
        }

        // Cek apakah user sudah mengunggah dokumen
        $dokumen = Dokumen::where('user_id', $user->id)->first();
        
        if (!$dokumen) {
            return back()->withErrors(['error' => 'Silakan unggah dokumen terlebih dahulu (CV, Surat Pengantar, Proposal).'])
                ->withInput();
        }

        // Decision: Dokumen valid?
        // Validasi dokumen lengkap (CV, Surat Pengantar, Proposal)
        $dokumenLengkap = true;
        $pesanError = [];

        if (empty($dokumen->cv)) {
            $dokumenLengkap = false;
            $pesanError[] = 'CV belum diunggah.';
        }

        if (empty($dokumen->surat_pengantar)) {
            $dokumenLengkap = false;
            $pesanError[] = 'Surat Pengantar belum diunggah.';
        }

        if (empty($dokumen->proposal)) {
            $dokumenLengkap = false;
            $pesanError[] = 'Proposal belum diunggah.';
        }

        // Decision: Dokumen valid? → Tidak
        if (!$dokumenLengkap) {
            // Sistem meminta user memperbaiki dokumen
            return back()->withErrors(['error' => 'Dokumen tidak lengkap. ' . implode(' ', $pesanError)])
                ->withInput();
        }

        // Decision: Dokumen valid? → Ya
        // Sistem menyimpan permohonan
        PermohonanMagang::create([
            'user_id' => $user->id,
            'dokumen_id' => $dokumen->id,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'Diajukan', // Status = "Diajukan"
        ]);

        return redirect()->route('pendaftar.status_permohonan')
            ->with('success', 'Permohonan magang berhasil diajukan. Status: Diajukan');
    }

    // Lihat Status Permohonan
    public function lihatStatusPermohonan()
    {
        $user = Auth::user();
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->with(['dokumen'])
            ->first();

        return view('pendaftar.status_permohonan', compact('permohonan'));
    }
}
