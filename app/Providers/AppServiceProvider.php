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
        PermohonanMagang::observe(PermohonanMagangObserver::class);
    }
}
