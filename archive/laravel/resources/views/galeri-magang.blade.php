<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Magang - BBKB Yogyakarta</title>
    {{-- OPTIMASI: Non-blocking font loading --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>
    <style>
      :root { 
        --primary:#0C3A6B; 
        --accent:#F4B400; 
        --dark:#0b1020; 
        --muted:#6b7280;
        --yellow-start: #fbbf24;
        --yellow-mid: #fcd34d;
        --yellow-end: #fde047;
        --red-start: #dc2626;
        --red-mid: #b91c1c;
      }
      *{box-sizing:border-box} 
      html {
        font-size: 16px;
        -webkit-text-size-adjust: 100%;
        text-size-adjust: 100%;
        scroll-behavior: smooth;
      }
      body{
        margin:0;
        font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
        background: linear-gradient(
          135deg,
          #fef3c7 0%,
          #fde68a 20%,
          #fcd34d 40%,
          #fbbf24 60%,
          #f59e0b 80%,
          #d97706 100%
        );
        color:#0f172a;
        min-height: 100vh;
        position: relative;
        font-size: 16px;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
      body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* OPTIMASI: Simplify gradient untuk performa lebih cepat */
        background: radial-gradient(
          circle at 20% 30%,
          rgba(254, 243, 199, 0.2) 0%,
          transparent 50%
        ),
        radial-gradient(
          circle at 80% 70%,
          rgba(251, 191, 36, 0.15) 0%,
          transparent 50%
        );
        pointer-events: none;
        z-index: 0;
        will-change: transform;
        transform: translateZ(0); /* GPU acceleration */
      }
      /* OPTIMASI: Hapus body::after untuk mengurangi beban render */
      /* Navbar Styling - Sama dengan halaman beranda */
      .gallery-navbar {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
      }
      
      .gallery-navbar .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 14px 24px 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
      }
      
      .gallery-navbar .logo-section {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: 0;
      }
      
      .gallery-navbar .logo-section img {
        height: 48px;
        max-height: 48px;
        object-fit: contain;
      }
      
      .gallery-navbar .logo-section img:last-child {
        height: 56px;
        max-height: 56px;
      }
      
      .gallery-navbar .nav-menu {
        display: flex;
        gap: 28px;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
      }
      
      .gallery-navbar .nav-link {
        position: relative;
        display: inline-block;
        padding: 8px 4px;
        color: #0f172a;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform-origin: center;
      }
      
      .gallery-navbar .nav-link::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(251, 191, 36, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                    height 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        z-index: -1;
        opacity: 0;
      }
      
      .gallery-navbar .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #fbbf24 0%, #fcd34d 50%, #fde047 100%);
        transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                    left 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.5);
        transform: translateX(-50%);
      }
      
      .gallery-navbar .nav-link:hover {
        color: #f59e0b;
        transform: translateY(-3px) scale(1.05);
        font-weight: 600;
      }
      
      .gallery-navbar .nav-link:hover::before {
        width: 120px;
        height: 120px;
        opacity: 1;
      }
      
      .gallery-navbar .nav-link:hover::after {
        width: 100%;
        left: 50%;
      }
      
      .gallery-navbar .nav-link:active {
        transform: translateY(-1px) scale(1.02);
        transition: all 0.15s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      }
      
      /* Smooth page transition overlay */
      .gallery-page-transition-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fcd34d 100%);
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      }
      
      .gallery-page-transition-overlay.active {
        opacity: 1;
        pointer-events: all;
      }
      
      .gallery-navbar .user-profile-btn {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 16px;
        border-radius: 12px;
        background: #ffffff;
        border: 2px solid #e5e7eb;
        color: #0b1020;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }
      
      .gallery-navbar .user-profile-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #fbbf24 0%, #fcd34d 50%, #fde047 100%);
        opacity: 0;
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 0;
      }
      
      .gallery-navbar .user-profile-btn:hover::before {
        opacity: 1;
      }
      
      .gallery-navbar .user-profile-btn:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        border-color: #fbbf24;
      }
      
      .gallery-navbar .user-profile-btn:active {
        transform: translateY(0) scale(1);
      }
      
      .gallery-navbar .user-profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(11, 16, 32, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 10;
      }
      
      .gallery-navbar .user-profile-btn:hover .user-profile-img {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.6);
      }
      
      .gallery-navbar .user-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        position: relative;
        z-index: 10;
      }
      
      .gallery-navbar .user-name {
        font-size: 14px;
        font-weight: 700;
        line-height: 1.2;
        color: #111827;
        transition: color 0.3s ease, text-shadow 0.3s ease;
      }
      
      .gallery-navbar .user-email {
        font-size: 12px;
        opacity: 0.8;
        line-height: 1.2;
        color: #6b7280;
        transition: color 0.3s ease, text-shadow 0.3s ease;
      }
      
      .gallery-navbar .user-profile-btn:hover .user-name,
      .gallery-navbar .user-profile-btn:hover .user-email {
        color: #1f2937;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
      }
      
      .gallery-navbar .user-initial {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(11, 16, 32, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: #0b1020;
        border: 2px solid rgba(11, 16, 32, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 10;
      }
      
      .gallery-navbar .user-profile-btn:hover .user-initial {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.6);
      }
      
      .gallery-navbar .login-btn {
        color: #0b1020;
        font-weight: 600;
      }
      
      @media (max-width: 968px) {
        .gallery-navbar .nav-menu {
          display: none;
        }
      }
      
      .container{max-width:1200px;margin:0 auto;padding:0 24px}
      .section{
        padding:60px 0;
        background: transparent;
        position: relative;
        z-index: 1;
      }
      
      /* Category Buttons with Effects */
      .gallery-category {
        display: flex;
        gap: 12px;
        margin-bottom: 48px;
        flex-wrap: wrap;
        justify-content: center;
        position: relative;
        z-index: 2;
      }
      .category-btn {
        position: relative;
        padding: 14px 28px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
      }
      
      .category-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--yellow-start) 0%, var(--yellow-mid) 50%, var(--yellow-end) 100%);
        transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 0;
        will-change: left;
      }
      
      .category-btn span {
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
      }
      
      .category-btn:hover {
        color: #1f2937;
        border-color: var(--yellow-start);
        background: rgba(255, 255, 255, 1);
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
      }
      
      .category-btn:hover::before {
        left: 0;
      }
      
      .category-btn:hover span {
        color: #1f2937;
      }
      
      .category-btn:active {
        transform: translateY(-1px) scale(1);
      }
      
      .category-btn.active {
        background: rgba(255, 255, 255, 0.95);
        color: #1f2937;
        border-color: var(--yellow-start);
        box-shadow: 0 6px 18px rgba(251, 191, 36, 0.5);
        transform: translateY(0) scale(1);
      }
      
      .category-btn.active::before {
        left: 0;
      }
      
      .category-btn.active span {
        color: #1f2937;
      }
      
      /* Ensure smooth transition when removing active state */
      .category-btn:not(.active):not(:hover)::before {
        left: -100%;
        transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      /* Smooth transition for all state changes */
      .category-btn.active,
      .category-btn:not(.active) {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      /* Gallery Container */
      .gallery-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 32px;
        margin-top: 32px;
        position: relative;
        z-index: 2;
      }
      
      /* Gallery Items - Simplified */
      .gallery-item {
        position: relative;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
      }
      
      .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
      }
      
      .gallery-item img {
        /* OPTIMASI: GPU acceleration dan lazy loading optimization */
        will-change: transform;
        transform: translateZ(0);
        /* OPTIMASI: Object-fit untuk faster rendering */
        object-fit: cover;
        width: 100%;
        height: 260px;
        object-fit: cover;
        display: block;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      }
      
      .gallery-item-info {
        padding: 24px;
        position: relative;
        z-index: 2;
        background: transparent;
      }
      
      .gallery-item-title {
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 12px;
        font-size: 20px;
        background: linear-gradient(135deg, var(--yellow-start) 0%, var(--red-start) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.3;
      }
      
      .gallery-item-desc {
        color: #64748b;
        font-size: 15px;
        margin: 0;
        line-height: 1.7;
      }
      
      
      /* Responsive */
      @media (max-width: 968px) {
        body {
          background-attachment: scroll;
        }
        .gallery-container {
          grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
          gap: 24px;
        }
        .category-btn {
          padding: 12px 20px;
          font-size: 14px;
        }
        .section {
          padding: 60px 0;
        }
      }
      @media (max-width: 640px) {
        .gallery-container {
          grid-template-columns: 1fr;
          gap: 20px;
        }
        .gallery-navbar .navbar-container {
          padding: 12px 16px;
        }
        .gallery-navbar .nav-menu {
          gap: 16px;
        }
        .gallery-navbar .nav-link {
          font-size: 14px;
        }
        .gallery-navbar .user-info {
          display: none;
        }
        body {
          background-attachment: scroll;
        }
        .category-btn {
          padding: 10px 16px;
          font-size: 13px;
        }
      }
    </style>
  </head>
  <body>
    <nav class="gallery-navbar">
      <div class="navbar-container">
        <div class="logo-section">
          <img src="/images/logoBBKB.png" alt="Logo BBKB" onerror="this.src='/imgs/logo.png'; this.onerror=null;">
          <img src="/images/logokemenperi.png" alt="Logo Kemenperin" onerror="this.onerror=null;">
        </div>

        <ul class="nav-menu">
          <li><a href="{{ route('home') }}#beranda" class="nav-link" data-nav="beranda">Beranda</a></li>
          <li><a href="{{ route('tentang-kami') }}" class="nav-link" data-nav="tentang">Tentang Kami</a></li>
          <li><a href="{{ route('home') }}#alur" class="nav-link" data-nav="alur">Alur</a></li>
          <li><a href="{{ route('home') }}#lowongan" class="nav-link" data-nav="lowongan">Lowongan</a></li>
          <li><a href="{{ route('galeri-magang') }}" class="nav-link" data-nav="galeri">Galeri Magang</a></li>
        </ul>
        
        <div class="gallery-page-transition-overlay" id="galleryPageTransitionOverlay"></div>

        @auth
          <a href="{{ route('dashboard') }}" class="user-profile-btn">
            @if(auth()->user()->foto_profil)
              <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                   alt="{{ auth()->user()->nama }}" 
                   class="user-profile-img">
            @elseif(auth()->user()->avatar)
              <img src="{{ auth()->user()->avatar }}" 
                   alt="{{ auth()->user()->nama }}" 
                   class="user-profile-img">
            @else
              <div class="user-initial">
                {{ strtoupper(substr(auth()->user()->nama ?? 'U', 0, 1)) }}
              </div>
            @endif
            <div class="user-info">
              <span class="user-name">{{ auth()->user()->nama ?? 'User' }}</span>
              <span class="user-email">{{ auth()->user()->email }}</span>
            </div>
          </a>
        @else
          <a href="{{ route('login') }}" class="user-profile-btn">
            <span class="login-btn">Login / Daftar</span>
          </a>
        @endauth
      </div>
    </nav>

    <section class="section">
      <div class="container">
          @if($galeri->count() > 0)
            <div class="gallery-category">
              <button class="category-btn active" data-category="all"><span>Semua</span></button>
            </div>
          @endif

          <div class="gallery-container">
            @forelse($galeri as $item)
              <div class="gallery-item" data-category="all">
                <img src="{{ $item->foto_url ?? 'https://via.placeholder.com/300x260?text=Image+Not+Found' }}" 
                     alt="{{ $item->judul }}" 
                     loading="lazy" 
                     decoding="async"
                     onerror="this.src='https://via.placeholder.com/300x260?text=Image+Not+Found'">
                <div class="gallery-item-info">
                  <h3 class="gallery-item-title">{{ $item->judul }}</h3>
                  @if($item->deskripsi)
                    <p class="gallery-item-desc">{{ $item->deskripsi }}</p>
                  @endif
                </div>
              </div>
            @empty
              <div style="grid-column: 1 / -1; text-align: center; padding: 60px 24px; color: #6B7280;">
                <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.5;">📷</div>
                <h3 style="font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">Belum Ada Foto Galeri</h3>
                <p style="margin: 0; font-size: 14px; color: #6B7280;">Foto galeri akan ditampilkan di sini.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </section>

    <script>
      // OPTIMASI: Remove fade-in untuk faster initial render
      (function() {
        // Store transition state
        let isTransitioning = false;
        
        // OPTIMASI: Skip fade-in animation untuk performa lebih cepat
        // OPTIMASI: Skip fade-in animation untuk faster initial render
        function fadeInOnLoad() {
          // Skip animation - langsung show content untuk performa lebih cepat
          if (document.body) {
            document.body.style.opacity = '1';
          }
        }
        
        // Handle page transitions for navigation links with modern effects
        function initPageTransitions() {
          const navLinks = document.querySelectorAll('.gallery-navbar a[href]');
          const overlay = document.getElementById('galleryPageTransitionOverlay');
          
          navLinks.forEach(link => {
            const href = link.getAttribute('href');
            
            // Skip anchor links, external links, and special links
            if (!href || 
                href.startsWith('#') || 
                href.startsWith('http') ||
                href.startsWith('mailto:') ||
                href.startsWith('javascript:')) {
              return;
            }
            
            // Apply transition to internal navigation
            link.addEventListener('click', function(e) {
              // Don't prevent default for forms or special links
              if (this.hasAttribute('download') || 
                  this.hasAttribute('target') || 
                  this.hasAttribute('onclick') ||
                  isTransitioning) {
                return;
              }
              
              isTransitioning = true;
              e.preventDefault();
              const targetUrl = this.getAttribute('href');
              
              // Add ripple effect
              const ripple = document.createElement('span');
              ripple.style.cssText = `
                position: fixed;
                border-radius: 50%;
                background: rgba(251, 191, 36, 0.6);
                width: 20px;
                height: 20px;
                pointer-events: none;
                z-index: 10000;
                animation: ripple 0.6s ease-out;
              `;
              
              const rect = this.getBoundingClientRect();
              ripple.style.left = (rect.left + rect.width / 2) + 'px';
              ripple.style.top = (rect.top + rect.height / 2) + 'px';
              document.body.appendChild(ripple);
              
              // Show overlay with smooth fade
              if (overlay) {
                overlay.classList.add('active');
              }
              
              // Fade out body
              document.body.style.transition = 'opacity 0.3s ease-out';
              document.body.style.opacity = '0';
              
              // Navigate after transition
              setTimeout(() => {
                window.location.href = targetUrl;
              }, 300);
              
              // Remove ripple after animation
              setTimeout(() => {
                ripple.remove();
              }, 600);
            });
          });
          
          // Add ripple animation if not exists
          if (!document.getElementById('galleryRippleStyle')) {
            const style = document.createElement('style');
            style.id = 'galleryRippleStyle';
            style.textContent = `
              @keyframes ripple {
                to {
                  transform: scale(8);
                  opacity: 0;
                }
              }
            `;
            document.head.appendChild(style);
          }
        }
        
        // Initialize on DOM ready
        if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', function() {
            fadeInOnLoad();
            initPageTransitions();
          });
        } else {
          fadeInOnLoad();
          initPageTransitions();
        }
      })();
      
      // Filter gallery by category with smooth transitions (if category buttons exist)
      const categoryButtons = document.querySelectorAll('.category-btn');
      if (categoryButtons.length > 0) {
        categoryButtons.forEach(btn => {
          btn.addEventListener('click', function() {
            // Get current active button
            const currentActive = document.querySelector('.category-btn.active');
            const newActive = this;
            
            // Only update if clicking different button
            if (currentActive !== newActive) {
              // Remove active from current - ensure smooth transition
              if (currentActive) {
                // Force reflow to ensure transition starts
                currentActive.offsetHeight;
                currentActive.classList.remove('active');
              }
              
              // Add active to new button - use double requestAnimationFrame for smoother transition
              requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                  newActive.classList.add('active');
                });
              });
            }

            // Filter items - simple show/hide
            const category = this.dataset.category;
            const items = document.querySelectorAll('.gallery-item');
            
            items.forEach(item => {
              const shouldShow = category === 'all' || item.dataset.category === category;
              item.style.display = shouldShow ? 'block' : 'none';
            });
          });
        });
      }
    </script>
  </body>
</html>


