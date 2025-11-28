<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ClearAllCaches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todos los caches del sistema (Laravel, vistas, configuración, logs, sesiones)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Iniciando limpieza completa de caches...');
        $this->newLine();

        // Limpiar cache de aplicación
        $this->info('→ Limpiando cache de aplicación...');
        Artisan::call('cache:clear');
        $this->line('  ✓ Cache de aplicación limpiado');

        // Limpiar cache de configuración
        $this->info('→ Limpiando cache de configuración...');
        Artisan::call('config:clear');
        $this->line('  ✓ Cache de configuración limpiado');

        // Limpiar cache de rutas
        $this->info('→ Limpiando cache de rutas...');
        Artisan::call('route:clear');
        $this->line('  ✓ Cache de rutas limpiado');

        // Limpiar vistas compiladas
        $this->info('→ Limpiando vistas compiladas...');
        Artisan::call('view:clear');
        $this->line('  ✓ Vistas compiladas limpiadas');

        // Limpiar cache de eventos
        $this->info('→ Limpiando cache de eventos...');
        Artisan::call('event:clear');
        $this->line('  ✓ Cache de eventos limpiado');

        // Limpiar cache de optimización
        $this->info('→ Limpiando cache de optimización...');
        Artisan::call('optimize:clear');
        $this->line('  ✓ Cache de optimización limpiado');

        // Limpiar logs antiguos
        $this->info('→ Limpiando logs antiguos...');
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            File::put($logFile, '');
            $this->line('  ✓ Logs limpiados');
        }

        // Limpiar sesiones antiguas
        $this->info('→ Limpiando sesiones antiguas...');
        $sessionsPath = storage_path('framework/sessions');
        if (File::exists($sessionsPath)) {
            $files = File::files($sessionsPath);
            foreach ($files as $file) {
                File::delete($file);
            }
            $this->line('  ✓ Sesiones antiguas eliminadas');
        }

        // Limpiar cache de datos del framework
        $this->info('→ Limpiando cache de datos del framework...');
        $cachePath = storage_path('framework/cache/data');
        if (File::exists($cachePath)) {
            File::cleanDirectory($cachePath);
            $this->line('  ✓ Cache de datos limpiado');
        }

        $this->newLine();
        $this->info('✅ Limpieza completa finalizada exitosamente!');
        $this->comment('📅 Fecha: ' . now()->format('d/m/Y H:i:s'));

        return Command::SUCCESS;
    }
}
