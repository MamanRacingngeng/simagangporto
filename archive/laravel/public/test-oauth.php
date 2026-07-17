<?php
/**
 * Test Google OAuth Configuration
 * Akses via browser: http://127.0.0.1:8000/test-oauth.php
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
    <title>Test Google OAuth</title>
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
        h1 { color: #333; margin-bottom: 30px; }
        .test-item {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #ddd;
        }
        .test-item.success {
            background: #d4edda;
            border-left-color: #28a745;
        }
        .test-item.error {
            background: #f8d7da;
            border-left-color: #dc3545;
        }
        .test-item.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        .test-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .test-value {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            word-break: break-all;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover { background: #5568d3; }
        .status-icon {
            font-size: 20px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Google OAuth Configuration</h1>

        <?php
        $appUrl = config('app.url');
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');
        
        $allOk = true;
        ?>

        <div class="test-item <?php echo $appUrl ? 'success' : 'error'; ?>">
            <div class="test-label">
                <span class="status-icon"><?php echo $appUrl ? '✅' : '❌'; ?></span>
                APP_URL
            </div>
            <div class="test-value"><?php echo $appUrl ?: 'TIDAK DIISI'; ?></div>
        </div>

        <div class="test-item <?php echo $clientId ? 'success' : 'error'; ?>">
            <div class="test-label">
                <span class="status-icon"><?php echo $clientId ? '✅' : '❌'; ?></span>
                GOOGLE_CLIENT_ID
            </div>
            <div class="test-value"><?php echo $clientId ? substr($clientId, 0, 50) . '...' : 'TIDAK DIISI'; ?></div>
        </div>

        <div class="test-item <?php echo $clientSecret ? 'success' : 'error'; ?>">
            <div class="test-label">
                <span class="status-icon"><?php echo $clientSecret ? '✅' : '❌'; ?></span>
                GOOGLE_CLIENT_SECRET
            </div>
            <div class="test-value"><?php echo $clientSecret ? 'DIISI (tersembunyi)' : 'TIDAK DIISI'; ?></div>
        </div>

        <div class="test-item <?php echo $redirectUri ? 'success' : 'error'; ?>">
            <div class="test-label">
                <span class="status-icon"><?php echo $redirectUri ? '✅' : '❌'; ?></span>
                REDIRECT URI
            </div>
            <div class="test-value"><?php echo $redirectUri ?: 'TIDAK DIISI'; ?></div>
        </div>

        <?php
        // Test route availability
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $hasRedirectRoute = $routes->has('oauth.google.redirect');
        $hasCallbackRoute = $routes->has('oauth.google.callback');
        ?>

        <div class="test-item <?php echo $hasRedirectRoute ? 'success' : 'error'; ?>">
            <div class="test-label">
                <span class="status-icon"><?php echo $hasRedirectRoute ? '✅' : '❌'; ?></span>
                Route: oauth.google.redirect
            </div>
            <div class="test-value"><?php echo $hasRedirectRoute ? 'Tersedia' : 'Tidak ditemukan'; ?></div>
        </div>

        <div class="test-item <?php echo $hasCallbackRoute ? 'success' : 'error'; ?>">
            <div class="test-label">
                <span class="status-icon"><?php echo $hasCallbackRoute ? '✅' : '❌'; ?></span>
                Route: oauth.google.callback
            </div>
            <div class="test-value"><?php echo $hasCallbackRoute ? 'Tersedia' : 'Tidak ditemukan'; ?></div>
        </div>

        <?php
        if ($appUrl && $clientId && $clientSecret && $redirectUri && $hasRedirectRoute && $hasCallbackRoute) {
            $allOk = true;
        } else {
            $allOk = false;
        }
        ?>

        <div class="test-item <?php echo $allOk ? 'success' : 'error'; ?>" style="margin-top: 30px;">
            <div class="test-label">
                <span class="status-icon"><?php echo $allOk ? '✅' : '❌'; ?></span>
                Status Keseluruhan
            </div>
            <div class="test-value">
                <?php if ($allOk): ?>
                    <strong>Semua konfigurasi sudah benar! OAuth siap digunakan.</strong>
                <?php else: ?>
                    <strong>Masih ada konfigurasi yang belum lengkap.</strong>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($allOk): ?>
        <div style="margin-top: 30px; text-align: center;">
            <a href="/oauth/google" class="btn">🔐 Test Login dengan Google</a>
            <br><br>
            <a href="/admin/login" class="btn" style="background: #6c757d;">🔐 Test Login Admin dengan Google</a>
        </div>
        <?php else: ?>
        <div style="margin-top: 30px;">
            <div class="test-item warning">
                <div class="test-label">⚠️ Perbaikan Diperlukan</div>
                <div class="test-value">
                    Silakan perbaiki konfigurasi yang masih error di atas, atau akses 
                    <a href="/fix-oauth.php">halaman perbaikan</a> untuk panduan lengkap.
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
            <p><strong>Catatan:</strong></p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Pastikan redirect URI sudah ditambahkan di Google Cloud Console</li>
                <li>Jika masih error redirect_uri_mismatch, tunggu beberapa detik setelah update di Google Cloud Console</li>
                <li>Pastikan redirect URI di Google Cloud Console sama persis dengan yang ditampilkan di atas</li>
            </ul>
        </div>
    </div>
</body>
</html>

