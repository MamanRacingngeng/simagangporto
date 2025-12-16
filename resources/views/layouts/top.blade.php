<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Magang Digital')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
      .topnav { position:sticky; top:0; z-index:20; background:#ffffff; border-bottom:1px solid #e5e7eb; }
      .topnav-inner { max-width:1200px; margin:0 auto; padding:12px 20px; display:flex; align-items:center; justify-content:space-between; }
      .brand { display:flex; align-items:center; gap:12px; font-weight:800; color:#0f172a; }
      .brand .mini { width:32px; height:32px; border-radius:8px; background:#2563eb; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; }
      .menu { display:flex; gap:18px; }
      .menu a { color:#0f172a; text-decoration:none; font-weight:600; opacity:.85 }
      .menu a.active, .menu a:hover { opacity:1 }
      .user { display:flex; align-items:center; gap:10px; }
      .btn-out { border:1px solid #e5e7eb; background:#fff; padding:6px 10px; border-radius:8px; }
      .content-wrap { max-width:1200px; margin:0 auto; padding:22px 20px; }
      .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:18px; }
    </style>
  </head>
  <body class="dashboard-body" style="background:#f5f7fb;">
    <header class="topnav">
      <div class="topnav-inner">
        <div class="brand">
          <img src="/images/logoBBKB.png" alt="BBKB" style="height:44px">
          <span>Magang Digital</span>
        </div>
        <nav class="menu">
          <a class="{{ request()->routeIs('app2.dashboard') ? 'active' : '' }}" href="{{ route('app2.dashboard') }}">Dashboard</a>
          <a class="{{ request()->routeIs('app2.lowongan') ? 'active' : '' }}" href="{{ route('app2.lowongan') }}">Lowongan</a>
          <a class="{{ request()->routeIs('app2.lamaran') ? 'active' : '' }}" href="{{ route('app2.lamaran') }}">Lamaran Saya</a>
          <a class="{{ request()->routeIs('app2.profil') ? 'active' : '' }}" href="{{ route('app2.profil') }}">Profil</a>
        </nav>
        <div class="user">
          <span>Halo, {{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-out">Keluar</button>
          </form>
        </div>
      </div>
    </header>
    <main class="content-wrap">
      @yield('content')
    </main>
  </body>
  </html>

