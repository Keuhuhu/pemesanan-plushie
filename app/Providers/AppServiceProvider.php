<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 1. Wajib tambahkan baris import ini

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
        // 2. Paksa Laravel menggunakan HTTPS jika tidak dijalankan di komputer lokal
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}