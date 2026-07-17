<?php
/**
 * FIX SIMAGANG SEKARANG - Halaman web untuk update
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/html; charset=utf-8');

$currentId = config('services.google.client_id');
$currentSecret = config('services.google.client_secret');
$redirect = config('services.google.redirect');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newId = trim($_POST['client_id'] ?? '');
    $newSecret = trim($_POST['client_secret'] ?? '');
    
    if (empty($newId) || empty($newSecret)) {
        $error = 'Client ID dan Secret tidak boleh kosong!';
    } else {
        $envPath = __DIR__ . '/../.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $envContent = preg_replace('/^GOOGLE_CLIENT_ID=.*$/m', 'GOOGLE_CLIENT_ID=' . $newId, $envContent);
            $envContent = preg_replace('/^GOOGLE_CLIENT_SECRET=.*$/m', 'GOOGLE_CLIENT_SECRET=' . $newSecret, $envContent);
            
            if (file_put_contents($envPath, $envContent)) {
                \Artisan::call('config:clear');
                \Artisan::call('cache:clear');
                $message = '✅ Berhasil di-update! Clear cache dan test login lagi.';
            } else {
                $error = 'Gagal menyimpan file .env';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fix SIMAGANG Sekarang</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 800px; margin: 0 auto; background: #f5f5f5; }
        .box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .alert { padding: 15px; border-radius: 5px; margin: 15px 0; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .form-group { margin: 20px 0; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"] { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-family: monospace; }
        button { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #218838; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🔧 Fix SIMAGANG Sekarang</h1>
        
        <?php if ($message): ?>
            <div class="alert success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="alert warning">
            <strong>⚠️ MASALAH:</strong> Masih muncul "Simagang" berarti Client ID di .env masih dari OAuth Client LAMA atau OAuth Client "SIMAGANG" memiliki Client ID yang BERBEDA.
        </div>
        
        <h3>Langkah 1: Dapatkan Client ID dari OAuth Client "SIMAGANG"</h3>
        <ol>
            <li>Buka: <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console</a></li>
            <li>Klik OAuth Client <strong>"SIMAGANG"</strong> (yang baru)</li>
            <li>Copy <strong>Client ID</strong> dan <strong>Client Secret</strong></li>
        </ol>
        
        <h3>Langkah 2: Update di bawah ini</h3>
        <form method="POST">
            <div class="form-group">
                <label>Client ID saat ini:</label>
                <code><?php echo htmlspecialchars($currentId); ?></code>
            </div>
            
            <div class="form-group">
                <label>Client ID BARU dari OAuth Client "SIMAGANG":</label>
                <input type="text" name="client_id" placeholder="Paste Client ID dari SIMAGANG" required>
            </div>
            
            <div class="form-group">
                <label>Client Secret BARU dari OAuth Client "SIMAGANG":</label>
                <input type="text" name="client_secret" placeholder="Paste Client Secret dari SIMAGANG" required>
            </div>
            
            <button type="submit">💾 Update Sekarang</button>
        </form>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <h3>Setelah Update:</h3>
            <ol>
                <li>Pastikan redirect URI sudah ditambahkan di OAuth Client "SIMAGANG":
                    <ul>
                        <li><code><?php echo htmlspecialchars($redirect); ?></code></li>
                        <li><code><?php echo htmlspecialchars(str_replace('127.0.0.1', 'localhost', $redirect)); ?></code></li>
                    </ul>
                </li>
                <li>HAPUS OAuth Client lama "Simagang" di Google Cloud Console</li>
                <li>Test login: <a href="/oauth/google">http://127.0.0.1:8000/oauth/google</a></li>
            </ol>
        </div>
    </div>
</body>
</html>

