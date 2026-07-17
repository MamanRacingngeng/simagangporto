<?php
/**
 * Update Google OAuth Client ID ke yang baru (SIMAGANG)
 * Akses via browser: http://127.0.0.1:8000/update-oauth-client.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/html; charset=utf-8');

$envPath = __DIR__ . '/../.env';
$message = '';
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = trim($_POST['client_id'] ?? '');
    $clientSecret = trim($_POST['client_secret'] ?? '');
    
    if (empty($clientId) || empty($clientSecret)) {
        $error = 'Client ID dan Client Secret tidak boleh kosong!';
    } else {
        if (!file_exists($envPath)) {
            $error = 'File .env tidak ditemukan!';
        } else {
            $envContent = file_get_contents($envPath);
            
            // Update atau tambahkan GOOGLE_CLIENT_ID
            if (preg_match('/^GOOGLE_CLIENT_ID=(.*)$/m', $envContent)) {
                $envContent = preg_replace('/^GOOGLE_CLIENT_ID=.*$/m', 'GOOGLE_CLIENT_ID=' . $clientId, $envContent);
            } else {
                $envContent .= "\nGOOGLE_CLIENT_ID=" . $clientId . "\n";
            }
            
            // Update atau tambahkan GOOGLE_CLIENT_SECRET
            if (preg_match('/^GOOGLE_CLIENT_SECRET=(.*)$/m', $envContent)) {
                $envContent = preg_replace('/^GOOGLE_CLIENT_SECRET=.*$/m', 'GOOGLE_CLIENT_SECRET=' . $clientSecret, $envContent);
            } else {
                $envContent .= "\nGOOGLE_CLIENT_SECRET=" . $clientSecret . "\n";
            }
            
            if (file_put_contents($envPath, $envContent)) {
                $success = true;
                $message = 'Konfigurasi berhasil diperbarui!';
                
                // Clear config cache
                try {
                    \Artisan::call('config:clear');
                } catch (\Exception $e) {
                    // Ignore
                }
            } else {
                $error = 'Gagal menyimpan file .env. Pastikan file memiliki permission write.';
            }
        }
    }
}

// Baca konfigurasi saat ini
$currentClientId = '';
$currentClientSecret = '';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    if (preg_match('/^GOOGLE_CLIENT_ID=(.+)$/m', $envContent, $matches)) {
        $currentClientId = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
    if (preg_match('/^GOOGLE_CLIENT_SECRET=(.+)$/m', $envContent, $matches)) {
        $currentClientSecret = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Google OAuth Client ID</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
        }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 14px; }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Courier New', monospace;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: background 0.3s;
        }
        .btn:hover { background: #5568d3; }
        .current-config {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .current-config h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .current-config code {
            display: block;
            padding: 8px;
            background: white;
            border-radius: 3px;
            margin: 5px 0;
            font-size: 12px;
            word-break: break-all;
        }
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .steps ol {
            margin-left: 20px;
        }
        .steps li {
            margin: 10px 0;
            line-height: 1.6;
        }
        .link-btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .link-btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Update Google OAuth Client ID</h1>
        <p class="subtitle">Update ke OAuth Client baru "SIMAGANG"</p>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <strong>✅ Berhasil!</strong> <?php echo $message; ?>
                <br><br>
                <a href="/test-oauth.php" class="link-btn">Test Konfigurasi</a>
                <a href="/oauth/google" class="link-btn" style="background: #667eea;">Test Login Google</a>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <strong>❌ Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <strong>📋 Cara mendapatkan Client ID dan Secret baru:</strong>
            <ol style="margin-top: 10px; margin-left: 20px;">
                <li>Buka <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console - Credentials</a></li>
                <li>Klik pada OAuth Client ID "SIMAGANG" (yang terbaru)</li>
                <li>Copy <strong>Client ID</strong> dan <strong>Client Secret</strong></li>
                <li>Paste di form di bawah ini</li>
            </ol>
        </div>

        <?php if ($currentClientId): ?>
        <div class="current-config">
            <h3>Konfigurasi Saat Ini:</h3>
            <strong>Client ID:</strong>
            <code><?php echo htmlspecialchars($currentClientId); ?></code>
            <strong>Client Secret:</strong>
            <code><?php echo htmlspecialchars(substr($currentClientSecret, 0, 20)) . '...'; ?></code>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="client_id">GOOGLE_CLIENT_ID (dari OAuth Client "SIMAGANG"):</label>
                <input type="text" id="client_id" name="client_id" 
                       placeholder="Contoh: 123456789-abcdefghijklmnop.apps.googleusercontent.com"
                       value="<?php echo htmlspecialchars($_POST['client_id'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="client_secret">GOOGLE_CLIENT_SECRET (dari OAuth Client "SIMAGANG"):</label>
                <input type="text" id="client_secret" name="client_secret" 
                       placeholder="Contoh: GOCSPX-xxxxxxxxxxxxx"
                       value="<?php echo htmlspecialchars($_POST['client_secret'] ?? ''); ?>" required>
            </div>

            <button type="submit" class="btn">💾 Update Konfigurasi</button>
        </form>

        <div class="steps" style="margin-top: 30px;">
            <h3>⚠️ Setelah Update:</h3>
            <ol>
                <li>Pastikan redirect URI sudah ditambahkan di OAuth Client "SIMAGANG":<br>
                    <code>http://127.0.0.1:8000/oauth/google/callback</code><br>
                    <code>http://localhost:8000/oauth/google/callback</code>
                </li>
                <li>Clear config cache: <code>php artisan config:clear</code></li>
                <li>Test login dengan Google</li>
            </ol>
        </div>
    </div>
</body>
</html>

