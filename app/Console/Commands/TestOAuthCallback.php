<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class TestOAuthCallback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:test-callback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if OAuth callback route is accessible';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Test OAuth Callback Accessibility ===');
        $this->newLine();

        $appUrl = config('app.url');
        $callbackUrl = $appUrl . '/oauth/google/callback';
        $testUrl = $appUrl . '/test-oauth-callback';

        $this->info("1. Testing callback route accessibility...");
        $this->line("   Callback URL: " . $callbackUrl);
        $this->line("   Test URL: " . $testUrl);
        $this->newLine();

        // Check if route exists
        if (!Route::has('oauth.google.callback')) {
            $this->error('   ❌ Route oauth.google.callback tidak ditemukan!');
            return 1;
        }
        $this->info('   ✅ Route oauth.google.callback tersedia');
        $this->newLine();

        // Try to access test route
        $this->info("2. Testing server accessibility...");
        try {
            $response = Http::timeout(5)->get($testUrl);
            if ($response->successful()) {
                $this->info('   ✅ Server bisa diakses');
                $this->info('   ✅ Callback route bisa diakses');
            } else {
                $this->warn('   ⚠️  Server merespons dengan status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Server TIDAK bisa diakses!');
            $this->error('   Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('   SOLUSI: Pastikan server Laravel berjalan dengan:');
            $this->line('   php artisan serve');
            return 1;
        }
        $this->newLine();

        // Check redirect URI configuration
        $this->info("3. Verifying redirect URI configuration...");
        $redirectUri = config('services.google.redirect');
        $expectedCallback = $appUrl . '/oauth/google/callback';
        
        if ($redirectUri === $expectedCallback) {
            $this->info('   ✅ Redirect URI cocok: ' . $redirectUri);
        } else {
            $this->error('   ❌ Redirect URI TIDAK COCOK!');
            $this->line('   Konfigurasi: ' . ($redirectUri ?: 'TIDAK DIISI'));
            $this->line('   Diharapkan:  ' . $expectedCallback);
        }
        $this->newLine();

        // Summary
        $this->info('=== Ringkasan ===');
        $this->newLine();
        $this->info('Langkah selanjutnya:');
        $this->line('1. Pastikan server Laravel berjalan: php artisan serve');
        $this->line('2. Buka browser dan akses: ' . $testUrl);
        $this->line('3. Pastikan redirect URI di Google Cloud Console sama dengan:');
        $this->line('   ' . $expectedCallback);
        $this->line('4. Test login dengan Google OAuth');
        $this->newLine();
        $this->warn('PENTING: Jika callback tidak dipanggil setelah pilih akun,');
        $this->warn('periksa redirect URI di Google Cloud Console harus SAMA PERSIS!');

        return 0;
    }
}

