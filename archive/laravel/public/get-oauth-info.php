<?php
/**
 * Halaman untuk mendapatkan informasi OAuth Client dari Google Cloud Console
 * Akses: http://127.0.0.1:8000/get-oauth-info.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Mendapatkan OAuth Client ID Baru</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .step {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .step h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .step-number {
            display: inline-block;
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            margin-right: 10px;
        }
        .screenshot-placeholder {
            background: #e9ecef;
            padding: 40px;
            text-align: center;
            border-radius: 5px;
            margin: 15px 0;
            color: #666;
        }
        .highlight {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
        .code-block {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin: 10px 0;
            word-break: break-all;
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
        ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        li {
            margin: 8px 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Cara Mendapatkan OAuth Client ID dari "SIMAGANG"</h1>
        <p class="subtitle">Panduan lengkap untuk mendapatkan Client ID dan Secret dari OAuth Client baru</p>

        <div class="step">
            <h2><span class="step-number">1</span>Buka Google Cloud Console</h2>
            <p>Buka halaman credentials di Google Cloud Console:</p>
            <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="btn">
                🔗 Buka Google Cloud Console - Credentials
            </a>
        </div>

        <div class="step">
            <h2><span class="step-number">2</span>Cari OAuth Client "SIMAGANG"</h2>
            <p>Di halaman credentials, cari OAuth 2.0 Client ID dengan nama <strong>"SIMAGANG"</strong> (yang terbaru, dibuat Dec 17, 2025).</p>
            <div class="highlight">
                <strong>⚠️ Penting:</strong> Pastikan Anda memilih OAuth Client yang bernama <strong>"SIMAGANG"</strong>, bukan yang lama.
            </div>
        </div>

        <div class="step">
            <h2><span class="step-number">3</span>Klik pada OAuth Client "SIMAGANG"</h2>
            <p>Klik pada nama "SIMAGANG" atau ikon edit (pensil) untuk membuka detail OAuth Client.</p>
        </div>

        <div class="step">
            <h2><span class="step-number">4</span>Copy Client ID dan Client Secret</h2>
            <p>Di halaman detail OAuth Client, Anda akan melihat:</p>
            <ul>
                <li><strong>Client ID:</strong> Format seperti <code>123456789-abcdefghijklmnop.apps.googleusercontent.com</code></li>
                <li><strong>Client secret:</strong> Format seperti <code>GOCSPX-xxxxxxxxxxxxx</code></li>
            </ul>
            <div class="highlight">
                <strong>💡 Tips:</strong> Klik ikon copy di sebelah Client ID dan Client Secret untuk menyalin dengan mudah.
            </div>
        </div>

        <div class="step">
            <h2><span class="step-number">5</span>Update di Aplikasi</h2>
            <p>Setelah mendapatkan Client ID dan Secret, update di aplikasi menggunakan salah satu cara berikut:</p>
            
            <h3 style="margin-top: 15px;">Cara 1: Menggunakan Halaman Web (Paling Mudah)</h3>
            <a href="/update-oauth-client.php" class="btn btn-success">
                🔄 Update via Web Form
            </a>
            
            <h3 style="margin-top: 20px;">Cara 2: Menggunakan Script CLI</h3>
            <div class="code-block">
                php update_oauth_env.php
            </div>
            
            <h3 style="margin-top: 20px;">Cara 3: Edit File .env Manual</h3>
            <p>Edit file <code>.env</code> di root project dan update:</p>
            <div class="code-block">
GOOGLE_CLIENT_ID=PASTE_CLIENT_ID_DISINI<br>
GOOGLE_CLIENT_SECRET=PASTE_CLIENT_SECRET_DISINI
            </div>
        </div>

        <div class="step">
            <h2><span class="step-number">6</span>Pastikan Redirect URI Sudah Ditambahkan</h2>
            <p>Di halaman detail OAuth Client "SIMAGANG", scroll ke bagian <strong>"Authorized redirect URIs"</strong> dan pastikan sudah ada:</p>
            <div class="code-block">
http://127.0.0.1:8000/oauth/google/callback<br>
http://localhost:8000/oauth/google/callback
            </div>
            <p style="margin-top: 10px;">Jika belum ada, klik <strong>"ADD URI"</strong> dan tambahkan kedua URI di atas, lalu klik <strong>"SAVE"</strong>.</p>
        </div>

        <div class="step">
            <h2><span class="step-number">7</span>Clear Config Cache</h2>
            <p>Setelah update, clear config cache Laravel:</p>
            <div class="code-block">
                php artisan config:clear
            </div>
        </div>

        <div class="step">
            <h2><span class="step-number">8</span>Test Login</h2>
            <p>Setelah semua konfigurasi selesai, test login dengan Google:</p>
            <a href="/test-oauth.php" class="btn">🧪 Test Konfigurasi</a>
            <a href="/oauth/google" class="btn">🔐 Test Login Google</a>
        </div>

        <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #ddd;">
            <h3>📝 Checklist:</h3>
            <ul style="list-style: none; margin-left: 0;">
                <li>☐ OAuth Client "SIMAGANG" sudah dibuat di Google Cloud Console</li>
                <li>☐ Client ID dan Secret sudah di-copy dari OAuth Client "SIMAGANG"</li>
                <li>☐ File .env sudah di-update dengan Client ID dan Secret baru</li>
                <li>☐ Redirect URI sudah ditambahkan di OAuth Client "SIMAGANG"</li>
                <li>☐ Config cache sudah di-clear</li>
                <li>☐ Login dengan Google sudah di-test</li>
            </ul>
        </div>
    </div>
</body>
</html>

