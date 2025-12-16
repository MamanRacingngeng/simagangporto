<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin - SIMAGANG')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            margin: 0;
            font-family: 'Inter', 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #F9FAFB;
            color: #0f172a;
            overflow-x: hidden;
        }

        /* Admin Layout with Sidebar */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #FFFFFF 0%, #F9FAFB 100%);
            border-right: 1px solid #E5E7EB;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 2px 0 16px rgba(0, 0, 0, 0.04);
        }

        .admin-sidebar .brand {
            padding: 28px 24px;
            border-bottom: 1px solid #F3F4F6;
        }

        .admin-sidebar .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #0C3A6B;
            line-height: 1.3;
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }

        .admin-sidebar .brand-subtitle {
            font-size: 12px;
            color: #6B7280;
            font-weight: 500;
        }

        .admin-sidebar .nav {
            flex: 1;
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .admin-sidebar .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .admin-sidebar .nav-item:hover {
            background: #F3F4F6;
            transform: translateX(3px);
        }

        .admin-sidebar .nav-item.active {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
        }

        .admin-sidebar .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-sidebar .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #F3F4F6;
        }

        .admin-sidebar .logout-btn {
            width: 100%;
            padding: 10px 16px;
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
        }

        .admin-sidebar .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
        }

        /* Main Content Area */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            padding: 32px 40px;
            background: #F9FAFB;
        }

        /* Cards */
        .admin-card {
            background: #FFFFFF;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), 0 20px 60px rgba(15, 23, 42, 0.05);
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        .slide-up {
            animation: slideUp 0.5s ease-out;
        }

        /* Status Colors */
        .status-blue { color: #06B6D4; }
        .status-orange { color: #F59E0B; }
        .status-green { color: #10B981; }
        .status-red { color: #EF4444; }

        .bg-status-blue { background: #ECFEFF; }
        .bg-status-orange { background: #FFFBEB; }
        .bg-status-green { background: #ECFDF5; }
        .bg-status-red { background: #FEF2F2; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="brand">
                <div class="brand-title">SIMAGANG</div>
                <div class="brand-subtitle">Panel Admin</div>
            </div>
            
            <nav class="nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 11.5L12 4l9 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 21V12h14v9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.data_pendaftar') }}" class="nav-item {{ request()->routeIs('admin.data_pendaftar') || request()->routeIs('admin.detail_pendaftar') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Data Pendaftar</span>
                </a>
                <a href="{{ route('admin.atur_kuota_magang') }}" class="nav-item {{ request()->routeIs('admin.atur_kuota_magang') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M16 7V6a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Kuota</span>
                </a>
                <a href="{{ route('admin.atur_jadwal_magang') }}" class="nav-item {{ request()->routeIs('admin.atur_jadwal_magang') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M3 10h18M8 2v4m8-4v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Jadwal</span>
                </a>
                <a href="{{ route('admin.pengawasan_sumber_daya') }}" class="nav-item {{ request()->routeIs('admin.pengawasan_sumber_daya') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Pengawasan Sumber Daya</span>
                </a>
                <a href="{{ route('admin.kelola_galeri') }}" class="nav-item {{ request()->routeIs('admin.kelola_galeri') || request()->routeIs('admin.store_galeri') || request()->routeIs('admin.update_galeri') || request()->routeIs('admin.delete_galeri') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="7" height="7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="14" y="3" width="7" height="7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="3" y="14" width="7" height="7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="14" y="14" width="7" height="7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Galeri Magang</span>
                </a>
                <a href="{{ route('admin.notifikasi_kekurangan_syarat') }}" class="nav-item {{ request()->routeIs('admin.notifikasi_kekurangan_syarat') || request()->routeIs('admin.kirim_notifikasi') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Notifikasi</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
