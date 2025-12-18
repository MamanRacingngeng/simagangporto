<?php
/**
 * Script Web untuk memperbaiki konfigurasi Google OAuth
 * Akses via browser: http://127.0.0.1:8000/fix-oauth.php
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
    <title>Perbaikan Google OAuth Configuration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
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
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .section h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        .status.ok { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .uri-list {
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .uri-item {
            padding: 10px;
            margin: 5px 0;
            background: #e7f3ff;
            border-left: 3px solid #2196F3;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            word-break: break-all;
        }
        .copy-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 10px;
            transition: background 0.3s;
        }
        .copy-btn:hover { background: #1976D2; }
        .steps {
            list-style: none;
            counter-reset: step-counter;
        }
        .steps li {
            counter-increment: step-counter;
            margin: 15px 0;
            padding-left: 40px;
            position: relative;
            line-height: 1.6;
        }
        .steps li:before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            background: #667eea;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
        .link-btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            transition: background 0.3s;
        }
        .link-btn:hover { background: #5568d3; }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Perbaikan Google OAuth Configuration</h1>
        <p class="subtitle">Script untuk memperbaiki error redirect_uri_mismatch</p>

        <?php
        $appUrl = config('app.url');
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        // Generate semua kemungkinan redirect URI
        $possibleUris = [];
        if ($appUrl) {
            $baseUrl = rtrim($appUrl, '/');
            $possibleUris[] = $baseUrl . '/oauth/google/callback';
            
            if (strpos($appUrl, '127.0.0.1') !== false) {
                $localhostUrl = str_replace('127.0.0.1', 'localhost', $baseUrl);
                $possibleUris[] = $localhostUrl . '/oauth/google/callback';
            }
            
            if (strpos($appUrl, 'localhost') !== false) {
                $ipUrl = str_replace('localhost', '127.0.0.1', $baseUrl);
                $possibleUris[] = $ipUrl . '/oauth/google/callback';
            }
        }
        ?>

        <div class="section">
            <h2>Status Konfigurasi</h2>
            <p>
                <strong>APP_URL:</strong> <?php echo $appUrl ?: 'TIDAK DIISI'; ?>
                <span class="status <?php echo $appUrl ? 'ok' : 'error'; ?>">
                    <?php echo $appUrl ? 'OK' : 'ERROR'; ?>
                </span>
            </p>
            <p>
                <strong>GOOGLE_CLIENT_ID:</strong> <?php echo $clientId ? 'DIISI' : 'TIDAK DIISI'; ?>
                <span class="status <?php echo $clientId ? 'ok' : 'error'; ?>">
                    <?php echo $clientId ? 'OK' : 'ERROR'; ?>
                </span>
            </p>
            <p>
                <strong>GOOGLE_CLIENT_SECRET:</strong> <?php echo $clientSecret ? 'DIISI' : 'TIDAK DIISI'; ?>
                <span class="status <?php echo $clientSecret ? 'ok' : 'error'; ?>">
                    <?php echo $clientSecret ? 'OK' : 'ERROR'; ?>
                </span>
            </p>
            <p>
                <strong>REDIRECT URI:</strong> <?php echo $redirectUri ?: 'TIDAK DIISI'; ?>
                <span class="status <?php echo $redirectUri ? 'ok' : 'error'; ?>">
                    <?php echo $redirectUri ? 'OK' : 'ERROR'; ?>
                </span>
            </p>
        </div>

        <div class="section">
            <h2>Redirect URI yang Harus Ditambahkan di Google Cloud Console</h2>
            <div class="uri-list">
                <?php foreach ($possibleUris as $uri): ?>
                <div class="uri-item">
                    <?php echo htmlspecialchars($uri); ?>
                    <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($uri); ?>')">Copy</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <h2>Cara Menambahkan di Google Cloud Console</h2>
            <ol class="steps">
                <li>Buka: <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console - Credentials</a></li>
                <li>Klik pada OAuth 2.0 Client ID Anda (Client ID: <code><?php echo substr($clientId, 0, 30) . '...'; ?></code>)</li>
                <li>Scroll ke bagian <strong>"Authorized redirect URIs"</strong></li>
                <li>Klik <strong>"ADD URI"</strong></li>
                <li>Tambahkan semua URI di atas (satu per satu)</li>
                <li>Klik <strong>"SAVE"</strong></li>
            </ol>
            
            <?php if ($clientId): ?>
            <a href="https://console.cloud.google.com/apis/credentials/oauthclient/<?php echo $clientId; ?>" 
               target="_blank" class="link-btn">
                🔗 Buka Halaman Konfigurasi OAuth Client
            </a>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Setelah Menambahkan Redirect URI</h2>
            <div class="alert alert-info">
                <strong>⚠️ Penting:</strong>
                <ul style="margin-top: 10px; margin-left: 20px;">
                    <li>Tunggu beberapa detik (Google perlu waktu untuk update konfigurasi)</li>
                    <li>Coba login dengan Google lagi</li>
                    <li>Jika masih error, pastikan redirect URI sama persis (termasuk <code>http</code>/<code>https</code> dan port)</li>
                    <li>Pastikan tidak ada trailing slash (<code>/</code>) di akhir URI</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Copied to clipboard: ' + text);
            }, function(err) {
                // Fallback untuk browser lama
                var textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Copied to clipboard: ' + text);
            });
        }
    </script>
</body>
</html>

