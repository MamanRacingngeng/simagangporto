@extends('layouts.app')

@section('title', 'Tentang Kami - BBKB Yogyakarta')

@section('content')
<!-- Hero Section Tentang Kami -->
<section class="relative py-32 md:py-40 text-white flex items-center justify-center" style="background-image: url('/images/baground2.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 70vh;">
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/45 to-black/40 backdrop-blur-[0.5px]"></div>
    <div class="relative max-w-6xl mx-auto px-6 text-center w-full">
        <div class="mb-8 flex justify-center">
            <div class="w-28 h-28 rounded-full overflow-hidden bg-white flex items-center justify-center shadow-lg p-2">
                <img src="/images/profilebbkb.png" alt="Logo BBKB Yogyakarta" class="w-full h-full object-contain">
            </div>
        </div>
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-4 text-white drop-shadow-lg">Tentang Kami</h1>
        <p class="text-base md:text-lg opacity-95 max-w-3xl mx-auto text-white drop-shadow-md">
            Balai Besar Kerajinan dan Batik (BBKB) Yogyakarta
        </p>
    </div>
</section>

<!-- Profil BBKB -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold mb-6 text-gray-800">Profil BBKB Yogyakarta</h2>
                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p>
                        Balai Besar Kerajinan dan Batik (BBKB) Yogyakarta merupakan unit pelaksana teknis di bawah Kementerian Perindustrian RI yang berfokus pada penelitian, pengembangan, pelayanan teknis, serta peningkatan kompetensi di bidang kerajinan dan batik.
                    </p>
                    <p>
                        BBKB Yogyakarta mendukung talenta muda untuk belajar secara langsung melalui program magang, penelitian, dan pengembangan produk, guna berkontribusi pada industri kreatif nasional.
                    </p>
                    <p>
                        Sejak didirikan, BBKB Yogyakarta telah menjadi pusat keunggulan dalam pengembangan teknologi dan inovasi di bidang kerajinan dan batik, melayani kebutuhan industri, UMKM, dan masyarakat luas.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="bg-gray-50 p-8 rounded-2xl shadow-lg w-full max-w-md">
                    <div class="flex flex-col items-center justify-center">
                        <div class="mb-6 flex items-center justify-center">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-white flex items-center justify-center shadow-md p-1.5">
                                <img src="/images/profilebbkb.png" alt="Logo BBKB Yogyakarta" class="w-full h-full object-contain">
                            </div>
                        </div>
                        <div class="text-center space-y-2">
                            <h3 class="text-lg font-bold text-gray-800">Balai Besar Kerajinan Dan Batik Yogyakarta</h3>
                            <p class="text-sm text-gray-600">Kementerian Perindustrian</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Visi</h3>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    Menjadi pusat keunggulan dalam penelitian, pengembangan, dan pelayanan teknis di bidang kerajinan dan batik yang berkelanjutan dan berdaya saing global.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Misi</h3>
                </div>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Melakukan penelitian dan pengembangan teknologi kerajinan dan batik</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Menyediakan pelayanan teknis dan konsultasi untuk industri</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Meningkatkan kompetensi SDM melalui program magang dan pelatihan</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-600 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Mendukung pengembangan industri kreatif nasional</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Fitur Utama -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-10 text-center">Mengapa Memilih BBKB Yogyakarta?</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-800">Mentoring Ahli</h3>
                <p class="text-gray-700 leading-relaxed">
                    Belajar langsung dari praktisi berpengalaman dan peneliti terbaik di industri kerajinan & batik dengan pengalaman bertahun-tahun.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-800">Proyek Nyata</h3>
                <p class="text-gray-700 leading-relaxed">
                    Terlibat langsung dalam proyek penelitian dan pengembangan yang berdampak nyata pada industri dan masyarakat.
                </p>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-yellow-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4 text-gray-800">Sertifikat Resmi</h3>
                <p class="text-gray-700 leading-relaxed">
                    Dapatkan sertifikat penyelesaian resmi dari BBKB Yogyakarta yang diakui oleh Kementerian Perindustrian RI.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Program Magang -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-10 text-center">Program Magang</h2>
        
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <p class="text-gray-700 mb-6 leading-relaxed">
                Program magang kami dirancang untuk memberikan pengalaman nyata dalam penelitian, pengujian mutu, desain produk, dan manajemen layanan teknis.
            </p>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Bidang Program</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Penelitian & Pengembangan Material Kerajinan</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Pengujian Mutu dan Standarisasi Produk Batik</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Desain Produk Kreatif (Craft & Batik)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Proses Produksi dan Rekayasa Material</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Administrasi Perkantoran & Manajemen Layanan Teknis</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-blue-50 p-6 rounded-xl">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Fasilitas</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Laboratorium lengkap dengan peralatan modern
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Perpustakaan dengan koleksi referensi lengkap
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Workshop dan studio produksi
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Akses ke jaringan industri dan UMKM
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kontak -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-10 text-center">Kontak Kami</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gray-50 p-6 rounded-xl text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Alamat</h3>
                <p class="text-gray-600">Jl. Kusumanegara No. 7<br>Yogyakarta 55166</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl text-center">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Telepon</h3>
                <p class="text-gray-600">(0274) 512929</p>
            </div>

            <div class="bg-gray-50 p-6 rounded-xl text-center">
                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Email</h3>
                <p class="text-gray-600">
                    <a href="mailto:info@bbkb.kemenperin.go.id" class="text-blue-600 hover:text-blue-800">
                        info@bbkb.kemenperin.go.id
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

