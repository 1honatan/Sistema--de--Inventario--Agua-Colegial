<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Comando Artisan para realizar backups automáticos de la base de datos.
 *
 * Genera un archivo SQL con el dump completo de la base de datos MySQL
 * y lo guarda en storage/app/backups/ con timestamp.
 *
 * Uso:
 *   php artisan backup:database
 *   php artisan backup:database --compress
 */
class BackupDatabase extends Command
{
    /**
     * Nombre y firma del comando.
     *
     * @var string
     */
    protected $signature = 'backup:database
                            {--compress : Comprimir el backup en formato ZIP}
                            {--keep-days=30 : Número de días para mantener backups antiguos}';

    /**
     * Descripción del comando.
     *
     * @var string
     */
    protected $description = 'Realizar backup automático de la base de datos MySQL';

    /**
     * Ejecutar el comando.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('🔄 Iniciando backup de base de datos...');

        try {
            // Verificar que existe mysqldump
            if (!$this->verificarMysqldump()) {
                $this->error('❌ mysqldump no encontrado. Verifica la instalación de MySQL.');
                return self::FAILURE;
            }

            // Crear directorio de backups si no existe
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
                $this->info('📁 Directorio de backups creado: ' . $backupPath);
            }

            // Obtener configuración de base de datos
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            // Generar nombre del archivo
            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "agua_colegial_backup_{$timestamp}.sql";
            $filePath = $backupPath . DIRECTORY_SEPARATOR . $fileName;

            // Construir comando mysqldump
            $command = $this->construirComandoMysqldump(
                $dbHost,
                $dbPort,
                $dbUser,
                $dbPass,
                $dbName,
                $filePath
            );

            // Ejecutar backup
            $this->info('📦 Generando backup...');
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('❌ Error al ejecutar mysqldump. Código: ' . $returnCode);
                return self::FAILURE;
            }

            // Verificar que el archivo se creó
            if (!File::exists($filePath)) {
                $this->error('❌ El archivo de backup no se generó correctamente.');
                return self::FAILURE;
            }

            $fileSize = $this->formatBytes(File::size($filePath));
            $this->info("✅ Backup generado: {$fileName} ({$fileSize})");

            // Comprimir si se solicitó
            if ($this->option('compress')) {
                $zipPath = $this->comprimirBackup($filePath, $fileName);
                if ($zipPath) {
                    $zipSize = $this->formatBytes(File::size($zipPath));
                    $this->info("🗜️  Backup comprimido: {$zipPath} ({$zipSize})");

                    // Eliminar archivo SQL sin comprimir
                    File::delete($filePath);
                }
            }

            // Limpiar backups antiguos
            $keepDays = (int) $this->option('keep-days');
            $this->limpiarBackupsAntiguos($backupPath, $keepDays);

            $this->info('✅ Proceso de backup completado exitosamente.');
            $this->newLine();
            $this->info('📂 Ubicación: ' . $backupPath);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error durante el backup: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    /**
     * Verificar que mysqldump está disponible en el sistema.
     *
     * @return bool
     */
    protected function verificarMysqldump(): bool
    {
        // En Windows (XAMPP), mysqldump está en C:\xampp\mysql\bin\
        // En Linux/Mac, está en el PATH
        $command = $this->estaEnWindows()
            ? 'C:\xampp\mysql\bin\mysqldump --version 2>NUL'
            : 'mysqldump --version 2>/dev/null';

        exec($command, $output, $returnCode);

        return $returnCode === 0;
    }

    /**
     * Construir comando mysqldump con los parámetros necesarios.
     *
     * @param  string  $host
     * @param  string  $port
     * @param  string  $user
     * @param  string  $password
     * @param  string  $database
     * @param  string  $outputFile
     * @return string
     */
    protected function construirComandoMysqldump(
        string $host,
        string $port,
        string $user,
        string $password,
        string $database,
        string $outputFile
    ): string {
        // En Windows (XAMPP), usar ruta completa
        $mysqldump = $this->estaEnWindows()
            ? 'C:\xampp\mysql\bin\mysqldump'
            : 'mysqldump';

        // Construir comando con opciones recomendadas
        $command = sprintf(
            '"%s" --host=%s --port=%s --user=%s --password=%s ' .
            '--single-transaction --routines --triggers --events ' .
            '--add-drop-table --extended-insert ' .
            '%s > "%s"',
            $mysqldump,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($user),
            escapeshellarg($password),
            escapeshellarg($database),
            $outputFile
        );

        // En Windows, evitar problemas con las comillas
        if ($this->estaEnWindows()) {
            $command = sprintf(
                '"%s" --host=%s --port=%s --user=%s --password=%s ' .
                '--single-transaction --routines --triggers --events ' .
                '--add-drop-table --extended-insert ' .
                '%s > "%s" 2>NUL',
                $mysqldump,
                $host,
                $port,
                $user,
                $password,
                $database,
                $outputFile
            );
        }

        return $command;
    }

    /**
     * Comprimir el archivo de backup en formato ZIP.
     *
     * @param  string  $filePath
     * @param  string  $fileName
     * @return string|null Ruta del archivo ZIP o null si falla
     */
    protected function comprimirBackup(string $filePath, string $fileName): ?string
    {
        try {
            $zipFileName = str_replace('.sql', '.zip', $fileName);
            $zipPath = dirname($filePath) . DIRECTORY_SEPARATOR . $zipFileName;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                $zip->addFile($filePath, $fileName);
                $zip->close();
                return $zipPath;
            }

            return null;
        } catch (\Exception $e) {
            $this->warn('⚠️  No se pudo comprimir el backup: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Eliminar backups más antiguos que el número de días especificado.
     *
     * @param  string  $backupPath
     * @param  int  $keepDays
     * @return void
     */
    protected function limpiarBackupsAntiguos(string $backupPath, int $keepDays): void
    {
        $files = File::files($backupPath);
        $fechaLimite = now()->subDays($keepDays);
        $eliminados = 0;

        foreach ($files as $file) {
            if (File::lastModified($file) < $fechaLimite->timestamp) {
                File::delete($file);
                $eliminados++;
            }
        }

        if ($eliminados > 0) {
            $this->info("🗑️  Eliminados {$eliminados} backups antiguos (>{$keepDays} días)");
        }
    }

    /**
     * Formatear bytes a formato legible (KB, MB, GB).
     *
     * @param  int  $bytes
     * @return string
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Verificar si el sistema operativo es Windows.
     *
     * @return bool
     */
    protected function estaEnWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
