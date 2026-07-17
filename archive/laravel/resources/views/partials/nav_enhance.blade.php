<style>
  /* Smooth page transition */
  .main-content {
    transition: opacity 0.2s ease-out, transform 0.2s ease-out;
    will-change: opacity, transform;
  }
  
  .main-content.page-transitioning {
    opacity: 0.3;
    transform: translateY(8px);
    pointer-events: none;
  }
  
  .main-content.page-loaded {
    opacity: 1;
    transform: translateY(0);
  }

  /* Lightweight loading indicator - subtle and non-intrusive */
  #pjax-loading-indicator {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #3B82F6, #2563EB, #1D4ED8);
    z-index: 9999;
    opacity: 0;
    transform: scaleX(0);
    transform-origin: left;
    transition: opacity 0.2s ease, transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
  }
  
  #pjax-loading-indicator.loading {
    opacity: 1;
    transform: scaleX(0.3);
    animation: loading-progress 0.6s ease-out forwards;
  }
  
  @keyframes loading-progress {
    0% { transform: scaleX(0.3); }
    50% { transform: scaleX(0.7); }
    100% { transform: scaleX(1); }
  }
  
  #pjax-loading-indicator.complete {
    opacity: 0;
    transform: scaleX(1);
    transition: opacity 0.3s ease 0.1s;
  }

  /* Hover menu smoothing */
  .nav-hover-debounce {
    transition: transform 160ms cubic-bezier(.2,.9,.2,1), opacity 160ms ease;
    transform-origin: top center;
    will-change: transform, opacity;
  }
  
  /* Instant feedback on menu click */
  .nav-item {
    transition: background-color 0.15s ease, transform 0.1s ease;
  }
  
  .nav-item:active {
    transform: scale(0.98);
  }
</style>

<div id="pjax-loading-indicator" aria-hidden="true"></div>

