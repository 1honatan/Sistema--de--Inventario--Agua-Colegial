<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--compress} {--keep-days=30}';
    protected $description = 'Backup de la base de datos';

    public function handle(): int
    {
        $this->info('Iniciando backup...');

        try {
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            $timestamp = now()->format('Y-m-d_H-i-s');
            $fileName = "backup_{$timestamp}.sql";
            $filePath = $backupPath . DIRECTORY_SEPARATOR . $fileName;

            // Ejecutar mysqldump
            $mysqldump = 'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump';
            $command = "\"{$mysqldump}\" --host={$dbHost} --port={$dbPort} --user={$dbUser} --password={$dbPass} {$dbName} > \"{$filePath}\" 2>NUL";

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !File::exists($filePath)) {
                $this->error('Error al crear backup');
                return self::FAILURE;
            }

            $this->info("Backup creado: {$fileName}");

            // Comprimir si se solicito
            if ($this->option('compress')) {
                $zipPath = str_replace('.sql', '.zip', $filePath);
                $zip = new \ZipArchive();
                if ($zip->open($zipPath, \ZipArchive::CREATE) === true) {
                    $zip->addFile($filePath, $fileName);
                    $zip->close();
                    File::delete($filePath);
                    $this->info("Comprimido: " . basename($zipPath));
                }
            }

            // Limpiar backups viejos
            $keepDays = (int) $this->option('keep-days');
            $fechaLimite = now()->subDays($keepDays);
            foreach (File::files($backupPath) as $file) {
                if (File::lastModified($file) < $fechaLimite->timestamp) {
                    File::delete($file);
                }
            }

            $this->info('Backup completado');
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
