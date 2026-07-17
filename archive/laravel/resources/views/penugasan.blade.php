<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Penugasan - Magang Digital</title>
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
          <h1>Detail Penugasan</h1>
          <ul class="list">
            <li class="list-item">Tugas 1: Membuat laporan mingguan</li>
            <li class="list-item">Tugas 2: Menyusun proposal kegiatan</li>
          </ul>
        </section>
      </main>
    </div>
  </body>
</html>

