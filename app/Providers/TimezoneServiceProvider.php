<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TimezoneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configurar zona horaria de Bolivia
        date_default_timezone_set('America/La_Paz');
        config(['app.timezone' => 'America/La_Paz']);
    }
}
