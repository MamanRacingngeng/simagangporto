<?php
/**
 * Halaman untuk memverifikasi OAuth Client yang digunakan
 * Akses: http://127.0.0.1:8000/check-oauth-client.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/html; charset=utf-8');

$clientId = config('services.google.client_id');
$clientSecret = config('services.google.client_secret');
$redirectUri = config('services.google.redirect');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OAuth Client</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
        }
        .config-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .config-item {
            margin: 10px 0;
        }
        .config-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .config-value {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            word-break: break-all;
            background: white;
            padding: 8px;
            border-radius: 3px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            transition: background 0.3s;
        }
        .btn:hover { background: #5568d3; }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover { background: #218838; }
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .steps ol {
            margin-left: 20px;
        }
        .steps li {
            margin: 10px 0;
            line-height: 1.6;
        }
        .highlight {
            background: #fff3cd;
            padding: 10px;
            border-radius: 3px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Verifikasi OAuth Client</h1>
        <p class="subtitle">Memastikan menggunakan OAuth Client "SIMAGANG" yang benar</p>

        <div class="alert alert-warning">
            <strong>⚠️ PENTING:</strong> Client ID yang ada di .env saat ini adalah:<br>
            <code><?php echo htmlspecialchars($clientId); ?></code><br><br>
            <strong>Pastikan Client ID ini BENAR-BENAR dari OAuth Client "SIMAGANG" yang baru, bukan dari OAuth Client lama "Simagang".</strong>
        </div>

        <div class="config-box">
            <h3>Konfigurasi Saat Ini:</h3>
            <div class="config-item">
                <div class="config-label">Client ID:</div>
                <div class="config-value"><?php echo htmlspecialchars($clientId); ?></div>
            </div>
            <div class="config-item">
                <div class="config-label">Client Secret:</div>
                <div class="config-value"><?php echo htmlspecialchars(substr($clientSecret, 0, 20)) . '...'; ?></div>
            </div>
            <div class="config-item">
                <div class="config-label">Redirect URI:</div>
                <div class="config-value"><?php echo htmlspecialchars($redirectUri); ?></div>
            </div>
        </div>

        <div class="alert alert-info">
            <strong>📋 Cara Memverifikasi:</strong>
            <ol style="margin-top: 10px; margin-left: 20px;">
                <li>Buka <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console - Credentials</a></li>
                <li>Cari OAuth Client dengan nama <strong>"SIMAGANG"</strong> (yang terbaru, Dec 17, 2025)</li>
                <li>Klik pada OAuth Client "SIMAGANG" untuk melihat detail</li>
                <li>Bandingkan Client ID di Google Cloud Console dengan Client ID di atas</li>
                <li>Jika <strong>BERBEDA</strong>, berarti Anda perlu update file .env dengan Client ID yang benar</li>
            </ol>
        </div>

        <div class="steps">
            <h3>🔧 Jika Client ID Berbeda:</h3>
            <ol>
                <li>Copy Client ID dan Client Secret dari OAuth Client "SIMAGANG" di Google Cloud Console</li>
                <li>Update file .env menggunakan salah satu cara:
                    <ul style="margin-top: 10px;">
                        <li><a href="/update-oauth-client.php" class="btn btn-success" style="padding: 8px 15px; font-size: 14px;">Update via Web Form</a></li>
                        <li>Atau jalankan: <code>php update_oauth_env.php</code></li>
                    </ul>
                </li>
                <li>Pastikan redirect URI sudah ditambahkan di OAuth Client "SIMAGANG":
                    <div class="highlight">
                        <code><?php echo htmlspecialchars($redirectUri); ?></code><br>
                        <code><?php echo htmlspecialchars(str_replace('127.0.0.1', 'localhost', $redirectUri)); ?></code>
                    </div>
                </li>
                <li>Clear config cache: <code>php artisan config:clear</code></li>
                <li>Test login dengan Google</li>
            </ol>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="btn">
                🔗 Buka Google Cloud Console
            </a>
            <a href="/update-oauth-client.php" class="btn btn-success">
                🔄 Update OAuth Client
            </a>
            <a href="/test-oauth.php" class="btn">
                🧪 Test Konfigurasi
            </a>
        </div>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd; color: #666; font-size: 12px;">
            <p><strong>💡 Tips:</strong></p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Nama OAuth Client di Google Cloud Console harus <strong>"SIMAGANG"</strong> (huruf besar semua)</li>
                <li>Jika masih muncul "Simagang" saat login, berarti masih menggunakan OAuth Client lama</li>
                <li>Pastikan Anda menggunakan Client ID dari OAuth Client yang BENAR di Google Cloud Console</li>
            </ul>
        </div>
    </div>
</body>
</html>

