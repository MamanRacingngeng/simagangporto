<aside class="sidebar">
  <div class="brand">
    <div style="padding:28px 20px">
      <div style="text-align:center">
        <img src="{{ asset('images/profilebbkb.png') }}" alt="Profile BBKB" style="width:100px;height:auto;object-fit:contain;display:block;margin:0 auto 14px">
        <div style="font-size:20px;font-weight:800;color:#0C3A6B;line-height:1.3;letter-spacing:-0.3px;margin-bottom:4px">Magang Digital</div>
        <div style="font-size:13px;color:#6B7280;font-weight:500">Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta</div>
      </div>
    </div>
  </div>
  <nav class="nav" aria-label="Main navigation">
    <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      <span class="nav-icon" aria-hidden="true"><!-- home icon -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11.5L12 4l9 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 21V12h14v9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
      <span class="nav-label">Dashboard</span>
    </a>

    @auth
    <a class="nav-item {{ request()->routeIs('lowongan') ? 'active' : '' }}" href="{{ route('lowongan') }}">
      <span class="nav-icon" aria-hidden="true"><!-- briefcase -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M16 7V6a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
      <span class="nav-label">Lowongan</span>
    </a>

    <a class="nav-item {{ request()->routeIs('lamaran') ? 'active' : '' }}" href="{{ route('lamaran') }}">
      <span class="nav-icon" aria-hidden="true"><!-- file -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
      <span class="nav-label">Lamaran Saya</span>
    </a>

    <a class="nav-item {{ request()->routeIs('riwayat.lamaran') ? 'active' : '' }}" href="{{ route('riwayat.lamaran') }}">
      <span class="nav-icon" aria-hidden="true"><!-- history -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
      <span class="nav-label">Status Lamaran</span>
    </a>

    @if (auth()->user() && auth()->user()->status_lamaran === 'diterima')
      <a class="nav-item {{ request()->routeIs('laporan') ? 'active' : '' }}" href="{{ route('laporan') }}">
        <span class="nav-icon" aria-hidden="true"><!-- upload -->
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 16V4m0 0 4 4m-4-4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 20h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </span>
        <span class="nav-label">Laporan Mingguan</span>
      </a>

      <a class="nav-item {{ request()->routeIs('penugasan') ? 'active' : '' }}" href="{{ route('penugasan') }}">
        <span class="nav-icon" aria-hidden="true"><!-- tasks -->
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 7h10M9 12h10M9 17h10M5 7h.01M5 12h.01M5 17h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <span class="nav-label">Detail Penugasan</span>
      </a>
    @endif

    <a class="nav-item {{ request()->routeIs('profil') ? 'active' : '' }}" href="{{ route('profil') }}">
      <span class="nav-icon" aria-hidden="true"><!-- user -->
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </span>
      <span class="nav-label">Profil</span>
    </a>
    @endauth
  </nav>
  
  @php
    // Cek apakah ada lowongan yang tersedia
    // Lowongan muncul jika: kuota tersedia DAN jadwal sudah dibuat DAN jadwal sudah dimulai dan belum berakhir
    $today = now()->toDateString();
    $allKuota = \App\Models\KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')->get();
    $allJadwal = \App\Models\JadwalMagang::all();
    
    $lowonganAktifTersedia = $allKuota->filter(function ($kuota) use ($allJadwal, $today) {
      // Cari jadwal dengan periode dan posisi yang sama (case-insensitive dan trim)
      // Setiap divisi memiliki jadwal terpisah
      $jadwal = $allJadwal->first(function ($j) use ($kuota) {
        return trim(strtolower($j->periode)) === trim(strtolower($kuota->periode))
            && trim(strtolower($j->posisi ?? '')) === trim(strtolower($kuota->posisi ?? ''));
      });
      
      if ($jadwal) {
        // Lowongan tersedia hanya jika jadwal sudah dimulai dan belum berakhir
        return $jadwal->tgl_mulai <= $today && $jadwal->tgl_selesai >= $today;
      }
      return false;
    })->count() > 0;
  @endphp
  
  @if($lowonganAktifTersedia)
    <div class="cta">
      <div class="cta-title">Lowongan Magang Dibuka!</div>
      <a href="{{ route('lowongan') }}" class="btn-cta">Lihat Detail</a>
    </div>
  @endif
</aside>
