<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Mingguan - Magang Digital</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
  </head>
  <body class="dashboard-body">
    <div class="dashboard-wrap">
      @include('partials.sidebar')
      <main class="main">
        <div class="topbar">
          <div class="topbar-right">
            <span>Halo, {{ auth()->user()->name ?? 'Pengguna' }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block;margin-left:10px">
              @csrf
              <button type="submit" class="btn-ghost" style="padding:6px 10px;border:1px solid #e5e7eb33;border-radius:8px">Keluar</button>
            </form>
          </div>
        </div>
        <section class="content">
          <h1>Unggah Laporan Mingguan</h1>
          <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" class="card p-4">
            @csrf
            <input type="file" name="laporan" class="input-file">
            @error('laporan')
              <p class="text-error">{{ $message }}</p>
            @enderror
            <button type="submit" class="btn-primary mt-3">Unggah Sekarang</button>
          </form>
          @if (session('success'))
            <p class="text-success mt-3">{{ session('success') }}</p>
          @endif
        </section>
      </main>
    </div>
  </body>
  </html>

