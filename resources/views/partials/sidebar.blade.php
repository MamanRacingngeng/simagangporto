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
  
  @auth
    @php
      // OPTIMASI: Gunakan cache untuk menghindari query setiap kali sidebar dimuat
      // Data lowongan sudah di-cache di controller, gunakan data dari session atau cache
      $today = now()->toDateString();
      $lowonganCacheKey = "sidebar_lowongan_{$today}";
      
      // Gunakan cache dengan durasi 5 menit untuk menghindari query berulang
      $lowonganData = \Illuminate\Support\Facades\Cache::remember($lowonganCacheKey, 300, function () use ($today) {
        $allKuota = \App\Models\KuotaMagang::whereColumn('kuota_terpakai', '<', 'kuota_max')
          ->get(['id', 'periode', 'posisi', 'kuota_max', 'kuota_terpakai']);
        $allJadwal = \App\Models\JadwalMagang::where('tgl_selesai', '>=', $today)
          ->get(['id', 'periode', 'posisi', 'tgl_mulai', 'tgl_selesai']);
      
      $lowonganTersediaList = $allKuota->filter(function ($kuota) use ($allJadwal, $today) {
        $jadwal = $allJadwal->first(function ($j) use ($kuota) {
          return trim(strtolower($j->periode)) === trim(strtolower($kuota->periode))
            && trim(strtolower($j->posisi ?? '')) === trim(strtolower($kuota->posisi ?? ''));
        });
        return $jadwal && $jadwal->tgl_selesai >= $today;
      });
      
        return [
          'lowonganTersedia' => $lowonganTersediaList->count() > 0,
          'jumlahLowongan' => $lowonganTersediaList->count(),
        ];
      });
      
      $lowonganTersedia = $lowonganData['lowonganTersedia'];
      $jumlahLowongan = $lowonganData['jumlahLowongan'];
    @endphp
    
    <!-- Sidebar Notification Card -->
    <div class="sidebar-notification-wrapper">
      @if($lowonganTersedia)
        <div class="sidebar-notification-card">
          <div class="sidebar-notification-content">
            <h4 class="sidebar-notification-title">Lowongan Tersedia!</h4>
            <p class="sidebar-notification-text">
              @if($jumlahLowongan > 0)
                Ada {{ $jumlahLowongan }} {{ $jumlahLowongan == 1 ? 'lowongan' : 'lowongan' }} yang bisa Anda lamar sekarang.
              @else
                Lowongan magang baru telah dibuka. Segera ajukan lamaran Anda!
              @endif
            </p>
            <a href="{{ route('lowongan') }}" class="sidebar-notification-link">
              Lihat Lowongan
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </div>
        </div>
      @else
        <div class="sidebar-notification-card">
          <div class="sidebar-notification-content">
            <h4 class="sidebar-notification-title">Belum Ada Lowongan</h4>
            <p class="sidebar-notification-text">
              Saat ini belum ada lowongan magang yang tersedia. Mohon bersabar, lowongan baru akan segera dibuka.
            </p>
            <div class="sidebar-notification-tip">
              <span>Pantau dashboard secara berkala untuk update terbaru</span>
            </div>
          </div>
        </div>
      @endif
    </div>
  @endauth
</aside>