<script>
  (function(){
    // Optimized PJAX navigation for faster page switches
    // Support multiple container selectors - prioritize main-content for dashboard pages
    const containerSelectors = ['.main-content', '.content', '.content-wrap', 'main', 'section.content'];
    const loadingIndicator = document.getElementById('pjax-loading-indicator');
    let inflight = null;
    let isTransitioning = false;

    // Lightweight loading indicator
    function showLoading() {
      if (loadingIndicator && !isTransitioning) {
        isTransitioning = true;
        loadingIndicator.classList.remove('complete');
        loadingIndicator.classList.add('loading');
        // Add transition class to content
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
          mainContent.classList.add('page-transitioning');
        }
      }
    }
    
    function hideLoading() {
      if (loadingIndicator) {
        loadingIndicator.classList.remove('loading');
        loadingIndicator.classList.add('complete');
        // Remove transition class from content
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
          mainContent.classList.remove('page-transitioning');
          mainContent.classList.add('page-loaded');
        }
        // Reset after animation
        setTimeout(() => {
          isTransitioning = false;
          loadingIndicator.classList.remove('complete');
        }, 400);
      }
    }

    function isSameOrigin(url) {
      try { const u = new URL(url, location.href); return u.origin === location.origin; } catch (e) { return false; }
    }

    function shouldHandleLink(a){
      if (!a || !a.href) return false;
      if (a.target && a.target !== '_self') return false;
      if (a.hasAttribute('data-no-ajax')) return false;
      if (!isSameOrigin(a.href)) return false;
      const url = new URL(a.href, location.href);
      if (url.hash && url.pathname === location.pathname) return false;
      // Skip logout and form submissions
      if (url.pathname.includes('/logout')) return false;
      // Skip home/landing page - different structure, needs full reload
      if (url.pathname === '/' || url.pathname === '/home' || url.pathname.includes('welcome')) {
        return false; // Force full reload for landing page
      }
      // Skip if navigating from landing page to dashboard (different layouts)
      const currentPath = window.location.pathname;
      if ((currentPath === '/' || currentPath === '/home') && url.pathname.includes('dashboard')) {
        return false; // Force full reload when leaving landing page
      }
      // Skip if navigating from dashboard to landing page (different layouts)
      if (currentPath.includes('dashboard') && (url.pathname === '/' || url.pathname === '/home')) {
        return false; // Force full reload when going to landing page
      }
      // Allow login/register for faster navigation (but they will do full reload if needed)
      return true;
    }

    function findContainer(doc) {
      // Check if this is a landing page (different structure)
      const isLandingPage = doc.querySelector('main#beranda, main.hero, body > main');
      
      // For landing page, use body as container (full page replacement)
      if (isLandingPage && (window.location.pathname === '/' || window.location.pathname === '/home')) {
        return { selector: 'body', element: document.body };
      }
      
      // For dashboard pages, use standard container selectors
      for (const selector of containerSelectors) {
        const el = doc.querySelector(selector);
        if (el) {
          // Also check if current page has matching container
          const currentEl = document.querySelector(selector);
          if (currentEl) {
            return { selector, element: currentEl };
          }
        }
      }
      return null;
    }

    async function loadUrl(url, pushState = true) {
      // Prevent duplicate requests
      if (inflight) { 
        try { 
          inflight.abort(); 
        } catch(e){} 
      }
      
      // Normalize URL to prevent duplicate cache entries
      const normalizedUrl = new URL(url, location.href).href;
      
      // Check prefetch cache first for INSTANT load (no loading bar needed)
      if (prefetchCache.has(normalizedUrl)) {
        const cachedHtml = prefetchCache.get(normalizedUrl);
        const parser = new DOMParser();
        const doc = parser.parseFromString(cachedHtml, 'text/html');
        // Process immediately without showing loading bar for cached pages
        processPage(doc, normalizedUrl, pushState);
        return;
      }
      
      const controller = new AbortController(); 
      inflight = controller;
      
      // Show lightweight loading indicator only if not cached
      if (!prefetchCache.has(normalizedUrl)) {
        showLoading();
      }
      
      try {
        const res = await fetch(normalizedUrl, { 
          headers: { 
            'X-Requested-With': 'XMLHttpRequest', 
            'Accept': 'text/html',
            'X-PJAX': 'true'
          }, 
          credentials: 'same-origin', 
          signal: controller.signal,
          cache: 'default',
          priority: 'high' // High priority for active navigation
        });
        if (!res.ok) throw new Error('Network');
        const text = await res.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(text, 'text/html');
        
        // Cache the response for future use - increased cache size
        prefetchCache.set(normalizedUrl, text);
        if (prefetchCache.size > 30) {
          // Keep last 30 pages in cache for faster navigation
          const firstKey = prefetchCache.keys().next().value;
          prefetchCache.delete(firstKey);
        }
        
        // Process immediately - no delay needed for instant navigation
        processPage(doc, normalizedUrl, pushState);
      } catch (e) {
        if (e.name !== 'AbortError') {
          console.warn('PJAX failed, falling back', e);
          location.href = normalizedUrl;
        }
      } finally {
        hideLoading();
        inflight = null;
      }
    }
    
    function processPage(doc, url, pushState) {
      // Check if this is a landing page transition (different layout structure)
      const urlPath = new URL(url, location.href).pathname;
      const currentPath = window.location.pathname;
      const isLandingPage = urlPath === '/' || urlPath === '/home';
      const isFromLandingPage = currentPath === '/' || currentPath === '/home';
      
      // If transitioning between landing page and dashboard, do full reload
      if ((isLandingPage && !isFromLandingPage) || (!isLandingPage && isFromLandingPage)) {
        location.href = url;
        return;
      }
      
      // Find container in new document
      const newContainer = findContainer(doc);
      // Find container in current document
      const oldContainer = findContainer(document);
      
      if (newContainer && oldContainer) {
        // Special handling for body replacement (landing page)
        if (newContainer.selector === 'body') {
          // Replace entire body content
          document.body.innerHTML = doc.body.innerHTML;
          // Re-execute all scripts
          const scripts = doc.querySelectorAll('script');
          scripts.forEach(script => {
            const newScript = document.createElement('script');
            if (script.src) {
              newScript.src = script.src;
              newScript.async = true;
            } else {
              newScript.textContent = script.textContent;
            }
            document.head.appendChild(newScript);
          });
        } else {
          // Standard container swap for dashboard pages - use requestAnimationFrame for smooth rendering
          oldContainer.element.innerHTML = newContainer.element.innerHTML;
          
          // Update title and history immediately (synchronous operations)
          updateTitle(doc);
          if (pushState) history.pushState({ url: url }, '', url);
          
          // Execute scripts and scroll asynchronously to not block rendering
          requestAnimationFrame(() => {
            executeScripts(newContainer);
            
            // Scroll to top smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Trigger custom event for page change
            window.dispatchEvent(new CustomEvent('pjax:complete', { detail: { url } }));
            
            // Hide loading after content is visible
            requestAnimationFrame(() => {
              hideLoading();
            });
          });
        }
      } else {
        // fallback: full navigation
        location.href = url;
      }
    }
    
    function executeScripts(container) {
      const newScripts = container.element.querySelectorAll('script');
      // Execute scripts asynchronously to not block rendering
      newScripts.forEach((script, index) => {
        // Use requestAnimationFrame for non-blocking execution
        requestAnimationFrame(() => {
          const newScript = document.createElement('script');
          if (script.src) {
            newScript.src = script.src;
            newScript.async = true;
            newScript.defer = true;
          } else {
            newScript.textContent = script.textContent;
          }
          document.head.appendChild(newScript);
        });
      });
    }
    
    function updateTitle(doc) {
      const newTitle = doc.querySelector('title');
      if (newTitle) document.title = newTitle.textContent;
    }

    // Intercept clicks - ULTRA FAST with immediate prefetch check
    // OPTIMASI: Prevent double binding dengan check flag
    if (!window.pjaxClickHandlerAttached) {
      window.pjaxClickHandlerAttached = true;
      document.addEventListener('click', function(e){
        const a = e.target.closest && e.target.closest('a');
        if (!a) return;
        if (!shouldHandleLink(a)) {
          // If link should not be handled by PJAX (e.g., landing page), clear cache and do full reload
          const url = new URL(a.href, location.href);
          if (url.pathname === '/' || url.pathname === '/home') {
            // Clear cache for landing page to ensure fresh content
            prefetchCache.delete(a.href);
            prefetchedUrls.delete(a.href);
          }
          return; // Let browser handle normally (full reload)
        }
        e.preventDefault();
        
        // Instant visual feedback on menu click
        if (a.classList.contains('nav-item')) {
          a.style.transform = 'scale(0.98)';
          setTimeout(() => {
            a.style.transform = '';
          }, 150);
        }
        
        // Normalize URL to prevent duplicate cache entries and bugs
        const normalizedHref = new URL(a.href, location.href).href;
        
        // If already prefetched, load INSTANTLY without any delay
        if (prefetchCache.has(normalizedHref)) {
          // Process immediately - instant navigation for cached pages
          const cachedHtml = prefetchCache.get(normalizedHref);
          const parser = new DOMParser();
          const doc = parser.parseFromString(cachedHtml, 'text/html');
          // Show brief loading indicator for visual feedback
          showLoading();
          // Process immediately - use microtask for instant execution
          Promise.resolve().then(() => {
            processPage(doc, normalizedHref, true);
          });
        } else {
          // Not cached - use normal loadUrl which will fetch and show loading
          loadUrl(normalizedHref, true);
        }
      }, { passive: true, once: false });
    }

    // handle back/forward
    window.addEventListener('popstate', function(e){
      const url = (e.state && e.state.url) || location.href;
      loadUrl(url, false);
    });

    // Ultra-aggressive prefetch - INSTANT on mouseenter
    const prefetchedUrls = new Set();
    const prefetchCache = new Map();
    const prefetchControllers = new Map();
    
    // Aggressive prefetch on mouseenter - INSTANT
    document.addEventListener('mouseenter', function(e){
      const a = e.target.closest && e.target.closest('a');
      if (!a || !shouldHandleLink(a)) return;
      
      // Normalize URL to prevent duplicate cache entries and bugs
      const normalizedHref = new URL(a.href, location.href).href;
      
      // Skip if already prefetched or in cache
      if (prefetchedUrls.has(normalizedHref) || prefetchCache.has(normalizedHref)) return;
      
      // Cancel any existing prefetch for this URL
      if (prefetchControllers.has(normalizedHref)) {
        try { prefetchControllers.get(normalizedHref).abort(); } catch(e) {}
      }
      
      // Prefetch IMMEDIATELY with high priority
      const controller = new AbortController();
      prefetchControllers.set(normalizedHref, controller);
      
      fetch(normalizedHref, { 
        credentials: 'same-origin', 
        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-PJAX': 'true' },
        signal: controller.signal,
        cache: 'default',
        priority: 'high' // High priority for faster prefetch
      })
      .then(res => {
        if (res.ok) return res.text();
        throw new Error('Prefetch failed');
      })
      .then(html => {
        prefetchedUrls.add(normalizedHref);
        prefetchCache.set(normalizedHref, html);
        prefetchControllers.delete(normalizedHref);
        
        // Limit cache size to prevent memory issues
        if (prefetchCache.size > 30) {
          const firstKey = prefetchCache.keys().next().value;
          prefetchCache.delete(firstKey);
          prefetchedUrls.delete(firstKey);
        }
      })
      .catch(() => {
        prefetchControllers.delete(normalizedHref);
      });
    }, { passive: true, capture: true });
    
    // Also prefetch on touchstart for mobile (faster than click)
    document.addEventListener('touchstart', function(e){
      const a = e.target.closest && e.target.closest('a');
      if (!a || !shouldHandleLink(a)) return;
      
      const normalizedHref = new URL(a.href, location.href).href;
      if (prefetchedUrls.has(normalizedHref) || prefetchCache.has(normalizedHref)) return;
      
      if (prefetchControllers.has(normalizedHref)) {
        try { prefetchControllers.get(normalizedHref).abort(); } catch(e) {}
      }
      
      const controller = new AbortController();
      prefetchControllers.set(normalizedHref, controller);
      
      fetch(normalizedHref, { 
        credentials: 'same-origin', 
        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-PJAX': 'true' },
        signal: controller.signal,
        cache: 'default',
        priority: 'high'
      })
      .then(res => res.ok ? res.text() : Promise.reject())
      .then(html => {
        prefetchedUrls.add(normalizedHref);
        prefetchCache.set(normalizedHref, html);
        prefetchControllers.delete(normalizedHref);
        
        if (prefetchCache.size > 30) {
          const firstKey = prefetchCache.keys().next().value;
          prefetchCache.delete(firstKey);
          prefetchedUrls.delete(firstKey);
        }
      })
      .catch(() => {
        prefetchControllers.delete(normalizedHref);
      });
    }, { passive: true, capture: true });
    
    // Prefetch all sidebar links immediately on page load - AGGRESSIVE
    function prefetchSidebarLinks() {
      const sidebarLinks = document.querySelectorAll('.sidebar a[href], .nav a[href], .nav-item[href]');
      const linksArray = Array.from(sidebarLinks);
      
      // Prefetch all sidebar links in parallel with staggered start to avoid overwhelming
      linksArray.forEach((link, index) => {
        if (shouldHandleLink(link) && !prefetchedUrls.has(link.href)) {
          // Stagger by small delay to avoid overwhelming server
          setTimeout(() => {
            fetch(link.href, {
              credentials: 'same-origin',
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-PJAX': 'true' },
              priority: index === 0 ? 'high' : 'low' // First link gets high priority
            })
            .then(res => res.ok ? res.text() : Promise.reject())
            .then(html => {
              prefetchedUrls.add(link.href);
              prefetchCache.set(link.href, html);
              
              // Limit cache size
              if (prefetchCache.size > 30) {
                const firstKey = prefetchCache.keys().next().value;
                prefetchCache.delete(firstKey);
                prefetchedUrls.delete(firstKey);
              }
            })
            .catch(() => {});
          }, index * 100); // Stagger by 100ms
        }
      });
    }
    
    // Prefetch sidebar links immediately - don't wait for DOMContentLoaded
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', prefetchSidebarLinks);
    } else {
      // Already loaded, prefetch immediately
      prefetchSidebarLinks();
    }
    
    // Also prefetch on page visibility change (when user returns to tab)
    document.addEventListener('visibilitychange', function() {
      if (!document.hidden) {
        prefetchSidebarLinks();
      }
    });
    
    // Prefetch critical pages on page load
    if (document.readyState === 'complete') {
      prefetchCriticalPages();
    } else {
      window.addEventListener('load', prefetchCriticalPages);
    }
    
    function prefetchCriticalPages() {
      // Get current path to determine which pages to prefetch
      const currentPath = window.location.pathname;
      const criticalPages = [];
      
      // Prefetch based on current page
      if (currentPath === '/' || currentPath.includes('home')) {
        // From landing page, prefetch login
        criticalPages.push('/login');
        criticalPages.push('/register');
      } else if (currentPath.includes('login') || currentPath.includes('register')) {
        // From login/register, prefetch dashboard if authenticated
        @auth
        criticalPages.push('{{ route("dashboard") }}');
        criticalPages.push('{{ route("lowongan") }}');
        @endauth
      } else if (currentPath.includes('lamaran')) {
        // From lamaran, prefetch lowongan and riwayat
        criticalPages.push('{{ route("lowongan") }}');
        criticalPages.push('{{ route("riwayat.lamaran") }}');
        criticalPages.push('{{ route("dashboard") }}');
      } else if (currentPath.includes('lowongan')) {
        // From lowongan, prefetch lamaran and riwayat
        criticalPages.push('{{ route("lamaran") }}');
        criticalPages.push('{{ route("riwayat.lamaran") }}');
        criticalPages.push('{{ route("dashboard") }}');
      } else if (currentPath.includes('dashboard')) {
        // From dashboard, prefetch semua halaman penting dengan prioritas tinggi
        criticalPages.push('{{ route("riwayat.lamaran") }}'); // Prioritas tinggi - sering dikunjungi
        criticalPages.push('{{ route("profil") }}');
        criticalPages.push('{{ route("lowongan") }}');
        criticalPages.push('{{ route("lamaran") }}');
      } else if (currentPath.includes('riwayat') || currentPath.includes('status')) {
        // From riwayat/status, prefetch dashboard dan profil dengan prioritas tinggi
        criticalPages.push('{{ route("dashboard") }}'); // Prioritas tinggi - sering dikunjungi
        criticalPages.push('{{ route("profil") }}');
        criticalPages.push('{{ route("lowongan") }}');
        criticalPages.push('{{ route("lamaran") }}');
      } else if (currentPath.includes('profil')) {
        // From profil, prefetch dashboard dengan prioritas tinggi
        criticalPages.push('{{ route("dashboard") }}'); // Prioritas tinggi - sering dikunjungi
        criticalPages.push('{{ route("riwayat.lamaran") }}');
        criticalPages.push('{{ route("lowongan") }}');
        criticalPages.push('{{ route("lamaran") }}');
      }
      
      criticalPages.filter(url => url && url !== window.location.href).forEach((url, index) => {
        if (!prefetchedUrls.has(url)) {
          // Prioritas tinggi untuk halaman yang sering dikunjungi (dashboard, riwayat, profil)
          const isHighPriority = url.includes('dashboard') || url.includes('riwayat') || url.includes('profil');
          
          // Stagger prefetch requests - halaman prioritas tinggi lebih cepat
          setTimeout(() => {
            fetch(url, {
              credentials: 'same-origin',
              headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-PJAX': 'true' },
              priority: isHighPriority ? 'high' : 'low' // High priority untuk halaman penting
            })
            .then(res => res.ok ? res.text() : Promise.reject())
            .then(html => {
              prefetchedUrls.add(url);
              prefetchCache.set(url, html);
            })
            .catch(() => {});
          }, isHighPriority ? index * 20 : index * 50); // Stagger lebih cepat untuk high priority
        }
      });
    }

    // Hover menu debounce helper
    let hoverMap = new WeakMap();
    function attachHoverDebounce(el, openCb, closeCb, delay = 120) {
      let tOpen = null, tClose = null;
      el.addEventListener('mouseenter', () => {
        clearTimeout(tClose);
        tOpen = setTimeout(() => openCb(el), delay);
      });
      el.addEventListener('mouseleave', () => {
        clearTimeout(tOpen);
        tClose = setTimeout(() => closeCb(el), delay);
      });
    }

    // apply hover debounce to probable nav menus
    document.querySelectorAll('.nav, .navbar, .menu, .has-submenu').forEach(menu => {
      attachHoverDebounce(menu, (el)=> el.classList && el.classList.add('open','nav-hover-debounce'), (el)=> el.classList && el.classList.remove('open'));
    });

    // Expose function to opt-out on links if needed
    window.PJAX = { loadUrl };
    
    // Mark page as loaded when DOM is ready - hide initial loading bar
    // OPTIMASI: Prevent multiple calls dengan flag
    if (!window.pageLoadedMarked) {
      window.pageLoadedMarked = true;
      function markPageLoaded() {
        if (document.body && !document.body.classList.contains('page-loaded')) {
          document.body.classList.add('page-loaded');
          // Loading bar disabled - tidak perlu hide loading bar
        }
      }
      
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', markPageLoaded, { once: true });
      } else {
        markPageLoaded();
      }
    }
  })();
</script>
