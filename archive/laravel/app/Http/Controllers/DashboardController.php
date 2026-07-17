<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanMagang;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use App\Models\Dokumen;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CacheHelper;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Cache key untuk user-specific data (cache 5 menit - lebih lama untuk performa)
        $cacheKey = "dashboard_user_{$user->id}";
        
        $data = Cache::remember($cacheKey, 300, function () use ($user) {
            // Optimasi: Ambil SEMUA data dalam satu batch untuk mengurangi query
            $permohonanQuery = PermohonanMagang::where('user_id', $user->id);
            
            // Statistik dalam satu query
            $stats = (clone $permohonanQuery)
                ->selectRaw('
                    COUNT(*) as total_lamaran,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as menunggu_verifikasi,
                    SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as perlu_perbaikan,
                    SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as diterima
                ', ['Diajukan', 'Ditolak', 'Revisi', 'Diterima'])
                ->first();
            
            // Permohonan terbaru dengan eager loading
            $permohonanTerbaru = (clone $permohonanQuery)
                ->with(['dokumen:id,user_id,cv,surat_pengantar,proposal', 'kuotaMagang:id,periode,posisi'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'user_id', 'status', 'created_at', 'dokumen_id', 'alasan_penolakan']);
            
            // Permohonan aktif untuk action items
            $permohonanAktif = (clone $permohonanQuery)
                ->with('dokumen:id,user_id,cv,surat_pengantar,proposal')
                ->orderBy('created_at', 'desc')
                ->first(['id', 'user_id', 'status', 'dokumen_id', 'alasan_penolakan']);
            
            // Cek profil lengkap
            $fieldWajib = ['nama', 'email', 'no_telepon', 'ttl', 'domisili', 'nim', 'semester', 'program', 'universitas'];
            $profilLengkap = true;
            foreach ($fieldWajib as $field) {
                if (empty($user->$field)) {
                    $profilLengkap = false;
                    break;
                }
            }
            
            // Action Items
            $actionItem = null;
            $actionItemType = null;
            
            if (!$permohonanAktif) {
                if (!$profilLengkap) {
                    $actionItem = 'Langkah 1: Lengkapi Data Diri Anda di menu Profil dan Ajukan Permohonan di menu Lowongan.';
                    $actionItemType = 'info';
                }
            } else {
                $dokumen = $permohonanAktif->dokumen;
                $dokumenLengkap = $dokumen && !empty($dokumen->cv) && !empty($dokumen->surat_pengantar) && !empty($dokumen->proposal);
                
                if ($permohonanAktif->status === 'Ditolak') {
                    $actionItem = 'Permohonan Anda Ditolak. Silakan cek detail alasan penolakan di menu Lamaran Saya.';
                    $actionItemType = 'error';
                } elseif ($permohonanAktif->status === 'Revisi') {
                    $actionItem = '⚠️ Permohonan Anda Memerlukan Revisi! Silakan cek catatan revisi dan perbaiki dokumen Anda di menu Lamaran Saya.';
                    $actionItemType = 'warning';
                } elseif (!$dokumenLengkap && $permohonanAktif->status !== 'Ditolak' && $permohonanAktif->status !== 'Revisi') {
                    $actionItem = '⚠️ Dokumen Belum Lengkap! Harap segera lengkapi dokumen Anda di menu Lamaran Saya untuk menghindari penolakan.';
                    $actionItemType = 'warning';
                }
            }
            
            return [
                'totalLamaran' => $stats->total_lamaran ?? 0,
                'menungguVerifikasi' => $stats->menunggu_verifikasi ?? 0,
                'perluPerbaikan' => $stats->perlu_perbaikan ?? 0,
                'diterima' => $stats->diterima ?? 0,
                'permohonanTerbaru' => $permohonanTerbaru,
                'actionItem' => $actionItem,
                'actionItemType' => $actionItemType,
            ];
        });
        
        // Total Kebutuhan - cache 10 menit (data jarang berubah)
        $totalKebutuhan = Cache::remember('total_kuota_max', 600, function () {
            return KuotaMagang::sum('kuota_max');
        });
        
        // OPTIMASI: Query lowongan tersedia dengan JOIN langsung - jauh lebih cepat
        $today = now()->toDateString();
        $lowonganData = Cache::remember("lowongan_tersedia_{$today}", 300, function () use ($today) {
            try {
                // Query dengan JOIN langsung - menghilangkan filter collection yang lambat
                $lowonganList = DB::table('kuota_magang as km')
                    ->join('jadwal_magang as jm', function($join) {
                        $join->on(DB::raw('LOWER(TRIM(km.periode))'), '=', DB::raw('LOWER(TRIM(jm.periode))'))
                             ->on(DB::raw('LOWER(TRIM(COALESCE(km.posisi, \'\')))'), '=', DB::raw('LOWER(TRIM(COALESCE(jm.posisi, \'\')))'));
                    })
                    ->whereColumn('km.kuota_terpakai', '<', 'km.kuota_max')
                    ->where('jm.tgl_selesai', '>=', $today)
                    ->select('km.id', 'km.periode', 'km.posisi', 'jm.tgl_mulai', 'jm.tgl_selesai')
                    ->get();
                
                $lowonganTersedia = $lowonganList->count() > 0;
                $jumlahLowongan = $lowonganList->count();
                
                // Cek jadwal akan datang
                $adaJadwalAkanDatang = $lowonganList->filter(function ($item) use ($today) {
                    return $item->tgl_mulai > $today;
                })->count() > 0;
                
                return [
                    'lowonganTersedia' => $lowonganTersedia,
                    'jumlahLowongan' => $jumlahLowongan,
                    'adaJadwalAkanDatang' => $adaJadwalAkanDatang,
                ];
            } catch (\Exception $e) {
                \Log::error('Error in lowongan_tersedia query: ' . $e->getMessage());
                return [
                    'lowonganTersedia' => false,
                    'jumlahLowongan' => 0,
                    'adaJadwalAkanDatang' => false,
                ];
            }
        });
        
        // Ambil notifikasi yang belum dibaca - tanpa cache untuk memastikan data real-time
        // PENTING: Query ini mengambil SEMUA tipe notifikasi termasuk 'revisi'
        // Tidak menggunakan cache untuk memastikan notifikasi selalu ter-update
        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->where('dibaca', false) // Hanya notifikasi yang belum dibaca
            ->orderBy('created_at', 'desc') // Terbaru di atas
            ->limit(10) // Batasi jumlah notifikasi
            ->get(['id', 'user_id', 'judul', 'pesan', 'tipe', 'dibaca', 'created_at', 'permohonan_magang_id']);
        
        // Filter notifikasi revisi: hanya tampilkan jika status permohonan masih "Revisi"
        // Optimasi: Ambil semua permohonan revisi dalam satu query untuk menghindari N+1
        $notifikasiRevisi = $notifikasi->where('tipe', 'revisi');
        if ($notifikasiRevisi->isNotEmpty()) {
            $permohonanRevisiIds = $notifikasiRevisi->pluck('permohonan_magang_id')->filter()->unique();
            
            // Ambil semua permohonan yang masih berstatus "Revisi" dalam satu query
            $permohonanMasihRevisi = PermohonanMagang::where('user_id', $user->id)
                ->where('status', 'Revisi')
                ->when($permohonanRevisiIds->isNotEmpty(), function($query) use ($permohonanRevisiIds) {
                    $query->whereIn('id', $permohonanRevisiIds);
                })
                ->pluck('id')
                ->toArray();
            
            // Filter notifikasi revisi: hanya tampilkan jika permohonan masih berstatus "Revisi"
            $notifikasi = $notifikasi->filter(function ($notif) use ($permohonanMasihRevisi, $user) {
                // Jika bukan notifikasi revisi, tampilkan seperti biasa
                if ($notif->tipe !== 'revisi') {
                    return true;
                }
                
                // Untuk notifikasi revisi dengan permohonan_magang_id, cek apakah masih "Revisi"
                if ($notif->permohonan_magang_id) {
                    return in_array($notif->permohonan_magang_id, $permohonanMasihRevisi);
                }
                
                // Jika tidak ada permohonan_magang_id, cek apakah ada permohonan dengan status Revisi
                return !empty($permohonanMasihRevisi);
            });
        }
        
        // Log untuk debugging - selalu log untuk troubleshooting
        $totalNotifikasi = Notifikasi::where('user_id', $user->id)->count();
        $totalBelumDibaca = Notifikasi::where('user_id', $user->id)->where('dibaca', false)->count();
        
        Log::info("Notifikasi diambil untuk user {$user->id}", [
            'count' => $notifikasi->count(),
            'notifikasi_ids' => $notifikasi->pluck('id')->toArray(),
            'tipe_list' => $notifikasi->pluck('tipe')->toArray(),
            'dibaca_list' => $notifikasi->pluck('dibaca')->toArray(),
            'total_notifikasi_db' => $totalNotifikasi,
            'total_belum_dibaca' => $totalBelumDibaca,
            'user_email' => $user->email,
        ]);
        
        // Jika tidak ada notifikasi tapi ada di database, log warning
        if ($notifikasi->count() === 0 && $totalBelumDibaca > 0) {
            Log::warning("Notifikasi tidak terambil meskipun ada di database", [
                'user_id' => $user->id,
                'total_belum_dibaca' => $totalBelumDibaca,
            ]);
        }
        
        return view('dashboard', [
            'totalLamaran' => $data['totalLamaran'],
            'menungguVerifikasi' => $data['menungguVerifikasi'],
            'perluPerbaikan' => $data['perluPerbaikan'],
            'diterima' => $data['diterima'],
            'totalKebutuhan' => $totalKebutuhan,
            'permohonanTerbaru' => $data['permohonanTerbaru'],
            'lowonganTersedia' => $lowonganData['lowonganTersedia'],
            'jumlahLowongan' => $lowonganData['jumlahLowongan'],
            'adaJadwalAkanDatang' => $lowonganData['adaJadwalAkanDatang'],
            'actionItem' => $data['actionItem'],
            'actionItemType' => $data['actionItemType'],
            'notifikasi' => $notifikasi,
        ]);
    }
    
    public function lowongan()
    {
        $today = now()->toDateString();
        $user = auth()->user();
        
        // ULTRA-OPTIMASI: Single cache key untuk semua data lowongan - mengurangi cache calls
        $cacheKey = "lowongan_complete_{$today}_{$user->id}";
        $data = Cache::remember($cacheKey, 300, function () use ($today, $user) {
            try {
                // OPTIMASI: Query dengan JOIN langsung - jauh lebih cepat daripada filter collection
                $kuotaMagang = DB::table('kuota_magang as km')
                    ->join('jadwal_magang as jm', function($join) {
                        $join->on(DB::raw('LOWER(TRIM(km.periode))'), '=', DB::raw('LOWER(TRIM(jm.periode))'))
                             ->on(DB::raw('LOWER(TRIM(COALESCE(km.posisi, \'\')))'), '=', DB::raw('LOWER(TRIM(COALESCE(jm.posisi, \'\')))'));
                    })
                    ->whereColumn('km.kuota_terpakai', '<', 'km.kuota_max')
                    ->where('jm.tgl_selesai', '>=', $today)
                    ->select(
                        'km.id',
                        'km.periode',
                        'km.posisi',
                        'km.kuota_max',
                        'km.kuota_terpakai',
                        'jm.id as jadwal_id',
                        'jm.tgl_mulai',
                        'jm.tgl_selesai'
                    )
                    ->get()
                    ->map(function ($item) use ($today) {
                        $sisaKuota = $item->kuota_max - $item->kuota_terpakai;
                        $item->sisa_kuota = $sisaKuota;
                        $item->kuota_tersedia = $sisaKuota > 0;
                        $item->jadwal_aktif = $item->tgl_mulai <= $today && $item->tgl_selesai >= $today;
                        $item->jadwal_akan_datang = $item->tgl_mulai > $today;
                        return $item;
                    });
                
                // Ambil dokumen user - single query
                $dokumen = Dokumen::where('user_id', $user->id)
                    ->select('id', 'user_id', 'cv', 'surat_pengantar', 'proposal')
                    ->first();
                
                // Cek dokumen lengkap
                $dokumenLengkap = $dokumen && 
                    !empty($dokumen->cv) && 
                    !empty($dokumen->surat_pengantar) && 
                    !empty($dokumen->proposal);
                
                // Cek apakah user bisa mendaftar
                $cekDaftar = PermohonanMagang::cekBisaDaftar($user->id);
                
                return [
                    'kuotaMagang' => $kuotaMagang,
                    'dokumen' => $dokumen,
                    'dokumenLengkap' => $dokumenLengkap,
                    'cekDaftar' => $cekDaftar,
                ];
            } catch (\Exception $e) {
                \Log::error('Error in lowongan method: ' . $e->getMessage());
                return [
                    'kuotaMagang' => collect([]),
                    'dokumen' => null,
                    'dokumenLengkap' => false,
                    'cekDaftar' => ['bisa_daftar' => true, 'alasan' => ''],
                ];
            }
        });
        
        $memilikiPermohonanAktif = isset($data['cekDaftar']['bisa_daftar']) ? !$data['cekDaftar']['bisa_daftar'] : false;
        
        return view('lowongan', [
            'kuotaMagang' => $data['kuotaMagang'],
            'dokumen' => $data['dokumen'],
            'dokumenLengkap' => $data['dokumenLengkap'],
            'memilikiPermohonanAktif' => $memilikiPermohonanAktif,
            'cekDaftar' => $data['cekDaftar'],
        ]);
    }
    public function lamaran()
    {
        $user = auth()->user();
        
        // Cache permohonan dan dokumen untuk 5 menit (lebih lama untuk performa)
        $cacheKey = "lamaran_user_{$user->id}";
        $data = Cache::remember($cacheKey, 300, function () use ($user) {
            // Optimasi: Ambil permohonan dan dokumen dalam satu query dengan select spesifik
            $permohonan = PermohonanMagang::where('user_id', $user->id)
                ->with([
                    'dokumen:id,user_id,cv,surat_pengantar,proposal,tanggal_upload',
                    'kuotaMagang' => function($q) {
                        $q->select('kuota_magang.id', 'kuota_magang.periode', 'kuota_magang.posisi');
                    }
                ])
                ->select('id', 'user_id', 'dokumen_id', 'status', 'alasan_penolakan', 'catatan_revisi', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->first();
            
            // Load jadwal untuk kuota jika ada
            if ($permohonan && $permohonan->relationLoaded('kuotaMagang') && $permohonan->kuotaMagang->isNotEmpty()) {
                $periodePosisiList = $permohonan->kuotaMagang->map(function($kuota) {
                    return ['periode' => $kuota->periode, 'posisi' => $kuota->posisi];
                })->unique(function($item) {
                    return strtolower(trim($item['periode'] ?? '')) . '|' . strtolower(trim($item['posisi'] ?? ''));
                })->values()->toArray();
                
                if (!empty($periodePosisiList)) {
                    $periodeList = array_unique(array_column($periodePosisiList, 'periode'));
                    $posisiList = array_unique(array_filter(array_column($periodePosisiList, 'posisi')));
                    
                    $jadwalList = JadwalMagang::whereIn('periode', $periodeList)
                        ->when(!empty($posisiList), function($q) use ($posisiList) {
                            $q->whereIn('posisi', $posisiList);
                        })
                        ->select('id', 'periode', 'posisi', 'tgl_mulai', 'tgl_selesai')
                        ->get()
                        ->keyBy(function($jadwal) {
                            return strtolower(trim($jadwal->periode ?? '')) . '|' . strtolower(trim($jadwal->posisi ?? ''));
                        });
                    
                    // Attach jadwal ke kuota
                    foreach ($permohonan->kuotaMagang as $kuota) {
                        $key = strtolower(trim($kuota->periode ?? '')) . '|' . strtolower(trim($kuota->posisi ?? ''));
                        if (isset($jadwalList[$key])) {
                            $kuota->setRelation('jadwalMagang', $jadwalList[$key]);
                        }
                    }
                }
            }
            
            // Ambil dokumen user (bisa dari permohonan atau langsung dari user) - optimasi dengan select
            $dokumen = null;
            if ($permohonan && $permohonan->dokumen) {
                $dokumen = $permohonan->dokumen;
            } else {
                $dokumen = Dokumen::where('user_id', $user->id)
                    ->select('id', 'user_id', 'cv', 'surat_pengantar', 'proposal', 'tanggal_upload')
                    ->first();
            }
            
            return compact('permohonan', 'dokumen');
        });
        
        return view('lamaran', $data);
    }
    
    public function riwayatLamaran()
    {
        $user = auth()->user();
        
        // ULTRA-OPTIMASI: Single cache dengan durasi lebih pendek untuk data yang lebih fresh
        $cacheKey = "riwayat_user_{$user->id}";
        
        // Cache data utama dengan query yang sangat dioptimalkan - ULTRA FAST
        $data = Cache::remember($cacheKey, 300, function () use ($user) {
            try {
                // OPTIMASI Maksimal: Load semua relasi yang diperlukan dengan eager loading
                // Pastikan kuotaMagang di-load untuk menghindari N+1 di view
                // Cek apakah kolom surat_kerja ada di database
                $hasSuratKerjaColumn = DB::getSchemaBuilder()->hasColumn('permohonan_magang', 'surat_kerja');
                $selectFields = ['id', 'user_id', 'dokumen_id', 'status', 'alasan_penolakan', 'catatan_revisi', 'created_at', 'updated_at', 'tanggal_pengajuan'];
                if ($hasSuratKerjaColumn) {
                    $selectFields[] = 'surat_kerja';
                }
                
                $allPermohonan = PermohonanMagang::where('user_id', $user->id)
                    ->with([
                        'dokumen:id,user_id,cv,surat_pengantar,proposal,tanggal_upload',
                        'kuotaMagang:id,periode,posisi' // Load kuotaMagang saja, jadwal akan di-load terpisah jika perlu
                    ])
                    ->select($selectFields)
                    ->orderBy('created_at', 'desc')
                    ->limit(20)
                    ->get();
                
                // Load jadwal untuk semua kuota sekaligus dengan query yang efisien (menghindari N+1)
                if ($allPermohonan->isNotEmpty()) {
                    // Kumpulkan semua periode dan posisi unik
                    $periodePosisiList = [];
                    foreach ($allPermohonan as $permohonan) {
                        if ($permohonan->relationLoaded('kuotaMagang') && $permohonan->kuotaMagang->isNotEmpty()) {
                            foreach ($permohonan->kuotaMagang as $kuota) {
                                $key = strtolower(trim($kuota->periode ?? '')) . '|' . strtolower(trim($kuota->posisi ?? ''));
                                if (!isset($periodePosisiList[$key])) {
                                    $periodePosisiList[$key] = [
                                        'periode' => $kuota->periode,
                                        'posisi' => $kuota->posisi
                                    ];
                                }
                            }
                        }
                    }
                    
                    // Load semua jadwal sekaligus
                    if (!empty($periodePosisiList)) {
                        $periodeList = array_unique(array_column($periodePosisiList, 'periode'));
                        $posisiList = array_unique(array_filter(array_column($periodePosisiList, 'posisi')));
                        
                        $jadwalList = JadwalMagang::whereIn('periode', $periodeList)
                            ->when(!empty($posisiList), function($q) use ($posisiList) {
                                $q->whereIn('posisi', $posisiList);
                            })
                            ->select('id', 'periode', 'posisi', 'tgl_mulai', 'tgl_selesai')
                            ->get()
                            ->keyBy(function($jadwal) {
                                return strtolower(trim($jadwal->periode ?? '')) . '|' . strtolower(trim($jadwal->posisi ?? ''));
                            });
                        
                        // Attach jadwal ke kuota
                        foreach ($allPermohonan as $permohonan) {
                            if ($permohonan->relationLoaded('kuotaMagang') && $permohonan->kuotaMagang->isNotEmpty()) {
                                foreach ($permohonan->kuotaMagang as $kuota) {
                                    $key = strtolower(trim($kuota->periode ?? '')) . '|' . strtolower(trim($kuota->posisi ?? ''));
                                    if (isset($jadwalList[$key])) {
                                        $kuota->setRelation('jadwalMagang', $jadwalList[$key]);
                                    }
                                }
                            }
                        }
                    }
                }
                
                // Hitung statistik dari collection yang sudah di-load - LEBIH CEPAT (tidak perlu query tambahan)
                $stats = [
                    'diajukan' => 0,
                    'diverifikasi' => 0,
                    'diterima' => 0,
                    'ditolak' => 0,
                ];
                
                // Single pass untuk statistik dan permohonan aktif
                $permohonanAktif = null;
                $dokumenUser = null;
                
                foreach ($allPermohonan as $permohonan) {
                    // Hitung statistik
                    $status = $permohonan->status;
                    if ($status === 'Diajukan') {
                        $stats['diajukan']++;
                    } elseif ($status === 'Diverifikasi') {
                        $stats['diverifikasi']++;
                    } elseif ($status === 'Diterima') {
                        $stats['diterima']++;
                    } elseif ($status === 'Ditolak') {
                        $stats['ditolak']++;
                    }
                    
                    // Ambil permohonan aktif (pertama)
                    if (!$permohonanAktif) {
                        $permohonanAktif = $permohonan;
                        $dokumenUser = $permohonan->dokumen;
                    }
                }
                
                // Jika tidak ada dokumen dari permohonan, ambil langsung dengan cache
                if (!$dokumenUser) {
                    $dokumenCacheKey = "dokumen_user_{$user->id}_riwayat";
                    $dokumenUser = Cache::remember($dokumenCacheKey, 300, function () use ($user) {
                        return Dokumen::where('user_id', $user->id)
                            ->select('cv', 'surat_pengantar', 'proposal')
                            ->first();
                    });
                }
                
                // Load jadwal hanya jika ada permohonan aktif dengan kuotaMagang - OPTIMASI dengan eager loading
                // Jadwal sudah di-load melalui eager loading di query utama, ambil dari relasi
                $jadwalData = null;
                if ($permohonanAktif && $permohonanAktif->relationLoaded('kuotaMagang') && $permohonanAktif->kuotaMagang->isNotEmpty()) {
                    $kuota = $permohonanAktif->kuotaMagang->first();
                    if ($kuota && $kuota->relationLoaded('jadwalMagang') && $kuota->jadwalMagang) {
                        $jadwalData = $kuota->jadwalMagang;
                    }
                }
                
                // Status Dokumen
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
                $statusLamaran = [
                    'status' => null,
                    'sedang_proses' => false,
                    'perlu_perbaikan' => false,
                    'berhasil' => false,
                    'jadwal' => null,
                    'alasan_penolakan' => null,
                    'catatan_revisi' => null,
                ];
                
                if ($permohonanAktif) {
                    $statusLamaran['status'] = $permohonanAktif->status;
                    $statusLamaran['sedang_proses'] = in_array($permohonanAktif->status, ['Diajukan', 'Diverifikasi']);
                    $statusLamaran['perlu_perbaikan'] = in_array($permohonanAktif->status, ['Ditolak', 'Revisi']);
                    $statusLamaran['berhasil'] = $permohonanAktif->status === 'Diterima';
                    $statusLamaran['alasan_penolakan'] = $permohonanAktif->alasan_penolakan;
                    $statusLamaran['catatan_revisi'] = $permohonanAktif->catatan_revisi;
                    $statusLamaran['surat_kerja'] = $permohonanAktif->surat_kerja; // Tambahkan surat_kerja untuk download
                    
                    // Set jadwal dari query yang sudah di-load
                    if ($jadwalData) {
                        $statusLamaran['jadwal'] = (object) $jadwalData;
                    }
                }
                
                // Cek memiliki permohonan aktif
                $memilikiPermohonanAktif = $allPermohonan->contains(function ($permohonan) {
                    return in_array($permohonan->status, ['Diajukan', 'Diverifikasi']);
                });
                
                return [
                    'riwayatPermohonan' => $allPermohonan,
                    'diajukan' => $stats['diajukan'],
                    'diverifikasi' => $stats['diverifikasi'],
                    'diterima' => $stats['diterima'],
                    'ditolak' => $stats['ditolak'],
                    'statusDokumen' => $statusDokumen,
                    'statusLamaran' => $statusLamaran,
                    'memilikiPermohonanAktif' => $memilikiPermohonanAktif,
                ];
            } catch (\Exception $e) {
                \Log::error('Error in riwayatLamaran: ' . $e->getMessage());
                return [
                    'riwayatPermohonan' => collect([]),
                    'diajukan' => 0,
                    'diverifikasi' => 0,
                    'diterima' => 0,
                    'ditolak' => 0,
                    'statusDokumen' => ['sudah_upload' => false, 'lengkap' => false, 'belum_upload' => true],
                    'statusLamaran' => ['status' => null, 'sedang_proses' => false, 'perlu_perbaikan' => false, 'berhasil' => false, 'jadwal' => null, 'alasan_penolakan' => null],
                    'memilikiPermohonanAktif' => false,
                ];
            }
        });
        
        // Query lowongan tersedia hanya jika diperlukan (lazy dengan cache) - OPTIMASI
        $today = now()->toDateString();
        $lowonganCacheKey = "riwayat_lowongan_{$today}";
        $data['lowonganTersedia'] = Cache::remember($lowonganCacheKey, 300, function () use ($today) {
            try {
                return DB::table('kuota_magang as km')
                    ->join('jadwal_magang as jm', function($join) {
                        $join->on(DB::raw('LOWER(TRIM(km.periode))'), '=', DB::raw('LOWER(TRIM(jm.periode))'))
                             ->on(DB::raw('LOWER(TRIM(COALESCE(km.posisi, \'\')))'), '=', DB::raw('LOWER(TRIM(COALESCE(jm.posisi, \'\')))'));
                    })
                    ->whereColumn('km.kuota_terpakai', '<', 'km.kuota_max')
                    ->where('jm.tgl_mulai', '<=', $today)
                    ->where('jm.tgl_selesai', '>=', $today)
                    ->exists();
            } catch (\Exception $e) {
                \Log::error('Error checking lowongan tersedia: ' . $e->getMessage());
                return false;
            }
        });
        
        return view('riwayat-lamaran', $data);
    }

    public function panduanOnboarding()
    {
        $user = auth()->user();
        
        // Ambil permohonan aktif yang diterima - pastikan field surat_kerja di-load
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
        
        // Refresh untuk memastikan data terbaru (termasuk surat_kerja jika baru diupload)
        $permohonan->refresh();
        
        // Debug: Log untuk memastikan surat_kerja ter-load (hapus di production jika tidak perlu)
        if (config('app.debug')) {
            Log::info('Panduan Onboarding - SK Check', [
                'permohonan_id' => $permohonan->id,
                'surat_kerja' => $permohonan->surat_kerja,
                'surat_kerja_exists' => !empty($permohonan->surat_kerja),
                'file_exists' => $permohonan->surat_kerja ? Storage::disk('public')->exists($permohonan->surat_kerja) : false,
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

    public function downloadPanduanOnboarding()
    {
        $user = auth()->user();
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diterima')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$permohonan) {
            return redirect()->route('riwayat.lamaran')->withErrors([
                'error' => 'Anda belum diterima. File panduan onboarding hanya tersedia untuk peserta yang diterima.'
            ]);
        }

        $file = public_path('onboarding/panduan_onboarding.pdf');
        if (!file_exists($file)) {
            return back()->withErrors(['error' => 'File panduan onboarding belum tersedia.']);
        }

        return response()->download($file, 'Panduan_Onboarding.pdf');
    }

    public function downloadSK()
    {
        $user = auth()->user();
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Diterima')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$permohonan) {
            return redirect()->route('riwayat.lamaran')->withErrors([
                'error' => 'Anda belum diterima. SK hanya tersedia untuk peserta yang diterima.'
            ]);
        }

        // Cek apakah Surat Kerja ada di database (jika masih draft)
        $filePath = null;
        if (!empty($permohonan->surat_kerja)) {
            // Validasi: pastikan file path mengandung ID permohonan yang benar
            $cleanPath = ltrim($permohonan->surat_kerja, '/');
            if (strpos($cleanPath, 'sk_' . $permohonan->id . '_') !== false) {
                $filePath = $cleanPath;
            }
        } else {
            // Jika sudah dikirim (surat_kerja = null), cari file berdasarkan pattern
            // Pattern: sk_{permohonan_id}_*.pdf - PASTIKAN ID sesuai dengan permohonan user
            $storagePath = storage_path('app/public/surat_kerja');
            if (is_dir($storagePath)) {
                $pattern = $storagePath . '/sk_' . $permohonan->id . '_*.pdf';
                $files = glob($pattern);
                if (!empty($files)) {
                    // Validasi: pastikan file benar-benar mengandung ID permohonan yang benar
                    $validFiles = array_filter($files, function($file) use ($permohonan) {
                        $filename = basename($file);
                        return preg_match('/^sk_' . preg_quote($permohonan->id, '/') . '_\d+\.pdf$/', $filename);
                    });
                    
                    if (!empty($validFiles)) {
                        // Ambil file terbaru berdasarkan timestamp
                        usort($validFiles, function($a, $b) {
                            return filemtime($b) - filemtime($a);
                        });
                        $filePath = 'surat_kerja/' . basename($validFiles[0]);
                    }
                }
            }
        }

        if (empty($filePath)) {
            return back()->withErrors(['error' => 'Surat Kerja belum tersedia. Admin belum mengirim Surat Kerja untuk permohonan Anda. Harap menunggu maksimal 2x24 jam atau hubungi admin untuk informasi lebih lanjut.']);
        }

        // Cek apakah file ada di storage
        $fullPath = storage_path('app/public/' . $filePath);
        if (!file_exists($fullPath)) {
            return back()->withErrors(['error' => 'File Surat Kerja tidak ditemukan. Silakan hubungi admin.']);
        }

        // Validasi final: pastikan file path mengandung ID permohonan yang benar
        if (strpos($filePath, 'sk_' . $permohonan->id . '_') === false) {
            Log::warning('SK download attempt with invalid file path', [
                'user_id' => $user->id,
                'permohonan_id' => $permohonan->id,
                'file_path' => $filePath,
            ]);
            return back()->withErrors(['error' => 'Surat Kerja tidak valid. Silakan hubungi admin.']);
        }

        // Download file
        $fileName = 'Surat_Kerja_' . str_replace(' ', '_', $user->nama) . '_' . date('Y-m-d') . '.pdf';
        
        return response()->download($fullPath, $fileName);
    }

    public function profil()
    {
        // OPTIMASI: User data sudah di-load dari auth()->user(), tidak perlu query atau cache
        // Langsung return view dengan user yang sudah ada
        $user = auth()->user();
        return view('profil', compact('user'));
    }
    
    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        // Clear user cache when profile is updated
        CacheHelper::clearUserCache($user->id);
        
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
        // Cache galeri untuk 10 menit (jarang berubah) - OPTIMASI: hanya ambil kolom yang diperlukan + limit
        $galeri = Cache::remember('galeri_magang_aktif', 600, function () {
            return \App\Models\GaleriMagang::aktif()
                ->terurut()
                ->select('id', 'foto', 'judul', 'deskripsi', 'created_at') // Hanya kolom yang diperlukan
                ->limit(50) // Limit untuk performa - maksimal 50 foto
                ->get()
                ->map(function ($item) {
                    // Pre-generate URL untuk menghindari multiple asset() calls di view
                    $item->foto_url = $item->foto ? asset('storage/' . $item->foto) : null;
                    return $item;
                });
        });
        
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
        
        // Cache status untuk 2 menit
        $cacheKey = "status_lamaran_user_{$user->id}";
        $data = Cache::remember($cacheKey, 120, function () use ($user) {
            $permohonan = PermohonanMagang::where('user_id', $user->id)
                ->select('status')
                ->orderBy('created_at', 'desc')
                ->first();
            
            return [
                'status' => $permohonan->status ?? 'Diajukan',
                'nama' => $user->nama ?? null,
            ];
        });
        
        return response()->json($data);
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
        
        // Clear cache notifikasi setelah update
        Cache::forget("notifikasi_user_{$user->id}");
        
        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca.');
    }
}
