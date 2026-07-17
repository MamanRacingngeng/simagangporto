<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Magang Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if($recaptchaEnabled)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-image: url('/images/registrasifoto.jpeg');
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
        
        /* Filter overlay untuk background body - perpaduan merah dan kuning */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(254, 243, 199, 0.75) 0%,
                rgba(252, 211, 77, 0.70) 25%,
                rgba(245, 158, 11, 0.65) 50%,
                rgba(239, 68, 68, 0.60) 75%,
                rgba(220, 38, 38, 0.55) 100%
            );
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Pattern batik halus overlay untuk tekstur tambahan - perpaduan merah dan kuning */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(242, 201, 76, 0.05) 10px, rgba(242, 201, 76, 0.05) 20px),
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
        
        .register-container {
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
        
        /* Kolom Kiri - Background Perpaduan Merah dan Kuning dengan Gradient */
        .register-left {
            background: linear-gradient(to top, #991b1b 0%, #dc2626 20%, #f59e0b 40%, #f2c94c 60%, #fcd34d 80%, #fef3c7 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            border-radius: 40px 0 0 40px;
        }
        
        /* Pattern Batik Floral - perpaduan merah dan kuning */
        .register-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='600' height='600' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='batik-floral' x='0' y='0' width='600' height='600' patternUnits='userSpaceOnUse'%3E%3Cpath d='M0,300 Q150,150 300,300 T600,300' stroke='rgba(255,255,255,0.12)' stroke-width='2' fill='none'/%3E%3Cpath d='M0,150 Q150,250 300,150 T600,150' stroke='rgba(255,255,255,0.10)' stroke-width='1.5' fill='none'/%3E%3Cpath d='M0,450 Q150,350 300,450 T600,450' stroke='rgba(255,255,255,0.10)' stroke-width='1.5' fill='none'/%3E%3Cpath d='M150,0 Q300,150 450,0' stroke='rgba(255,255,255,0.08)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M150,600 Q300,450 450,600' stroke='rgba(255,255,255,0.08)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M100,200 Q200,100 300,200 Q400,300 500,200' stroke='rgba(255,255,255,0.09)' stroke-width='1' fill='none'/%3E%3Cpath d='M100,400 Q200,500 300,400 Q400,300 500,400' stroke='rgba(255,255,255,0.09)' stroke-width='1' fill='none'/%3E%3Ccircle cx='150' cy='250' r='25' fill='none' stroke='rgba(255,255,255,0.07)' stroke-width='1'/%3E%3Ccircle cx='450' cy='350' r='25' fill='none' stroke='rgba(255,255,255,0.07)' stroke-width='1'/%3E%3Ccircle cx='300' cy='150' r='20' fill='none' stroke='rgba(255,255,255,0.06)' stroke-width='0.8'/%3E%3Ccircle cx='300' cy='450' r='20' fill='none' stroke='rgba(255,255,255,0.06)' stroke-width='0.8'/%3E%3Cpath d='M200,100 Q250,150 300,100 Q350,50 400,100' stroke='rgba(255,255,255,0.08)' stroke-width='1' fill='none'/%3E%3Cpath d='M200,500 Q250,450 300,500 Q350,550 400,500' stroke='rgba(255,255,255,0.08)' stroke-width='1' fill='none'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23batik-floral)'/%3E%3C/svg%3E"),
                radial-gradient(ellipse 400% 300% at 50% 50%, rgba(255, 255, 255, 0.08) 0%, transparent 60%),
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
        .register-left::after {
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
        
        .register-right {
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: linear-gradient(to bottom, #FFFFFF 0%, #FEF2F2 20%, #FEE2E2 40%, #FEF3C7 60%, #FDF4D9 80%, #FEFBF3 100%);
            overflow: hidden;
            border-radius: 0 40px 40px 0;
        }
        
        /* Pattern Batik untuk kolom kanan - perpaduan merah dan kuning */
        .register-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='500' height='500' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='batik-right' x='0' y='0' width='500' height='500' patternUnits='userSpaceOnUse'%3E%3Cpath d='M0,250 Q125,125 250,250 T500,250' stroke='rgba(220,38,38,0.08)' stroke-width='1.5' fill='none'/%3E%3Cpath d='M0,125 Q125,200 250,125 T500,125' stroke='rgba(242,201,76,0.08)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M0,375 Q125,300 250,375 T500,375' stroke='rgba(220,38,38,0.08)' stroke-width='1.2' fill='none'/%3E%3Cpath d='M125,0 Q250,125 375,0' stroke='rgba(242,201,76,0.06)' stroke-width='1' fill='none'/%3E%3Cpath d='M125,500 Q250,375 375,500' stroke='rgba(220,38,38,0.06)' stroke-width='1' fill='none'/%3E%3Cpath d='M80,160 Q160,80 240,160 Q320,240 400,160' stroke='rgba(242,201,76,0.09)' stroke-width='0.8' fill='none'/%3E%3Cpath d='M80,340 Q160,420 240,340 Q320,260 400,340' stroke='rgba(220,38,38,0.09)' stroke-width='0.8' fill='none'/%3E%3Ccircle cx='125' cy='200' r='20' fill='none' stroke='rgba(242,201,76,0.07)' stroke-width='0.8'/%3E%3Ccircle cx='375' cy='300' r='20' fill='none' stroke='rgba(220,38,38,0.07)' stroke-width='0.8'/%3E%3Ccircle cx='250' cy='100' r='15' fill='none' stroke='rgba(242,201,76,0.05)' stroke-width='0.6'/%3E%3Ccircle cx='250' cy='400' r='15' fill='none' stroke='rgba(220,38,38,0.05)' stroke-width='0.6'/%3E%3Cpath d='M160,60 Q200,100 240,60 Q280,20 320,60' stroke='rgba(242,201,76,0.07)' stroke-width='0.8' fill='none'/%3E%3Cpath d='M160,440 Q200,400 240,440 Q280,480 320,440' stroke='rgba(220,38,38,0.07)' stroke-width='0.8' fill='none'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23batik-right)'/%3E%3C/svg%3E"),
                radial-gradient(ellipse 350% 250% at 50% 50%, rgba(242, 201, 76, 0.06) 0%, transparent 65%),
                radial-gradient(ellipse 350% 250% at 50% 50%, rgba(220, 38, 38, 0.06) 0%, transparent 65%),
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 70px,
                    rgba(242, 201, 76, 0.04) 70px,
                    rgba(242, 201, 76, 0.04) 72px,
                    transparent 72px,
                    transparent 140px
                ),
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 65px,
                    rgba(220, 38, 38, 0.04) 65px,
                    rgba(220, 38, 38, 0.04) 67px,
                    transparent 67px,
                    transparent 130px
                );
            background-size: 500px 500px, 100% 100%, 100% 100%, 180px 180px, 160px 160px;
            background-position: 0 0, 0 0, 0 0, 0 0, 12px 12px;
            opacity: 1;
            z-index: 0;
            pointer-events: none;
        }
        
        /* Overlay tambahan untuk efek depth pada kolom kanan */
        .register-right::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 15% 25%, rgba(245, 158, 11, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 85% 75%, rgba(220, 38, 38, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(254, 243, 199, 0.04) 0%, transparent 60%);
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
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        
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
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
            transform: translateY(-2px);
        }
        
        .form-input:hover {
            border-color: #d1d5db;
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(90deg, #dc2626 0%, #f59e0b 50%, #f2c94c 100%);
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
        
        .btn-register::before {
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
        
        .btn-register:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.5);
        }
        
        .btn-register:active {
            transform: translateY(0);
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
        
        .form-footer {
            margin-top: auto;
            padding-top: 24px;
            text-align: center;
        }
        
        .link-login {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 400;
        }
        
        .link-login:hover {
            color: #dc2626;
            text-decoration: underline;
        }
        
        @media (max-width: 968px) {
            .register-container {
                grid-template-columns: 1fr;
            }
            
            .register-left {
                padding: 40px 30px;
                min-height: 300px;
                border-radius: 40px 40px 0 0;
            }
            
            .welcome-title {
                font-size: 36px;
            }
            
            .form-footer {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <div class="welcome-content">
                <h1 class="welcome-title">Mulai Perjalanan Magangmu</h1>
                <p class="welcome-subtitle">
                    Buat akun untuk melamar dan pantau progres seleksi secara realtime.
                </p>
            </div>
        </div>
        
        <div class="register-right">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2 class="form-title">Daftar Akun Baru</h2>
                    <p class="form-subtitle">Silakan isi data berikut untuk membuat akun</p>
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
                
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="nama">Nama</label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            value="{{ old('nama') }}" 
                            class="form-input" 
                            placeholder="Masukkan nama lengkap"
                            required
                            autofocus
                        >
                        @error('nama')
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
                        <label class="form-label" for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="form-input" 
                            placeholder="nama@email.com"
                            required
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
                    
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input" 
                            placeholder="Masukkan ulang kata sandi"
                            required
                        >
                    </div>
                    
                    @if($recaptchaEnabled)
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                            @error('g-recaptcha-response')
                                <div class="error-message">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @else
                        <div class="form-group" style="padding: 12px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px; margin-bottom: 24px;">
                            <p style="margin: 0; font-size: 13px; color: #92400e;">
                                <strong>Catatan:</strong> reCAPTCHA belum dikonfigurasi. Untuk keamanan, silakan konfigurasi RECAPTCHA_SITE_KEY dan RECAPTCHA_SECRET_KEY di file .env
                            </p>
                        </div>
                    @endif
                    
                    <button type="submit" class="btn-register">
                        <span style="position: relative; z-index: 1;">Daftar</span>
                    </button>
                </form>
                
                <div class="form-footer">
                    <a href="{{ route('login') }}" class="link-login">
                        Sudah punya akun? Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
