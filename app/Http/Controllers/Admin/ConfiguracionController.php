<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador de configuración del sistema (solo admin).
 *
 * Gestiona backups, parámetros del sistema y configuración general.
 */
class ConfiguracionController extends Controller
{
    /**
     * Mostrar panel de configuración.
     */
    public function index(): View
    {
        // Obtener información del último backup
        $backups = $this->listarBackups();
        $ultimoBackup = !empty($backups) ? $backups[0] : null;

        return view('admin.configuracion.index', compact('ultimoBackup', 'backups'));
    }

    /**
     * Generar backup manual de la base de datos.
     *
     * Utiliza el comando artisan backup:database con compresión.
     */
    public function generarBackup(): RedirectResponse
    {
        try {
            // Ejecutar comando artisan backup:database con compresión
            Artisan::call('backup:database', [
                '--compress' => true,
                '--keep-days' => 90,
            ]);

            $output = Artisan::output();

            // Obtener el último backup generado
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

    /**
     * Descargar archivo de backup.
     */
    public function descargarBackup(string $nombreArchivo): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        // Los backups ahora están en storage/app/backups/
        $rutaBackup = storage_path("app/backups/{$nombreArchivo}");

        if (!file_exists($rutaBackup)) {
            abort(404, 'Archivo de backup no encontrado');
        }

        return response()->download($rutaBackup);
    }

    /**
     * Eliminar archivo de backup.
     */
    public function eliminarBackup(string $nombreArchivo): RedirectResponse
    {
        // Los backups ahora están en storage/app/backups/
        $rutaBackup = storage_path("app/backups/{$nombreArchivo}");

        if (file_exists($rutaBackup)) {
            unlink($rutaBackup);

            return back()->with('success', 'Backup eliminado exitosamente');
        }

        return back()->with('error', 'Archivo de backup no encontrado');
    }

    /**
     * Listar archivos de backup disponibles.
     *
     * Busca archivos .sql y .zip en storage/app/backups/
     *
     * @return array
     */
    protected function listarBackups(): array
    {
        $rutaBackups = storage_path('app/backups');

        if (!is_dir($rutaBackups)) {
            return [];
        }

        // Buscar archivos .sql y .zip
        $archivos = array_merge(
            glob($rutaBackups . '/*.sql') ?: [],
            glob($rutaBackups . '/*.zip') ?: []
        );

        // Ordenar por fecha de modificación (más reciente primero)
        usort($archivos, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        return array_map(function ($archivo) {
            $sizeInBytes = filesize($archivo);
            $sizeInMB = round($sizeInBytes / 1024 / 1024, 2);

            // Si es menor a 1 MB, mostrar en KB
            $tamano = $sizeInMB >= 1
                ? $sizeInMB . ' MB'
                : round($sizeInBytes / 1024, 2) . ' KB';

            return [
                'nombre' => basename($archivo),
                'fecha' => date('Y-m-d H:i:s', filemtime($archivo)),
                'tamano' => $tamano,
                'ruta' => $archivo,
            ];
        }, $archivos);
    }
}
