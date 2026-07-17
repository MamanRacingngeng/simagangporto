<section id="galeri" class="py-20 bg-gray-50 relative">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-4 text-center">Galeri Magang</h2>
        <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
            Lihat momen-momen berharga dari kegiatan magang di BBKB Yogyakarta. Dokumentasi pengalaman peserta magang dalam berbagai kegiatan dan proyek.
        </p>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center text-3xl shadow-md">
                    👥
                </div>
                <div class="flex-1">
                    <div class="text-2xl font-bold text-blue-900 mb-1">500+</div>
                    <div class="text-sm font-semibold text-gray-600">Peserta Magang</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center text-3xl shadow-md">
                    🎓
                </div>
                <div class="flex-1">
                    <div class="text-2xl font-bold text-blue-900 mb-1">15+</div>
                    <div class="text-sm font-semibold text-gray-600">Program Tersedia</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center text-3xl shadow-md">
                    ⭐
                </div>
                <div class="flex-1">
                    <div class="text-2xl font-bold text-blue-900 mb-1">10+</div>
                    <div class="text-sm font-semibold text-gray-600">Tahun Pengalaman</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center text-3xl shadow-md">
                    📜
                </div>
                <div class="flex-1">
                    <div class="text-2xl font-bold text-blue-900 mb-1">450+</div>
                    <div class="text-sm font-semibold text-gray-600">Sertifikat Diterbitkan</div>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('galeri-magang') }}" 
               class="inline-flex items-center px-6 py-3 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-500 transition font-semibold shadow-md hover:shadow-lg">
                Lihat Galeri Magang
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

