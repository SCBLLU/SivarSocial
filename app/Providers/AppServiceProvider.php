<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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
        // Configurar vista personalizada para paginación
        Paginator::defaultView('custom.pagination');
        Paginator::defaultSimpleView('custom.simple-pagination');
        
        // Usar en automatico el protocolo segun este en .env (APP_URL)
        if (str_starts_with(config('app.url'), 'https://') && 
            !str_contains(config('app.url'), 'localhost') && 
            !str_contains(config('app.url'), '127.0.0.1')) {
            URL::forceScheme('https');
        }
    }
}
