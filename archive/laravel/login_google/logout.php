<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/vendor/autoload.php';

// Load konfigurasi Google OAuth
$googleConfig = require_once __DIR__ . '/google_config.php';

// Revoke token jika ada access token
if (isset($_SESSION['access_token'])) {
    $access_token = $_SESSION['access_token'];
    
    // Inisiasi google client
    $client = new Google_Client();
    
    // Set client ID dan secret untuk revoke token
    $client_id = $googleConfig['client_id'];
    $client_secret = $googleConfig['client_secret'];
    
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    
    try {
        $client->revokeToken($access_token);
    } catch (Exception $e) {
        // Jika revoke gagal, lanjutkan proses logout
    }
}

// Hapus semua data session
$_SESSION = array();

// Hapus cookie session jika ada
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header('location: login.php');
exit;

