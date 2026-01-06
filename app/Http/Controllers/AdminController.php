<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermohonanMagang;
use App\Models\Dokumen;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use App\Models\Notifikasi;
use App\Mail\NotifikasiKekuranganSyarat;
use App\Mail\SuratKerjaTersedia;
use App\Mail\NotifikasiStatusPermohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheHelper;

class AdminController extends Controller
{
    /**
     * Login Admin
     * Hanya user dengan role 'admin' yang bisa login melalui route ini
     */
    public function showLoginForm()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Proses Login Admin
     * Validasi: Hanya admin yang bisa login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Normalize email: trim dan lowercase untuk menghindari masalah case sensitivity
        $email = strtolower(trim($credentials['email']));
        $password = $credentials['password'];

        // Cek apakah user ada dengan query case-insensitive untuk memastikan
        $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

        if (!$user) {
            Log::warning('Admin login attempt failed: User not found', ['email' => $email]);
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->onlyInput('email');
        }

        // Cek password
        if (!Hash::check($password, $user->password)) {
            Log::warning('Admin login attempt failed: Invalid password', ['email' => $email, 'user_id' => $user->id]);
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->onlyInput('email');
        }

        // Validasi: Hanya admin yang bisa login
        if (!$user->isAdmin()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Anda tidak memiliki akses admin. Silakan login sebagai user.'])
                ->withInput($request->only('email'));
        }

        // Login admin
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        
        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Login berhasil! Selamat datang Admin.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Dashboard Admin
    public function dashboard()
    {
        // Cache dashboard data untuk 3 menit (lebih lama untuk performa)
        $cacheKey = 'admin_dashboard_' . now()->format('Y-m-d-H-i');
        $data = Cache::remember($cacheKey, 180, function () {
            // Optimasi: Ambil semua statistik dalam satu query
            $stats = PermohonanMagang::selectRaw('
                COUNT(*) as total_permohonan,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as siap_diverifikasi,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as perlu_keputusan,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as diterima,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as ditolak
            ', ['Diajukan', 'Diverifikasi', 'Diterima', 'Ditolak'])
            ->first();
            
            $totalPendaftar = User::where('role', 'user')->count();
            
            return [
                'totalPendaftar' => $totalPendaftar,
                'totalPermohonan' => $stats->total_permohonan ?? 0,
                'siapDiverifikasi' => $stats->siap_diverifikasi ?? 0,
                'perluKeputusan' => $stats->perlu_keputusan ?? 0,
                'diterima' => $stats->diterima ?? 0,
                'ditolak' => $stats->ditolak ?? 0,
            ];
        });

        // Cari semua jadwal aktif - cache 5 menit (lebih lama)
        $today = now()->toDateString();
        $jadwalCacheKey = "jadwal_aktif_{$today}";
        $jadwalAktif = Cache::remember($jadwalCacheKey, 300, function () use ($today) {
            return JadwalMagang::where('tgl_mulai', '<=', $today)
            ->where('tgl_selesai', '>=', $today)
            ->orderBy('created_at', 'desc')
                ->get(['id', 'periode', 'posisi', 'tgl_mulai', 'tgl_selesai']);
        });
        
        // Ambil semua kuota aktif untuk semua divisi - cache 5 menit (lebih lama)
        $kuotaAktif = Cache::remember("kuota_aktif_{$today}", 300, function () use ($jadwalAktif) {
            $periodePosisi = $jadwalAktif->map(function ($j) {
                return ['periode' => $j->periode, 'posisi' => $j->posisi];
            })->unique(function ($item) {
                return $item['periode'] . '|' . $item['posisi'];
            });
            
            return KuotaMagang::where(function ($query) use ($periodePosisi) {
                foreach ($periodePosisi as $pp) {
                    $query->orWhere(function ($q) use ($pp) {
                        $q->where('periode', $pp['periode'])
                          ->where('posisi', $pp['posisi']);
                    });
                }
            })->get();
        });

        // Aktivitas Pendaftar Terbaru - cache 2 menit (lebih lama)
        $activities = Cache::remember('admin_activities', 120, function () {
            return PermohonanMagang::with('user:id,nama')
            ->orderBy('created_at', 'desc')
            ->limit(7)
                ->get(['id', 'user_id', 'status', 'created_at'])
            ->map(function ($permohonan) {
                $userName = $permohonan->user->nama ?? 'User';
                $statusMap = [
                    'Diajukan' => 'mengajukan permohonan magang',
                    'Diverifikasi' => 'dokumen telah diverifikasi',
                    'Diterima' => 'permohonan diterima',
                    'Ditolak' => 'permohonan ditolak',
                ];
                $aksi = $statusMap[$permohonan->status] ?? 'memperbarui permohonan';
                
                return [
                    'id' => $permohonan->id,
                    'user_id' => $permohonan->user_id,
                    'nama' => $userName,
                    'aksi' => $aksi,
                    'status' => $permohonan->status,
                    'waktu' => $permohonan->created_at,
                    'diff' => $permohonan->created_at->diffForHumans(),
                ];
                });
            });

        // ========== DATA UNTUK GRAFIK ==========
        
        // 1. Bar Chart Data
        $barChartData = [
            'labels' => ['Siap Diverifikasi', 'Perlu Keputusan', 'Diterima', 'Ditolak'],
            'data' => [
                $data['siapDiverifikasi'],
                $data['perluKeputusan'],
                $data['diterima'],
                $data['ditolak']
            ],
            'colors' => ['#06B6D4', '#F59E0B', '#10B981', '#EF4444']
        ];

        // 2. Line Chart Data - cache 5 menit
        $lineChartData = Cache::remember('admin_line_chart_data', 300, function () {
            return $this->getMonthlyTrendData(12);
        });

        // 3. Pie Chart Data
        $totalStatus = $data['siapDiverifikasi'] + $data['perluKeputusan'] + $data['diterima'] + $data['ditolak'];
        $pieChartData = [
            'labels' => ['Siap Diverifikasi', 'Perlu Keputusan', 'Diterima', 'Ditolak'],
            'data' => [
                $data['siapDiverifikasi'],
                $data['perluKeputusan'],
                $data['diterima'],
                $data['ditolak']
            ],
            'colors' => ['#06B6D4', '#F59E0B', '#10B981', '#EF4444'],
            'total' => $totalStatus
        ];

        return view('admin.dashboard', [
            'totalPendaftar' => $data['totalPendaftar'],
            'totalPermohonan' => $data['totalPermohonan'],
            'siapDiverifikasi' => $data['siapDiverifikasi'],
            'perluKeputusan' => $data['perluKeputusan'],
            'diterima' => $data['diterima'],
            'ditolak' => $data['ditolak'],
            'jadwalAktif' => $jadwalAktif,
            'kuotaAktif' => $kuotaAktif,
            'activities' => $activities,
            'barChartData' => $barChartData,
            'lineChartData' => $lineChartData,
            'pieChartData' => $pieChartData,
        ]);
    }

    /**
     * Activity Diagram - Admin Verifikasi Permohonan Magang
     * Admin membuka dashboard admin
     * Admin memilih menu "Data Pendaftar"
     * Decision: Ada permohonan baru?
     */
    public function lihatDataPendaftar(Request $request)
    {
        // Ambil semua permohonan dari database dengan filter status jika ada
        $query = PermohonanMagang::with(['user:id,nama,email', 'dokumen:id,user_id,cv,surat_pengantar,proposal', 'kuotaMagang:id,periode,posisi']);
        
        // Filter berdasarkan status jika ada parameter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $permohonan = $query->orderBy('created_at', 'desc')->paginate(15);

        // Decision: Ada permohonan baru? - cache 2 menit (lebih lama)
        $adaPermohonanBaru = Cache::remember('ada_permohonan_baru', 120, function () {
            return PermohonanMagang::where('status', 'Diajukan')->exists();
        });

        return view('admin.data_pendaftar', compact('permohonan', 'adaPermohonanBaru'));
    }

    /**
     * Activity Diagram - Admin Verifikasi Permohonan Magang
     * Admin membuka detail permohonan
     * Admin mengecek dokumen (CV, Surat Pengantar, Proposal)
     */
    public function detailPendaftar($id)
    {
        // Ambil dokumen & data lengkap dari database
        $permohonan = PermohonanMagang::with(['user', 'dokumen', 'kuotaMagang'])
            ->findOrFail($id);

        // Admin mengecek dokumen (CV, Surat Pengantar, Proposal)
        $dokumenLengkap = false;
        $dokumenValid = false;
        
        if ($permohonan->dokumen) {
            $dokumen = $permohonan->dokumen;
            // Cek dokumen lengkap
            $dokumenLengkap = !empty($dokumen->cv) && 
                             !empty($dokumen->surat_pengantar) && 
                             !empty($dokumen->proposal);
            
            // Cek dokumen valid (file exists)
            if ($dokumenLengkap) {
                $dokumenValid = Storage::disk('public')->exists($dokumen->cv) &&
                               Storage::disk('public')->exists($dokumen->surat_pengantar) &&
                               Storage::disk('public')->exists($dokumen->proposal);
            }
        }

        // Ambil kuota untuk cek ketersediaan
        $kuota = KuotaMagang::all();

        return view('admin.detail_pendaftar', compact('permohonan', 'dokumenLengkap', 'dokumenValid', 'kuota'));
    }

    // Verifikasi Dokumen (sesuai ERD: verifikasi dilakukan melalui status permohonan)
    // Catatan: Status "Diterima" dan "Ditolak" bersifat final dan tidak dapat diubah
    public function verifikasiDokumen()
    {
        // Sesuai ERD: verifikasi dilakukan melalui status permohonan, bukan dokumen terpisah
        $permohonan = PermohonanMagang::with(['user', 'dokumen'])
            ->where('status', 'Diajukan') // Sesuai ERD: status "Diajukan" perlu diverifikasi
            ->paginate(15);

        return view('admin.verifikasi_dokumen', compact('permohonan'));
    }

    /**
     * Update Verifikasi Dokumen (legacy method untuk verifikasi manual)
     * Method ini digunakan untuk verifikasi dokumen dari halaman verifikasi dokumen
     */
    public function updateVerifikasiDokumen(Request $request, $id)
    {
        try {
            $permohonan = PermohonanMagang::with(['dokumen'])->findOrFail($id);

            // Validasi: Status "Diterima" dan "Ditolak" bersifat final dan tidak dapat diubah
            $statusFinal = ['Diterima', 'Ditolak'];
            if (in_array($permohonan->status, $statusFinal)) {
                return back()->withErrors([
                    'error' => "Status permohonan sudah final ({$permohonan->status}) dan tidak dapat diverifikasi ulang. Status 'Diterima' dan 'Ditolak' bersifat permanen."
                ]);
            }

            // Admin mengecek dokumen (CV, Surat Pengantar, Proposal)
            if (!$permohonan->dokumen) {
                return back()->withErrors(['error' => 'Dokumen tidak ditemukan untuk permohonan ini.']);
            }

            $dokumen = $permohonan->dokumen;
            
            // Decision: Dokumen lengkap & valid?
            $dokumenLengkap = !empty($dokumen->cv) && 
                             !empty($dokumen->surat_pengantar) && 
                             !empty($dokumen->proposal);
            
            $dokumenValid = false;
            if ($dokumenLengkap) {
                $dokumenValid = Storage::disk('public')->exists($dokumen->cv) &&
                               Storage::disk('public')->exists($dokumen->surat_pengantar) &&
                               Storage::disk('public')->exists($dokumen->proposal);
            }

            // Decision: Dokumen lengkap & valid? → Tidak
            if (!$dokumenLengkap || !$dokumenValid) {
                // Admin menolak permohonan dengan alasan
                $permohonan->update([
                    'status' => 'Ditolak',
                    'alasan_penolakan' => 'Dokumen tidak lengkap atau tidak valid. Pastikan CV, Surat Pengantar, dan Proposal telah diunggah dengan benar.',
                ]);

                return back()->with('error', 'Permohonan ditolak. Dokumen tidak lengkap atau tidak valid.');
            }

            // Decision: Dokumen lengkap & valid? → Ya
            // Admin mengubah status menjadi "Diverifikasi"
            $permohonan->update([
                'status' => 'Diverifikasi',
            ]);

            return back()->with('success', 'Dokumen berhasil diverifikasi. Status permohonan: Diverifikasi. Silakan tentukan keputusan (Diterima/Ditolak) di halaman detail.');
        } catch (\Exception $e) {
            \Log::error('Error verifying dokumen: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memverifikasi dokumen. Silakan coba lagi.'
            ]);
        }
    }

