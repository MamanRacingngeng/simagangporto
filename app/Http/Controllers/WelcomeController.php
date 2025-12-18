<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KuotaMagang;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil data lowongan magang dari kuota magang
        try {
            $jobs = KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')
                ->limit(3)
                ->get()
                ->map(function ($kuota) {
                    return (object) [
                        'title' => 'Magang Periode ' . $kuota->periode,
                        'description' => 'Kuota tersedia: ' . ($kuota->kuota_max - $kuota->kuota_terpakai) . ' dari ' . $kuota->kuota_max,
                    ];
                });
        } catch (\Exception $e) {
            // Jika tabel belum ada atau error, gunakan sample data
            $jobs = collect([
                (object) ['title' => 'Magang Desain Batik', 'description' => 'Pelajari teknik desain batik modern dan tradisional'],
                (object) ['title' => 'Magang Pemasaran Digital', 'description' => 'Kembangkan skill pemasaran digital untuk produk kerajinan'],
                (object) ['title' => 'Magang Produksi Kerajinan', 'description' => 'Terlibat langsung dalam proses produksi kerajinan']
            ]);
        }

        // Ambil data galeri dari database atau gunakan gambar placeholder
        try {
            $galeri = \App\Models\GaleriMagang::aktif()
                ->terurut()
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'url' => asset('storage/' . $item->foto),
                        'judul' => $item->judul,
                    ];
                });
            
            // Jika tidak ada data galeri, gunakan gambar placeholder
            if ($galeri->isEmpty()) {
                $galeri = collect([
                    (object) ['url' => '/images/baground.jpg', 'judul' => 'Kegiatan Magang'],
                    (object) ['url' => '/images/hero-batik.jpg', 'judul' => 'Kegiatan Magang'],
                    (object) ['url' => '/images/baground.jpg', 'judul' => 'Kegiatan Magang'],
                    (object) ['url' => '/images/hero-batik.jpg', 'judul' => 'Kegiatan Magang'],
                ]);
            }
        } catch (\Exception $e) {
            // Jika error, gunakan gambar placeholder
            $galeri = collect([
                (object) ['url' => '/images/baground.jpg', 'judul' => 'Kegiatan Magang'],
                (object) ['url' => '/images/hero-batik.jpg', 'judul' => 'Kegiatan Magang'],
                (object) ['url' => '/images/baground.jpg', 'judul' => 'Kegiatan Magang'],
                (object) ['url' => '/images/hero-batik.jpg', 'judul' => 'Kegiatan Magang'],
            ]);
        }

        return view('welcome', compact('jobs', 'galeri'));
    }

    public function tentangKami()
    {
        return view('tentang-kami');
    }
}

