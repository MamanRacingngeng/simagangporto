<?php
/**
 * Konfigurasi Google OAuth
 * 
 * INSTRUKSI:
 * 1. Buka Google Cloud Console: https://console.cloud.google.com/
 * 2. Buat project baru atau pilih project yang sudah ada
 * 3. Aktifkan Google+ API atau Google OAuth 2.0 API
 * 4. Buat OAuth 2.0 Client ID:
 *    - Credentials > Create Credentials > OAuth client ID
 *    - Application type: Web application
 *    - Authorized redirect URIs: http://localhost/login_google/login.php
 *      (Sesuaikan dengan URL Anda)
 * 5. Copy Client ID dan Client Secret ke bawah ini
 */

return [
    'CLIENT_ID' => 'YOUR_GOOGLE_CLIENT_ID',
    'CLIENT_SECRET' => 'YOUR_GOOGLE_CLIENT_SECRET',
    'REDIRECT_URI' => 'http://localhost/login_google/login.php', // Sesuaikan dengan URL Anda
];