<style>
  /* Sidebar Navigation Hover Effects - Bar biru di kiri dan ripple effect */
  .nav-item {
    position: relative !important;
    overflow: hidden !important;
    -webkit-tap-highlight-color: transparent;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
    will-change: transform, background-color;
  }
  
  .nav-item::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    width: 4px;
    height: 0;
    background: #2563EB;
    border-radius: 0 4px 4px 0;
    transform: translateY(-50%);
    transition: height 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
  }
  
  .nav-item::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
  }
  
  .nav-item:hover::before {
    height: 60% !important;
  }
  
  .nav-item:hover::after {
    width: 200px !important;
    height: 200px !important;
  }
  
  .nav-item:hover .nav-icon {
    transform: scale(1.1) rotate(5deg) !important;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }
  
  /* Instant feedback on click - smooth and responsive */
  .nav-item:active {
    transform: translateX(2px) scale(0.98) !important;
    transition: transform 0.1s ease !important;
  }
  
  /* Active state - Biru jelas dan elegan - ULTRA HIGH SPECIFICITY untuk override semua CSS lain */
  /* Menggunakan multiple selectors dengan !important untuk memastikan override */
  aside.sidebar nav.nav a.nav-item.active,
  aside.sidebar .nav a.nav-item.active,
  .sidebar nav.nav a.nav-item.active,
  .sidebar .nav a.nav-item.active,
  aside.sidebar .nav .nav-item.active,
  .sidebar .nav .nav-item.active,
  .nav-item.active {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
    background-color: #2563EB !important;
    color: #FFFFFF !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25) !important;
    transition: all 0.2s ease !important;
    border-left: 4px solid #FFFFFF !important;
    border-color: #FFFFFF !important;
  }
  
  aside.sidebar nav.nav a.nav-item.active .nav-icon,
  aside.sidebar .nav a.nav-item.active .nav-icon,
  .sidebar nav.nav a.nav-item.active .nav-icon,
  .sidebar .nav a.nav-item.active .nav-icon,
  aside.sidebar .nav .nav-item.active .nav-icon,
  .sidebar .nav .nav-item.active .nav-icon,
  .nav-item.active .nav-icon,
  .nav-item.active .nav-icon svg,
  .nav-item.active svg {
    color: #FFFFFF !important;
    fill: none !important;
    stroke: #FFFFFF !important;
  }
  
  aside.sidebar nav.nav a.nav-item.active .nav-label,
  aside.sidebar .nav a.nav-item.active .nav-label,
  .sidebar nav.nav a.nav-item.active .nav-label,
  .sidebar .nav a.nav-item.active .nav-label,
  aside.sidebar .nav .nav-item.active .nav-label,
  .sidebar .nav .nav-item.active .nav-label,
  .nav-item.active .nav-label {
    color: #FFFFFF !important;
  }
  
  aside.sidebar nav.nav a.nav-item.active::before,
  aside.sidebar .nav a.nav-item.active::before,
  .sidebar nav.nav a.nav-item.active::before,
  .sidebar .nav a.nav-item.active::before,
  aside.sidebar .nav .nav-item.active::before,
  .sidebar .nav .nav-item.active::before,
  .nav-item.active::before {
    background: #FFFFFF !important;
    background-color: #FFFFFF !important;
    height: 60% !important;
    width: 4px !important;
    opacity: 1 !important;
    display: block !important;
  }
  
  aside.sidebar nav.nav a.nav-item.active::after,
  aside.sidebar .nav a.nav-item.active::after,
  .sidebar nav.nav a.nav-item.active::after,
  .sidebar .nav a.nav-item.active::after,
  aside.sidebar .nav .nav-item.active::after,
  .sidebar .nav .nav-item.active::after,
  .nav-item.active::after {
    display: none !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
  }
  
  /* Override hover state untuk active item */
  aside.sidebar nav.nav a.nav-item.active:hover,
  aside.sidebar .nav a.nav-item.active:hover,
  .sidebar nav.nav a.nav-item.active:hover,
  .sidebar .nav a.nav-item.active:hover,
  aside.sidebar .nav .nav-item.active:hover,
  .sidebar .nav .nav-item.active:hover,
  .nav-item.active:hover {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
    background-color: #2563EB !important;
    color: #FFFFFF !important;
  }
  
  aside.sidebar nav.nav a.nav-item.active:hover .nav-icon,
  aside.sidebar .nav a.nav-item.active:hover .nav-icon,
  .sidebar nav.nav a.nav-item.active:hover .nav-icon,
  .sidebar .nav a.nav-item.active:hover .nav-icon,
  aside.sidebar .nav .nav-item.active:hover .nav-icon,
  .sidebar .nav .nav-item.active:hover .nav-icon,
  .nav-item.active:hover .nav-icon,
  .nav-item.active:hover .nav-icon svg {
    color: #FFFFFF !important;
    transform: none !important;
    stroke: #FFFFFF !important;
  }
  
  /* Optimize for performance */
  .nav-item * {
    pointer-events: none;
  }
  
  .nav-item {
    pointer-events: auto;
  }
  
  .sidebar-notification-wrapper {
    margin: 12px 10px;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid #E5E7EB;
  }
  
  .sidebar-notification-card {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 1px 3px rgba(59, 130, 246, 0.1);
    border: 1px solid #BFDBFE;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }
  
  .sidebar-notification-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    border-radius: 8px 8px 0 0;
    background: linear-gradient(90deg, #3B82F6, #2563EB, #1D4ED8);
    transition: all 0.3s ease;
  }
  
  .sidebar-notification-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(59, 130, 246, 0.15);
    border-color: #93C5FD;
    background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
  }
  
  .sidebar-notification-content {
    position: relative;
    z-index: 1;
  }
  
  .sidebar-notification-title {
    font-size: 12px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0 0 4px 0;
    line-height: 1.3;
    letter-spacing: -0.1px;
  }
  
  .sidebar-notification-text {
    font-size: 10px;
    color: #475569;
    margin: 0 0 8px 0;
    line-height: 1.4;
  }
  
  .sidebar-notification-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 600;
    color: #2563EB;
    text-decoration: none;
    transition: all 0.3s ease;
    padding: 4px 8px;
    background: #FFFFFF;
    border-radius: 5px;
    border: 1px solid #BFDBFE;
  }
  
  .sidebar-notification-link:hover {
    background: #3B82F6;
    color: #FFFFFF;
    border-color: #3B82F6;
    transform: translateX(2px);
  }
  
  .sidebar-notification-link svg {
    width: 10px;
    height: 10px;
    transition: transform 0.3s ease;
  }
  
  .sidebar-notification-link:hover svg {
    transform: translateX(2px);
  }
  
  .sidebar-notification-tip {
    padding: 6px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 5px;
    margin-top: 8px;
    border: 1px solid rgba(191, 219, 254, 0.5);
  }
  
  .sidebar-notification-tip span {
    font-size: 9px;
    color: #475569;
    line-height: 1.4;
    display: block;
  }
  
  @media (max-width: 1024px) {
    .sidebar-notification-wrapper {
      margin: 10px 8px;
      padding-top: 10px;
    }
    
    .sidebar-notification-card {
      padding: 8px;
    }
    
    .sidebar-notification-title {
      font-size: 11px;
    }
    
    .sidebar-notification-text {
      font-size: 9px;
    }
    
  }
  
  /* FINAL OVERRIDE - Memastikan active state biru jelas untuk SEMUA halaman */
  /* Style ini di-load terakhir untuk memastikan override semua CSS lain */
  aside.sidebar a.nav-item.active,
  .sidebar a.nav-item.active {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
    background-color: #2563EB !important;
    color: #FFFFFF !important;
  }
  aside.sidebar a.nav-item.active *,
  .sidebar a.nav-item.active * {
    color: #FFFFFF !important;
  }
  aside.sidebar a.nav-item.active svg,
  .sidebar a.nav-item.active svg {
    stroke: #FFFFFF !important;
  }
</style>
