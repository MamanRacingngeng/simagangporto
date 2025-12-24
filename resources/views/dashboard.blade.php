<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Magang Digital</title>
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
        background: linear-gradient(135deg, #F9FAFB 0%, #FFFFFF 50%, #F0F9FF 100%);
        min-height: 100vh;
        position: relative;
      }
      
      .dashboard-body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
          radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
          radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
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
        position: relative;
        z-index: 1;
      }
      
      .main-content {
        flex: 1;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        padding: 40px 48px 60px;
        overflow-y: auto;
      }
      
      /* Topbar */
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        -webkit-tap-highlight-color: transparent;
      }
      
      .user-greeting::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
        pointer-events: none;
      }
      
      .user-greeting:hover {
        background: #F3F4F6;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      }
      
      .user-greeting:hover::before {
        width: 200px;
        height: 200px;
      }
      
      .user-greeting:active {
        transform: translateY(0) scale(0.98);
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        -webkit-tap-highlight-color: transparent;
      }
      
      .btn-logout::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
        pointer-events: none;
      }
      
      .btn-logout:hover {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FCA5A5;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
      }
      
      .btn-logout:hover::after {
        width: 200px;
        height: 200px;
      }
      
      .btn-logout:active {
        transform: translateY(0) scale(0.97);
        background: #FEE2E2;
      }
      
      @media (hover: none) and (pointer: coarse) {
        .user-greeting:active,
        .btn-logout:active {
          transform: scale(0.95);
        }
      }
      
      /* Content */
      .content {
        animation: fadeIn 0.4s ease-out;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
      }
      
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      /* Lowongan Banner */
      .lowongan-banner {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        border-radius: 16px;
        padding: 24px 32px;
        margin: 0 0 24px;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.2);
        width: 100%;
      }
      
      .lowongan-banner-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        width: 100%;
      }
      
      .lowongan-banner-title {
        font-size: 24px;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
        flex: 1;
      }
      
      .lowongan-banner-btn {
        background: #FFFFFF;
        color: #2563EB;
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
      }
      
      .lowongan-banner-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(37, 99, 235, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.5s ease, height 0.5s ease;
        pointer-events: none;
      }
      
      .lowongan-banner-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        background: #F8FAFC;
      }
      
      .lowongan-banner-btn:hover::after {
        width: 300px;
        height: 300px;
      }
      
      .lowongan-banner-btn:active {
        transform: translateY(0) scale(0.98);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      }
      
      .lowongan-banner-btn svg,
      .lowongan-banner-btn::after {
        transition: transform 0.3s ease;
      }
      
      .lowongan-banner-btn:hover svg {
        transform: translateX(4px);
      }
      
      @media (hover: none) and (pointer: coarse) {
        .lowongan-banner-btn:active {
          transform: scale(0.95);
          background: #F3F4F6;
        }
      }
      
      /* Sidebar Navigation Animations */
      .nav-item {
        position: relative;
        overflow: hidden;
        -webkit-tap-highlight-color: transparent;
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
        transition: height 0.3s ease;
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
        transition: width 0.4s ease, height 0.4s ease;
        pointer-events: none;
      }
      
      .nav-item:hover::before {
        height: 60%;
      }
      
      .nav-item:hover::after {
        width: 200px;
        height: 200px;
      }
      
      .nav-item:active {
        transform: translateX(2px) scale(0.98);
      }
      
      .nav-item:hover .nav-icon {
        transform: scale(1.1) rotate(5deg);
        transition: transform 0.3s ease;
      }
      
      .nav-item:active .nav-icon {
        transform: scale(0.95) rotate(-2deg);
      }
      
      .nav-item .nav-icon svg {
        transition: transform 0.3s ease;
      }
      
      @media (hover: none) and (pointer: coarse) {
        .nav-item:active {
          transform: scale(0.97);
          background: #EFF6FF;
        }
      }
      
      @media (max-width: 768px) {
        .lowongan-banner {
          padding: 20px 24px;
        }
        
        .lowongan-banner-content {
          flex-direction: column;
          align-items: stretch;
          gap: 16px;
        }
        
        .lowongan-banner-title {
          text-align: center;
          font-size: 20px;
        }
        
        .lowongan-banner-btn {
          width: 100%;
          text-align: center;
        }
      }
      
      /* Welcome Section */
      .welcome-section {
        margin-bottom: 20px;
        background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);
        border-radius: 24px;
        padding: 32px 48px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
      }
      
      .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
        pointer-events: none;
      }
      
      .welcome-title {
        font-size: 42px;
        font-weight: 800;
        background: linear-gradient(135deg, #0C3A6B 0%, #2563EB 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 12px;
        line-height: 1.2;
        letter-spacing: -0.8px;
        position: relative;
      }
      
      .welcome-subtitle {
        font-size: 22px;
        color: #1F2937;
        margin: 0 0 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
      }
      
      .welcome-subtitle::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, #3B82F6 0%, transparent 100%);
        max-width: 200px;
      }
      
      .welcome-desc {
        font-size: 16px;
        color: #4B5563;
        line-height: 1.7;
        max-width: 720px;
        margin: 0;
      }
      
      /* Quick Actions */
      .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
      }
      
      .quick-action-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        border: 2px solid #E5E7EB;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
      }
      
      .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3B82F6, #10B981);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
      }
      
      .quick-action-card::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
        pointer-events: none;
      }
      
      .quick-action-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        border-color: #3B82F6;
      }
      
      .quick-action-card:hover::before {
        transform: scaleX(1);
      }
      
      .quick-action-card:active {
        transform: translateY(-2px) scale(0.98);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      }
      
      .quick-action-card:active::after {
        width: 300px;
        height: 300px;
        transition: width 0.3s ease, height 0.3s ease;
      }
      
      /* Touch feedback untuk mobile */
      @media (hover: none) and (pointer: coarse) {
        .quick-action-card:active {
          transform: scale(0.95);
          background: #F9FAFB;
        }
      }
      
      .quick-action-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        transition: all 0.3s ease;
        position: relative;
      }
      
      .quick-action-card:nth-child(1) .quick-action-icon {
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
      }
      
      .quick-action-card:nth-child(1) .quick-action-icon svg {
        color: #3B82F6;
      }
      
      .quick-action-card:nth-child(1):hover .quick-action-icon {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
      }
      
      .quick-action-card:nth-child(2) .quick-action-icon {
        background: linear-gradient(135deg, #F0FDF4 0%, #D1FAE5 100%);
      }
      
      .quick-action-card:nth-child(2) .quick-action-icon svg {
        color: #10B981;
      }
      
      .quick-action-card:nth-child(2):hover .quick-action-icon {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
      }
      
      .quick-action-card:nth-child(3) .quick-action-icon {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
      }
      
      .quick-action-card:nth-child(3) .quick-action-icon svg {
        color: #F59E0B;
      }
      
      .quick-action-card:nth-child(3):hover .quick-action-icon {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
      }
      
      .quick-action-card:nth-child(4) .quick-action-icon {
        background: linear-gradient(135deg, #FDF2F8 0%, #FCE7F3 100%);
      }
      
      .quick-action-card:nth-child(4) .quick-action-icon svg {
        color: #EC4899;
      }
      
      .quick-action-card:nth-child(4):hover .quick-action-icon {
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
      }
      
      .quick-action-card:hover .quick-action-icon {
        transform: scale(1.1) rotate(5deg);
        animation: iconBounce 0.6s ease;
      }
      
      .quick-action-card:active .quick-action-icon {
        transform: scale(0.95) rotate(-2deg);
      }
      
      .quick-action-card:hover .quick-action-icon svg {
        color: #FFFFFF;
        animation: iconPulse 0.6s ease;
      }
      
      @keyframes iconBounce {
        0%, 100% {
          transform: scale(1.1) rotate(5deg);
        }
        50% {
          transform: scale(1.15) rotate(8deg);
        }
      }
      
      @keyframes iconPulse {
        0%, 100% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.1);
        }
      }
      
      .quick-action-icon svg {
        width: 28px;
        height: 28px;
        transition: color 0.3s ease;
      }
      
      .quick-action-content {
        transition: transform 0.3s ease;
        position: relative;
        z-index: 1;
      }
      
      .quick-action-card:hover .quick-action-content {
        transform: translateX(4px);
      }
      
      .quick-action-card:active .quick-action-content {
        transform: translateX(2px);
      }
      
      .quick-action-content h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 6px;
        transition: color 0.3s ease;
      }
      
      .quick-action-card:hover .quick-action-content h3 {
        color: #3B82F6;
      }
      
      .quick-action-content p {
        font-size: 13px;
        color: #6B7280;
        margin: 0;
        line-height: 1.5;
        transition: color 0.3s ease;
      }
      
      .quick-action-card:hover .quick-action-content p {
        color: #4B5563;
      }
      
      /* Stats Grid */
      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 48px;
      }
      
      .stat-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 28px;
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
      .stat-card.purple::before { background: #8B5CF6; }
      
      .stat-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
      }
      
      .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 14px;
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
      
      .stat-card.purple .stat-icon { 
        background: #EDE9FE; 
      }
      
      .stat-info {
        flex: 1;
        min-width: 0;
      }
      
      .stat-label {
        font-size: 13px;
        color: #6B7280;
        font-weight: 500;
        margin: 0 0 10px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
      }
      
      .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #1F2937;
        margin: 0;
        line-height: 1;
        letter-spacing: -1px;
      }
      
      .stat-card.orange .stat-value { color: #F59E0B; }
      .stat-card.green .stat-value { color: #10B981; }
      .stat-card.red .stat-value { color: #EF4444; }
      .stat-card.purple .stat-value { color: #8B5CF6; }
      
      /* Status Card */
      .status-card {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #E5E7EB;
        position: relative;
        overflow: hidden;
      }
      
      .status-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.03) 0%, transparent 70%);
        pointer-events: none;
      }
      
      .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #F3F4F6;
      }
      
      .status-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
        letter-spacing: -0.3px;
      }
      
      /* Empty State */
      .empty-state {
        text-align: center;
        padding: 80px 40px;
        position: relative;
        z-index: 1;
      }
      
      .empty-icon {
        width: 140px;
        height: 140px;
        margin: 0 auto 32px;
        opacity: 0.3;
        color: #3B82F6;
        animation: float 3s ease-in-out infinite;
      }
      
      @keyframes float {
        0%, 100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-10px);
        }
      }
      
      .empty-title {
        font-size: 24px;
        font-weight: 700;
        background: linear-gradient(135deg, #1F2937 0%, #4B5563 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 16px;
        letter-spacing: -0.4px;
      }
      
      .empty-desc {
        font-size: 16px;
        color: #6B7280;
        margin: 0 auto 40px;
        max-width: 550px;
        line-height: 1.8;
      }
      
      /* Enhanced Empty State */
      .empty-state-enhanced {
        padding: 60px 24px;
        position: relative;
        z-index: 1;
      }
      
      .empty-state-main {
        text-align: center;
        margin-bottom: 48px;
      }
      
      .empty-state-icon-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto 32px;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.15);
        animation: float 3s ease-in-out infinite;
      }
      
      .empty-icon-enhanced {
        width: 64px;
        height: 64px;
        color: #2563EB;
      }
      
      .empty-title-enhanced {
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(135deg, #1F2937 0%, #4B5563 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 16px;
        letter-spacing: -0.5px;
      }
      
      .empty-desc-enhanced {
        font-size: 16px;
        color: #6B7280;
        margin: 0 auto 32px;
        max-width: 600px;
        line-height: 1.8;
      }
      
      .empty-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 32px;
      }
      
      .btn-primary-enhanced {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 32px;
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: #FFFFFF;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        animation: fadeInUp 0.6s ease-out 0.2s both;
      }
      
      .btn-primary-enhanced:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
      }
      
      .btn-secondary-enhanced {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 32px;
        background: #FFFFFF;
        color: #3B82F6;
        border: 2px solid #3B82F6;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease-out 0.3s both;
      }
      
      .btn-secondary-enhanced:hover {
        background: #3B82F6;
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
      }
      
      @media (max-width: 768px) {
        .empty-state-enhanced {
          padding: 40px 16px;
        }
        
        .empty-state-icon-wrapper {
          width: 100px;
          height: 100px;
        }
        
        .empty-icon-enhanced {
          width: 52px;
          height: 52px;
        }
        
        .empty-title-enhanced {
          font-size: 24px;
        }
        
        .empty-desc-enhanced {
          font-size: 15px;
        }
        
        .empty-actions {
          flex-direction: column;
        }
        
        .btn-primary-enhanced,
        .btn-secondary-enhanced {
          width: 100%;
          justify-content: center;
        }
      }
      
      /* Buttons */
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
      
      .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: #FFFFFF;
        color: #3B82F6;
        border: 2px solid #3B82F6;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s ease;
        cursor: pointer;
      }
      
      .btn-secondary:hover {
        background: #EFF6FF;
        transform: translateY(-1px);
      }
      
      /* Table */
      .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      
      table {
        width: 100%;
        border-collapse: collapse;
      }
      
      thead tr {
        border-bottom: 2px solid #E5E7EB;
      }
      
      th {
        text-align: left;
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
      }
      
      tbody tr {
        border-bottom: 1px solid #F3F4F6;
        transition: background 0.15s ease;
      }
      
      tbody tr:hover {
        background: #F9FAFB;
      }
      
      td {
        padding: 18px 16px;
        font-size: 14px;
        color: #1F2937;
        vertical-align: middle;
      }
      
      .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
      }
      
      .link-detail {
        color: #3B82F6;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: color 0.2s ease;
      }
      
      .link-detail:hover {
        color: #2563EB;
        text-decoration: underline;
      }
      
      /* Action Item Notification */
      .action-item-notification {
        margin-bottom: 32px;
        padding: 20px 24px;
        border-radius: 12px;
        border-left: 4px solid #3B82F6;
        background: #EFF6FF;
      }
      
      .action-item-notification.error {
        border-left-color: #EF4444;
        background: #FEE2E2;
      }
      
      .action-item-notification.warning {
        border-left-color: #F59E0B;
        background: #FEF3C7;
      }
      
      .action-item-notification-content {
        display: flex;
        align-items: flex-start;
        gap: 12px;
      }
      
      .action-item-icon {
        flex-shrink: 0;
        margin-top: 2px;
      }
      
      .action-item-text {
        margin: 0;
        font-size: 15px;
        line-height: 1.6;
        color: #1F2937;
        font-weight: 500;
      }
      
      /* Notification Item */
      .notification-item {
        padding: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .notification-item.info {
        background: #EFF6FF;
        border-left: 4px solid #3B82F6;
      }
      
      .notification-item.warning {
        background: #FEF3C7;
        border-left: 4px solid #F59E0B;
      }
      
      .notification-item.error {
        background: #FEE2E2;
        border-left: 4px solid #EF4444;
      }
      
      .notification-item.success {
        background: #ECFDF5;
        border-left: 4px solid #10B981;
      }
      
      .notification-item:hover {
        transform: translateX(4px);
      }
      
      .notification-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
      }
      
      .notification-text-wrapper {
        flex: 1;
      }
      
      .notification-title {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
      }
      
      .notification-item.info .notification-title {
        color: #1E40AF;
      }
      
      .notification-item.warning .notification-title {
        color: #92400E;
      }
      
      .notification-item.error .notification-title {
        color: #991B1B;
      }
      
      .notification-item.success .notification-title {
        color: #065F46;
      }
      
      .notification-message {
        margin: 0 0 8px 0;
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
      }
      
      .notification-time {
        margin: 0;
        font-size: 12px;
        color: #6B7280;
      }
      
      .notification-badge {
        background: #DC2626;
        color: #FFFFFF;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
      }
      
      .notification-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
      }
      
      .notification-mark-read-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 4px;
        color: #6B7280;
        opacity: 0.6;
      }
      
      .notification-mark-read-btn:hover {
        opacity: 1;
      }
      
      /* Responsive */
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
        
        .welcome-title {
          font-size: 32px;
        }
        
        .stats-grid {
          grid-template-columns: 1fr;
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
            <!-- Welcome Section -->
            <div class="welcome-section">
              <h1 class="welcome-title">Dashboard Overview</h1>
              <h2 class="welcome-subtitle">Selamat Datang, {{ auth()->user()->nama ?? 'Pengguna' }}! 👋</h2>
              <p class="welcome-desc">
                Ini adalah pusat kontrol magang Anda. Pantau status lamaran, lengkapi berkas, dan dapatkan informasi terbaru seputar program magang di Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta.
              </p>
            </div>

            <!-- Lowongan Banner -->
            @if($lowonganTersedia)
              <div class="lowongan-banner">
                <div class="lowongan-banner-content">
                  <div style="flex: 1;">
                    <h3 class="lowongan-banner-title">
                      @if(isset($adaJadwalAkanDatang) && $adaJadwalAkanDatang)
                        🎉 Lowongan Magang Tersedia!
                      @else
                        🎉 Lowongan Magang Dibuka!
                      @endif
                    </h3>
                    <p style="color: rgba(255, 255, 255, 0.95); margin: 8px 0 0 0; font-size: 15px; font-weight: 500;">
                      @if(isset($jumlahLowongan) && $jumlahLowongan > 0)
                        Ada {{ $jumlahLowongan }} {{ $jumlahLowongan == 1 ? 'lowongan' : 'lowongan' }} yang tersedia untuk Anda.
                      @else
                        Ada lowongan magang yang tersedia untuk Anda.
                      @endif
                      @if(isset($adaJadwalAkanDatang) && $adaJadwalAkanDatang)
                        <span style="display: block; margin-top: 4px; font-size: 14px; opacity: 0.9;">
                          ⏰ Beberapa lowongan akan segera dibuka!
                        </span>
                      @endif
                    </p>
                  </div>
                  <a href="{{ route('lowongan') }}" class="lowongan-banner-btn">Lihat Detail →</a>
                </div>
              </div>
            @endif

            <!-- Quick Actions -->
            <div class="quick-actions">
              <a href="{{ route('lowongan') }}" class="quick-action-card">
                <div class="quick-action-icon">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M16 7V6a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21 11l-9 5-9-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <div class="quick-action-content">
                  <h3>Lihat Lowongan</h3>
                  <p>Jelajahi periode magang yang tersedia</p>
                </div>
              </a>

              <a href="{{ route('lamaran') }}" class="quick-action-card">
                <div class="quick-action-icon">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </div>
                <div class="quick-action-content">
                  <h3>Status Dokumen</h3>
                  <p>Cek & kelola dokumen lamaran Anda</p>
                </div>
              </a>

              <a href="{{ route('riwayat.lamaran') }}" class="quick-action-card">
                <div class="quick-action-icon">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <div class="quick-action-content">
                  <h3>Status Lamaran</h3>
                  <p>Pantau status semua permohonan Anda</p>
                </div>
              </a>

              <a href="{{ route('profil') }}" class="quick-action-card">
                <div class="quick-action-icon">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
                <div class="quick-action-content">
                  <h3>Profil Saya</h3>
                  <p>Kelola informasi data diri Anda</p>
                </div>
              </a>
            </div>

            @if(isset($actionItem) && $actionItem)
              <div class="action-item-notification {{ $actionItemType ?? 'info' }}">
                <div class="action-item-notification-content">
                  <div class="action-item-icon">
                    @if(isset($actionItemType) && $actionItemType === 'error')
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#EF4444" stroke-width="2"/>
                        <path d="M12 8v4M12 16h.01" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    @elseif(isset($actionItemType) && $actionItemType === 'warning')
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 9v4M12 17h.01" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    @else
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#3B82F6" stroke-width="2"/>
                        <path d="M12 16v-4M12 8h.01" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                      </svg>
                    @endif
                  </div>
                  <p class="action-item-text">
                    {{ $actionItem }}
                  </p>
                </div>
              </div>
            @endif

            <!-- Notifikasi dari Admin -->
            @if(isset($notifikasi) && $notifikasi->count() > 0)
              <div style="margin-bottom: 32px;">
                <div class="status-card">
                  <div class="status-header">
                    <h2 class="status-title">📬 Notifikasi</h2>
                    <span class="notification-badge">
                      {{ $notifikasi->count() }} baru
                    </span>
                  </div>
                  <div class="notification-actions">
                    @foreach($notifikasi as $notif)
                      @php
                        $tipe = $notif->tipe ?? 'info';
                      @endphp
                      <div class="notification-item {{ $tipe }} notification-item-clickable" 
                           data-notif-id="{{ $notif->id }}"
                           data-mark-read-id="{{ $notif->id }}">
                        <div class="notification-content">
                          <div class="notification-text-wrapper">
                            <h3 class="notification-title">
                              {{ $notif->judul }}
                            </h3>
                            <p class="notification-message">
                              {!! nl2br(e($notif->pesan)) !!}
                            </p>
                            <p class="notification-time">
                              {{ $notif->created_at->diffForHumans() }}
                            </p>
                          </div>
                          <form action="{{ route('notifikasi.baca', $notif->id) }}" method="POST" style="display: inline;" id="form-read-{{ $notif->id }}">
                            @csrf
                            <button type="button" 
                                    data-mark-read-id="{{ $notif->id }}"
                                    class="notification-mark-read-btn btn-mark-read"
                                    title="Tandai sudah dibaca">
                              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                            </button>
                          </form>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endif

            <!-- Status Lamaran Terbaru -->
            <div class="status-card">
              <div class="status-header">
                <h2 class="status-title">Status Lamaran Terbaru</h2>
                @if($totalLamaran > 5)
                  <a href="{{ route('lamaran') }}" class="link-detail">Lihat Semua →</a>
                @endif
              </div>

              @if($permohonanTerbaru->count() > 0)
                <div class="table-container">
                  <table>
                    <thead>
                      <tr>
                        <th>Posisi Magang</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($permohonanTerbaru as $permohonan)
                        <tr>
                          <td style="font-weight: 500;">
                            @if($permohonan->kuotaMagang->count() > 0)
                              Magang Periode {{ $permohonan->kuotaMagang->first()->periode }}
                            @else
                              Magang {{ $permohonan->kuotaMagang->first()->periode ?? 'BBKB' }}
                            @endif
                          </td>
                          <td style="color: #6B7280;">
                            {{ $permohonan->tanggal_pengajuan ? $permohonan->tanggal_pengajuan->format('d/m/Y') : $permohonan->created_at->format('d/m/Y') }}
                          </td>
                          <td>
                            @php
                              $statusColors = [
                                'Diajukan' => ['bg' => '#EFF6FF', 'text' => '#2563EB', 'label' => 'Diajukan'],
                                'Diverifikasi' => ['bg' => '#FEF3C7', 'text' => '#F59E0B', 'label' => 'Diverifikasi'],
                                'Diterima' => ['bg' => '#D1FAE5', 'text' => '#10B981', 'label' => 'Diterima'],
                                'Ditolak' => ['bg' => '#FEE2E2', 'text' => '#EF4444', 'label' => 'Ditolak'],
                              ];
                              $status = $statusColors[$permohonan->status] ?? ['bg' => '#F3F4F6', 'text' => '#6B7280', 'label' => $permohonan->status];
                            @endphp
                            <span class="status-badge" data-bg="{{ $status['bg'] }}" data-text="{{ $status['text'] }}">
                              {{ $status['label'] }}
                            </span>
                          </td>
                          <td>
                            <a href="{{ route('lamaran') }}" class="link-detail">Detail</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="empty-state-enhanced">
                  <div class="empty-state-main">
                    <div class="empty-state-icon-wrapper">
                      <svg class="empty-icon-enhanced" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="13" r="2" stroke="currentColor" stroke-width="1.5"/>
                      </svg>
                    </div>
                    <h3 class="empty-title-enhanced">Belum Ada Lamaran</h3>
                    <p class="empty-desc-enhanced">
                      Mulai perjalanan magang Anda dengan membuat permohonan baru. Kami siap membantu Anda mewujudkan impian magang di Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta.
                    </p>
                    <div class="empty-actions">
                      <a href="{{ route('lowongan') }}" class="btn-primary-enhanced">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Buat Permohonan Baru
                      </a>
                      @if($lowonganTersedia)
                        <a href="{{ route('lowongan') }}" class="btn-secondary-enhanced">
                          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                          Lihat Lowongan Tersedia
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </section>
        </div>
      </main>
    </div>
  <script>
    function markAsRead(notifId) {
      const form = document.getElementById('form-read-' + notifId);
      if (form) {
        form.submit();
      }
    }
    
    // Handle notification item clicks and mark as read buttons
    document.addEventListener('DOMContentLoaded', function() {
      // Set status badge styles from data attributes
      document.querySelectorAll('.status-badge[data-bg]').forEach(function(badge) {
        const bg = badge.getAttribute('data-bg');
        const text = badge.getAttribute('data-text');
        if (bg && text) {
          badge.style.background = bg;
          badge.style.color = text;
        }
      });
      
      // Handle notification item clicks
      document.querySelectorAll('[data-mark-read-id]').forEach(function(element) {
        element.addEventListener('click', function(e) {
          // Don't trigger if clicking on the mark read button
          if (e.target.closest('.btn-mark-read')) {
            return;
          }
          const notifId = this.getAttribute('data-mark-read-id');
          if (notifId) {
            markAsRead(notifId);
          }
        });
      });
      
      // Handle mark as read button clicks
      document.querySelectorAll('.btn-mark-read').forEach(function(button) {
        button.addEventListener('click', function(e) {
          e.stopPropagation(); // Prevent triggering parent click
          const notifId = this.getAttribute('data-mark-read-id');
          if (notifId) {
            markAsRead(notifId);
          }
        });
      });
    });
  </script>
</body>
</html>
