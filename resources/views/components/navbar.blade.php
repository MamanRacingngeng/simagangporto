<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <style>
        .nav-link {
            position: relative;
            display: inline-block;
            padding: 8px 4px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            transform-origin: center;
        }
        
        .nav-link::before {
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
        
        .nav-link::after {
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
        
        .nav-link:hover {
            color: #f59e0b;
            transform: translateY(-3px) scale(1.05);
            font-weight: 600;
        }
        
        .nav-link:hover::before {
            width: 120px;
            height: 120px;
            opacity: 1;
        }
        
        .nav-link:hover::after {
            width: 100%;
            left: 50%;
        }
        
        .nav-link:active {
            transform: translateY(-1px) scale(1.02);
            transition: all 0.15s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        /* Smooth page transition overlay */
        .page-transition-overlay {
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
        
        .page-transition-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        
        .user-profile-btn {
            position: relative;
            overflow: hidden;
            background: #ffffff !important;
            border: 2px solid #e5e7eb;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .user-profile-btn::before {
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
        
        .user-profile-btn:hover::before {
            opacity: 1;
        }
        
        .user-profile-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
            border-color: #fbbf24;
        }
        
        .user-profile-btn:active {
            transform: translateY(0) scale(1);
        }
        
        .user-profile-img {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .user-profile-btn:hover .user-profile-img {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.6);
        }
        
        .user-profile-btn:hover .user-profile-img,
        .user-profile-btn:hover span {
            color: #1f2937;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.4);
            }
            50% {
                box-shadow: 0 0 0 4px rgba(251, 191, 36, 0);
            }
        }
        
        .user-profile-btn:focus {
            animation: pulse-glow 1.5s ease-in-out infinite;
        }
    </style>
    <div class="max-w-7xl mx-auto py-4 flex justify-between items-center" style="padding-left: 16px; padding-right: 24px;">
        <div class="flex items-center gap-3" style="margin-left: 0;">
            <div class="flex items-center gap-2">
                <img src="/images/logoBBKB.png" alt="Logo BBKB" class="h-12 max-h-12 object-contain transition-transform duration-300 hover:scale-110" onerror="this.src='/imgs/logo.png'; this.onerror=null;">
                <img src="/images/logokemenperi.png" alt="Logo Kemenperin" class="h-14 max-h-14 object-contain transition-transform duration-300 hover:scale-110" onerror="this.onerror=null;">
            </div>
        </div>

            <ul class="hidden md:flex gap-6 font-medium">
                <li><a href="{{ route('home') }}#beranda" class="nav-link text-gray-700" data-nav="beranda">Beranda</a></li>
                <li><a href="{{ route('tentang-kami') }}" class="nav-link text-gray-700" data-nav="tentang">Tentang Kami</a></li>
                <li><a href="{{ route('home') }}#alur" class="nav-link text-gray-700" data-nav="alur">Alur</a></li>
                <li><a href="{{ route('home') }}#lowongan" class="nav-link text-gray-700" data-nav="lowongan">Lowongan</a></li>
                <li><a href="{{ route('galeri-magang') }}" class="nav-link text-gray-700" data-nav="galeri">Galeri Magang</a></li>
            </ul>
            
            <div class="page-transition-overlay" id="pageTransitionOverlay"></div>
            
            <script>
                // Smooth page transitions with modern effects
                (function() {
                    const overlay = document.getElementById('pageTransitionOverlay');
                    const navLinks = document.querySelectorAll('.nav-link[href^="/"]');
                    
                    navLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            const href = this.getAttribute('href');
                            
                            // Skip anchor links and external links
                            if (!href || href.startsWith('#') || href.startsWith('http') || href.startsWith('mailto:')) {
                                return;
                            }
                            
                            // Don't prevent default for special links
                            if (this.hasAttribute('download') || this.hasAttribute('target')) {
                                return;
                            }
                            
                            e.preventDefault();
                            
                            // Add ripple effect
                            const ripple = document.createElement('span');
                            ripple.style.cssText = `
                                position: absolute;
                                border-radius: 50%;
                                background: rgba(251, 191, 36, 0.6);
                                width: 20px;
                                height: 20px;
                                margin-top: -10px;
                                margin-left: -10px;
                                pointer-events: none;
                                animation: ripple 0.6s ease-out;
                            `;
                            
                            const rect = this.getBoundingClientRect();
                            ripple.style.left = (rect.left + rect.width / 2) + 'px';
                            ripple.style.top = (rect.top + rect.height / 2) + 'px';
                            document.body.appendChild(ripple);
                            
                            // Show overlay with smooth fade
                            overlay.classList.add('active');
                            
                            // Navigate after short delay
                            setTimeout(() => {
                                window.location.href = href;
                            }, 300);
                            
                            // Remove ripple after animation
                            setTimeout(() => {
                                ripple.remove();
                            }, 600);
                        });
                    });
                    
                    // Add ripple animation
                    if (!document.getElementById('rippleStyle')) {
                        const style = document.createElement('style');
                        style.id = 'rippleStyle';
                        style.textContent = `
                            @keyframes ripple {
                                to {
                                    transform: scale(4);
                                    opacity: 0;
                                }
                            }
                        `;
                        document.head.appendChild(style);
                    }
                })();
            </script>

        @auth
            <a href="{{ route('dashboard') }}"
                class="user-profile-btn flex items-center gap-3 px-4 py-2 rounded-lg font-semibold shadow-sm">
                @if(auth()->user()->foto_profil)
                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" 
                         alt="{{ auth()->user()->nama }}" 
                         class="user-profile-img w-10 h-10 rounded-full object-cover border-2 border-gray-200 shadow-sm relative z-10 transition-all duration-300">
                @elseif(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" 
                         alt="{{ auth()->user()->nama }}" 
                         class="user-profile-img w-10 h-10 rounded-full object-cover border-2 border-gray-200 shadow-sm relative z-10 transition-all duration-300">
                @else
                    <div class="user-profile-img w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-700 border-2 border-gray-200 shadow-sm relative z-10 transition-all duration-300">
                        {{ strtoupper(substr(auth()->user()->nama ?? 'U', 0, 1)) }}
                    </div>
                @endif
                <div class="hidden md:flex flex-col items-start relative z-10">
                    <span class="text-sm font-bold leading-tight text-gray-900 transition-colors duration-300">{{ auth()->user()->nama ?? 'User' }}</span>
                    <span class="text-xs font-medium text-gray-600 leading-tight transition-colors duration-300">{{ auth()->user()->email }}</span>
                </div>
            </a>
        @else
            <a href="{{ route('login') }}"
                class="user-profile-btn px-4 py-2 rounded-lg font-semibold shadow-sm relative overflow-hidden">
                <span class="relative z-10 text-gray-900 transition-colors duration-300">Login / Daftar</span>
            </a>
        @endauth
    </div>
</nav>

