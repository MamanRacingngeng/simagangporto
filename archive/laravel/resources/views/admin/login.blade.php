<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Magang Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-image: url('/images/baground2.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            overflow: hidden;
            box-sizing: border-box;
        }
        
        /* Filter overlay untuk background body - tema merah */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(220, 38, 38, 0.85) 0%,
                rgba(185, 28, 28, 0.80) 30%,
                rgba(153, 27, 27, 0.75) 60%,
                rgba(127, 29, 29, 0.70) 100%
            );
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Pattern batik halus overlay untuk tekstur tambahan */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(220, 38, 38, 0.05) 10px, rgba(220, 38, 38, 0.05) 20px),
                repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(220, 38, 38, 0.05) 10px, rgba(220, 38, 38, 0.05) 20px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.3;
        }
        
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }
        }
        
        /* Kolom Kiri - Background Merah dengan Gradient Halus */
        .login-left {
            background: linear-gradient(to top, #991b1b 0%, #b91c1c 30%, #dc2626 60%, #ef4444 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Pattern Batik Floral yang lebih halus dan elegan */
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                /* Pattern batik floral utama - lebih halus dan mengalir */
                url("data:image/svg+xml,%3Csvg width='600' height='600' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='batik-floral' x='0' y='0' width='600' height='600' patternUnits='userSpaceOnUse'%3E%3Cpath d='M0,300 Q150,150 300,300 T600,300' stroke='rgba(255,255,255,0.12)' stroke-width='2' fill='none'/%3E%3Cpath d='M0,150 Q150,250 300,150 T600,150' stroke='rgba(255,255,255,0.10)' stroke-width='1.5' fill='none'/%3E%3Cpath d='M0,450 Q150,350 300,450 T600,450' stroke='rgba(255,255,255,0.10)' stroke-width='1.5' fill='none'/%3E%3Cpath d='M150,0 Q300,150 450,0' stroke='rgba(255,255,255,0.08)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M150,600 Q300,450 450,600' stroke='rgba(255,255,255,0.08)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M100,200 Q200,100 300,200 Q400,300 500,200' stroke='rgba(255,255,255,0.09)' stroke-width='1' fill='none'/%3E%3Cpath d='M100,400 Q200,500 300,400 Q400,300 500,400' stroke='rgba(255,255,255,0.09)' stroke-width='1' fill='none'/%3E%3Ccircle cx='150' cy='250' r='25' fill='none' stroke='rgba(255,255,255,0.07)' stroke-width='1'/%3E%3Ccircle cx='450' cy='350' r='25' fill='none' stroke='rgba(255,255,255,0.07)' stroke-width='1'/%3E%3Ccircle cx='300' cy='150' r='20' fill='none' stroke='rgba(255,255,255,0.06)' stroke-width='0.8'/%3E%3Ccircle cx='300' cy='450' r='20' fill='none' stroke='rgba(255,255,255,0.06)' stroke-width='0.8'/%3E%3Cpath d='M200,100 Q250,150 300,100 Q350,50 400,100' stroke='rgba(255,255,255,0.08)' stroke-width='1' fill='none'/%3E%3Cpath d='M200,500 Q250,450 300,500 Q350,550 400,500' stroke='rgba(255,255,255,0.08)' stroke-width='1' fill='none'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23batik-floral)'/%3E%3C/svg%3E"),
                /* Overlay gradient untuk tekstur tambahan */
                radial-gradient(ellipse 400% 300% at 50% 50%, rgba(255, 255, 255, 0.08) 0%, transparent 60%),
                /* Garis-garis halus diagonal */
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 80px,
                    rgba(255, 255, 255, 0.05) 80px,
                    rgba(255, 255, 255, 0.05) 82px,
                    transparent 82px,
                    transparent 160px
                ),
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 75px,
                    rgba(255, 255, 255, 0.04) 75px,
                    rgba(255, 255, 255, 0.04) 77px,
                    transparent 77px,
                    transparent 150px
                );
            background-size: 600px 600px, 100% 100%, 200px 200px, 180px 180px;
            background-position: 0 0, 0 0, 0 0, 15px 15px;
            opacity: 1;
            z-index: 1;
        }
        
        /* Overlay tambahan untuk efek depth */
        .login-left::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            z-index: 1;
            pointer-events: none;
        }
        
        .login-container {
            display: grid;
            grid-template-columns: 42% 58%;
            max-width: 850px;
            width: 95%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.5);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
            position: relative;
            z-index: 10;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-left {
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            border-radius: 40px 0 0 40px;
        }
        
        .welcome-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: #ffffff;
            animation: fadeInUp 1s ease-out;
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
        
        .panel-badge {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
            backdrop-filter: blur(10px);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .welcome-title {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 18px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        
        .welcome-subtitle {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
            max-width: 420px;
            margin: 0 auto;
            font-weight: 400;
        }
        
        .login-right {
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: linear-gradient(to bottom, #FFFFFF 0%, #FEF2F2 30%, #FEE2E2 70%, #FECACA 100%);
            overflow: hidden;
            border-radius: 0 40px 40px 0;
        }
        
        /* Pattern Batik untuk kolom kanan - dengan warna merah */
        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                /* Pattern batik floral subtle untuk kolom kanan - merah */
                url("data:image/svg+xml,%3Csvg width='500' height='500' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='batik-right' x='0' y='0' width='500' height='500' patternUnits='userSpaceOnUse'%3E%3Cpath d='M0,250 Q125,125 250,250 T500,250' stroke='rgba(220,38,38,0.12)' stroke-width='1.5' fill='none'/%3E%3Cpath d='M0,125 Q125,200 250,125 T500,125' stroke='rgba(220,38,38,0.10)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M0,375 Q125,300 250,375 T500,375' stroke='rgba(220,38,38,0.10)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M125,0 Q250,125 375,0' stroke='rgba(220,38,38,0.08)' stroke-width='1' fill='none'/%3E%3Cpath d='M125,500 Q250,375 375,500' stroke='rgba(220,38,38,0.08)' stroke-width='1' fill='none'/%3E%3Cpath d='M80,160 Q160,80 240,160 Q320,240 400,160' stroke='rgba(220,38,38,0.11)' stroke-width='0.8' fill='none'/%3E%3Cpath d='M80,340 Q160,420 240,340 Q320,260 400,340' stroke='rgba(220,38,38,0.11)' stroke-width='0.8' fill='none'/%3E%3Ccircle cx='125' cy='200' r='20' fill='none' stroke='rgba(220,38,38,0.08)' stroke-width='0.8'/%3E%3Ccircle cx='375' cy='300' r='20' fill='none' stroke='rgba(220,38,38,0.08)' stroke-width='0.8'/%3E%3Ccircle cx='250' cy='100' r='15' fill='none' stroke='rgba(220,38,38,0.06)' stroke-width='0.6'/%3E%3Ccircle cx='250' cy='400' r='15' fill='none' stroke='rgba(220,38,38,0.06)' stroke-width='0.6'/%3E%3Cpath d='M160,60 Q200,100 240,60 Q280,20 320,60' stroke='rgba(220,38,38,0.09)' stroke-width='0.8' fill='none'/%3E%3Cpath d='M160,440 Q200,400 240,440 Q280,480 320,440' stroke='rgba(220,38,38,0.09)' stroke-width='0.8' fill='none'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23batik-right)'/%3E%3C/svg%3E"),
                /* Overlay gradient untuk tekstur - merah */
                radial-gradient(ellipse 350% 250% at 50% 50%, rgba(220, 38, 38, 0.08) 0%, transparent 65%),
                /* Garis-garis halus diagonal - merah */
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 70px,
                    rgba(220, 38, 38, 0.06) 70px,
                    rgba(220, 38, 38, 0.06) 72px,
                    transparent 72px,
                    transparent 140px
                ),
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 65px,
                    rgba(220, 38, 38, 0.05) 65px,
                    rgba(220, 38, 38, 0.05) 67px,
                    transparent 67px,
                    transparent 130px
                );
            background-size: 500px 500px, 100% 100%, 180px 180px, 160px 160px;
            background-position: 0 0, 0 0, 0 0, 12px 12px;
            opacity: 1;
            z-index: 0;
            pointer-events: none;
        }
        
        /* Overlay tambahan untuk efek depth pada kolom kanan */
        .login-right::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 15% 25%, rgba(239, 68, 68, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 75%, rgba(254, 202, 202, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(254, 242, 242, 0.05) 0%, transparent 60%);
            z-index: 0;
            pointer-events: none;
        }
        
        .form-wrapper {
            position: relative;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            z-index: 1;
        }
        
        .form-footer {
            margin-top: auto;
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .form-header {
            margin-bottom: 35px;
            animation: fadeIn 0.8s ease-out;
            position: relative;
            z-index: 1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .form-title {
            font-size: 26px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }
        
        .form-subtitle {
            font-size: 14px;
            color: #6b7280;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 24px;
            animation: slideInRight 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            color: #1f2937;
            font-family: 'Inter', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            transform: translateY(-2px);
        }
        
        .form-input:hover {
            border-color: #d1d5db;
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            cursor: pointer;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #dc2626;
        }
        
        .remember-me label {
            font-size: 14px;
            color: #6b7280;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(90deg, #991b1b 0%, #dc2626 50%, #ef4444 100%);
            color: #ffffff;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.4);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .user-link {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 400;
        }
        
        .user-link:hover {
            color: #dc2626;
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            animation: shake 0.5s ease-out;
            font-size: 14px;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .warning-box {
            margin-top: 20px;
            padding: 14px 16px;
            background: #fef3c7;
            border-radius: 12px;
            border-left: 4px solid #f59e0b;
        }
        
        .warning-box p {
            margin: 0;
            color: #92400e;
            font-size: 13px;
            line-height: 1.5;
        }
        
        @media (max-width: 968px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            
            .login-left {
                padding: 40px 30px;
                min-height: 300px;
                border-radius: 40px 40px 0 0;
            }
            
            .welcome-title {
                font-size: 36px;
            }
            
            .form-footer {
                flex-direction: column;
                align-items: center;
                text-align: center;
                margin-top: 20px;
                gap: 8px;
            }
            
            .user-link {
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="welcome-content">
                <div class="panel-badge">Panel Administrasi</div>
                <h1 class="welcome-title">Login Admin</h1>
                <p class="welcome-subtitle">
                    Masuk sebagai administrator untuk mengelola sistem magang, verifikasi permohonan, dan mengatur kuota.
                </p>
            </div>
        </div>
        
        <div class="login-right">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2 class="form-title">Masuk sebagai Admin</h2>
                    <p class="form-subtitle">Silakan masuk dengan kredensial administrator</p>
                </div>
                
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Admin</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="form-input" 
                        placeholder="admin@email.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="error-message">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Masukkan kata sandi"
                        required
                    >
                    @error('password')
                        <div class="error-message">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                
                <button type="submit" class="btn-login">
                    <span style="position: relative; z-index: 1;">Masuk sebagai Admin</span>
                </button>
                
                </form>
                
                <div class="form-footer">
                    <a href="{{ route('login') }}" class="user-link">
                        Bukan Admin? Login sebagai User
                    </a>
                </div>
                
                <div class="warning-box">
                    <p>
                        <strong>Perhatian:</strong> Hanya administrator yang memiliki akses untuk login melalui halaman ini.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
