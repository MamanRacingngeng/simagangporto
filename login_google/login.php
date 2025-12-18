<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

if (isset($_SESSION['logged_in'])) {
    header('location: index.php');
    exit;
}

// Koneksi database
require_once __DIR__ . '/db_config.php';
$conn = getDBConnection();

// Panggil library
require_once __DIR__ . '/vendor/autoload.php';

// Load konfigurasi Google OAuth
$googleConfig = require_once __DIR__ . '/google_config.php';

// Tampung client id, client secret dan redirect uri
$client_id = $googleConfig['client_id'];
$client_secret = $googleConfig['client_secret'];
$redirect_uri = $googleConfig['redirect_uri'];

// Validasi: Pastikan Client ID dan Secret sudah diisi
if (empty($client_id) || empty($client_secret)) {
    die('
    <html>
    <head>
        <title>Error: Google OAuth Configuration</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 40px; max-width: 800px; margin: 0 auto; }
            .error-box { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; }
            h1 { color: #721c24; }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h1>❌ Error: Google OAuth Configuration</h1>
            <p>Google Client ID atau Client Secret tidak dikonfigurasi.</p>
            <p>Silakan edit file <code>.env</code> dan tambahkan:</p>
            <pre>GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here</pre>
            <p><a href="SETUP_OAUTH_CLIENT.md">Lihat panduan lengkap</a></p>
        </div>
    </body>
    </html>
    ');
}

// Inisiasi google client
$client = new Google_Client();

// Konfigurasi google client
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);

$client->addScope('email');
$client->addScope('profile');

// Cek apakah ini callback dari Google (ada parameter 'code')
if (isset($_GET['code'])) {
    try {
        // Exchange authorization code untuk access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['error'])) {
            throw new Exception('Error: ' . $token['error_description']);
        }
        
        // Simpan access token di session
        $_SESSION['access_token'] = $token['access_token'];
        
        // Set access token ke client
        $client->setAccessToken($token);
        
        // Ambil informasi user dari Google
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
        
        $google_id = $userInfo->getId();
        $email = $userInfo->getEmail();
        $nama = $userInfo->getName();
        $picture = $userInfo->getPicture();
        
        // Escape string untuk keamanan
        $google_id = mysqli_real_escape_string($conn, $google_id);
        $email = mysqli_real_escape_string($conn, $email);
        $nama = mysqli_real_escape_string($conn, $nama);
        $picture = mysqli_real_escape_string($conn, $picture);
        
        // Cek apakah user sudah ada di database
        $check_user = mysqli_query($conn, "SELECT * FROM users WHERE google_id = '$google_id' OR email = '$email'");
        
        if (mysqli_num_rows($check_user) > 0) {
            // Update data user yang sudah ada
            $update_query = "UPDATE users SET 
                            google_id = '$google_id',
                            nama = '$nama',
                            picture = '$picture',
                            updated_at = NOW()
                            WHERE email = '$email' OR google_id = '$google_id'";
            mysqli_query($conn, $update_query);
            
            $user_data = mysqli_fetch_assoc($check_user);
        } else {
            // Insert user baru ke database
            $insert_query = "INSERT INTO users (google_id, email, nama, picture) 
                            VALUES ('$google_id', '$email', '$nama', '$picture')";
            mysqli_query($conn, $insert_query);
            
            $user_data = [
                'id' => mysqli_insert_id($conn),
                'google_id' => $google_id,
                'email' => $email,
                'nama' => $nama,
                'picture' => $picture
            ];
        }
        
        // Simpan informasi user di session
        $_SESSION['logged_in'] = true;
        $_SESSION['uname'] = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['google_id'] = $google_id;
        $_SESSION['picture'] = $picture;
        $_SESSION['user_info'] = [
            'id' => $google_id,
            'email' => $email,
            'name' => $nama,
            'picture' => $picture,
        ];
        
        // Tutup koneksi database
        closeDBConnection($conn);
        
        // Redirect ke halaman utama
        header('Location: index.php');
        exit;
        
    } catch (Exception $e) {
        // Tutup koneksi database jika ada error
        if (isset($conn)) {
            closeDBConnection($conn);
        }
        // Jika terjadi error, tampilkan pesan error
        $error = 'Error: ' . $e->getMessage();
        header('Location: index.php?error=' . urlencode($error));
        exit;
    }
}

// Cek jika ada error dari Google
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    header('Location: index.php?error=' . urlencode($error));
    exit;
}

// Jika tidak ada code, berarti ini request pertama - redirect ke Google
$authUrl = $client->createAuthUrl();
header('Location: ' . $authUrl);
exit;

