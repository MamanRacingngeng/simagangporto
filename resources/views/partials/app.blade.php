<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Magang Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 w-64 h-full bg-gray-900 text-white flex flex-col">
        <div class="text-2xl font-bold p-6 border-b border-gray-700">
            Magang Digital<br><span class="text-blue-400 text-sm">BBKB</span>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-blue-600 {{ request()->is('dashboard') ? 'bg-blue-700' : '' }}">Dashboard</a>
            <a href="{{ route('lowongan') }}" class="block px-3 py-2 rounded-lg hover:bg-blue-600 {{ request()->is('lowongan') ? 'bg-blue-700' : '' }}">Lowongan</a>
            <a href="{{ route('lamaran') }}" class="block px-3 py-2 rounded-lg hover:bg-blue-600 {{ request()->is('lamaran') ? 'bg-blue-700' : '' }}">Lamaran Saya</a>
            <a href="{{ route('profil') }}" class="block px-3 py-2 rounded-lg hover:bg-blue-600 {{ request()->is('profil') ? 'bg-blue-700' : '' }}">Profil</a>

            {{-- Tambahan jika status diterima --}}
            @if (auth()->user()->status_lamaran === 'diterima')
                <a href="{{ route('laporan') }}" class="block px-3 py-2 rounded-lg hover:bg-blue-600 {{ request()->is('laporan') ? 'bg-blue-700' : '' }}">Laporan Mingguan</a>
                <a href="{{ route('penugasan') }}" class="block px-3 py-2 rounded-lg hover:bg-blue-600 {{ request()->is('penugasan') ? 'bg-blue-700' : '' }}">Detail Penugasan</a>
            @endif
        </nav>

        <div class="p-4">
            <a href="{{ route('lowongan') }}" class="w-full text-center block bg-blue-500 hover:bg-blue-600 py-2 rounded-lg">Lihat Lowongan</a>
        </div>
    </aside>

    <!-- Header -->
    <header class="ml-64 bg-white shadow flex justify-between items-center px-8 py-4">
        <h1 class="text-lg font-semibold">@yield('page-title', 'Dashboard')</h1>
        <div class="flex items-center space-x-3">
            <span class="font-semibold text-gray-700">Halo, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md text-sm">Keluar</button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="ml-64 p-8">
        @yield('content')
    </main>

</body>
</html>
