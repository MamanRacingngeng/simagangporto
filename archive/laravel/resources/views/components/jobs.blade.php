<section id="lowongan" class="py-20 bg-white relative">
    <div class="max-w-6xl mx-auto px-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <h2 class="text-3xl font-bold mb-4 sm:mb-0">Lowongan Magang</h2>

            <a href="{{ route('lowongan') }}" class="px-4 py-2 bg-yellow-400 rounded-lg hover:bg-yellow-500 transition">
                Lihat Semua Lowongan
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @if(isset($jobs) && count($jobs) > 0)
                @foreach($jobs as $job)
                <div class="p-6 bg-white rounded-xl shadow hover:shadow-xl transition">
                    <h3 class="font-bold text-lg">{{ $job->title ?? $job->nama_posisi ?? 'Lowongan Magang' }}</h3>
                    <p class="text-gray-600 mt-2">{{ $job->description ?? $job->deskripsi ?? 'Deskripsi lowongan magang' }}</p>
                    <a href="{{ route('lowongan') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 transition">
                        Selengkapnya →
                    </a>
                </div>
                @endforeach
            @else
                @php
                    // Sample data jika tidak ada data dari controller
                    $sampleJobs = [
                        ['title' => 'Magang Desain Batik', 'description' => 'Pelajari teknik desain batik modern dan tradisional'],
                        ['title' => 'Magang Pemasaran Digital', 'description' => 'Kembangkan skill pemasaran digital untuk produk kerajinan'],
                        ['title' => 'Magang Produksi Kerajinan', 'description' => 'Terlibat langsung dalam proses produksi kerajinan']
                    ];
                @endphp
                @foreach($sampleJobs as $job)
                <div class="p-6 bg-white rounded-xl shadow hover:shadow-xl transition">
                    <h3 class="font-bold text-lg">{{ $job['title'] }}</h3>
                    <p class="text-gray-600 mt-2">{{ $job['description'] }}</p>
                    <a href="{{ route('lowongan') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 transition">
                        Selengkapnya →
                    </a>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