    // Ubah Status Permohonan
    public function ubahStatusPermohonan()
    {
        $permohonan = PermohonanMagang::with(['user', 'dokumen'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.ubah_status_permohonan', compact('permohonan'));
    }

    /**
     * Activity Diagram - Admin Verifikasi Permohonan Magang
     * Decision: Dokumen lengkap & valid?
     *   - Tidak: Admin menolak permohonan → Status = "Ditolak (Dokumen tidak valid)"
     *   - Ya: Admin mengubah status menjadi "Diverifikasi"
     * Decision: Kuota masih tersedia?
     *   - Tidak: Admin mengubah status menjadi "Ditolak (Kuota penuh)"
     *   - Ya: Mengubah status menjadi "Diterima"
     */
    public function verifikasiPermohonan(Request $request, $id)
    {
        try {
            $permohonan = PermohonanMagang::with(['dokumen'])->findOrFail($id);

            // Validasi: Status "Diterima" dan "Ditolak" bersifat final dan tidak dapat diubah
            $statusFinal = ['Diterima', 'Ditolak'];
            if (in_array($permohonan->status, $statusFinal)) {
                return back()->withErrors([
                    'error' => "Status permohonan sudah final ({$permohonan->status}) dan tidak dapat diverifikasi ulang. Status 'Diterima' dan 'Ditolak' bersifat permanen."
                ]);
            }

            // Admin mengecek dokumen (CV, Surat Pengantar, Proposal)
            if (!$permohonan->dokumen) {
                return back()->withErrors(['error' => 'Dokumen tidak ditemukan untuk permohonan ini.']);
            }

            $dokumen = $permohonan->dokumen;
            $statusLama = $permohonan->status;
            $user = $permohonan->user;
            
            // Decision: Dokumen lengkap & valid?
            $dokumenLengkap = !empty($dokumen->cv) && 
                             !empty($dokumen->surat_pengantar) && 
                             !empty($dokumen->proposal);
            
            $dokumenValid = false;
            if ($dokumenLengkap) {
                $dokumenValid = Storage::disk('public')->exists($dokumen->cv) &&
                               Storage::disk('public')->exists($dokumen->surat_pengantar) &&
                               Storage::disk('public')->exists($dokumen->proposal);
            }

            // Decision: Dokumen lengkap & valid? → Tidak
            if (!$dokumenLengkap || !$dokumenValid) {
                // Admin menolak permohonan dengan alasan
                $alasanPenolakan = 'Dokumen tidak lengkap atau tidak valid. Pastikan CV, Surat Pengantar, dan Proposal telah diunggah dengan benar.';
                $permohonan->update([
                    'status' => 'Ditolak',
                    'alasan_penolakan' => $alasanPenolakan,
                ]);
                
                // Kirim email notifikasi ke user
                if ($user && !empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($user->email)->send(
                            new NotifikasiStatusPermohonan($user, $permohonan, 'Ditolak', $statusLama, $alasanPenolakan, null)
                        );
                        Log::info("Email notifikasi status 'Ditolak' berhasil dikirim ke {$user->email} untuk permohonan {$permohonan->id}");
                    } catch (\Exception $e) {
                        Log::error("Gagal mengirim email notifikasi status 'Ditolak': " . $e->getMessage());
                    }
                }
                
                // Clear cache user - PASTIKAN SEMUA CACHE DI-CLEAR
                Cache::forget("riwayat_user_{$user->id}");
                Cache::forget("dashboard_user_{$user->id}");
                Cache::forget("lamaran_user_{$user->id}");
                Cache::forget("notifikasi_user_{$user->id}");
                CacheHelper::clearUserCache($user->id);
                
                // Force refresh dengan delay kecil untuk memastikan cache benar-benar di-clear
                usleep(100000); // 100ms delay

                return back()->with('error', 'Permohonan ditolak. Dokumen tidak lengkap atau tidak valid.');
            }

            // Decision: Dokumen lengkap & valid? → Ya
            // Admin mengubah status menjadi "Diverifikasi"
            $permohonan->update([
                'status' => 'Diverifikasi',
            ]);
            
            // Kirim email notifikasi ke user
            if ($user && !empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($user->email)->send(
                        new NotifikasiStatusPermohonan($user, $permohonan, 'Diverifikasi', $statusLama, null, null)
                    );
                    Log::info("Email notifikasi status 'Diverifikasi' berhasil dikirim ke {$user->email} untuk permohonan {$permohonan->id}");
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim email notifikasi status 'Diverifikasi': " . $e->getMessage());
                }
            }
            
            // Clear cache user - PASTIKAN SEMUA CACHE DI-CLEAR
            Cache::forget("riwayat_user_{$user->id}");
            Cache::forget("dashboard_user_{$user->id}");
            Cache::forget("lamaran_user_{$user->id}");
            Cache::forget("notifikasi_user_{$user->id}");
            CacheHelper::clearUserCache($user->id);
            
            // Force refresh dengan delay kecil untuk memastikan cache benar-benar di-clear
            usleep(100000); // 100ms delay

            return back()->with('success', 'Dokumen berhasil diverifikasi. Status permohonan: Diverifikasi. Silakan tentukan keputusan (Diterima/Ditolak) di halaman detail.');
        } catch (\Exception $e) {
            \Log::error('Error verifying permohonan: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memverifikasi permohonan. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Method untuk update status manual (jika diperlukan)
     * Status "Diterima" dan "Ditolak" bersifat final dan tidak dapat diubah
     */
    public function updateStatusPermohonan(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:Diajukan,Diverifikasi,Diterima,Ditolak,Revisi',
                'alasan_penolakan' => 'required_if:status,Ditolak|string|max:1000',
                'catatan_revisi' => 'nullable|string|max:1000',
            ], [
                'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi jika status adalah Ditolak.',
            ]);

            $permohonan = PermohonanMagang::findOrFail($id);
            $statusLama = $permohonan->status;
            
            // Validasi: Status "Diterima" dan "Ditolak" bersifat final dan tidak dapat diubah
            $statusFinal = ['Diterima', 'Ditolak'];
            if (in_array($statusLama, $statusFinal)) {
                return back()->withErrors([
                    'error' => "Status permohonan sudah final ({$statusLama}) dan tidak dapat diubah. Status 'Diterima' dan 'Ditolak' bersifat permanen."
                ])->withInput();
            }
            
            // Validasi: Tidak boleh mengubah dari status final ke status lain
            if (in_array($statusLama, $statusFinal) && $request->status !== $statusLama) {
                return back()->withErrors([
                    'error' => "Tidak dapat mengubah status dari '{$statusLama}' ke '{$request->status}'. Status 'Diterima' dan 'Ditolak' bersifat final."
                ])->withInput();
            }
            
            $updateData = ['status' => $request->status];
            
            // Jika status Ditolak, wajib ada alasan
            if ($request->status === 'Ditolak') {
                if (empty($request->alasan_penolakan)) {
                    return back()->withErrors([
                        'alasan_penolakan' => 'Alasan penolakan wajib diisi saat menolak permohonan.'
                    ])->withInput();
                }
                $updateData['alasan_penolakan'] = $request->alasan_penolakan;
                // hapus catatan revisi jika ada
                $updateData['catatan_revisi'] = null;
            } elseif ($request->status === 'Revisi') {
                // Untuk status Revisi, simpan catatan revisi terpisah
                $updateData['catatan_revisi'] = $request->catatan_revisi ?: null;
                // jangan gunakan alasan_penolakan sebagai revisi
                $updateData['alasan_penolakan'] = null;
            } else {
                // Jika bukan Ditolak atau Revisi, hapus kedua field terkait
                $updateData['alasan_penolakan'] = null;
                $updateData['catatan_revisi'] = null;
            }
            
            // Update status - Observer akan menangani sinkronisasi kuota secara otomatis
            $permohonan->update($updateData);
            
            // Kirim email notifikasi ke user jika status berubah
            $user = $permohonan->user;
            if ($user && !empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $alasan = null;
                    $catatanRevisi = null;
                    
                    if ($request->status === 'Ditolak') {
                        $alasan = $request->alasan_penolakan;
                    } elseif ($request->status === 'Revisi') {
                        $catatanRevisi = $request->catatan_revisi;
                    }
                    
                    Mail::to($user->email)->send(
                        new NotifikasiStatusPermohonan($user, $permohonan, $request->status, $statusLama, $alasan, $catatanRevisi)
                    );
                    Log::info("Email notifikasi status '{$request->status}' berhasil dikirim ke {$user->email} untuk permohonan {$permohonan->id}");
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim email notifikasi status '{$request->status}': " . $e->getMessage());
                }
            }
            
            // Buat notifikasi untuk user jika status berubah menjadi Revisi
            $revisiBerhasilDikirim = false;
            $notifikasiId = null;
            if ($request->status === 'Revisi' && $statusLama !== 'Revisi') {
                $catatanRevisi = $request->catatan_revisi ?: 'Dokumen Anda memerlukan perbaikan. Silakan periksa dan unggah ulang dokumen yang telah diperbaiki.';
                
                try {
                    $notifikasi = Notifikasi::create([
                        'user_id' => $permohonan->user_id,
                        'permohonan_magang_id' => $permohonan->id,
                        'admin_id' => Auth::id(),
                        'judul' => 'Permohonan Magang Memerlukan Revisi',
                        'pesan' => 'Permohonan magang Anda memerlukan revisi. ' . ($request->catatan_revisi ? "\n\nCatatan Revisi:\n" . $request->catatan_revisi : 'Silakan periksa dokumen Anda dan unggah ulang dokumen yang telah diperbaiki.'),
                        'tipe' => 'revisi',
                        'dibaca' => false,
                    ]);
                    
                    $notifikasiId = $notifikasi->id;
                    $revisiBerhasilDikirim = true;
                    
                    // PENTING: Clear cache notifikasi SEBELUM clear user cache untuk memastikan notifikasi langsung muncul
                    Cache::forget("notifikasi_user_{$permohonan->user_id}");
                    Cache::forget("riwayat_user_{$permohonan->user_id}");
                    Cache::forget("dashboard_user_{$permohonan->user_id}");
                    Cache::forget("lamaran_user_{$permohonan->user_id}");
                    
                    // Log untuk debugging
                    \Log::info("Notifikasi revisi berhasil dibuat", [
                        'user_id' => $permohonan->user_id,
                        'notifikasi_id' => $notifikasiId,
                        'permohonan_id' => $permohonan->id,
                        'admin_id' => Auth::id(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Gagal membuat notifikasi revisi", [
                        'user_id' => $permohonan->user_id,
                        'permohonan_id' => $permohonan->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            // Clear cache untuk admin dan user yang bersangkutan agar perubahan langsung terlihat
            // Clear cache notifikasi lagi untuk memastikan - PASTIKAN SEMUA CACHE DI-CLEAR
            $userId = $permohonan->user_id;
            Cache::forget("notifikasi_user_{$userId}");
            Cache::forget("riwayat_user_{$userId}");
            Cache::forget("dashboard_user_{$userId}");
            Cache::forget("lamaran_user_{$userId}");
            CacheHelper::clearAdminCache();
            CacheHelper::clearUserCache($userId);
            
            // Force refresh dengan delay kecil untuk memastikan cache benar-benar di-clear
            usleep(100000); // 100ms delay

            // Pesan sukses yang lebih informatif
            $successMessage = 'Status permohonan berhasil diperbarui.';
            if ($request->status === 'Diterima') {
                $successMessage .= ' Kuota telah disinkronisasi secara otomatis.';
            } elseif ($request->status === 'Revisi' && $revisiBerhasilDikirim) {
                $userNama = $permohonan->user->nama ?? 'Pendaftar';
                $successMessage = "✅ Notifikasi revisi telah berhasil dikirim kepada {$userNama}. Status permohonan telah diubah menjadi 'Revisi' dan pendaftar akan menerima notifikasi untuk memperbaiki dokumen.";
            } elseif ($request->status === 'Ditolak') {
                $successMessage .= ' Alasan penolakan telah disimpan.';
            }

            return back()->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating status permohonan: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui status permohonan. Silakan coba lagi.'
            ])->withInput();
        }
    }

    // Hapus Permohonan (untuk semua status)
    public function deletePermohonan($id)
    {
        try {
            $permohonan = PermohonanMagang::findOrFail($id);
            $userNama = $permohonan->user->nama ?? 'N/A';
            $status = $permohonan->status;
            
            // Jika status Diterima, perlu update kuota terlebih dahulu (kembalikan kuota)
            if ($status === 'Diterima') {
                // Detach dari kuota untuk mengembalikan kuota yang terpakai
                $permohonan->kuotaMagang()->detach();
            } else {
                // Hapus relasi di tabel pivot
                $permohonan->kuotaMagang()->detach();
            }
            
            // Hapus permohonan
            $permohonan->delete();
            
            $pesanSukses = "Permohonan dari {$userNama} (Status: {$status}) berhasil dihapus dari sistem.";
            if ($status === 'Diterima') {
                $pesanSukses .= " Kuota telah dikembalikan.";
            }
            $pesanSukses .= " Pengguna dapat mencoba lagi dengan akun yang sama.";
            
            return back()->with('success', $pesanSukses);
        } catch (\Exception $e) {
            \Log::error('Error deleting permohonan: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus permohonan: ' . $e->getMessage()
            ]);
        }
    }

    // Atur Kuota Magang
    public function aturKuotaMagang()
    {
        $kuota = KuotaMagang::orderBy('created_at', 'desc')->get();
        return view('admin.atur_kuota_magang', compact('kuota'));
    }

    public function storeKuotaMagang(Request $request)
    {
        try {
            // Periode bisa sama asalkan posisi berbeda
            $request->validate([
                'periode' => 'required|string|max:255',
                'posisi' => 'required|string|max:255',
                'kuota_max' => 'required|integer|min:1',
            ], [
                'posisi.required' => 'Posisi/Departemen wajib diisi.',
                'kuota_max.min' => 'Kuota maksimal minimal 1.',
            ]);

            // Normalisasi input: trim untuk menghindari masalah whitespace
            $periode = trim($request->periode);
            $posisi = trim($request->posisi);

            // Validasi: kombinasi periode + posisi harus unique (case-insensitive comparison)
            $exists = KuotaMagang::whereRaw('LOWER(TRIM(periode)) = ?', [strtolower($periode)])
                ->whereRaw('LOWER(TRIM(posisi)) = ?', [strtolower($posisi)])
                ->exists();
            
            if ($exists) {
                return back()->withErrors([
                    'posisi' => 'Kombinasi periode dan posisi ini sudah ada. Silakan gunakan periode atau posisi yang berbeda.',
                ])->withInput();
            }

            KuotaMagang::create([
                'periode' => $periode, // Normalized: trimmed
                'posisi' => $posisi, // Normalized: trimmed
                'kuota_max' => $request->kuota_max, // Sesuai ERD: kuota_max
                'kuota_terpakai' => 0, // Sesuai ERD: kuota_terpakai
            ]);

            return back()->with('success', 'Kuota magang berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing kuota: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menambahkan kuota. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function updateKuotaMagang(Request $request, $id)
    {
        try {
            $kuota = KuotaMagang::findOrFail($id);
            
            // Periode bisa sama asalkan posisi berbeda
            $request->validate([
                'periode' => 'required|string|max:255',
                'posisi' => 'required|string|max:255',
                'kuota_max' => 'required|integer|min:1',
                'kuota_terpakai' => 'required|integer|min:0',
            ], [
                'posisi.required' => 'Posisi/Departemen wajib diisi.',
            ]);

            // Normalisasi input: trim untuk menghindari masalah whitespace
            $periode = trim($request->periode);
            $posisi = trim($request->posisi);

            // Validasi: kombinasi periode + posisi harus unique (kecuali untuk record yang sedang diupdate)
            // Case-insensitive comparison untuk konsistensi dengan matching di user side
            $exists = KuotaMagang::whereRaw('LOWER(TRIM(periode)) = ?', [strtolower($periode)])
                ->whereRaw('LOWER(TRIM(posisi)) = ?', [strtolower($posisi)])
                ->where('id', '!=', $id)
                ->exists();
            
            if ($exists) {
                return back()->withErrors([
                    'posisi' => 'Kombinasi periode dan posisi ini sudah digunakan oleh kuota lain. Silakan gunakan periode atau posisi yang berbeda.',
                ])->withInput();
            }
            
            // Validasi: kuota_terpakai tidak boleh lebih besar dari kuota_max
            if ($request->kuota_terpakai > $request->kuota_max) {
                return back()->withErrors([
                    'error' => 'Kuota terpakai tidak boleh lebih besar dari kuota maksimal.'
                ])->withInput();
            }
            
            // Validasi: kuota_max tidak boleh lebih kecil dari jumlah permohonan diterima
            $permohonanDiterima = PermohonanMagang::whereHas('kuotaMagang', function($query) use ($id) {
                $query->where('kuota_magang.id', $id);
            })->where('status', 'Diterima')->count();
            
            if ($request->kuota_max < $permohonanDiterima) {
                return back()->withErrors([
                    'error' => "Kuota maksimal tidak boleh lebih kecil dari jumlah permohonan yang diterima ({$permohonanDiterima})."
                ])->withInput();
            }

            // Update dengan data yang sudah dinormalisasi
            $kuota->update([
                'periode' => $periode,
                'posisi' => $posisi,
                'kuota_max' => $request->kuota_max,
                'kuota_terpakai' => $request->kuota_terpakai,
            ]);

            return back()->with('success', 'Kuota magang berhasil diperbarui. Status permohonan pendaftar tidak terpengaruh.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating kuota: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui kuota: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function deleteKuotaMagang($id)
    {
        try {
            $kuota = KuotaMagang::findOrFail($id);
            
            // Catatan penting: Admin tetap dapat menghapus kuota tanpa memengaruhi status pendaftar
            // Status permohonan (termasuk "Diterima" dan "Ditolak" yang bersifat final) tidak akan berubah
            
            // Detach semua relasi permohonan dengan kuota ini (hanya hapus relasi di pivot table, tidak ubah status permohonan)
            $userIds = DB::table('permohonan_kuota')
                ->where('kuota_magang_id', $id)
                ->join('permohonan_magang', 'permohonan_kuota.permohonan_magang_id', '=', 'permohonan_magang.id')
                ->pluck('permohonan_magang.user_id')
                ->unique();
            
            DB::table('permohonan_kuota')
                ->where('kuota_magang_id', $id)
                ->delete();
            
            $kuota->delete();
            
            // Clear cache untuk semua user yang terpengaruh
            foreach ($userIds as $userId) {
                CacheHelper::clearUserCache($userId);
                Cache::forget("dashboard_user_{$userId}");
                Cache::forget("riwayat_user_{$userId}");
            }

            return back()->with('success', 'Kuota magang berhasil dihapus. Status permohonan pendaftar tidak terpengaruh.');
        } catch (\Exception $e) {
            \Log::error('Error deleting kuota: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus kuota: ' . $e->getMessage()
            ]);
        }
    }

    // Atur Jadwal Magang
    public function aturJadwalMagang()
    {
        $jadwal = JadwalMagang::orderBy('created_at', 'desc')->get();
        return view('admin.atur_jadwal_magang', compact('jadwal'));
    }

    public function storeJadwalMagang(Request $request)
    {
        try {
            // Setiap divisi punya jadwal sendiri
            $request->validate([
                'periode' => 'required|string|max:255',
                'posisi' => 'required|string|max:255',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after:tgl_mulai',
            ], [
                'posisi.required' => 'Posisi/Divisi wajib diisi.',
                'tgl_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            ]);

            // Normalisasi input: trim untuk menghindari masalah whitespace
            $periode = trim($request->periode);
            $posisi = trim($request->posisi);

            // Validasi: kombinasi periode + posisi harus unique (case-insensitive comparison)
            $exists = JadwalMagang::whereRaw('LOWER(TRIM(periode)) = ?', [strtolower($periode)])
                ->whereRaw('LOWER(TRIM(posisi)) = ?', [strtolower($posisi)])
                ->exists();
            
            if ($exists) {
                return back()->withErrors([
                    'posisi' => 'Kombinasi periode dan posisi ini sudah ada. Silakan gunakan periode atau posisi yang berbeda.',
                ])->withInput();
            }

            JadwalMagang::create([
                'periode' => $periode, // Normalized: trimmed
                'posisi' => $posisi, // Normalized: trimmed
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
            ]);

            return back()->with('success', 'Jadwal magang berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing jadwal: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menambahkan jadwal. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function updateJadwalMagang(Request $request, $id)
    {
        try {
            // Setiap divisi punya jadwal sendiri
            $request->validate([
                'periode' => 'required|string|max:255',
                'posisi' => 'required|string|max:255',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after:tgl_mulai',
            ], [
                'posisi.required' => 'Posisi/Divisi wajib diisi.',
                'tgl_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
            ]);

            $jadwal = JadwalMagang::findOrFail($id);
            
            // Normalisasi input: trim untuk menghindari masalah whitespace
            $periode = trim($request->periode);
            $posisi = trim($request->posisi);
            
            // Validasi: kombinasi periode + posisi harus unique (kecuali untuk record yang sedang diupdate)
            // Case-insensitive comparison untuk konsistensi dengan matching di user side
            $exists = JadwalMagang::whereRaw('LOWER(TRIM(periode)) = ?', [strtolower($periode)])
                ->whereRaw('LOWER(TRIM(posisi)) = ?', [strtolower($posisi)])
                ->where('id', '!=', $id)
                ->exists();
            
            if ($exists) {
                return back()->withErrors([
                    'posisi' => 'Kombinasi periode dan posisi ini sudah digunakan oleh jadwal lain. Silakan gunakan periode atau posisi yang berbeda.',
                ])->withInput();
            }
            
            // Update dengan data yang sudah dinormalisasi
            $jadwal->update([
                'periode' => $periode,
                'posisi' => $posisi,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
            ]);

            return back()->with('success', 'Jadwal magang berhasil diperbarui. Status permohonan pendaftar tidak terpengaruh.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating jadwal: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui jadwal. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function deleteJadwalMagang($id)
    {
        try {
            $jadwal = JadwalMagang::findOrFail($id);
            
            // Catatan penting: Admin tetap dapat menghapus jadwal tanpa memengaruhi status pendaftar
            // Status permohonan (termasuk "Diterima" dan "Ditolak" yang bersifat final) tidak akan berubah
            // Jadwal tidak memiliki relasi langsung dengan permohonan, hanya melalui kuota
            
            // Ambil semua user yang memiliki permohonan dengan kuota yang menggunakan jadwal ini
            $userIds = DB::table('permohonan_magang')
                ->join('permohonan_kuota', 'permohonan_magang.id', '=', 'permohonan_kuota.permohonan_magang_id')
                ->join('kuota_magang', 'permohonan_kuota.kuota_magang_id', '=', 'kuota_magang.id')
                ->where('kuota_magang.periode', $jadwal->periode)
                ->where('kuota_magang.posisi', $jadwal->posisi)
                ->pluck('permohonan_magang.user_id')
                ->unique();
            
            $jadwal->delete();
            
            // Clear cache untuk semua user yang terpengaruh
            foreach ($userIds as $userId) {
                CacheHelper::clearUserCache($userId);
                Cache::forget("dashboard_user_{$userId}");
                Cache::forget("riwayat_user_{$userId}");
            }

            return back()->with('success', 'Jadwal magang berhasil dihapus. Status permohonan pendaftar tidak terpengaruh.');
        } catch (\Exception $e) {
            \Log::error('Error deleting jadwal: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus jadwal: ' . $e->getMessage()
            ]);
        }
    }

    // Pengawasan Sumber Daya
    public function pengawasanSumberDaya()
    {
        // Ambil semua kuota dengan relasi
        $allKuota = KuotaMagang::with(['permohonanMagang.user', 'jadwalMagang'])
            ->orderBy('posisi')
            ->orderBy('periode')
            ->get();
        
        // Group data per divisi (posisi)
        $dataPerDivisi = $allKuota->groupBy('posisi')->map(function ($kuotas, $posisi) {
            $totalKuota = 0;
            $totalTerpakai = 0;
            $distribusiStatus = [
                'Diajukan' => 0,
                'Diverifikasi' => 0,
                'Diterima' => 0,
                'Ditolak' => 0,
            ];
            
            $detailPeriode = collect();
            $allUserIds = collect(); // Untuk tracking user unik per divisi
            
            foreach ($kuotas as $kuota) {
                $totalKuota += $kuota->kuota_max;
                $totalTerpakai += $kuota->kuota_terpakai;
                
                // Hitung pendaftar per kuota (unik user_id)
                $permohonan = $kuota->permohonanMagang;
                $pendaftarPeriode = $permohonan->unique('user_id')->count();
                
                // Kumpulkan semua user_id untuk divisi ini (unique)
                $userIdsPeriode = $permohonan->pluck('user_id')->unique();
                $allUserIds = $allUserIds->merge($userIdsPeriode)->unique();
                
                // Distribusi status per periode
                $statusPeriode = [
                    'Diajukan' => $permohonan->where('status', 'Diajukan')->count(),
                    'Diverifikasi' => $permohonan->where('status', 'Diverifikasi')->count(),
                    'Diterima' => $permohonan->where('status', 'Diterima')->count(),
                    'Ditolak' => $permohonan->where('status', 'Ditolak')->count(),
                ];
                
                // Akumulasi distribusi status
                foreach ($statusPeriode as $status => $count) {
                    $distribusiStatus[$status] += $count;
                }
                
                // Detail per periode
                $detailPeriode->push([
                    'id' => $kuota->id,
                    'periode' => $kuota->periode,
                    'kuota_max' => $kuota->kuota_max,
                    'kuota_terpakai' => $kuota->kuota_terpakai,
                    'kuota_tersedia' => $kuota->kuota_max - $kuota->kuota_terpakai,
                    'total_pendaftar' => $pendaftarPeriode,
                    'status_distribusi' => $statusPeriode,
                    'jadwal' => $kuota->jadwalMagang,
                ]);
            }
            
            return [
                'posisi' => $posisi,
                'total_kuota' => $totalKuota,
                'total_terpakai' => $totalTerpakai,
                'total_tersedia' => $totalKuota - $totalTerpakai,
                'total_pendaftar' => $allUserIds->count(), // Total pendaftar unik per divisi
                'distribusi_status' => $distribusiStatus,
                'detail_periode' => $detailPeriode,
            ];
        });
        
        // Statistik keseluruhan
        $statistikKeseluruhan = [
            'total_divisi' => $dataPerDivisi->count(),
            'total_kuota_keseluruhan' => $allKuota->sum('kuota_max'),
            'total_terpakai_keseluruhan' => $allKuota->sum('kuota_terpakai'),
            'total_tersedia_keseluruhan' => $allKuota->sum('kuota_max') - $allKuota->sum('kuota_terpakai'),
            'total_pendaftar_keseluruhan' => PermohonanMagang::distinct()->count('user_id'),
        ];
        
        return view('admin.pengawasan_sumber_daya', compact('dataPerDivisi', 'statistikKeseluruhan'));
    }

    // Kelola Data Pendaftar
    public function kelolaDataPendaftar()
    {
        $pendaftar = User::where('role', 'user') // Sesuai ERD: role adalah 'user' atau 'admin'
            ->with(['permohonanMagang', 'dokumen'])
            ->paginate(15);

        return view('admin.kelola_data_pendaftar', compact('pendaftar'));
    }

    public function updateDataPendaftar(Request $request, $id)
    {
        // Sesuai ERD: nama, email, no_telepon, instansi
        $request->validate([
            'nama' => 'required|string|max:255', // Sesuai ERD: nama bukan name
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'no_telepon' => 'nullable|string',
            'instansi' => 'nullable|string', // Sesuai ERD: instansi
        ]);

        $pendaftar = User::findOrFail($id);
        $pendaftar->update($request->only([
            'nama', // Sesuai ERD: nama bukan name
            'email',
            'no_telepon',
            'instansi', // Sesuai ERD: instansi
        ]));

        return back()->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    public function deleteDataPendaftar($id)
    {
        $pendaftar = User::findOrFail($id);
        if ($pendaftar->role === 'user') { // Sesuai ERD: role adalah 'user' atau 'admin'
            $pendaftar->delete();
            return back()->with('success', 'Data pendaftar berhasil dihapus.');
        }

        return back()->withErrors(['error' => 'Tidak dapat menghapus data ini.']);
    }

/**
     * Menampilkan form untuk mengirim revisi ke pendaftar
     */
    public function kirimRevisi($id = null)
    {
        $pendaftar = null;
        $permohonan = null;
        
        // Ambil semua pendaftar yang memiliki permohonan aktif untuk dropdown
        $allPendaftar = User::where('role', 'user')
            ->whereHas('permohonanMagang', function($q) {
                $q->whereIn('status', ['Diajukan', 'Diverifikasi']);
            })
            ->with(['permohonanMagang' => function($q) {
                $q->whereIn('status', ['Diajukan', 'Diverifikasi'])->orderBy('created_at', 'desc');
            }, 'dokumen'])
            ->orderBy('nama')
            ->get();
        
        if ($id) {
            $pendaftar = User::where('role', 'user')
                ->with(['dokumen', 'permohonanMagang' => function($q) {
                    $q->whereIn('status', ['Diajukan', 'Diverifikasi'])->orderBy('created_at', 'desc');
                }])
                ->findOrFail($id);
            
            $permohonan = $pendaftar->permohonanMagang->first();
        }

        return view('admin.kirim_revisi', compact('pendaftar', 'permohonan', 'allPendaftar'));
    }
    
    /**
     * API: Ambil permohonan berdasarkan user_id
     */
    public function getPermohonanByUser($userId)
    {
        try {
            $user = User::where('role', 'user')->findOrFail($userId);
            
            $permohonan = PermohonanMagang::where('user_id', $userId)
                ->whereIn('status', ['Diajukan', 'Diverifikasi'])
                ->orderBy('created_at', 'desc')
                ->get(['id', 'status', 'created_at']);
            
            return response()->json([
                'success' => true,
                'permohonan' => $permohonan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengirim revisi kepada pendaftar (mengubah status menjadi Revisi, kirim notifikasi dan email)
     */
    public function storeRevisi(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'permohonan_magang_id' => 'required|exists:permohonan_magang,id',
                'judul' => 'required|string|max:255',
                'tipe' => 'required|in:revisi',
                'pesan' => 'required|string|max:2000',
                'kirim_email' => 'nullable|boolean',
            ], [
                'user_id.required' => 'Pendaftar harus dipilih.',
                'permohonan_magang_id.required' => 'Permohonan harus dipilih.',
                'judul.required' => 'Judul notifikasi wajib diisi.',
                'tipe.required' => 'Tipe notifikasi wajib dipilih.',
                'pesan.required' => 'Pesan revisi wajib diisi.',
            ]);

            $user = User::findOrFail($request->user_id);
            $permohonan = PermohonanMagang::findOrFail($request->permohonan_magang_id);
            
            // Validasi: Pastikan permohonan milik user yang dipilih
            if ($permohonan->user_id != $user->id) {
                return back()->withErrors([
                    'error' => 'Permohonan tidak sesuai dengan pendaftar yang dipilih.'
                ])->withInput();
            }
            
            // Validasi: Status harus bisa diubah menjadi Revisi
            $statusFinal = ['Diterima', 'Ditolak'];
            if (in_array($permohonan->status, $statusFinal)) {
                return back()->withErrors([
                    'error' => "Status permohonan sudah final ({$permohonan->status}) dan tidak dapat diubah menjadi Revisi."
                ])->withInput();
            }
            
            $adminId = Auth::id();

            // Update status menjadi Revisi
            // Simpan pesan sebagai catatan_revisi juga untuk kompatibilitas
            $permohonan->update([
                'status' => 'Revisi',
                'catatan_revisi' => $request->pesan,
                'alasan_penolakan' => null, // Hapus alasan penolakan jika ada
            ]);

            Log::info("Status permohonan {$permohonan->id} diubah menjadi Revisi untuk user {$user->id}");

            // Buat notifikasi untuk user dengan judul, tipe, dan pesan dari form
            $notifikasi = Notifikasi::create([
                'user_id' => $user->id,
                'permohonan_magang_id' => $permohonan->id,
                'admin_id' => $adminId,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
                'tipe' => 'revisi',
                'dibaca' => false,
            ]);

            Log::info("Notifikasi revisi berhasil dibuat dengan ID {$notifikasi->id} untuk user {$user->id}");

            // Kirim email - default true (checkbox default checked)
            // Jika checkbox checked, nilai = "1", jika tidak checked, tidak ada parameter
            $kirimEmail = $request->has('kirim_email') ? $request->boolean('kirim_email') : true;
            $emailStatus = '';
            $emailSent = false;
            
            if ($kirimEmail) {
                try {
                    // Pastikan email user valid
                    if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                        Log::warning("Email user tidak valid: {$user->email} untuk user {$user->id}");
                        $emailStatus = ' (email tidak valid)';
                    } else {
                        // Kirim email secara synchronous untuk memastikan terkirim
                        Mail::to($user->email)->send(
                            new NotifikasiKekuranganSyarat(
                                $user,
                                $request->judul,
                                $request->pesan
                            )
                        );
                        $emailSent = true;
                        Log::info("Email revisi berhasil dikirim ke {$user->email} untuk permohonan {$permohonan->id}", [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'permohonan_id' => $permohonan->id,
                            'judul' => $request->judul,
                        ]);
                        $emailStatus = ' (termasuk email)';
                    }
                } catch (\Swift_TransportException $e) {
                    // Error transport (SMTP, dll)
                    Log::error("Gagal mengirim email revisi (Transport Error) ke {$user->email}: " . $e->getMessage(), [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                    $emailStatus = ' (email gagal dikirim - cek konfigurasi email di .env)';
                } catch (\Exception $e) {
                    // Error lainnya
                    Log::error("Gagal mengirim email revisi ke {$user->email}: " . $e->getMessage(), [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    $emailStatus = ' (email gagal dikirim, silakan cek log)';
                }
            } else {
                Log::info("Email tidak dikirim karena checkbox tidak dicentang untuk user {$user->id}");
            }

            // PENTING: Clear cache notifikasi SEBELUM clear user cache untuk memastikan notifikasi langsung muncul
            // Clear cache dengan multiple attempts untuk memastikan cache benar-benar ter-clear
            $cacheKeys = [
                "notifikasi_user_{$user->id}",
                "dashboard_user_{$user->id}",
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            // Clear cache menggunakan helper
            CacheHelper::clearAdminCache();
            CacheHelper::clearUserCache($user->id);
            
            // Clear cache lagi untuk memastikan (race condition protection)
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            // Verifikasi notifikasi benar-benar dibuat
            $notifikasiCheck = Notifikasi::find($notifikasi->id);
            if (!$notifikasiCheck) {
                Log::error("Notifikasi tidak ditemukan setelah dibuat", [
                    'notifikasi_id' => $notifikasi->id,
                    'user_id' => $user->id,
                ]);
            } else {
                Log::info("Notifikasi berhasil diverifikasi", [
                    'notifikasi_id' => $notifikasi->id,
                    'user_id' => $user->id,
                    'judul' => $notifikasiCheck->judul,
                    'tipe' => $notifikasiCheck->tipe,
                    'dibaca' => $notifikasiCheck->dibaca,
                ]);
            }
            
            // Refresh permohonan untuk memastikan data terbaru
            $permohonan->refresh();

            Log::info("Cache dibersihkan untuk user {$user->id} setelah mengirim revisi", [
                'notifikasi_id' => $notifikasi->id,
                'permohonan_id' => $permohonan->id,
                'status' => $permohonan->status,
                'user_id' => $user->id,
                'cache_keys_cleared' => $cacheKeys,
            ]);

            // Buat pesan sukses yang informatif
            $successMessage = '✅ Revisi berhasil dikirim kepada ' . $user->nama . ' (' . $user->email . '). ';
            $successMessage .= 'Status permohonan telah diubah menjadi "Revisi" dan pendaftar akan menerima notifikasi';
            if ($kirimEmail && $emailSent) {
                $successMessage .= ' serta email';
            } elseif ($kirimEmail && !$emailSent) {
                $successMessage .= ' (email gagal dikirim)';
            }
            $successMessage .= ' untuk memperbaiki dokumen.';
            
            // Redirect ke halaman detail pendaftar dengan success message
            // Menggunakan redirect ke route detail pendaftar untuk memastikan flash message muncul
            return redirect()->route('admin.detail_pendaftar', $permohonan->id)
                ->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error sending revision: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengirim revisi. Silakan coba lagi.'
            ])->withInput();
        }
    }

    // Manajemen Galeri Magang
    public function kelolaGaleri()
    {
        $galeri = \App\Models\GaleriMagang::orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.kelola_galeri', compact('galeri'));
    }

    public function storeGaleri(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
                'urutan' => 'nullable|integer|min:0',
                'aktif' => 'nullable|boolean',
            ], [
                'judul.required' => 'Judul wajib diisi.',
                'foto.required' => 'Foto wajib diunggah.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.mimes' => 'Format gambar yang didukung: JPEG, PNG, JPG, GIF.',
                'foto.max' => 'Ukuran gambar maksimal 5MB.',
            ]);

            // Upload foto
            $file = $request->file('foto');
            $filename = 'galeri_' . time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('galeri', $filename, 'public');

            \App\Models\GaleriMagang::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'foto' => $path,
                'aktif' => $request->has('aktif') ? (bool)$request->aktif : true,
                'urutan' => $request->urutan ?? 0,
            ]);
            
            // Clear cache untuk landing page dan galeri
            Cache::forget('welcome_page_data');
            Cache::forget('galeri_magang_aktif');

            return back()->with('success', 'Foto galeri berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing galeri: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menambahkan foto galeri. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function updateGaleri(Request $request, $id)
    {
        try {
            $galeri = \App\Models\GaleriMagang::findOrFail($id);
            
            $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'urutan' => 'nullable|integer|min:0',
                'aktif' => 'nullable|boolean',
            ], [
                'judul.required' => 'Judul wajib diisi.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.mimes' => 'Format gambar yang didukung: JPEG, PNG, JPG, GIF.',
                'foto.max' => 'Ukuran gambar maksimal 5MB.',
            ]);

            $updateData = [
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'aktif' => $request->has('aktif') ? (bool)$request->aktif : $galeri->aktif,
                'urutan' => $request->urutan ?? $galeri->urutan,
            ];

            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($galeri->foto && \Storage::disk('public')->exists($galeri->foto)) {
                    \Storage::disk('public')->delete($galeri->foto);
                }
                
                $file = $request->file('foto');
                $filename = 'galeri_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('galeri', $filename, 'public');
                $updateData['foto'] = $path;
            }

            $galeri->update($updateData);
            
            // Clear cache untuk landing page dan galeri
            Cache::forget('welcome_page_data');
            Cache::forget('galeri_magang_aktif');

            return back()->with('success', 'Foto galeri berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating galeri: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat memperbarui foto galeri. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function deleteGaleri($id)
    {
        try {
            $galeri = \App\Models\GaleriMagang::findOrFail($id);
            
            // Hapus foto dari storage
            if ($galeri->foto && \Storage::disk('public')->exists($galeri->foto)) {
                \Storage::disk('public')->delete($galeri->foto);
            }
            
            $galeri->delete();
            
            // Clear cache untuk landing page dan galeri
            Cache::forget('welcome_page_data');
            Cache::forget('galeri_magang_aktif');

            return back()->with('success', 'Foto galeri berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting galeri: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus foto galeri. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Get monthly trend data for line chart
     * Returns data for the last N months
     */
    private function getMonthlyTrendData($months = 12)
    {
        $data = [];
        $labels = [];
        
        // Generate labels and data for the last N months
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Count permohonan created in this month
            $count = PermohonanMagang::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            
            // Format label (e.g., "Jan 2025")
            $labels[] = $date->format('M Y');
            $data[] = $count;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Upload Surat Kerja (SK) untuk peserta yang diterima
     */
    public function uploadSK(Request $request, $id)
    {
        try {
            $permohonan = PermohonanMagang::findOrFail($id);
            
            // Validasi: Hanya bisa upload SK untuk status Diterima
            if ($permohonan->status !== 'Diterima') {
                return back()->withErrors([
                    'error' => 'Surat Kerja hanya dapat diunggah untuk permohonan dengan status "Diterima".'
                ]);
            }
            
            $request->validate([
                'surat_kerja' => 'required|file|mimes:pdf|max:5120', // Max 5MB
            ], [
                'surat_kerja.required' => 'File Surat Kerja wajib diunggah.',
                'surat_kerja.file' => 'File yang diunggah tidak valid.',
                'surat_kerja.mimes' => 'File harus berformat PDF.',
                'surat_kerja.max' => 'Ukuran file maksimal 5MB.',
            ]);
            
            // Hapus file lama jika ada
            if ($permohonan->surat_kerja && Storage::exists($permohonan->surat_kerja)) {
                Storage::delete($permohonan->surat_kerja);
            }
            
            // Simpan file baru
            $file = $request->file('surat_kerja');
            $fileName = 'sk_' . $permohonan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('surat_kerja', $fileName, 'public');
            
            // Update database
            $permohonan->update([
                'surat_kerja' => $filePath
            ]);
            
            // Kirim email ke user
            $user = $permohonan->user;
            $emailSent = false;
            $emailStatus = '';
            
            try {
                // Pastikan email user valid
                if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    Log::warning("Email user tidak valid: {$user->email} untuk user {$user->id}");
                    $emailStatus = ' (email tidak valid)';
                } else {
                    // Kirim email secara synchronous
                    Mail::to($user->email)->send(
                        new SuratKerjaTersedia($user, $permohonan)
                    );
                    $emailSent = true;
                    Log::info("Email Surat Kerja berhasil dikirim ke {$user->email} untuk permohonan {$permohonan->id}", [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'permohonan_id' => $permohonan->id,
                    ]);
                    $emailStatus = ' (termasuk email)';
                }
            } catch (\Swift_TransportException $e) {
                Log::error("Gagal mengirim email Surat Kerja (Transport Error) ke {$user->email}: " . $e->getMessage(), [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
                $emailStatus = ' (email gagal dikirim - cek konfigurasi email di .env)';
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email Surat Kerja ke {$user->email}: " . $e->getMessage(), [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $emailStatus = ' (email gagal dikirim, silakan cek log)';
            }
            
            // Clear cache - pastikan semua cache terkait user di-clear
            CacheHelper::clearUserCache($permohonan->user_id);
            $cacheKeys = [
                "dashboard_user_{$permohonan->user_id}",
                "riwayat_user_{$permohonan->user_id}",
                "notifikasi_user_{$permohonan->user_id}",
            ];
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            Log::info("Surat Kerja berhasil diunggah untuk permohonan {$permohonan->id}", [
                'permohonan_id' => $permohonan->id,
                'user_id' => $permohonan->user_id,
                'file_path' => $filePath,
                'email_sent' => $emailSent,
            ]);
            
            $successMessage = 'Surat Kerja berhasil diunggah.';
            if ($emailSent) {
                $successMessage .= ' Email notifikasi telah dikirim ke ' . $user->email . '.';
            } else {
                $successMessage .= $emailStatus;
            }
            
            return back()->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error uploading SK: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengunggah Surat Kerja. Silakan coba lagi.'
            ])->withInput();
        }
    }
}
