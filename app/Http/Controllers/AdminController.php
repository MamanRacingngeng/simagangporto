<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermohonanMagang;
use App\Models\Dokumen;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use App\Models\Notifikasi;
use App\Mail\NotifikasiKekuranganSyarat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $totalPendaftar = User::where('role', 'user')->count(); // Sesuai ERD: role adalah 'user' atau 'admin'
        $totalPermohonan = PermohonanMagang::count();
        $siapDiverifikasi = PermohonanMagang::where('status', 'Diajukan')->count(); // Status = "Diajukan"
        $perluKeputusan = PermohonanMagang::where('status', 'Diverifikasi')->count(); // Status = "Diverifikasi"
        $diterima = PermohonanMagang::where('status', 'Diterima')->count(); // Status = "Diterima"
        $ditolak = PermohonanMagang::where('status', 'Ditolak')->count(); // Status = "Ditolak"

        // Cari semua jadwal aktif berdasarkan tanggal saat ini (setiap divisi punya jadwal sendiri)
        $today = now()->toDateString();
        $jadwalAktif = JadwalMagang::where('tgl_mulai', '<=', $today)
            ->where('tgl_selesai', '>=', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Ambil semua kuota aktif untuk semua divisi
        $kuotaAktif = collect();
        foreach ($jadwalAktif as $jadwal) {
            $kuota = KuotaMagang::where('periode', $jadwal->periode)
                ->where('posisi', $jadwal->posisi)
                ->first();
            if ($kuota) {
                $kuotaAktif->push($kuota);
            }
        }

        // Aktivitas Pendaftar Terbaru (5-7 item)
        $activities = PermohonanMagang::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get()
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

        // ========== DATA UNTUK GRAFIK ==========
        
        // 1. Bar Chart Data - Jumlah pendaftar berdasarkan status
        $barChartData = [
            'labels' => ['Siap Diverifikasi', 'Perlu Keputusan', 'Diterima', 'Ditolak'],
            'data' => [
                $siapDiverifikasi,
                $perluKeputusan,
                $diterima,
                $ditolak
            ],
            'colors' => ['#06B6D4', '#F59E0B', '#10B981', '#EF4444']
        ];

        // 2. Line Chart Data - Tren pendaftar per bulan (12 bulan terakhir)
        $lineChartData = $this->getMonthlyTrendData(12);

        // 3. Pie Chart Data - Persentase status pendaftar
        $totalStatus = $siapDiverifikasi + $perluKeputusan + $diterima + $ditolak;
        $pieChartData = [
            'labels' => ['Siap Diverifikasi', 'Perlu Keputusan', 'Diterima', 'Ditolak'],
            'data' => [
                $siapDiverifikasi,
                $perluKeputusan,
                $diterima,
                $ditolak
            ],
            'colors' => ['#06B6D4', '#F59E0B', '#10B981', '#EF4444'],
            'total' => $totalStatus
        ];

        return view('admin.dashboard', compact(
            'totalPendaftar', 
            'totalPermohonan', 
            'siapDiverifikasi', 
            'perluKeputusan',
            'diterima',
            'ditolak',
            'jadwalAktif',
            'kuotaAktif',
            'activities',
            'barChartData',
            'lineChartData',
            'pieChartData'
        ));
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
        $query = PermohonanMagang::with(['user', 'dokumen', 'kuotaMagang']);
        
        // Filter berdasarkan status jika ada parameter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $permohonan = $query->orderBy('created_at', 'desc')->paginate(15);

        // Decision: Ada permohonan baru?
        $adaPermohonanBaru = PermohonanMagang::where('status', 'Diajukan')->exists();

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
                'status' => 'required|in:Diajukan,Diverifikasi,Diterima,Ditolak',
                'alasan_penolakan' => 'required_if:status,Ditolak|string|max:1000',
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
            } else {
                // Jika bukan ditolak, hapus alasan penolakan
                $updateData['alasan_penolakan'] = null;
            }
            
            // Update status - Observer akan menangani sinkronisasi kuota secara otomatis
            $permohonan->update($updateData);

            return back()->with('success', 'Status permohonan berhasil diperbarui. ' . ($request->status === 'Diterima' ? 'Kuota telah disinkronisasi secara otomatis.' : ''));
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
            DB::table('permohonan_kuota')
                ->where('kuota_magang_id', $id)
                ->delete();
            
            $kuota->delete();

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
            
            $jadwal->delete();

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

    // Notifikasi Kekurangan Syarat User
    public function notifikasiKekuranganSyarat()
    {
        // Ambil semua user yang memiliki permohonan dengan dokumen tidak lengkap
        $usersKurangSyarat = User::where('role', 'user')
            ->with(['dokumen', 'permohonanMagang'])
            ->get()
            ->filter(function ($user) {
                $dokumen = $user->dokumen;
                $hasPermohonan = $user->permohonanMagang->count() > 0;
                
                // Cek apakah dokumen tidak lengkap
                $dokumenLengkap = $dokumen && 
                    !empty($dokumen->cv) && 
                    !empty($dokumen->surat_pengantar) && 
                    !empty($dokumen->proposal);
                
                // User yang memiliki permohonan tapi dokumen tidak lengkap
                return $hasPermohonan && !$dokumenLengkap;
            })
            ->map(function ($user) {
                $dokumen = $user->dokumen;
                $permohonan = $user->permohonanMagang->first();
                
                $kekurangan = [];
                if (!$dokumen || empty($dokumen->cv)) {
                    $kekurangan[] = 'CV';
                }
                if (!$dokumen || empty($dokumen->surat_pengantar)) {
                    $kekurangan[] = 'Surat Pengantar';
                }
                if (!$dokumen || empty($dokumen->proposal)) {
                    $kekurangan[] = 'Proposal';
                }
                
                return [
                    'user' => $user,
                    'permohonan' => $permohonan,
                    'kekurangan' => $kekurangan,
                    'dokumen' => $dokumen,
                ];
            });

        // Ambil semua pendaftar untuk dropdown
        $allPendaftar = User::where('role', 'user')
            ->with(['permohonanMagang', 'dokumen'])
            ->orderBy('nama')
            ->get();

        return view('admin.notifikasi_kekurangan_syarat', compact('usersKurangSyarat', 'allPendaftar'));
    }

    /**
     * Menampilkan form untuk mengirim notifikasi ke pendaftar tertentu
     */
    public function kirimNotifikasi($id = null)
    {
        $pendaftar = null;
        $permohonan = null;
        
        // Ambil semua pendaftar untuk dropdown
        $allPendaftar = User::where('role', 'user')
            ->with(['permohonanMagang', 'dokumen'])
            ->orderBy('nama')
            ->get();
        
        if ($id) {
            $pendaftar = User::where('role', 'user')
                ->with(['dokumen', 'permohonanMagang.kuotaMagang'])
                ->findOrFail($id);
            
            $permohonan = $pendaftar->permohonanMagang->first();
        }

        return view('admin.kirim_notifikasi', compact('pendaftar', 'permohonan', 'allPendaftar'));
    }

    /**
     * Mengirim notifikasi kepada pendaftar
     */
    public function storeNotifikasi(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'permohonan_magang_id' => 'nullable|exists:permohonan_magang,id',
                'judul' => 'required|string|max:255',
                'pesan' => 'required|string',
                'tipe' => 'required|in:info,warning,error,success',
                'kirim_email' => 'nullable|boolean',
            ], [
                'user_id.required' => 'Pendaftar harus dipilih.',
                'judul.required' => 'Judul notifikasi wajib diisi.',
                'pesan.required' => 'Pesan notifikasi wajib diisi.',
                'tipe.required' => 'Tipe notifikasi wajib dipilih.',
            ]);

            $user = User::findOrFail($request->user_id);
            $adminId = Auth::id();

            // Simpan notifikasi ke database
            $notifikasi = Notifikasi::create([
                'user_id' => $user->id,
                'permohonan_magang_id' => $request->permohonan_magang_id,
                'admin_id' => $adminId,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
                'tipe' => $request->tipe,
                'dibaca' => false,
            ]);

            // Kirim email jika diminta
            if ($request->has('kirim_email') && $request->kirim_email) {
                try {
                    Mail::to($user->email)->send(
                        new NotifikasiKekuranganSyarat(
                            $user,
                            $request->judul,
                            $request->pesan
                        )
                    );
                    Log::info("Notifikasi email berhasil dikirim ke {$user->email}");
                } catch (\Exception $e) {
                    Log::error("Gagal mengirim email notifikasi: " . $e->getMessage());
                    // Notifikasi tetap tersimpan meskipun email gagal
                }
            }

            return back()->with('success', 'Notifikasi berhasil dikirim kepada ' . $user->nama . ($request->has('kirim_email') && $request->kirim_email ? ' (termasuk email)' : ''));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengirim notifikasi. Silakan coba lagi.'
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
}
