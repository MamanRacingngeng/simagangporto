<?php
/**
 * Konfigurasi Google OAuth
 * Membaca dari .env file di root project Laravel atau menggunakan default
 */

// Cek apakah ada file .env di root project (untuk integrasi dengan Laravel)
$envPath = __DIR__ . '/../.env';
$googleConfig = [
    'client_id' => '',
    'client_secret' => '',
    'redirect_uri' => ''
];

// Tentukan redirect URI berdasarkan environment
// Deteksi apakah menggunakan localhost atau 127.0.0.1
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '/login-google');
$redirectUri = $protocol . '://' . $host . $scriptPath . '/login.php';

if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    
    // Parse GOOGLE_CLIENT_ID
    if (preg_match('/^GOOGLE_CLIENT_ID=(.*)$/m', $envContent, $matches)) {
        $googleConfig['client_id'] = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    
    // Parse GOOGLE_CLIENT_SECRET
    if (preg_match('/^GOOGLE_CLIENT_SECRET=(.*)$/m', $envContent, $matches)) {
        $googleConfig['client_secret'] = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    
    // Parse GOOGLE_REDIRECT_URI jika ada (untuk login_google khusus)
    if (preg_match('/^GOOGLE_LOGIN_REDIRECT_URI=(.*)$/m', $envContent, $matches)) {
        $redirectUri = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
}

// Jika tidak ada di .env, tampilkan error yang jelas
if (empty($googleConfig['client_id']) || empty($googleConfig['client_secret'])) {
    // Jangan gunakan default yang sudah dihapus
    // User harus membuat Client ID baru di Google Cloud Console
    die('
    <html>
    <head>
        <title>Error: Google OAuth Configuration</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; }
            .error-box { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .info-box { background: #d1ecf1; border: 1px solid #bee5eb; padding: 20px; border-radius: 5px; margin: 20px 0; }
            h1 { color: #721c24; }
            h2 { color: #0c5460; }
            code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
            ol { line-height: 1.8; }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h1>❌ Error: Google OAuth Client ID Tidak Ditemukan</h1>
            <p>File <code>.env</code> tidak memiliki <code>GOOGLE_CLIENT_ID</code> dan <code>GOOGLE_CLIENT_SECRET</code> yang valid.</p>
        </div>
        
        <div class="info-box">
            <h2>📋 Cara Memperbaiki:</h2>
            <ol>
                <li>Buka <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a></li>
                <li>Pilih project Anda</li>
                <li>Pergi ke <strong>APIs & Services</strong> > <strong>Credentials</strong></li>
                <li>Klik <strong>+ CREATE CREDENTIALS</strong> > <strong>OAuth client ID</strong></li>
                <li>Pilih <strong>Web application</strong></li>
                <li>Tambahkan <strong>Authorized redirect URIs</strong>:
                    <ul>
                        <li><code>http://localhost/login-google/login.php</code></li>
                        <li><code>http://127.0.0.1/login-google/login.php</code></li>
                    </ul>
                </li>
                <li>Copy <strong>Client ID</strong> dan <strong>Client Secret</strong></li>
                <li>Edit file <code>.env</code> di root project dan tambahkan:
                    <pre>GOOGLE_CLIENT_ID=PASTE_CLIENT_ID_DISINI
GOOGLE_CLIENT_SECRET=PASTE_CLIENT_SECRET_DISINI</pre>
                </li>
                <li>Refresh halaman ini</li>
            </ol>
            <p><strong>📖 Lihat file <code>SETUP_OAUTH_CLIENT.md</code> untuk panduan lengkap</strong></p>
        </div>
    </body>
    </html>
    ');
}

// Set redirect URI
$googleConfig['redirect_uri'] = $redirectUri;

// Return config
return $googleConfig;

