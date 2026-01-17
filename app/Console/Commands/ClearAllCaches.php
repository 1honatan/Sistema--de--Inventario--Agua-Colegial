<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ClearAllCaches extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = 'Limpia todos los caches del sistema';

    public function handle()
    {
        $this->info('Limpiando caches...');

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // Limpiar logs
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            File::put($logFile, '');
        }

        // Limpiar sesiones
        $sessionsPath = storage_path('framework/sessions');
        if (File::exists($sessionsPath)) {
            foreach (File::files($sessionsPath) as $file) {
                File::delete($file);
            }
        }

        $this->info('Caches limpiados');
        return Command::SUCCESS;
    }
}
