<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class VerifyGoogleOAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:verify-google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify Google OAuth configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Verifikasi Konfigurasi Google OAuth ===');
        $this->newLine();

        // Check APP_URL
        $appUrl = config('app.url');
        $this->info("1. APP_URL: " . ($appUrl ?: 'TIDAK DIISI'));
        if (empty($appUrl)) {
            $this->error('   ❌ APP_URL tidak diisi di .env!');
        } else {
            $this->info('   ✅ APP_URL: ' . $appUrl);
            if (substr($appUrl, -1) === '/') {
                $this->warn('   ⚠️  APP_URL memiliki trailing slash, sebaiknya dihapus');
            }
        }
        $this->newLine();

        // Check Google Client ID
        $clientId = config('services.google.client_id');
        $this->info("2. GOOGLE_CLIENT_ID: " . ($clientId ? 'DIISI' : 'TIDAK DIISI'));
        if (empty($clientId)) {
            $this->error('   ❌ GOOGLE_CLIENT_ID tidak diisi di .env!');
        } else {
            $this->info('   ✅ GOOGLE_CLIENT_ID sudah diisi');
        }
        $this->newLine();

        // Check Google Client Secret
        $clientSecret = config('services.google.client_secret');
        $this->info("3. GOOGLE_CLIENT_SECRET: " . ($clientSecret ? 'DIISI' : 'TIDAK DIISI'));
        if (empty($clientSecret)) {
            $this->error('   ❌ GOOGLE_CLIENT_SECRET tidak diisi di .env!');
        } else {
            $this->info('   ✅ GOOGLE_CLIENT_SECRET sudah diisi');
        }
        $this->newLine();

        // Check Redirect URI
        $redirectUri = config('services.google.redirect');
        $expectedCallback = $appUrl . '/oauth/google/callback';
        $this->info("4. Redirect URI:");
        $this->info("   Konfigurasi: " . ($redirectUri ?: 'TIDAK DIISI'));
        $this->info("   Diharapkan:  " . $expectedCallback);
        
        if (empty($redirectUri)) {
            $this->error('   ❌ Redirect URI tidak diisi!');
        } else {
            if ($redirectUri === $expectedCallback) {
                $this->info('   ✅ Redirect URI cocok');
            } else {
                $this->error('   ❌ Redirect URI TIDAK COCOK!');
                $this->warn('   Pastikan redirect URI di Google Cloud Console sama dengan:');
                $this->line('   ' . $expectedCallback);
            }
        }
        $this->newLine();

        // Check Route
        $this->info("5. Route Callback:");
        if (Route::has('oauth.google.callback')) {
            $this->info('   ✅ Route oauth.google.callback tersedia');
        } else {
            $this->error('   ❌ Route oauth.google.callback tidak ditemukan!');
        }
        $this->newLine();

        // Summary
        $this->info('=== Ringkasan ===');
        $allOk = !empty($appUrl) && !empty($clientId) && !empty($clientSecret) && 
                 !empty($redirectUri) && ($redirectUri === $expectedCallback) && 
                 Route::has('oauth.google.callback');
        
        if ($allOk) {
            $this->info('✅ Semua konfigurasi sudah benar!');
            $this->newLine();
            $this->info('Langkah selanjutnya:');
            $this->line('1. Pastikan redirect URI di Google Cloud Console sama dengan:');
            $this->line('   ' . $expectedCallback);
            $this->line('2. Clear cache: php artisan config:clear');
            $this->line('3. Test login dengan Google');
        } else {
            $this->error('❌ Ada masalah dengan konfigurasi!');
            $this->newLine();
            $this->info('Langkah perbaikan:');
            $this->line('1. Periksa file .env dan pastikan semua variabel sudah diisi');
            $this->line('2. Pastikan APP_URL tidak ada trailing slash');
            $this->line('3. Pastikan redirect URI di Google Cloud Console sama dengan yang ditampilkan di atas');
            $this->line('4. Jalankan: php artisan config:clear');
        }
        
        return $allOk ? 0 : 1;
    }
}
