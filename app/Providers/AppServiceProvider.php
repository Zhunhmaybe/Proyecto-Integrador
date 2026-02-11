<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- ESTO FALTABA EN TU FOTO

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
        // Forzar HTTPS en producción (Koyeb) o Ngrok
        if ($this->app->environment('production') || str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}