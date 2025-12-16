<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat - Magang Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  </head>
  <body class="dashboard-body">
    <div class="dashboard-wrap">
      @include('partials.sidebar')
      <main class="main">
        <div class="topbar">
          <div class="topbar-right">
            <span style="color: var(--text-dark); font-weight: 500;">Halo, {{ auth()->user()->nama ?? 'Pengguna' }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block">
              @csrf
              <button type="submit" class="btn-ghost">Keluar</button>
            </form>
          </div>
        </div>
        <section class="content fade-in">
          <h1 style="margin: 0 0 8px; font-size: 32px; font-weight: 800; color: var(--text-dark);">
            Sertifikat Magang
          </h1>
          <p style="margin: 0 0 32px; color: var(--text-muted); font-size: 15px;">
            Unduh sertifikat magang resmi dari BBKB Yogyakarta
          </p>

          <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5"/>
              <path d="M14 2v6h6M9 15l2 2 4-4" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <h3 class="empty-title">Belum ada sertifikat</h3>
            <p class="empty-description">
              Sertifikat akan tersedia setelah Anda menyelesaikan program magang dan dinyatakan lulus.
            </p>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>

