<?php
/**
 * Konfigurasi Database MySQL
 * Sesuaikan dengan konfigurasi database Anda
 * Bisa membaca dari .env file di root project Laravel
 */

// Cek apakah ada file .env di root project (untuk integrasi dengan Laravel)
$envPath = __DIR__ . '/../.env';
$envVars = [];

if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    preg_match_all('/^DB_LOGIN_GOOGLE_(\w+)=(.*)$/m', $envContent, $matches, PREG_SET_ORDER);
    
    foreach ($matches as $match) {
        $key = strtolower($match[1]);
        $value = trim($match[2]);
        // Hapus quotes jika ada
        $value = trim($value, '"\'');
        $envVars[$key] = $value;
    }
}

// Konfigurasi Database - Gunakan dari .env jika ada, jika tidak gunakan default
define('DB_HOST', $envVars['host'] ?? 'localhost');
define('DB_USER', $envVars['username'] ?? 'root');
define('DB_PASS', $envVars['password'] ?? ''); // Jika ada password, isi di sini atau di .env
define('DB_NAME', $envVars['database'] ?? 'db_login_google');
define('DB_PORT', $envVars['port'] ?? '3306');

/**
 * Fungsi untuk membuat koneksi database
 * @return mysqli|false Koneksi database atau false jika gagal
 */
function getDBConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if (!$conn) {
        die('Koneksi database gagal: ' . mysqli_connect_error() . 
            '<br>Host: ' . DB_HOST . 
            '<br>Database: ' . DB_NAME . 
            '<br>User: ' . DB_USER);
    }
    
    // Set charset ke utf8mb4 untuk mendukung emoji dan karakter khusus
    mysqli_set_charset($conn, 'utf8mb4');
    
    return $conn;
}

/**
 * Fungsi untuk menutup koneksi database
 * @param mysqli $conn Koneksi database
 */
function closeDBConnection($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}

