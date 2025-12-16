<section id="galeri" class="py-20 bg-gray-50 relative">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-4 text-center">Galeri Magang</h2>
        <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
            Lihat momen-momen berharga dari kegiatan magang di BBKB Yogyakarta. Dokumentasi pengalaman peserta magang dalam berbagai kegiatan dan proyek.
        </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            @if(isset($galeri) && count($galeri) > 0)
                @foreach($galeri as $img)
                <div class="rounded-xl shadow overflow-hidden hover:scale-105 transition">
                    <img src="{{ $img->url ?? $img }}" alt="Kegiatan Magang" class="w-full h-full object-cover">
                </div>
                @endforeach
            @else
                @php
                    // Sample images untuk galeri magang
                    $sampleImages = [
                        '/images/baground.jpg',
                        '/images/hero-batik.jpg',
                        '/images/baground.jpg',
                        '/images/hero-batik.jpg'
                    ];
                @endphp
                @foreach($sampleImages as $img)
                <div class="rounded-xl shadow overflow-hidden hover:scale-105 transition bg-gray-200 aspect-square">
                    <img src="{{ $img }}" alt="Kegiatan Magang" class="w-full h-full object-cover">
                </div>
                @endforeach
            @endif
        </div>

        <div class="text-center">
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

