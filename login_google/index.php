<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$uname = isset($_SESSION['uname']) ? $_SESSION['uname'] : '';
$userInfo = isset($_SESSION['user_info']) ? $_SESSION['user_info'] : null;

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google OAuth Login - Halaman Utama</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
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
        
        .status {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .status.logged-in {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status.logged-out {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .user-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .user-info h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .user-info-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .user-info-item strong {
            color: #555;
            min-width: 100px;
            display: inline-block;
        }
        
        .user-info-item span {
            color: #333;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: block;
            border: 3px solid #667eea;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-logout {
            background: #dc3545;
            color: white;
        }
        
        .btn-logout:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.4);
        }
        
        .config-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .config-warning strong {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Google OAuth Login</h1>
        <p class="subtitle">Sistem Login dengan Google Account</p>
        
        <?php if ($isLoggedIn): ?>
            <div class="status logged-in">
                ✅ Anda sudah login
            </div>
            
            <?php if (isset($_SESSION['picture'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['picture']); ?>" alt="Avatar" class="user-avatar">
            <?php endif; ?>
            
            <div class="user-info">
                <h3>Informasi User</h3>
                <div class="user-info-item">
                    <strong>Nama:</strong>
                    <span><?php echo htmlspecialchars($uname); ?></span>
                </div>
                <?php if (isset($_SESSION['email'])): ?>
                <div class="user-info-item">
                    <strong>Email:</strong>
                    <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 10px; color: #004085;">
                <strong>Selamat datang, <?= htmlspecialchars($uname) ?>!</strong>
            </div>
            
            <a href="logout.php" class="btn btn-logout">Logout</a>
        <?php else: ?>
            <div class="status logged-out">
                ❌ Anda belum login
            </div>
            
            <p style="margin-bottom: 30px; color: #666;">
                Silakan login dengan akun Google Anda untuk melanjutkan.
            </p>
            
            <a href="login.php" class="btn btn-login">Login dengan Google</a>
        <?php endif; ?>
    </div>
</body>
</html>

