<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .email-logo {
            max-width: 150px;
            height: auto;
        }
        .email-content {
            padding: 30px 20px;
            background-color: #ffffff;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 15px;
        }
        .message {
            font-size: 15px;
            color: #4a4a4a;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 14px 40px;
            background-color: #f0f0f0;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            border: 1px solid #d0d0d0;
            transition: all 0.3s ease;
        }
        .verify-button:hover {
            background-color: #e0e0e0;
            border-color: #b0b0b0;
        }
        .link-text {
            font-size: 13px;
            color: #666666;
            margin-top: 20px;
            word-break: break-all;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .disclaimer {
            font-size: 13px;
            color: #666666;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #e5e5e5;
        }
        @media only screen and (max-width: 600px) {
            .email-content {
                padding: 20px 15px;
            }
            .greeting {
                font-size: 16px;
            }
            .message {
                font-size: 14px;
            }
            .verify-button {
                padding: 12px 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h2 style="margin: 0; color: #1a1a1a; font-size: 20px;">Magang BKKKB Yogyakarta</h2>
        </div>
        
        <div class="email-content">
            <div class="greeting">
                Halo, Calon Peserta Magang!
            </div>
            
            <div class="message">
                Terima kasih telah mendaftar di sistem Magang Balai Besar Kerajinan & Batik Yogyakarta.
            </div>
            
            <div class="message">
                Mohon klik tombol di bawah ini untuk memverifikasi alamat email Anda agar akun Anda aktif.
            </div>
            
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    Verifikasi Email Saya
                </a>
            </div>
            
            <div class="link-text">
                Atau salin link berikut ke browser Anda:<br>
                <span style="color: #2563eb;">{{ $verificationUrl }}</span>
            </div>
            
            <div class="disclaimer">
                <p style="margin: 0;">Jika Anda merasa tidak mendaftar akun ini, silakan abaikan email ini.</p>
            </div>
        </div>
        
        <div class="email-footer">
            <p style="margin: 5px 0;">© {{ date('Y') }} Balai Besar Kerajinan dan Batik Yogyakarta</p>
            <p style="margin: 5px 0;">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>

