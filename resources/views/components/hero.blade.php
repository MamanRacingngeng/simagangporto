<section id="beranda" class="relative w-full flex flex-col items-center justify-center text-white hero-batik" style="background-image: url('/images/BagroundDashboard.jpg'); background-size: cover; background-position: center center; background-attachment: fixed; margin: 0; padding: 0; min-height: 100vh;">
    <style>
        @media (max-width: 768px) {
            .hero-batik {
                background-attachment: scroll !important;
            }
        }
        
        .hero-batik {
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    {{-- Filter hitam dengan gradient yang fleksibel ke bawah --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/45 via-black/40 to-black/30 backdrop-blur-[0.5px]"></div>
    
    {{-- Transisi smooth ke bagian putih di bawah --}}
    <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-b from-transparent via-white/20 to-white"></div>

    {{-- Konten utama --}}
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-32 text-center flex-1 flex flex-col justify-center">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight text-white drop-shadow-2xl animate-fade-in">
            Jelajahi Pengalaman Magang di Dunia Kerajinan & Batik
        </h1>

        <p class="mt-6 text-lg md:text-xl text-white font-medium drop-shadow-lg opacity-95">
            Wujudkan potensi Anda bersama Balai Besar Kerajinan & Batik Yogyakarta.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="#lowongan"
                class="px-8 py-4 bg-yellow-400 text-gray-900 font-semibold rounded-xl shadow-lg hover:bg-yellow-500 hover:shadow-xl transition transform hover:scale-105">
                Lihat Lowongan Magang
            </a>
            <a href="#program"
                class="px-8 py-4 bg-white/95 text-gray-900 rounded-xl border-2 border-white/50 hover:bg-white hover:border-white transition transform hover:scale-105 shadow-lg font-semibold">
                Pelajari Program
            </a>
        </div>
    </div>
    
    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

