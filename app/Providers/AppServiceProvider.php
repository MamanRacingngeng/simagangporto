<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PermohonanMagang;
use App\Observers\PermohonanMagangObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observer untuk sinkronisasi kuota
        // Hanya register jika database connection tersedia
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('permohonan_magang')) {
                PermohonanMagang::observe(PermohonanMagangObserver::class);
            }
        } catch (\Exception $e) {
            // Jika database tidak tersedia atau tabel belum ada, skip observer registration
            \Log::warning('Cannot register PermohonanMagangObserver: ' . $e->getMessage());
        }
    }
}
