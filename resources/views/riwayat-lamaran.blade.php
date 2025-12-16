<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Lamaran - Magang Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    <style>
      * { 
        box-sizing: border-box; 
        margin: 0; 
        padding: 0; 
      }
      
      body { 
        margin: 0; 
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-size: 14px;
        line-height: 1.5;
        color: #1F2937;
      }
      
      .dashboard-body {
        background: #F9FAFB;
        min-height: 100vh;
      }
      
      .dashboard-wrap {
        display: flex;
        min-height: 100vh;
      }
      
      .main {
        flex: 1;
        padding: 0;
        background: transparent;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
      }
      
      .main-content {
        flex: 1;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        padding: 40px 48px 60px;
        overflow-y: auto;
      }
      
      .topbar {
        background: #FFFFFF;
        border-bottom: 1px solid #E5E7EB;
        padding: 16px 48px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        width: 100%;
        flex-shrink: 0;
      }
      
      .topbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      
      .user-greeting {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        background: #F9FAFB;
        border-radius: 10px;
        font-weight: 500;
        font-size: 14px;
        color: #374151;
      }
      
      .btn-logout {
        padding: 10px 18px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        color: #6B7280;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .btn-logout:hover {
        background: #F3F4F6;
        color: #1F2937;
        border-color: #D1D5DB;
      }

      .content {
        animation: fadeIn 0.4s ease-out;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
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
      
      @keyframes fadeInStep {
        from {
          opacity: 0;
          transform: translateY(15px) scale(0.95);
        }
        to {
          opacity: 1;
          transform: translateY(0) scale(1);
        }
      }
      
      @keyframes pulse-dot {
        0%, 100% {
          transform: scale(1);
          opacity: 1;
        }
        50% {
          transform: scale(1.3);
          opacity: 0.8;
        }
      }
      
      .pulse-dot {
        animation: pulse-dot 2s ease-in-out infinite;
      }
      
      @keyframes checkmarkDraw {
        0% {
          stroke-dasharray: 0 50;
          stroke-dashoffset: 0;
          opacity: 0;
        }
        50% {
          opacity: 1;
        }
        100% {
          stroke-dasharray: 50 0;
          stroke-dashoffset: 0;
          opacity: 1;
        }
      }
      
      @keyframes xMarkDraw {
        0% {
          stroke-dasharray: 0 30;
          stroke-dashoffset: 0;
          opacity: 0;
        }
        50% {
          opacity: 1;
        }
        100% {
          stroke-dasharray: 30 0;
          stroke-dashoffset: 0;
          opacity: 1;
        }
      }
      
      @keyframes cardGlow {
        0%, 100% {
          box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        50% {
          box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2), 0 8px 24px rgba(59, 130, 246, 0.3);
        }
      }
      
      .progress-step {
        animation: fadeInStep 0.6s ease-out both;
      }
      
      .progress-line-fill {
        position: relative;
        overflow: hidden;
      }
      
      .progress-line-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: shimmerProgress 2s infinite;
      }
      
      @keyframes shimmerProgress {
        0% {
          left: -100%;
        }
        100% {
          left: 100%;
        }
      }

      .page-title {
        font-size: 40px;
        font-weight: 800;
        color: #0C3A6B;
        margin: 0 0 12px;
        line-height: 1.2;
        letter-spacing: -0.5px;
      }

      .page-subtitle {
        font-size: 16px;
        color: #6B7280;
        margin: 0 0 32px;
      }

      /* Stats Grid untuk Alur Proses */
      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      
      @media (min-width: 1200px) {
        .stats-grid {
          grid-template-columns: repeat(4, 1fr);
        }
      }
      
      @media (max-width: 1024px) {
        .stats-grid {
          grid-template-columns: repeat(2, 1fr);
        }
      }
      
      @media (max-width: 640px) {
        .stats-grid {
          grid-template-columns: 1fr;
        }
        
        .progress-tracker {
          padding: 24px 16px !important;
        }
        
        .progress-steps {
          flex-direction: column !important;
          align-items: flex-start !important;
          gap: 32px !important;
        }
        
        .progress-line {
          display: none !important;
        }
        
        .progress-step {
          width: 100% !important;
          flex-direction: row !important;
          align-items: center !important;
          gap: 16px !important;
        }
        
        .step-content {
          text-align: left !important;
          max-width: none !important;
        }
        
        .step-circle {
          flex-shrink: 0 !important;
        }
      }
      
      @media (max-width: 1024px) and (min-width: 641px) {
        .progress-tracker {
          padding: 28px 20px !important;
        }
        
        .progress-steps {
          gap: 8px !important;
        }
        
        .step-content {
          max-width: 120px !important;
        }
      }
      
      .stat-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #F3F4F6;
        position: relative;
        overflow: hidden;
      }
      
      .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #3B82F6;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
      }
      
      .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #E5E7EB;
      }
      
      .stat-card:hover::before {
        transform: scaleX(1);
      }
      
      .stat-card.orange::before { background: #F59E0B; }
      .stat-card.green::before { background: #10B981; }
      .stat-card.red::before { background: #EF4444; }
      
      /* Animasi untuk icon yang aktif */
      .stat-card.active .stat-icon svg {
        filter: drop-shadow(0 0 8px currentColor);
      }
      
      .stat-card.active .stat-icon {
        position: relative;
      }
      
      .stat-card.active .stat-icon::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border-radius: 12px;
        background: currentColor;
        opacity: 0.1;
        animation: iconGlow 2s ease-in-out infinite;
      }
      
      @keyframes iconGlow {
        0%, 100% {
          transform: translate(-50%, -50%) scale(1);
          opacity: 0.1;
        }
        50% {
          transform: translate(-50%, -50%) scale(1.3);
          opacity: 0.2;
        }
      }
      
      .stat-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
      }
      
      .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #EFF6FF;
        flex-shrink: 0;
      }
      
      .stat-card.orange .stat-icon { 
        background: #FEF3C7; 
      }
      
      .stat-card.green .stat-icon { 
        background: #D1FAE5; 
      }
      
      .stat-card.red .stat-icon { 
        background: #FEE2E2; 
      }
      
      .stat-info {
        flex: 1;
        min-width: 0;
      }
      
      .stat-label {
        font-size: 12px;
        color: #6B7280;
        font-weight: 600;
        margin: 0 0 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      
      .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1F2937;
        margin: 0;
        line-height: 1;
        letter-spacing: -1px;
      }
      
      .stat-card.orange .stat-value { color: #F59E0B; }
      .stat-card.green .stat-value { color: #10B981; }
      .stat-card.red .stat-value { color: #EF4444; }

      .stat-card.active {
        border: 2px solid currentColor;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transform: translateY(-4px);
        animation: cardPulse 2s ease-in-out infinite;
      }

      .stat-card.active::before {
        transform: scaleX(1);
        animation: topBarGlow 2s ease-in-out infinite;
      }

      .stat-card.active .stat-icon {
        animation: iconBounce 2s ease-in-out infinite;
      }

      .stat-card.active .stat-value {
        animation: numberPulse 2s ease-in-out infinite;
      }

      @keyframes cardPulse {
        0%, 100% {
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        50% {
          box-shadow: 0 12px 30px rgba(59, 130, 246, 0.3);
        }
      }

      .stat-card.active.orange {
        animation: cardPulseOrange 2s ease-in-out infinite;
      }

      @keyframes cardPulseOrange {
        0%, 100% {
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        50% {
          box-shadow: 0 12px 30px rgba(245, 158, 11, 0.3);
        }
      }

      .stat-card.active.green {
        animation: cardPulseGreen 2s ease-in-out infinite;
      }

      @keyframes cardPulseGreen {
        0%, 100% {
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        50% {
          box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
        }
      }

      .stat-card.active.red {
        animation: cardPulseRed 2s ease-in-out infinite;
      }

      @keyframes cardPulseRed {
        0%, 100% {
          box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        50% {
          box-shadow: 0 12px 30px rgba(239, 68, 68, 0.3);
        }
      }

      @keyframes topBarGlow {
        0%, 100% {
          opacity: 1;
        }
        50% {
          opacity: 0.8;
          box-shadow: 0 0 10px currentColor;
        }
      }

      @keyframes iconBounce {
        0%, 100% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.1);
        }
      }

      @keyframes numberPulse {
        0%, 100% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.05);
        }
      }

      .alur-proses-section {
        margin-bottom: 40px;
      }

      .alur-proses-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .status-badge-realtime {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 8px;
      }

      .status-badge-realtime.success {
        background: #D1FAE5;
        color: #065F46;
      }

      .status-badge-realtime.warning {
        background: #FEF3C7;
        color: #92400E;
      }

      .status-badge-realtime.danger {
        background: #FEE2E2;
        color: #991B1B;
      }

      .status-badge-realtime.info {
        background: #DBEAFE;
        color: #1E40AF;
      }

      .status-badge-realtime.processing {
        background: #FEF3C7;
        color: #92400E;
        animation: pulse 2s infinite;
      }

      @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
      }

      .status-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
      }

      .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
      }

      .status-dot.success { background: #10B981; }
      .status-dot.warning { background: #F59E0B; }
      .status-dot.danger { background: #EF4444; }
      .status-dot.info { background: #3B82F6; }
      .status-dot.processing {
        background: #F59E0B;
        animation: pulse-dot 1.5s infinite;
      }

      @keyframes pulse-dot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
      }

      .riwayat-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #F3F4F6;
        margin-bottom: 24px;
        transition: all 0.3s ease;
      }

      .riwayat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #E5E7EB;
      }

      .riwayat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid #F3F4F6;
      }

      .riwayat-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
      }

      .status-badge.diajukan {
        background: #EFF6FF;
        color: #2563EB;
      }

      .status-badge.diverifikasi {
        background: #FEF3C7;
        color: #F59E0B;
      }

      .status-badge.diterima {
        background: #D1FAE5;
        color: #10B981;
      }

      .status-badge.ditolak {
        background: #FEE2E2;
        color: #EF4444;
      }

      .riwayat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
      }

      .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
      }

      .info-label {
        font-size: 13px;
        color: #6B7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
      }

      .info-value {
        font-size: 16px;
        color: #1F2937;
        font-weight: 600;
      }

      .dokumen-info {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 16px;
        margin-top: 20px;
      }

      .dokumen-info-title {
        font-size: 15px;
        font-weight: 600;
        color: #1F2937;
        margin: 0 0 12px;
      }

      .dokumen-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      .dokumen-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: #FFFFFF;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
      }

      .dokumen-item.has-file {
        border-color: #10B981;
        background: #F0FDF4;
      }

      .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #F3F4F6;
      }

      .empty-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 28px;
        opacity: 0.25;
        color: #9CA3AF;
      }

      .empty-title {
        font-size: 22px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 12px;
      }

      .empty-desc {
        font-size: 15px;
        color: #6B7280;
        margin: 0 0 32px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
      }

      .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: #3B82F6;
        color: #FFFFFF;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        border: none;
        cursor: pointer;
      }
      
      .btn-primary:hover {
        background: #2563EB;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
      }

      @media (max-width: 1024px) {
        .main-content {
          padding: 32px 32px 48px;
        }
        
        .topbar {
          padding: 16px 32px;
        }
      }
      
      @media (max-width: 768px) {
        .main-content {
          padding: 24px 20px 40px;
        }
        
        .topbar {
          padding: 12px 20px;
        }

        .riwayat-grid {
          grid-template-columns: 1fr;
        }

        .riwayat-header {
          flex-direction: column;
          gap: 16px;
        }
      }
    </style>
  </head>
  <body class="dashboard-body">
    <div class="dashboard-wrap">
      @include('partials.sidebar')
      <main class="main">
        <div class="topbar">
          <div class="topbar-right">
            <div class="user-greeting">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Halo, {{ auth()->user()->nama ?? 'Pengguna' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block">
              @csrf
              <button type="submit" class="btn-logout">Keluar</button>
            </form>
          </div>
        </div>
        
        <div class="main-content">
          <section class="content">
            <h1 class="page-title">Status Lamaran</h1>
            <p class="page-subtitle">Pantau status dan informasi terkini mengenai lamaran Anda. Lihat detail permohonan magang yang telah Anda ajukan.</p>

            @if(session('success'))
              <div style="padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px; background: #D1FAE5; border-left: 4px solid #10B981; color: #065F46;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span style="font-weight: 500;">{{ session('success') }}</span>
              </div>
            @endif

            <!-- Status Lamaran -->
            <div class="alur-proses-section">
              <!-- Status Lamaran - Progress Tracker -->
              <h2 class="alur-proses-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Status Lamaran
              </h2>
              
              @php
                // Tentukan tahap aktif berdasarkan status
                $currentStatus = isset($statusLamaran) && isset($statusLamaran['status']) ? $statusLamaran['status'] : null;
                $step1Status = 'pending'; // Berkas Diajukan
                $step2Status = 'pending'; // Dokumen Diverifikasi
                $step3Status = 'pending'; // Keputusan Ditetapkan
                
                if ($currentStatus === 'Diajukan') {
                  $step1Status = 'active';
                } elseif ($currentStatus === 'Diverifikasi') {
                  $step1Status = 'completed';
                  $step2Status = 'active';
                } elseif (in_array($currentStatus, ['Diterima', 'Ditolak'])) {
                  $step1Status = 'completed';
                  $step2Status = 'completed';
                  $step3Status = 'completed';
                }
              @endphp
              
              <!-- Progress Tracker -->
              <div class="progress-tracker" style="background: #FFFFFF; border-radius: 16px; padding: 32px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="progress-steps" style="display: flex; align-items: flex-start; justify-content: space-between; position: relative; margin-bottom: 40px;">
                  <!-- Progress Line -->
                  @php
                    $progressWidth = 0;
                    if ($step1Status === 'completed') {
                      $progressWidth = 33;
                      if ($step2Status === 'completed') {
                        $progressWidth = 66;
                        if ($step3Status === 'completed') {
                          $progressWidth = 100;
                        }
                      }
                    }
                    
                    $progressColor1 = $step1Status === 'completed' ? '#10B981' : ($step1Status === 'active' ? '#F59E0B' : '#E5E7EB');
                    $progressColor2 = $step2Status === 'completed' ? '#10B981' : ($step2Status === 'active' ? '#F59E0B' : ($step1Status === 'completed' ? '#10B981' : '#E5E7EB'));
                    $progressColor3 = $step3Status === 'completed' ? (($currentStatus && $currentStatus === 'Diterima') ? '#10B981' : '#EF4444') : '#E5E7EB';
                    
                    // Step 1 colors and styles
                    $step1Bg = $step1Status === 'completed' ? '#10B981' : ($step1Status === 'active' ? '#F59E0B' : '#E5E7EB');
                    $step1BoxShadow = $step1Status === 'active' ? '0 0 0 8px rgba(245, 158, 11, 0.15), 0 4px 12px rgba(245, 158, 11, 0.3)' : '0 2px 4px rgba(0,0,0,0.1)';
                    $step1Color = $step1Status === 'active' ? '#F59E0B' : ($step1Status === 'completed' ? '#10B981' : '#9CA3AF');
                    
                    // Step 2 colors and styles
                    $step2Bg = $step2Status === 'completed' ? '#10B981' : ($step2Status === 'active' ? '#F59E0B' : '#E5E7EB');
                    $step2BoxShadow = $step2Status === 'active' ? '0 0 0 8px rgba(245, 158, 11, 0.15), 0 4px 12px rgba(245, 158, 11, 0.3)' : '0 2px 4px rgba(0,0,0,0.1)';
                    $step2Color = $step2Status === 'active' ? '#F59E0B' : ($step2Status === 'completed' ? '#10B981' : '#9CA3AF');
                    
                    // Step 3 colors and styles
                    $step3Bg = $step3Status === 'completed' ? (($currentStatus && $currentStatus === 'Diterima') ? '#10B981' : '#EF4444') : '#E5E7EB';
                    $step3BoxShadow = $step3Status === 'active' ? '0 0 0 8px rgba(245, 158, 11, 0.15), 0 4px 12px rgba(245, 158, 11, 0.3)' : ($step3Status === 'completed' && $currentStatus && $currentStatus === 'Ditolak' ? '0 0 0 8px rgba(239, 68, 68, 0.15), 0 4px 12px rgba(239, 68, 68, 0.3)' : '0 2px 4px rgba(0,0,0,0.1)');
                    $step3Color = $step3Status === 'completed' ? (($currentStatus && $currentStatus === 'Diterima') ? '#10B981' : '#EF4444') : '#9CA3AF';
                    
                    // Status border color
                    $statusBorderColor = $step1Status === 'active' ? '#3B82F6' : ($step2Status === 'active' ? '#F59E0B' : (($currentStatus && $currentStatus === 'Diterima') ? '#10B981' : '#EF4444'));
                    
                    // Build style strings to avoid linter errors
                    $progressLineFillStyle = "height: 100%; background: linear-gradient(90deg, {$progressColor1} 0%, {$progressColor2} 50%, {$progressColor3} 100%); transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1), background 0.5s ease; border-radius: 2px; width: {$progressWidth}%; position: relative;";
                    $step1CircleStyle = "width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; background: {$step1Bg}; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: {$step1BoxShadow};";
                    $step2CircleStyle = "width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; background: {$step2Bg}; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: {$step2BoxShadow};";
                    $step3CircleStyle = "width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; background: {$step3Bg}; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: {$step3BoxShadow};";
                    $step1ColorStyle = "margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: {$step1Color}; transition: color 0.3s ease;";
                    $step2ColorStyle = "margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: {$step2Color}; transition: color 0.3s ease;";
                    $step3ColorStyle = "margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: {$step3Color}; transition: color 0.3s ease;";
                    $statusBorderStyle = "padding: 16px; background: #F9FAFB; border-radius: 12px; border-left: 4px solid " . (isset($statusBorderColor) ? $statusBorderColor : '#E5E7EB') . ";";
                  @endphp
                  <div class="progress-line" style="position: absolute; top: 20px; left: 40px; right: 40px; height: 4px; background: #E5E7EB; z-index: 0; border-radius: 2px; overflow: hidden;">
                    <div class="progress-line-fill" style="<?php echo $progressLineFillStyle; ?>"></div>
                  </div>
                  
                  <!-- Step 1: Berkas Diajukan -->
                  <div class="progress-step step-fade-in" style="flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; animation: fadeInStep 0.6s ease-out;">
                    <div class="step-circle" style="<?php echo $step1CircleStyle; ?>">
                      @if($step1Status === 'completed')
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: checkmarkDraw 0.5s ease-out;">
                          <path d="M20 6L9 17l-5-5" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      @elseif($step1Status === 'active')
                        <div class="pulse-dot" style="width: 12px; height: 12px; border-radius: 50%; background: #FFFFFF;"></div>
                      @else
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #9CA3AF;"></div>
                      @endif
                    </div>
                    <div class="step-content" style="text-align: center; max-width: 150px;">
                      <h4 style="<?php echo $step1ColorStyle; ?>">1. Berkas Diajukan</h4>
                      <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.5;">
                        @if($step1Status === 'completed')
                          Selesai
                        @elseif($step1Status === 'active')
                          {{ $currentStatus === 'Diajukan' ? 'Sedang Diproses' : 'Menunggu' }}
                        @else
                          Menunggu
                        @endif
                      </p>
                    </div>
                  </div>
                  
                  <!-- Step 2: Dokumen Diverifikasi -->
                  <div class="progress-step step-fade-in" style="flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; animation: fadeInStep 0.6s ease-out 0.2s both;">
                    <div class="step-circle" style="<?php echo $step2CircleStyle; ?>">
                      @if($step2Status === 'completed')
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: checkmarkDraw 0.5s ease-out;">
                          <path d="M20 6L9 17l-5-5" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      @elseif($step2Status === 'active')
                        <div class="pulse-dot" style="width: 12px; height: 12px; border-radius: 50%; background: #FFFFFF;"></div>
                      @else
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #9CA3AF;"></div>
                      @endif
                    </div>
                    <div class="step-content" style="text-align: center; max-width: 150px;">
                      <h4 style="<?php echo $step2ColorStyle; ?>">2. Dokumen Diverifikasi</h4>
                      <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.5;">
                        @if($step2Status === 'completed')
                          Selesai
                        @elseif($step2Status === 'active')
                          {{ $currentStatus === 'Diverifikasi' ? 'Sedang Diproses' : 'Menunggu' }}
                        @else
                          Menunggu
                        @endif
                      </p>
                    </div>
                  </div>
                  
                  <!-- Step 3: Keputusan Ditetapkan -->
                  <div class="progress-step step-fade-in" style="flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; z-index: 1; animation: fadeInStep 0.6s ease-out 0.4s both;">
                    <div class="step-circle" style="<?php echo $step3CircleStyle; ?>">
                      @if($step3Status === 'completed')
                        @if($currentStatus === 'Diterima')
                          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: checkmarkDraw 0.5s ease-out;">
                            <path d="M20 6L9 17l-5-5" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        @else
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: xMarkDraw 0.5s ease-out;">
                            <line x1="18" y1="6" x2="6" y2="18" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                            <line x1="6" y1="6" x2="18" y2="18" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round"/>
                          </svg>
                        @endif
                      @elseif($step3Status === 'active')
                        <div class="pulse-dot" style="width: 12px; height: 12px; border-radius: 50%; background: #FFFFFF;"></div>
                      @else
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #9CA3AF;"></div>
                      @endif
                    </div>
                    <div class="step-content" style="text-align: center; max-width: 150px;">
                      <h4 style="<?php echo $step3ColorStyle; ?>">3. Keputusan Ditetapkan</h4>
                      <p style="margin: 0; font-size: 12px; color: #6B7280; line-height: 1.5;">
                        @if($step3Status === 'completed')
                          {{ $currentStatus === 'Diterima' ? 'DITERIMA' : 'DITOLAK' }}
                        @else
                          Menunggu
                        @endif
                      </p>
                    </div>
                  </div>
                </div>
                
                @if(isset($currentStatus) && $currentStatus)
                  <div style="<?php echo $statusBorderStyle; ?>">
                    <p style="margin: 0; font-size: 13px; color: #374151; line-height: 1.6; font-weight: 500;">
                      @if($currentStatus === 'Diajukan')
                        Status saat ini: <strong style="color: #3B82F6;">Berkas Diajukan</strong> — Permohonan Anda sedang menunggu verifikasi dokumen oleh Admin.
                      @elseif($currentStatus === 'Diverifikasi')
                        Status saat ini: <strong style="color: #F59E0B;">Dokumen Diverifikasi</strong> — Dokumen Anda sudah valid, menunggu penetapan keputusan final.
                      @elseif($currentStatus === 'Diterima')
                        Status saat ini: <strong style="color: #10B981;">DITERIMA</strong> — Permohonan Anda telah disetujui.
                      @elseif($currentStatus === 'Ditolak')
                        Status saat ini: <strong style="color: #EF4444;">DITOLAK</strong> — Permohonan Anda tidak disetujui.
                      @endif
                    </p>
                  </div>
                @endif
              </div>

              <!-- Dynamic Feedback Banner -->
              @if(isset($statusLamaran) && isset($statusLamaran['status']) && $statusLamaran['status'])
                <div class="feedback-banner" style="margin-top: 24px; animation: slideUp 0.4s ease-out;">
                  @if(isset($statusLamaran['status']) && in_array($statusLamaran['status'], ['Diajukan', 'Diverifikasi']))
                    <!-- Banner untuk Status: Menunggu → Diverifikasi -->
                    <div style="background: linear-gradient(135deg, #DBEAFE 0%, #EFF6FF 100%); border-left: 4px solid #3B82F6; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);">
                      <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="flex-shrink: 0;">
                          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3B82F6;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </div>
                        <div style="flex: 1;">
                          <h3 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 700; color: #1E40AF;">
                            @if($statusLamaran['status'] === 'Diajukan')
                              Berkas Anda Sedang Diperiksa
                            @else
                              Dokumen Anda Sudah Diverifikasi
                            @endif
                          </h3>
                          <p style="margin: 0 0 8px 0; font-size: 15px; color: #1E40AF; line-height: 1.7;">
                            @if($statusLamaran['status'] === 'Diajukan')
                              Berkas Anda sedang diperiksa oleh Administrator BBSPJKB. Mohon tunggu 1-2 hari kerja untuk pembaruan status.
                            @else
                              Dokumen Anda sudah diverifikasi dan valid. Keputusan Diterima/Ditolak akan segera ditetapkan oleh Admin. Mohon tunggu 1-2 hari kerja untuk pembaruan status.
                            @endif
                          </p>
                          <div style="margin-top: 12px; padding: 12px; background: #FFFFFF; border-radius: 8px; border: 1px solid #BFDBFE;">
                            <p style="margin: 0; font-size: 13px; color: #1E40AF; line-height: 1.6;">
                              <strong>💡 Informasi:</strong> Sistem ini transparan dan efisien. Anda akan mendapatkan notifikasi segera setelah ada pembaruan status permohonan Anda.
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  @elseif($statusLamaran['status'] === 'Diterima')
                    <!-- Banner untuk Status: DITERIMA -->
                    <div style="background: linear-gradient(135deg, #D1FAE5 0%, #ECFDF5 100%); border-left: 4px solid #10B981; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);">
                      <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="flex-shrink: 0;">
                          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #10B981;">
                            <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </div>
                        <div style="flex: 1;">
                          <h3 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 800; color: #065F46;">
                            🎉 SELAMAT! Anda DITERIMA
                          </h3>
                          <p style="margin: 0 0 16px 0; font-size: 15px; color: #065F46; line-height: 1.7;">
                            Permohonan magang Anda telah disetujui. Silakan cek email Anda untuk surat konfirmasi.
                          </p>
                          @if(isset($statusLamaran['jadwal']) && $statusLamaran['jadwal'])
                            <div style="margin: 16px 0; padding: 16px; background: #FFFFFF; border-radius: 10px; border: 2px solid #10B981;">
                              <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 700; color: #065F46; text-transform: uppercase; letter-spacing: 0.5px;">
                                📅 Tanggal Mulai Magang:
                              </p>
                              <p style="margin: 0; font-size: 16px; color: #065F46; font-weight: 600;">
                                {{ $statusLamaran['jadwal']->tgl_mulai->format('d F Y') }}
                              </p>
                              <p style="margin: 8px 0 0 0; font-size: 13px; color: #059669;">
                                Periode: {{ $statusLamaran['jadwal']->tgl_mulai->format('d F Y') }} - {{ $statusLamaran['jadwal']->tgl_selesai->format('d F Y') }}
                              </p>
                            </div>
                          @endif
                          <a href="{{ route('panduan.onboarding') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #10B981; color: #FFFFFF; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);" onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.background='#10B981'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Lihat Panduan Onboarding
                          </a>
                        </div>
                      </div>
                    </div>
                  @elseif($statusLamaran['status'] === 'Ditolak')
                    <!-- Banner untuk Status: DITOLAK -->
                    <div style="background: linear-gradient(135deg, #FEE2E2 0%, #FEF2F2 100%); border-left: 4px solid #EF4444; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);">
                      <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="flex-shrink: 0;">
                          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #EF4444;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                            <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                            <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                          </svg>
                        </div>
                        <div style="flex: 1;">
                          <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #991B1B;">
                            ⚠️ MOHON MAAF, Permohonan DITOLAK
                          </h3>
                          @if(isset($statusLamaran['alasan_penolakan']) && !empty($statusLamaran['alasan_penolakan']))
                            <div style="margin: 16px 0; padding: 16px; background: #FFFFFF; border-radius: 10px; border: 2px solid #FCA5A5;">
                              <p style="margin: 0 0 10px 0; font-size: 13px; font-weight: 700; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px;">
                                📋 Alasan Penolakan:
                              </p>
                              <p style="margin: 0; font-size: 15px; color: #991B1B; line-height: 1.7; white-space: pre-wrap; font-weight: 500;">
                                {{ $statusLamaran['alasan_penolakan'] }}
                              </p>
                            </div>
                          @endif
                          <div style="margin-top: 16px; padding: 12px; background: #FFFFFF; border-radius: 8px; border: 1px solid #FCA5A5;">
                            <p style="margin: 0; font-size: 13px; color: #991B1B; line-height: 1.6;">
                              <strong>💡 Informasi:</strong> Sistem ini transparan dan efisien. Alasan penolakan telah dijelaskan di atas untuk membantu Anda memperbaiki permohonan berikutnya.
                            </p>
                          </div>
                          @php
                            // Cek apakah masih ada lowongan tersedia
                            $today = now()->toDateString();
                            $lowonganTersedia = \App\Models\KuotaMagang::with('jadwalMagang')
                              ->whereColumn('kuota_terpakai', '<', 'kuota_max')
                              ->get()
                              ->filter(function ($kuota) use ($today) {
                                $jadwal = $kuota->jadwalMagang;
                                return $jadwal && $jadwal->tgl_mulai <= $today && $jadwal->tgl_selesai >= $today;
                              })
                              ->count() > 0;
                          @endphp
                          @if($lowonganTersedia)
                            <a href="{{ route('lowongan') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #EF4444; color: #FFFFFF; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; margin-top: 16px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);" onmouseover="this.style.background='#DC2626'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(239, 68, 68, 0.4)'" onmouseout="this.style.background='#EF4444'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'">
                              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                              Ajukan Ulang
                            </a>
                          @else
                            <p style="margin: 16px 0 0 0; padding: 12px; background: #FEF2F2; border-radius: 8px; font-size: 14px; color: #991B1B; font-weight: 500;">
                              Saat ini tidak ada lowongan yang tersedia untuk pengajuan ulang. Silakan cek kembali di kemudian hari.
                            </p>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
              @endif
            </div>

            @if($riwayatPermohonan && $riwayatPermohonan->count() > 0)
              @foreach($riwayatPermohonan as $permohonan)
                <div class="riwayat-card">
                  <div class="riwayat-header">
                    <div>
                      <h2 class="riwayat-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        @if($permohonan->kuotaMagang->count() > 0)
                          Permohonan Magang Periode {{ $permohonan->kuotaMagang->first()->periode }}
                        @else
                          Permohonan Magang
                        @endif
                      </h2>
                      <p style="margin: 8px 0 0 0; color: #6B7280; font-size: 14px;">
                        Diajukan pada {{ $permohonan->tanggal_pengajuan ? $permohonan->tanggal_pengajuan->format('d F Y') : $permohonan->created_at->format('d F Y') }}
                      </p>
                    </div>
                    <span class="status-badge {{ strtolower($permohonan->status) }}">
                      {{ $permohonan->status }}
                    </span>
                  </div>

                  <div class="riwayat-grid">
                    <div class="info-item">
                      <span class="info-label">Tanggal Pengajuan</span>
                      <span class="info-value">
                        {{ $permohonan->tanggal_pengajuan ? $permohonan->tanggal_pengajuan->format('d F Y') : $permohonan->created_at->format('d F Y') }}
                      </span>
                    </div>
                    
                    @if($permohonan->kuotaMagang->count() > 0)
                      @php
                        $kuota = $permohonan->kuotaMagang->first();
                        $jadwal = $kuota->jadwalMagang;
                      @endphp
                      <div class="info-item">
                        <span class="info-label">Periode Magang</span>
                        <span class="info-value">{{ $kuota->periode }}</span>
                      </div>
                      
                      @if($jadwal)
                        <div class="info-item">
                          <span class="info-label">Tanggal Mulai</span>
                          <span class="info-value">{{ $jadwal->tgl_mulai->format('d F Y') }}</span>
                        </div>
                        
                        <div class="info-item">
                          <span class="info-label">Tanggal Selesai</span>
                          <span class="info-value">{{ $jadwal->tgl_selesai->format('d F Y') }}</span>
                        </div>
                      @endif
                    @endif
                    
                    <div class="info-item">
                      <span class="info-label">Instansi</span>
                      <span class="info-value">
                        {{ auth()->user()->instansi ?? 'Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta' }}
                      </span>
                    </div>
                  </div>

                  @if($permohonan->dokumen)
                    @php
                      // Hanya ambil dokumen yang sudah terunggah
                      $dokumenTerunggah = [];
                      if (!empty($permohonan->dokumen->cv)) {
                        $dokumenTerunggah[] = ['nama' => 'CV (Curriculum Vitae)', 'field' => 'cv', 'path' => $permohonan->dokumen->cv];
                      }
                      if (!empty($permohonan->dokumen->surat_pengantar)) {
                        $dokumenTerunggah[] = ['nama' => 'Surat Pengantar', 'field' => 'surat_pengantar', 'path' => $permohonan->dokumen->surat_pengantar];
                      }
                      if (!empty($permohonan->dokumen->proposal)) {
                        $dokumenTerunggah[] = ['nama' => 'Proposal', 'field' => 'proposal', 'path' => $permohonan->dokumen->proposal];
                      }
                    @endphp
                    
                    @if(count($dokumenTerunggah) > 0)
                      <div class="dokumen-info">
                        <h3 class="dokumen-info-title">Dokumen yang Terunggah</h3>
                        <div class="dokumen-list">
                          @foreach($dokumenTerunggah as $doc)
                            <div class="dokumen-item has-file">
                              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 6L9 17l-5-5" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                              <span style="flex: 1; font-size: 14px; font-weight: 500; color: #1F2937;">
                                {{ $doc['nama'] }}
                              </span>
                              <span style="font-size: 12px; color: #10B981; font-weight: 600;">Terunggah</span>
                              @php
                                $fileExists = \Storage::disk('public')->exists($doc['path']);
                              @endphp
                              @if($fileExists)
                                <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" style="margin-left: 8px; padding: 4px 12px; background: #3B82F6; color: #FFFFFF; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
                                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="15 3 21 3 21 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <line x1="10" y1="14" x2="21" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  </svg>
                                  Lihat
                                </a>
                              @endif
                            </div>
                          @endforeach
                        </div>
                        
                        @if($permohonan->dokumen->tanggal_upload)
                          <p style="margin: 16px 0 0 0; font-size: 13px; color: #6B7280; text-align: right;">
                            Diunggah pada {{ $permohonan->dokumen->tanggal_upload->format('d F Y, H:i') }}
                          </p>
                        @endif
                      </div>
                    @endif
                  @endif

                  @if($permohonan->status === 'Diterima')
                    <div style="margin-top: 20px; padding: 16px; background: #D1FAE5; border-radius: 8px; border-left: 4px solid #10B981;">
                      <p style="margin: 0; color: #065F46; font-weight: 500;">
                        🎉 Selamat! Permohonan Anda diterima. Informasi lebih lanjut akan disampaikan melalui email atau menu Laporan Mingguan.
                      </p>
                    </div>
                  @elseif($permohonan->status === 'Diverifikasi')
                    <div style="margin-top: 20px; padding: 16px; background: #FEF3C7; border-radius: 8px; border-left: 4px solid #F59E0B;">
                      <p style="margin: 0; color: #92400E; font-weight: 500;">
                        Permohonan Anda sedang dalam proses verifikasi. Mohon tunggu informasi selanjutnya.
                      </p>
                    </div>
                  @else
                    <div style="margin-top: 20px; padding: 16px; background: #EFF6FF; border-radius: 8px; border-left: 4px solid #2563EB;">
                      <p style="margin: 0; color: #1E40AF; font-weight: 500;">
                        Permohonan Anda telah diajukan dan sedang menunggu verifikasi dari admin.
                      </p>
                    </div>
                  @endif
                </div>
              @endforeach
            @else
              <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="12" cy="13" r="2" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <h3 class="empty-title">Belum Ada Status Lamaran</h3>
                <p class="empty-desc">
                  Anda belum pernah mengajukan permohonan magang. Mulai dengan melengkapi dan memeriksa dokumen di menu Lamaran Saya, kemudian ajukan permohonan di menu Lowongan.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                  <a href="{{ route('lamaran') }}" class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Lengkapi & Periksa Dokumen
                  </a>
                  <a href="{{ route('lowongan') }}" class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Lihat Lowongan
                  </a>
                </div>
              </div>
            @endif
          </section>
        </div>
      </main>
    </div>
  </body>
</html>

