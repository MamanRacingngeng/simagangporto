<?php
/**
 * Test Konfigurasi Google OAuth
 * Akses file ini untuk memverifikasi konfigurasi Google OAuth
 */

require_once __DIR__ . '/google_config.php';

echo "<h2>Test Konfigurasi Google OAuth</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .success { color: green; background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .error { color: red; background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; }
    .info { color: #004085; background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

echo "<div class='info'>";
echo "<h3>Konfigurasi Google OAuth</h3>";
echo "<table>";
echo "<tr><th>Item</th><th>Value</th><th>Status</th></tr>";

// Test Client ID
$clientIdStatus = !empty($googleConfig['client_id']) ? '✅ OK' : '❌ TIDAK DIISI';
$clientIdDisplay = !empty($googleConfig['client_id']) ? substr($googleConfig['client_id'], 0, 50) . '...' : 'TIDAK DIISI';
echo "<tr><td>Client ID</td><td>$clientIdDisplay</td><td>$clientIdStatus</td></tr>";

// Test Client Secret
$clientSecretStatus = !empty($googleConfig['client_secret']) ? '✅ OK' : '❌ TIDAK DIISI';
$clientSecretDisplay = !empty($googleConfig['client_secret']) ? substr($googleConfig['client_secret'], 0, 20) . '...' : 'TIDAK DIISI';
echo "<tr><td>Client Secret</td><td>$clientSecretDisplay</td><td>$clientSecretStatus</td></tr>";

// Test Redirect URI
$redirectUriStatus = !empty($googleConfig['redirect_uri']) ? '✅ OK' : '❌ TIDAK DIISI';
echo "<tr><td>Redirect URI</td><td>{$googleConfig['redirect_uri']}</td><td>$redirectUriStatus</td></tr>";

echo "</table>";
echo "</div>";

// Validasi
$errors = [];
$warnings = [];

if (empty($googleConfig['client_id'])) {
    $errors[] = "Client ID tidak diisi";
}

if (empty($googleConfig['client_secret'])) {
    $errors[] = "Client Secret tidak diisi";
}

if (empty($googleConfig['redirect_uri'])) {
    $errors[] = "Redirect URI tidak diisi";
}

// Cek apakah Client ID terlihat valid
if (!empty($googleConfig['client_id']) && !str_contains($googleConfig['client_id'], '.apps.googleusercontent.com')) {
    $warnings[] = "Client ID tidak terlihat valid (harus berakhiran .apps.googleusercontent.com)";
}

// Cek apakah Client ID terlihat seperti placeholder
if (str_contains($googleConfig['client_id'], 'YOUR_GOOGLE_CLIENT_ID') || 
    str_contains($googleConfig['client_id'], 'PASTE') ||
    empty($googleConfig['client_id'])) {
    $errors[] = "⚠️ Client ID belum dikonfigurasi! Edit file .env dan tambahkan GOOGLE_CLIENT_ID";
}

// Tampilkan errors
if (!empty($errors)) {
    echo "<div class='error'>";
    echo "<h3>❌ Error:</h3><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
}

// Tampilkan warnings
if (!empty($warnings)) {
    echo "<div class='error'>";
    echo "<h3>⚠️ Peringatan:</h3><ul>";
    foreach ($warnings as $warning) {
        echo "<li>$warning</li>";
    }
    echo "</ul></div>";
}

// Jika tidak ada error
if (empty($errors) && empty($warnings)) {
    echo "<div class='success'>";
    echo "<h3>✅ Konfigurasi Google OAuth Valid!</h3>";
    echo "<p>Semua konfigurasi sudah benar. Pastikan:</p>";
    echo "<ol>";
    echo "<li>Redirect URI di Google Cloud Console sama dengan: <strong>{$googleConfig['redirect_uri']}</strong></li>";
    echo "<li>Client ID di Google Cloud Console sama dengan yang digunakan</li>";
    echo "<li>OAuth consent screen sudah dikonfigurasi</li>";
    echo "</ol>";
    echo "</div>";
}

// Test koneksi ke Google (optional)
echo "<div class='info'>";
echo "<h3>Informasi Tambahan</h3>";
echo "<p><strong>File .env:</strong> " . (file_exists(__DIR__ . '/../.env') ? '✅ Ditemukan' : '❌ Tidak ditemukan') . "</p>";
echo "<p><strong>Library Google API:</strong> " . (file_exists(__DIR__ . '/vendor/autoload.php') ? '✅ Terinstall' : '❌ Tidak terinstall') . "</p>";
echo "</div>";

echo "<hr>";
echo "<p><a href='index.php'>Kembali ke Halaman Utama</a> | <a href='test_db.php'>Test Database</a></p>";

