<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\KuotaMagang;

class WelcomeController extends Controller
{
    public function index()
    {
        // Cache data untuk 5 menit (halaman landing jarang berubah)
        $cacheKey = 'welcome_page_data';
        
        $data = Cache::remember($cacheKey, 300, function () {
            // Ambil data lowongan magang dari kuota magang - optimasi query
            $jobs = $this->getJobs();
            
            // Ambil data galeri - optimasi query
            $galeri = $this->getGaleri();
            
            return compact('jobs', 'galeri');
        });

        return view('welcome', $data);
    }
    
    /**
     * Get jobs data dengan fallback
     */
    private function getJobs()
    {
        try {
            // Query langsung tanpa cek koneksi terlebih dahulu (lebih cepat)
            $jobs = KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')
                ->select('periode', 'kuota_max', 'kuota_terpakai') // Hanya ambil kolom yang diperlukan
                ->limit(3)
                ->get()
                ->map(function ($kuota) {
                    return (object) [
                        'title' => 'Magang Periode ' . $kuota->periode,
                        'description' => 'Kuota tersedia: ' . ($kuota->kuota_max - $kuota->kuota_terpakai) . ' dari ' . $kuota->kuota_max,
                    ];
                });
            
            // Fallback jika tidak ada data
            if ($jobs->isEmpty()) {
                return $this->getDefaultJobs();
            }
            
            return $jobs;
        } catch (\Exception $e) {
            \Log::warning('Error in WelcomeController fetching jobs: ' . $e->getMessage());
            return $this->getDefaultJobs();
        }
    }
    
    /**
     * Get galeri data dengan fallback
     */
    private function getGaleri()
    {
        try {
            $galeri = \App\Models\GaleriMagang::aktif()
                ->terurut()
                ->select('foto', 'judul') // Hanya ambil kolom yang diperlukan
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'url' => asset('storage/' . $item->foto),
                        'judul' => $item->judul,
                    ];
                });
            
            // Fallback jika tidak ada data
            if ($galeri->isEmpty()) {
                return $this->getDefaultGaleri();
            }
            
            return $galeri;
        } catch (\Exception $e) {
            \Log::warning('Error in WelcomeController fetching galeri: ' . $e->getMessage());
            return $this->getDefaultGaleri();
        }
    }
    
    /**
     * Default jobs data
     */
    private function getDefaultJobs()
    {
        return collect([
            (object) ['title' => 'Magang Desain Batik', 'description' => 'Pelajari teknik desain batik modern dan tradisional'],
            (object) ['title' => 'Magang Pemasaran Digital', 'description' => 'Kembangkan skill pemasaran digital untuk produk kerajinan'],
            (object) ['title' => 'Magang Produksi Kerajinan', 'description' => 'Terlibat langsung dalam proses produksi kerajinan']
        ]);
    }
    
    /**
     * Default galeri data
     */
    private function getDefaultGaleri()
    {
        return collect([
            (object) ['url' => '/images/baground.jpg', 'judul' => 'Kegiatan Magang'],
            (object) ['url' => '/images/hero-batik.jpg', 'judul' => 'Kegiatan Magang'],
            (object) ['url' => '/images/baground.jpg', 'judul' => 'Kegiatan Magang'],
            (object) ['url' => '/images/hero-batik.jpg', 'judul' => 'Kegiatan Magang'],
        ]);
    }

    public function tentangKami()
    {
        // Halaman statis, tidak perlu query database
        return view('tentang-kami');
    }
}

