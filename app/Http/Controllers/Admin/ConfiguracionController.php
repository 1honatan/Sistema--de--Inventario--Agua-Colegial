<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;

class ConfiguracionController extends Controller
{
    public function index(): View
    {
        $backups = $this->listarBackups();
        $ultimoBackup = !empty($backups) ? $backups[0] : null;

        return view('admin.configuracion.index', compact('ultimoBackup', 'backups'));
    }

    public function generarBackup(): RedirectResponse
    {
        try {
            Artisan::call('backup:database', [
                '--compress' => true,
                '--keep-days' => 90,
            ]);

            $backups = $this->listarBackups();
            $ultimoBackup = !empty($backups) ? $backups[0] : null;

            if ($ultimoBackup) {
                \Log::info("Backup manual creado: {$ultimoBackup['nombre']} ({$ultimoBackup['tamano']})");
                return back()->with('success', "Backup creado exitosamente: {$ultimoBackup['nombre']} ({$ultimoBackup['tamano']})");
            }

            return back()->with('success', 'Backup creado exitosamente');
        } catch (\Exception $e) {
            \Log::error("Error al crear backup: " . $e->getMessage());
            return back()->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }

    public function descargarBackup(string $nombreArchivo): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $rutaBackup = storage_path("app/backups/{$nombreArchivo}");

        if (!file_exists($rutaBackup)) {
            abort(404, 'Archivo de backup no encontrado');
        }

        return response()->download($rutaBackup);
    }

    public function eliminarBackup(string $nombreArchivo): RedirectResponse
    {
        $rutaBackup = storage_path("app/backups/{$nombreArchivo}");

        if (file_exists($rutaBackup)) {
            unlink($rutaBackup);
            return back()->with('success', 'Backup eliminado exitosamente');
        }

        return back()->with('error', 'Archivo de backup no encontrado');
    }

    protected function listarBackups(): array
    {
        $rutaBackups = storage_path('app/backups');

        if (!is_dir($rutaBackups)) {
            return [];
        }

        $archivos = array_merge(
            glob($rutaBackups . '/*.sql') ?: [],
            glob($rutaBackups . '/*.zip') ?: []
        );

        usort($archivos, fn($a, $b) => filemtime($b) - filemtime($a));

        return array_map(function ($archivo) {
            $sizeInBytes = filesize($archivo);
            $sizeInMB = round($sizeInBytes / 1024 / 1024, 2);

            return [
                'nombre' => basename($archivo),
                'fecha' => date('Y-m-d H:i:s', filemtime($archivo)),
                'tamano' => $sizeInMB >= 1 ? $sizeInMB . ' MB' : round($sizeInBytes / 1024, 2) . ' KB',
                'ruta' => $archivo,
            ];
        }, $archivos);
    }
}
