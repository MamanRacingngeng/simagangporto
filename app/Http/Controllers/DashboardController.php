<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanMagang;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use App\Models\Dokumen;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistik permohonan user
        $totalLamaran = PermohonanMagang::where('user_id', $user->id)->count();
        
        // Menunggu Verifikasi (Status: Diajukan)
        $menungguVerifikasi = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diajukan')
            ->count();
        
        // Perlu Perbaikan (Status: Ditolak)
        $perluPerbaikan = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Ditolak')
            ->count();
        
        // Diterima
        $diterima = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diterima')
            ->count();
        
        // Total Kebutuhan (Total kuota yang dibuka)
        $totalKebutuhan = KuotaMagang::sum('kuota_max');
        
        // Permohonan terbaru (limit 5)
        $permohonanTerbaru = PermohonanMagang::where('user_id', $user->id)
            ->with(['dokumen', 'kuotaMagang'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Cek apakah ada lowongan tersedia
        // Lowongan muncul jika: kuota tersedia DAN jadwal sudah dibuat DAN jadwal sudah dimulai dan belum berakhir
        $today = now()->toDateString();
        $allKuota = KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')->get();
        $allJadwal = JadwalMagang::all();
        
        $lowonganTersedia = $allKuota->filter(function ($kuota) use ($allJadwal, $today) {
            // Cari jadwal dengan periode dan posisi yang sama (case-insensitive dan trim)
            // Sistem mengizinkan periode yang sama selama divisi berbeda
            $jadwal = $allJadwal->first(function ($j) use ($kuota) {
                return trim(strtolower($j->periode)) === trim(strtolower($kuota->periode))
                    && trim(strtolower($j->posisi ?? '')) === trim(strtolower($kuota->posisi ?? ''));
            });
            
            if ($jadwal) {
                // Lowongan tersedia hanya jika jadwal sudah dimulai dan belum berakhir
                return $jadwal->tgl_mulai <= $today && $jadwal->tgl_selesai >= $today;
            }
            return false;
        })->count() > 0;
        
        // Action Items - Kondisi A: Belum Mengajukan
        $actionItem = null;
        $actionItemType = null;
        $permohonanAktif = PermohonanMagang::where('user_id', $user->id)->first();
        
        if (!$permohonanAktif) {
            // Cek apakah profil sudah lengkap
            // Field minimal yang harus diisi: nama, email, no_telepon, ttl, domisili, nim, semester, program, universitas
            $profilLengkap = true;
            $fieldWajib = ['nama', 'email', 'no_telepon', 'ttl', 'domisili', 'nim', 'semester', 'program', 'universitas'];
            
            foreach ($fieldWajib as $field) {
                if (empty($user->$field)) {
                    $profilLengkap = false;
                    break;
                }
            }
            
            if (!$profilLengkap) {
                // Kondisi A1: Belum ada permohonan DAN profil belum lengkap
                $actionItem = 'Langkah 1: Lengkapi Data Diri Anda di menu Profil dan Ajukan Permohonan di menu Lowongan.';
                $actionItemType = 'info';
            } else {
                // Kondisi A2: Belum ada permohonan TAPI profil sudah lengkap
                // Tidak perlu menampilkan notifikasi, atau bisa tampilkan notifikasi untuk ajukan permohonan
                // $actionItem = 'Profil Anda sudah lengkap! Ajukan permohonan di menu Lowongan.';
                // $actionItemType = 'info';
                // Untuk sekarang, kita biarkan kosong jika profil sudah lengkap dan belum ada permohonan
            }
        } else {
            // Cek dokumen melalui relasi permohonan atau langsung dari user
            $dokumen = $permohonanAktif->dokumen ?? Dokumen::where('user_id', $user->id)->first();
            $dokumenLengkap = true;
            
            if ($dokumen) {
                if (empty($dokumen->cv) || empty($dokumen->surat_pengantar) || empty($dokumen->proposal)) {
                    $dokumenLengkap = false;
                }
            } else {
                $dokumenLengkap = false;
            }
            
            // Kondisi C: Status Ditolak (prioritas tinggi)
            if ($permohonanAktif->status === 'Ditolak') {
                $actionItem = 'Permohonan Anda Ditolak. Silakan cek detail alasan penolakan di menu Lamaran Saya.';
                $actionItemType = 'error';
            }
            // Kondisi B: Dokumen Belum Lengkap
            elseif (!$dokumenLengkap && $permohonanAktif->status !== 'Ditolak') {
                $actionItem = '⚠️ Dokumen Belum Lengkap! Harap segera lengkapi dokumen Anda di menu Lamaran Saya untuk menghindari penolakan.';
                $actionItemType = 'warning';
            }
        }
        
        // Ambil notifikasi yang belum dibaca
        $notifikasi = \App\Models\Notifikasi::where('user_id', $user->id)
            ->belumDibaca()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard', compact(
            'totalLamaran',
            'menungguVerifikasi',
            'perluPerbaikan',
            'diterima',
            'totalKebutuhan',
            'permohonanTerbaru',
            'lowonganTersedia',
            'actionItem',
            'actionItemType',
            'notifikasi'
        ));
    }
    
    public function lowongan()
    {
        $today = now()->toDateString();
        
        // Ambil semua kuota yang memiliki kuota tersedia
        $allKuota = KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')->get();
        
        // Ambil semua jadwal
        $allJadwal = JadwalMagang::all();
        
        // Filter kuota yang memiliki jadwal dengan periode dan posisi yang sama
        // Lowongan muncul jika jadwal BELUM BERAKHIR (tgl_selesai >= today)
        // Termasuk jadwal yang AKAN DATANG (tgl_mulai > today) dan jadwal yang AKTIF (tgl_mulai <= today)
        // Sistem mengizinkan periode yang sama selama divisi berbeda
        $kuotaMagang = $allKuota->filter(function ($kuota) use ($allJadwal, $today) {
            // Cari jadwal dengan periode dan posisi yang sama (case-insensitive dan trim)
            $jadwal = $allJadwal->first(function ($j) use ($kuota) {
                return trim(strtolower($j->periode)) === trim(strtolower($kuota->periode))
                    && trim(strtolower($j->posisi ?? '')) === trim(strtolower($kuota->posisi ?? ''));
            });
            
            if ($jadwal) {
                // Tampilkan jika jadwal belum berakhir (tgl_selesai >= today)
                // Ini termasuk jadwal yang akan datang (tgl_mulai > today) dan jadwal aktif (tgl_mulai <= today)
                $belumBerakhir = $jadwal->tgl_selesai >= $today;
                
                return $belumBerakhir;
            }
            return false;
        })
        ->map(function ($kuota) use ($today, $allJadwal) {
            // Hitung sisa kuota
            $kuota->sisa_kuota = $kuota->kuota_max - $kuota->kuota_terpakai;
            $kuota->kuota_tersedia = $kuota->sisa_kuota > 0;
            
            // Load jadwal untuk tampilan berdasarkan periode + posisi (case-insensitive)
            $jadwal = $allJadwal->first(function ($j) use ($kuota) {
                return trim(strtolower($j->periode)) === trim(strtolower($kuota->periode))
                    && trim(strtolower($j->posisi ?? '')) === trim(strtolower($kuota->posisi ?? ''));
            });
            if ($jadwal) {
                $kuota->jadwalMagang = $jadwal;
                $kuota->jadwal_aktif = $jadwal->tgl_mulai <= $today && $jadwal->tgl_selesai >= $today;
                $kuota->jadwal_akan_datang = $jadwal->tgl_mulai > $today;
            }
            
            return $kuota;
        });
        
        // Cek status dokumen user untuk pre-requisite check
        $user = auth()->user();
        $dokumen = Dokumen::where('user_id', $user->id)->first();
        $dokumenLengkap = $dokumen && !empty($dokumen->cv) && !empty($dokumen->surat_pengantar) && !empty($dokumen->proposal);
        
        // Cek apakah user bisa mendaftar (satu akun hanya 1 divisi, kecuali masa berlaku habis dan ditolak)
        $cekDaftar = PermohonanMagang::cekBisaDaftar($user->id);
        $memilikiPermohonanAktif = !$cekDaftar['bisa_daftar'];
        
        return view('lowongan', compact('kuotaMagang', 'dokumenLengkap', 'memilikiPermohonanAktif', 'cekDaftar'));
    }
    public function lamaran()
    {
        $user = auth()->user();
        
        // Ambil permohonan terbaru user
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->with(['dokumen', 'kuotaMagang.jadwalMagang'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Ambil dokumen user (bisa dari permohonan atau langsung dari user)
        try {
            $dokumen = null;
            if ($permohonan && $permohonan->dokumen) {
                $dokumen = $permohonan->dokumen;
            } else {
                $dokumen = Dokumen::where('user_id', $user->id)->first();
            }
        } catch (\Exception $e) {
            // Jika tabel belum ada atau error, set dokumen ke null
            $dokumen = null;
        }
        
        return view('lamaran', compact('permohonan', 'dokumen'));
    }
    
    public function riwayatLamaran()
    {
        $user = auth()->user();
        
        // Ambil semua permohonan user dengan relasi lengkap
        $riwayatPermohonan = PermohonanMagang::where('user_id', $user->id)
            ->with(['dokumen', 'kuotaMagang.jadwalMagang'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistik untuk alur proses lamaran
        // 1. Diajukan (Status: Diajukan) - setelah upload dokumen
        $diajukan = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diajukan')
            ->count();
        
        // 2. Diverifikasi (Status: Diverifikasi) - sedang diverifikasi admin
        $diverifikasi = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diverifikasi')
            ->count();
        
        // 3. Diterima (Status: Diterima) - final status diterima
        $diterima = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diterima')
            ->count();
        
        // 4. Ditolak (Status: Ditolak) - jika ditolak
        $ditolak = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Ditolak')
            ->count();
        
        // Status Dokumen Real-time
        $dokumenUser = Dokumen::where('user_id', $user->id)->first();
        $statusDokumen = [
            'sudah_upload' => false,
            'lengkap' => false,
            'belum_upload' => true,
        ];
        
        if ($dokumenUser) {
            $cv = !empty($dokumenUser->cv);
            $suratPengantar = !empty($dokumenUser->surat_pengantar);
            $proposal = !empty($dokumenUser->proposal);
            
            $statusDokumen['sudah_upload'] = $cv || $suratPengantar || $proposal;
            $statusDokumen['lengkap'] = $cv && $suratPengantar && $proposal;
            $statusDokumen['belum_upload'] = !$cv && !$suratPengantar && !$proposal;
            $statusDokumen['detail'] = [
                'cv' => $cv,
                'surat_pengantar' => $suratPengantar,
                'proposal' => $proposal,
            ];
        }
        
        // Status Permohonan Aktif
        $permohonanAktif = PermohonanMagang::where('user_id', $user->id)
            ->with(['kuotaMagang.jadwalMagang'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        $statusLamaran = [
            'status' => null,
            'sedang_proses' => false,
            'perlu_perbaikan' => false,
            'berhasil' => false,
            'jadwal' => null,
            'alasan_penolakan' => null,
        ];
        
        if ($permohonanAktif) {
            $statusLamaran['status'] = $permohonanAktif->status;
            $statusLamaran['sedang_proses'] = in_array($permohonanAktif->status, ['Diajukan', 'Diverifikasi']);
            $statusLamaran['perlu_perbaikan'] = $permohonanAktif->status === 'Ditolak';
            $statusLamaran['berhasil'] = $permohonanAktif->status === 'Diterima';
            $statusLamaran['alasan_penolakan'] = $permohonanAktif->alasan_penolakan;
            
            // Ambil jadwal jika ada
            if ($permohonanAktif->kuotaMagang->count() > 0) {
                $kuota = $permohonanAktif->kuotaMagang->first();
                if ($kuota && $kuota->jadwalMagang) {
                    $statusLamaran['jadwal'] = $kuota->jadwalMagang;
                }
            }
        }
        
        // Cek apakah user memiliki permohonan aktif untuk pencegahan ganda
        $memilikiPermohonanAktif = PermohonanMagang::where('user_id', $user->id)
            ->whereIn('status', ['Diajukan', 'Diverifikasi'])
            ->exists();
        
        return view('riwayat-lamaran', compact('riwayatPermohonan', 'diajukan', 'diverifikasi', 'diterima', 'ditolak', 'statusDokumen', 'statusLamaran', 'memilikiPermohonanAktif'));
    }

    public function panduanOnboarding()
    {
        $user = auth()->user();
        
        // Ambil permohonan aktif yang diterima
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diterima')
            ->with(['kuotaMagang', 'dokumen'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$permohonan) {
            return redirect()->route('riwayat.lamaran')->withErrors([
                'error' => 'Anda belum memiliki permohonan yang diterima. Panduan onboarding hanya tersedia untuk peserta yang telah diterima.'
            ]);
        }
        
        // Ambil data kuota dan jadwal
        $kuota = $permohonan->kuotaMagang->first();
        $jadwal = null;
        
        if ($kuota) {
            // Load jadwal berdasarkan periode dan posisi
            $jadwal = \App\Models\JadwalMagang::where('periode', $kuota->periode)
                ->where('posisi', $kuota->posisi)
                ->first();
        }
        
        return view('panduan-onboarding', compact('user', 'permohonan', 'kuota', 'jadwal'));
    }

    public function profil()
    {
        $user = auth()->user();
        return view('profil', compact('user'));
    }
    
    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
            'ttl' => 'nullable|string|max:255',
            'domisili' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:50',
            'semester' => 'nullable|integer|min:1|max:14',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'program' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'software_tools' => 'nullable|string',
            'portofolio' => 'nullable|url|max:500',
            'kompetensi_utama' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $data = $request->only([
            'nama',
            'nama_panggilan',
            'email',
            'no_telepon',
            'ttl',
            'domisili',
            'nim',
            'semester',
            'ipk',
            'program',
            'universitas',
            'instansi',
            'software_tools',
            'portofolio',
            'kompetensi_utama',
        ]);
        
        // Upload foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil && \Storage::disk('public')->exists($user->foto_profil)) {
                \Storage::disk('public')->delete($user->foto_profil);
            }
            
            $file = $request->file('foto_profil');
            $filename = 'foto_profil_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_profil', $filename, 'public');
            $data['foto_profil'] = $path;
        }
        
        $user->update($data);
        
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function laporan() { return view('laporan'); }
    public function penugasan() { return view('penugasan'); }
    public function galeriMagang() 
    { 
        $galeri = \App\Models\GaleriMagang::aktif()
            ->terurut()
            ->get();
        
        return view('galeri-magang', compact('galeri')); 
    }
    public function sertifikat() { return view('sertifikat'); }

    public function storeLaporan(Request $request)
    {
        $request->validate(['laporan' => 'required|mimes:pdf,docx|max:2048']);
        $request->file('laporan')->store('laporan_mingguan');
        return back()->with('success', 'Laporan berhasil diunggah!');
    }

    public function statusLamaran()
    {
        $user = auth()->user();
        $permohonan = PermohonanMagang::where('user_id', $user->id)->first();
        return response()->json([
            'status' => $permohonan->status ?? 'Diajukan',
            'nama' => $user->nama ?? null,
        ]);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function tandaiNotifikasiDibaca($id)
    {
        $user = auth()->user();
        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->findOrFail($id);
        
        $notifikasi->tandaiDibaca();
        
        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }
}
