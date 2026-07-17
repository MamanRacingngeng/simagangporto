<?php
/**
 * Verifikasi Setup Google OAuth
 * File ini untuk memverifikasi bahwa semua konfigurasi sudah benar
 */

require_once __DIR__ . '/google_config.php';

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Verifikasi Setup Google OAuth</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 20px; 
            max-width: 1000px; 
            margin: 0 auto; 
            background: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .success { 
            background: #d4edda; 
            border: 1px solid #c3e6cb; 
            color: #155724; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 15px 0; 
        }
        .error { 
            background: #f8d7da; 
            border: 1px solid #f5c6cb; 
            color: #721c24; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 15px 0; 
        }
        .warning { 
            background: #fff3cd; 
            border: 1px solid #ffc107; 
            color: #856404; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 15px 0; 
        }
        .info { 
            background: #d1ecf1; 
            border: 1px solid #bee5eb; 
            color: #0c5460; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 15px 0; 
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin: 20px 0; 
            background: white;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #667eea; 
            color: white; 
            font-weight: 600;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        code { 
            background: #f4f4f4; 
            padding: 3px 8px; 
            border-radius: 3px; 
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        .status-icon {
            font-size: 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Verifikasi Setup Google OAuth</h1>";

// Check configuration
$allOk = true;
$warnings = [];
$errors = [];

// 1. Check Client ID
if (empty($googleConfig['client_id'])) {
    $errors[] = "Client ID tidak ditemukan";
    $allOk = false;
} else {
    // Validasi format Client ID (harus berakhiran .apps.googleusercontent.com)
    if (!empty($googleConfig['client_id']) && !str_contains($googleConfig['client_id'], '.apps.googleusercontent.com')) {
        $warnings[] = "Format Client ID tidak valid. Pastikan Client ID berakhiran .apps.googleusercontent.com";
    }
}

// 2. Check Client Secret
if (empty($googleConfig['client_secret'])) {
    $errors[] = "Client Secret tidak ditemukan";
    $allOk = false;
}

// 3. Check Redirect URI
if (empty($googleConfig['redirect_uri'])) {
    $errors[] = "Redirect URI tidak ditemukan";
    $allOk = false;
}

// Display configuration table
echo "<h2>📋 Konfigurasi Saat Ini</h2>";
echo "<table>";
echo "<tr><th>Item</th><th>Value</th><th>Status</th></tr>";

$clientIdDisplay = !empty($googleConfig['client_id']) ? substr($googleConfig['client_id'], 0, 60) . '...' : 'TIDAK DIISI';
$clientIdStatus = !empty($googleConfig['client_id']) ? '<span class="status-icon">✅</span>OK' : '<span class="status-icon">❌</span>TIDAK DIISI';
echo "<tr><td><strong>Client ID</strong></td><td><code>$clientIdDisplay</code></td><td>$clientIdStatus</td></tr>";

$clientSecretDisplay = !empty($googleConfig['client_secret']) ? substr($googleConfig['client_secret'], 0, 25) . '...' : 'TIDAK DIISI';
$clientSecretStatus = !empty($googleConfig['client_secret']) ? '<span class="status-icon">✅</span>OK' : '<span class="status-icon">❌</span>TIDAK DIISI';
echo "<tr><td><strong>Client Secret</strong></td><td><code>$clientSecretDisplay</code></td><td>$clientSecretStatus</td></tr>";

$redirectUriStatus = !empty($googleConfig['redirect_uri']) ? '<span class="status-icon">✅</span>OK' : '<span class="status-icon">❌</span>TIDAK DIISI';
echo "<tr><td><strong>Redirect URI</strong></td><td><code>{$googleConfig['redirect_uri']}</code></td><td>$redirectUriStatus</td></tr>";

echo "</table>";

// Display errors
if (!empty($errors)) {
    echo "<div class='error'>";
    echo "<h2>❌ Error yang Ditemukan:</h2><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
    $allOk = false;
}

// Display warnings
if (!empty($warnings)) {
    echo "<div class='warning'>";
    echo "<h2>⚠️ Peringatan:</h2><ul>";
    foreach ($warnings as $warning) {
        echo "<li>$warning</li>";
    }
    echo "</ul></div>";
}

// Display success message
if ($allOk && empty($warnings)) {
    echo "<div class='success'>";
    echo "<h2>✅ Konfigurasi Sudah Benar!</h2>";
    echo "<p><strong>Semua konfigurasi sudah lengkap dan siap digunakan.</strong></p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h2>📝 Checklist Sebelum Login:</h2>";
    echo "<ol style='line-height: 2;'>";
    echo "<li>✅ Client ID sudah dikonfigurasi: <code>" . substr($googleConfig['client_id'], 0, 50) . "...</code></li>";
    echo "<li>✅ Client Secret sudah dikonfigurasi</li>";
    echo "<li>✅ Redirect URI: <code>{$googleConfig['redirect_uri']}</code></li>";
    echo "<li>⚠️ <strong>PENTING:</strong> Pastikan redirect URI di atas sudah ditambahkan di Google Cloud Console sebagai <strong>Authorized redirect URI</strong></li>";
    echo "<li>⚠️ Pastikan OAuth consent screen sudah dikonfigurasi di Google Cloud Console</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin-top: 30px;'>";
    echo "<a href='index.php' class='btn btn-success'>🚀 Coba Login Sekarang</a>";
    echo "<a href='test_google_config.php' class='btn'>🔧 Test Lengkap</a>";
    echo "</div>";
} else {
    echo "<div class='info'>";
    echo "<h2>📖 Langkah Perbaikan:</h2>";
    echo "<ol style='line-height: 2;'>";
    if (empty($googleConfig['client_id']) || empty($googleConfig['client_secret'])) {
        echo "<li>Edit file <code>.env</code> di root project dan pastikan ada:</li>";
        echo "<pre style='background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto;'>GOOGLE_CLIENT_ID=YOUR_CLIENT_ID.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=YOUR_CLIENT_SECRET</pre>";
        echo "<p><strong>⚠️ PENTING:</strong> Ganti YOUR_CLIENT_ID dan YOUR_CLIENT_SECRET dengan credentials yang Anda dapatkan dari Google Cloud Console.</p>";
    }
    echo "<li>Pastikan redirect URI <code>{$googleConfig['redirect_uri']}</code> sudah ditambahkan di Google Cloud Console</li>";
    echo "<li>Lihat file <code>SETUP_OAUTH_CLIENT.md</code> untuk panduan lengkap</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<hr style='margin: 30px 0;'>";
echo "<div style='text-align: center;'>";
echo "<a href='index.php' class='btn'>🏠 Halaman Utama</a>";
echo "<a href='check_config.php' class='btn'>🔍 Quick Check</a>";
echo "<a href='test_google_config.php' class='btn'>🧪 Test Lengkap</a>";
echo "</div>";

echo "</div></body></html>";

