<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #DC2626;
        }
        .header h1 {
            color: #DC2626;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .content h2 {
            color: #1F2937;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .content p {
            color: #4B5563;
            margin-bottom: 15px;
        }
        .message-box {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .message-box p {
            margin: 0;
            color: #92400E;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #DC2626;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
        }
        .button:hover {
            background-color: #B91C1C;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SIMAGANG</h1>
            <p style="color: #6B7280; margin: 5px 0 0 0;">Sistem Informasi Magang</p>
        </div>
        
        <div class="content">
            <h2>{{ $judul }}</h2>
            
            <p>Halo, <strong>{{ $user->nama }}</strong>,</p>
            
            <div class="message-box">
                {!! nl2br(e($pesan)) !!}
            </div>
            
            <p>Silakan perbaiki dokumen sesuai catatan revisi di atas. Setelah dokumen diperbaiki, silakan unggah ulang melalui dashboard Anda. Status lamaran Anda akan berubah menjadi "Revisi" sampai dokumen yang diperbaiki telah diunggah ulang.</p>
            
            <p style="margin-top: 15px; color: #92400E; font-weight: 600;">⚠️ Penting: Pastikan semua dokumen yang diminta telah dilengkapi dengan benar sebelum mengunggah ulang.</p>
            
            <a href="{{ route('lamaran') }}" class="button" style="margin-top: 20px;">Buka Halaman Lamaran Saya</a>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem SIMAGANG.</p>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi administrator.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9CA3AF;">
                &copy; {{ date('Y') }} Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta
            </p>
        </div>
    </div>
</body>
</html>
