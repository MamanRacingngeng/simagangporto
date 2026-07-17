<?php
/**
 * Quick Check Configuration
 * File ini untuk memeriksa konfigurasi dengan cepat
 */

echo "<h2>🔍 Quick Configuration Check</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 900px; margin: 0 auto; }
    .ok { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
    .box { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .success { background: #d4edda; border-color: #c3e6cb; }
    .danger { background: #f8d7da; border-color: #f5c6cb; }
    .info { background: #d1ecf1; border-color: #bee5eb; }
</style>";

// Check .env file
$envPath = __DIR__ . '/../.env';
$envExists = file_exists($envPath);

echo "<div class='box " . ($envExists ? 'success' : 'danger') . "'>";
echo "<h3>" . ($envExists ? "✅" : "❌") . " File .env</h3>";
echo "<p>Path: <code>$envPath</code></p>";
echo "<p>Status: " . ($envExists ? "Ditemukan" : "TIDAK DITEMUKAN") . "</p>";
echo "</div>";

if ($envExists) {
    $envContent = file_get_contents($envPath);
    
    // Check GOOGLE_CLIENT_ID
    $hasClientId = preg_match('/^GOOGLE_CLIENT_ID=(.+)$/m', $envContent, $clientIdMatch);
    $clientId = $hasClientId ? trim($clientIdMatch[1], " \t\n\r\0\x0B\"'") : '';
    
    // Check GOOGLE_CLIENT_SECRET
    $hasClientSecret = preg_match('/^GOOGLE_CLIENT_SECRET=(.+)$/m', $envContent, $clientSecretMatch);
    $clientSecret = $hasClientSecret ? trim($clientSecretMatch[1], " \t\n\r\0\x0B\"'") : '';
    
    echo "<div class='box " . ($hasClientId && !empty($clientId) ? 'success' : 'danger') . "'>";
    echo "<h3>" . ($hasClientId && !empty($clientId) ? "✅" : "❌") . " GOOGLE_CLIENT_ID</h3>";
    if ($hasClientId && !empty($clientId)) {
        echo "<p>Value: <code>" . substr($clientId, 0, 50) . "...</code></p>";
        // Validasi format Client ID
        if (str_contains($clientId, '.apps.googleusercontent.com')) {
            echo "<p class='ok'><strong>✅ Format Client ID Valid:</strong> Format Client ID sudah benar.</p>";
        } else {
            echo "<p class='error'><strong>⚠️ Format Client ID Tidak Valid:</strong> Client ID harus berakhiran .apps.googleusercontent.com</p>";
        }
    } else {
        echo "<p class='error'>Tidak ditemukan atau kosong</p>";
    }
    echo "</div>";
    
    echo "<div class='box " . ($hasClientSecret && !empty($clientSecret) ? 'success' : 'danger') . "'>";
    echo "<h3>" . ($hasClientSecret && !empty($clientSecret) ? "✅" : "❌") . " GOOGLE_CLIENT_SECRET</h3>";
    if ($hasClientSecret && !empty($clientSecret)) {
        echo "<p>Value: <code>" . substr($clientSecret, 0, 20) . "...</code></p>";
    } else {
        echo "<p class='error'>Tidak ditemukan atau kosong</p>";
    }
    echo "</div>";
    
    // Check redirect URI
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
                  '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . 
                  dirname($_SERVER['SCRIPT_NAME'] ?? '/login-google') . 
                  '/login.php';
    
    echo "<div class='box info'>";
    echo "<h3>ℹ️ Redirect URI yang Digunakan</h3>";
    echo "<p><code>$currentUrl</code></p>";
    echo "<p><strong>PENTING:</strong> Pastikan URI ini sudah ditambahkan di Google Cloud Console sebagai Authorized redirect URI</p>";
    echo "</div>";
    
    // Summary
    $allOk = $hasClientId && !empty($clientId) && $hasClientSecret && !empty($clientSecret) && 
             str_contains($clientId, '.apps.googleusercontent.com');
    
    echo "<div class='box " . ($allOk ? 'success' : 'danger') . "'>";
    echo "<h3>" . ($allOk ? "✅" : "❌") . " Status Konfigurasi</h3>";
    if ($allOk) {
        echo "<p class='ok'><strong>Konfigurasi sudah benar!</strong></p>";
        echo "<p>Langkah selanjutnya:</p>";
        echo "<ol>";
        echo "<li>Pastikan redirect URI di Google Cloud Console sama dengan yang ditampilkan di atas</li>";
        echo "<li><a href='index.php'>Coba login</a></li>";
        echo "</ol>";
    } else {
        echo "<p class='error'><strong>Konfigurasi belum lengkap atau ada masalah!</strong></p>";
        echo "<p>Langkah perbaikan:</p>";
        echo "<ol>";
        if (!$hasClientId || empty($clientId)) {
            echo "<li>Tambahkan <code>GOOGLE_CLIENT_ID</code> di file .env</li>";
        }
        if (!$hasClientSecret || empty($clientSecret)) {
            echo "<li>Tambahkan <code>GOOGLE_CLIENT_SECRET</code> di file .env</li>";
        }
        if ($hasClientId && !empty($clientId) && !str_contains($clientId, '.apps.googleusercontent.com')) {
            echo "<li>Pastikan Client ID yang Anda masukkan berakhiran .apps.googleusercontent.com</li>";
        }
        echo "<li>Lihat file <code>SETUP_OAUTH_CLIENT.md</code> untuk panduan lengkap</li>";
        echo "</ol>";
    }
    echo "</div>";
} else {
    echo "<div class='box danger'>";
    echo "<h3>❌ File .env Tidak Ditemukan</h3>";
    echo "<p>File .env harus ada di: <code>$envPath</code></p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php'>Kembali ke Halaman Utama</a> | <a href='test_google_config.php'>Test Lengkap</a> | <a href='SETUP_OAUTH_CLIENT.md'>Panduan Setup</a></p>";

