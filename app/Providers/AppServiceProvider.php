<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RuntimeException;

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
        // Halaman debug Laravel menampilkan seluruh isi .env — termasuk APP_KEY
        // dan DB_PASSWORD — ke siapa pun yang bisa memicu exception. Mati saat
        // boot jauh lebih baik daripada bocor diam-diam sepanjang penelitian.
        if ($this->app->environment('production') && config('app.debug')) {
            throw new RuntimeException('APP_DEBUG wajib false saat APP_ENV=production.');
        }
    }
}
