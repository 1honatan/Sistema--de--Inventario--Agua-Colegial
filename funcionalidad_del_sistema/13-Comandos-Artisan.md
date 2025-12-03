# 13. COMANDOS ARTISAN (CONSOLE COMMANDS)

## 📋 ÍNDICE DE CONTENIDO

1. [BackupDatabase.php - Backups Automáticos](#backupdatabasephp)
2. [VerificarStockBajo.php - Alertas de Stock](#verificarstockbajophp)
3. [SincronizarProduccionInventario.php - Sincronización](#sincronizarproduccioninventariophp)
4. [ClearAllCaches.php - Limpieza de Caché](#clearallcachesphp)
5. [Resumen de Funcionalidades](#resumen)
6. [Programación con Cron](#cron)
7. [TODOs y Mejoras Futuras](#todos)

---

## 🎯 PROPÓSITO GENERAL

Este documento explica **línea por línea** cuatro comandos Artisan en `app/Console/Commands/`:

1. **BackupDatabase.php**: Backups automáticos de base de datos MySQL
2. **VerificarStockBajo.php**: Generación de alertas por stock bajo
3. **SincronizarProduccionInventario.php**: Sincronización de producción con inventario
4. **ClearAllCaches.php**: Limpieza completa de caches del sistema

**¿Por qué son críticos?**
Los comandos Artisan permiten:
- Automatizar tareas administrativas
- Ejecutar procesos en segundo plano
- Programar mantenimiento con Cron
- Generar alertas proactivas

---

# BACKUPDATABASE.PHP

**Ubicación**: `app/Console/Commands/BackupDatabase.php`
**Líneas totales**: 286
**Complejidad**: Alta
**Propósito**: Generar backups automáticos de la base de datos MySQL

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DECLARACIONES Y NAMESPACE (Líneas 1-20)

```php
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
```
**¿Qué hace?** Importa clases y documenta el comando.
**¿De dónde sale?** Laravel Console Framework.
**¿Para qué sirve?**
- `Command`: Clase base para comandos Artisan
- `File`: Operaciones de archivos
- `Storage`: Abstracción de almacenamiento

**Usos del comando**:
```bash
# Backup básico
php artisan backup:database

# Backup comprimido en ZIP
php artisan backup:database --compress

# Mantener solo últimos 7 días
php artisan backup:database --keep-days=7

# Combinar opciones
php artisan backup:database --compress --keep-days=15
```

---

### 🟢 SECCIÓN 2: CONFIGURACIÓN DEL COMANDO (Líneas 21-37)

```php
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
```
**¿Qué hace?** Define firma y descripción del comando.
**¿De dónde sale?** Sintaxis de comandos Artisan.
**¿Para qué sirve?** Configurar cómo se invoca el comando.

**¿Qué es `$signature`?**
- Define el nombre del comando: `backup:database`
- Define opciones (flags):
  - `--compress`: Boolean, activar compresión
  - `--keep-days=30`: Entero con valor por defecto 30

**¿Cómo se usa?**
```bash
# Ver ayuda del comando
php artisan help backup:database

# Output:
# Description:
#   Realizar backup automático de la base de datos MySQL
#
# Options:
#   --compress         Comprimir el backup en formato ZIP
#   --keep-days[=30]   Número de días para mantener backups antiguos
```

---

### 🟢 SECCIÓN 3: MÉTODO HANDLE - INICIO (Líneas 44-60)

```php
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
```
**¿Qué hace?** Inicia proceso de backup.
**¿De dónde sale?** Método principal del comando.
**¿Para qué sirve?** Ejecutar lógica del backup.

**¿Qué es `$this->info()`?**
- Imprime mensaje verde en consola
- Equivalente a `echo` pero con formato Laravel
- También hay: `$this->error()`, `$this->warn()`, `$this->comment()`

**¿Qué hace `storage_path('app/backups')`?**
- Retorna ruta completa: `C:\xampp\htdocs\agua_colegial\storage\app\backups`
- Laravel helper para rutas de storage

**¿Qué hace `File::makeDirectory($path, 0755, true)`?**
- Crea directorio con permisos 0755 (lectura/escritura)
- Tercer parámetro `true` = recursivo (crea padres si no existen)

**Ejemplo de ejecución**:
```bash
$ php artisan backup:database

🔄 Iniciando backup de base de datos...
📁 Directorio de backups creado: C:\xampp\htdocs\agua_colegial\storage\app\backups
```

---

### 🟢 SECCIÓN 4: OBTENER CONFIGURACIÓN DE BD (Líneas 62-72)

```php
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
```
**¿Qué hace?** Lee configuración de BD y genera nombre de archivo.
**¿De dónde sale?** Helper `config()` lee archivo `config/database.php`.
**¿Para qué sirve?** Obtener credenciales para mysqldump.

**¿De dónde vienen estos valores?**
Archivo `config/database.php`:
```php
'connections' => [
    'mysql' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'agua_colegial_bd'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
    ],
],
```

Archivo `.env`:
```env
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=agua_colegial_bd
DB_USERNAME=root
DB_PASSWORD=
```

**¿Qué hace `now()->format('Y-m-d_H-i-s')`?**
- Genera timestamp actual en formato: `2025-12-02_14-30-45`
- Permite ordenar backups cronológicamente
- Previene colisiones de nombres

**Ejemplo de nombres generados**:
```
agua_colegial_backup_2025-12-01_08-00-00.sql
agua_colegial_backup_2025-12-01_20-00-00.sql
agua_colegial_backup_2025-12-02_08-00-00.sql
```

---

### 🟢 SECCIÓN 5: EJECUTAR MYSQLDUMP (Líneas 74-100)

```php
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
```
**¿Qué hace?** Ejecuta comando mysqldump y verifica éxito.
**¿De dónde sale?** Función PHP `exec()`.
**¿Para qué sirve?** Generar archivo SQL con estructura y datos de BD.

**¿Qué es `exec($command, $output, $returnCode)`?**
- Ejecuta comando de sistema operativo
- `$output`: Array con salida del comando (stdout)
- `$returnCode`: Código de retorno (0 = éxito, != 0 = error)

**Ejemplo de comando generado** (ver método `construirComandoMysqldump`):
```bash
# Windows (XAMPP):
"C:\xampp\mysql\bin\mysqldump" --host=127.0.0.1 --port=3307 --user=root --password= --single-transaction --routines --triggers --events --add-drop-table --extended-insert agua_colegial_bd > "C:\xampp\htdocs\agua_colegial\storage\app\backups\agua_colegial_backup_2025-12-02_14-30-45.sql" 2>NUL

# Linux:
mysqldump --host=127.0.0.1 --port=3306 --user=root --password='secret' --single-transaction --routines --triggers --events --add-drop-table --extended-insert agua_colegial_bd > "/var/www/html/agua_colegial/storage/app/backups/agua_colegial_backup_2025-12-02_14-30-45.sql"
```

**Salida en consola**:
```bash
📦 Generando backup...
✅ Backup generado: agua_colegial_backup_2025-12-02_14-30-45.sql (2.45 MB)
```

---

### 🟢 SECCIÓN 6: COMPRIMIR Y LIMPIAR (Líneas 102-127)

```php
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
```
**¿Qué hace?** Comprime backup (opcional) y limpia backups antiguos.
**¿De dónde sale?** Métodos auxiliares del comando.
**¿Para qué sirve?** Ahorrar espacio y mantener solo backups recientes.

**¿Qué es `$this->option('compress')`?**
- Lee valor del flag `--compress`
- Retorna `true` si se pasó el flag, `false` si no

**Flujo con compresión**:
```
1. Generar agua_colegial_backup_2025-12-02_14-30-45.sql (2.45 MB)
2. Comprimir a agua_colegial_backup_2025-12-02_14-30-45.zip (450 KB)
3. Eliminar .sql original (ahorrar espacio)
```

**Ejemplo de ejecución con opciones**:
```bash
$ php artisan backup:database --compress --keep-days=7

🔄 Iniciando backup de base de datos...
📦 Generando backup...
✅ Backup generado: agua_colegial_backup_2025-12-02_14-30-45.sql (2.45 MB)
🗜️  Backup comprimido: C:\xampp\htdocs\agua_colegial\storage\app\backups\agua_colegial_backup_2025-12-02_14-30-45.zip (450 KB)
🗑️  Eliminados 3 backups antiguos (>7 días)
✅ Proceso de backup completado exitosamente.

📂 Ubicación: C:\xampp\htdocs\agua_colegial\storage\app\backups
```

---

### 🟢 SECCIÓN 7: VERIFICAR MYSQLDUMP (Líneas 134-145)

```php
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
```
**¿Qué hace?** Verifica que mysqldump esté disponible.
**¿De dónde sale?** Verificación de dependencias del sistema.
**¿Para qué sirve?** Fallar temprano si mysqldump no existe.

**¿Por qué rutas diferentes?**
```
Windows (XAMPP):
- mysqldump NO está en PATH
- Ruta fija: C:\xampp\mysql\bin\mysqldump.exe

Linux/Mac:
- mysqldump SÍ está en PATH (instalado con MySQL)
- Se puede llamar directamente: mysqldump
```

**¿Qué es `2>NUL` y `2>/dev/null`?**
- Redirige stderr (errores) a la nada
- Evita mostrar mensajes de error si mysqldump no existe

**Ejemplo de verificación**:
```bash
# Windows:
C:\xampp\mysql\bin\mysqldump --version
# mysqldump  Ver 10.19 Distrib 10.4.24-MariaDB, for Win64 (AMD64)

# Linux:
mysqldump --version
# mysqldump  Ver 8.0.27 for Linux on x86_64 (MySQL Community Server - GPL)
```

---

### 🟢 SECCIÓN 8: CONSTRUIR COMANDO MYSQLDUMP (Líneas 158-204)

```php
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
```
**¿Qué hace?** Construye comando mysqldump con opciones óptimas.
**¿De dónde sale?** Best practices de backups MySQL.
**¿Para qué sirve?** Generar dump completo y consistente.

**Opciones de mysqldump explicadas**:

#### `--single-transaction`
- Hace backup consistente sin bloquear tablas
- **CRÍTICO** para bases de datos en producción
- Usa transacciones InnoDB

**Sin esta opción**:
```
Backup comienza → Tabla A se respalda
Usuario inserta en Tabla A → Cambio NO está en backup
Backup termina → Inconsistencia
```

**Con --single-transaction**:
```
Backup comienza (snapshot del momento)
Usuarios siguen trabajando (no se bloquea)
Backup contiene estado consistente del momento inicial
```

#### `--routines --triggers --events`
- Respalda procedimientos almacenados, triggers y eventos programados
- Importante si se usan funciones personalizadas en BD

#### `--add-drop-table`
- Agrega `DROP TABLE IF EXISTS` antes de cada `CREATE TABLE`
- Permite restaurar backup sobre BD existente

#### `--extended-insert`
- Agrupa múltiples `INSERT` en uno solo
- Reduce tamaño de archivo y acelera restauración

**Ejemplo de output SQL**:
```sql
-- Sin --extended-insert (grande, lento):
INSERT INTO productos VALUES (1, 'Botellones', 20.00);
INSERT INTO productos VALUES (2, 'Agua natural', 10.00);
INSERT INTO productos VALUES (3, 'Gelatina', 5.00);

-- Con --extended-insert (compacto, rápido):
INSERT INTO productos VALUES
(1, 'Botellones', 20.00),
(2, 'Agua natural', 10.00),
(3, 'Gelatina', 5.00);
```

**¿Qué hace `escapeshellarg()`?**
- Escapa caracteres especiales para shell
- Previene command injection
- Ejemplo: `password'abc` → `'password'\''abc'`

---

### 🟢 SECCIÓN 9: COMPRIMIR BACKUP (Líneas 213-231)

```php
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
```
**¿Qué hace?** Comprime archivo SQL en formato ZIP.
**¿De dónde sale?** Clase `ZipArchive` de PHP.
**¿Para qué sirve?** Reducir tamaño de backups (compresión ~80%).

**Ejemplo de compresión**:
```
Original: agua_colegial_backup_2025-12-02_14-30-45.sql (2.45 MB)
Comprimido: agua_colegial_backup_2025-12-02_14-30-45.zip (450 KB)
Ahorro: 82% de espacio
```

**¿Cómo funciona `ZipArchive`?**
```php
$zip = new \ZipArchive();
$zip->open($zipPath, \ZipArchive::CREATE); // Crear archivo ZIP
$zip->addFile($filePath, $fileName);       // Agregar archivo SQL al ZIP
$zip->close();                             // Cerrar y guardar ZIP
```

**¿Por qué retornar `?string`?**
- `?` indica que puede retornar `null` (nullable)
- Retorna `null` si falla la compresión
- Retorna `string` (ruta del ZIP) si es exitoso

---

### 🟢 SECCIÓN 10: LIMPIAR BACKUPS ANTIGUOS (Líneas 240-256)

```php
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
```
**¿Qué hace?** Elimina backups más antiguos que N días.
**¿De dónde sale?** Política de retención de backups.
**¿Para qué sirve?** Liberar espacio en disco.

**¿Qué hace `now()->subDays($keepDays)`?**
- Calcula fecha límite
- Ejemplo: Si `$keepDays = 30`, retorna fecha de hace 30 días

**¿Qué hace `File::lastModified($file)`?**
- Retorna timestamp de última modificación del archivo
- Equivalente a `filemtime()` de PHP

**Ejemplo de limpieza**:
```
Hoy: 2025-12-02
keep-days: 7
Fecha límite: 2025-11-25

Backups existentes:
- agua_colegial_backup_2025-11-20_08-00-00.zip (20 Nov) → ELIMINAR ❌
- agua_colegial_backup_2025-11-24_08-00-00.zip (24 Nov) → ELIMINAR ❌
- agua_colegial_backup_2025-11-27_08-00-00.zip (27 Nov) → MANTENER ✅
- agua_colegial_backup_2025-12-01_08-00-00.zip (01 Dic) → MANTENER ✅

Resultado: Eliminados 2 backups antiguos (>7 días)
```

---

### 🟢 SECCIÓN 11: HELPERS (Líneas 264-284)

```php
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    protected function estaEnWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}
```
**¿Qué hace?** Métodos auxiliares para formateo y detección de SO.
**¿De dónde sale?** Utilidades comunes.
**¿Para qué sirve?** Facilitar lectura de tamaños y adaptar comandos al SO.

**¿Cómo funciona `formatBytes()`?**
```php
formatBytes(1234)       → "1.21 KB"
formatBytes(1234567)    → "1.18 MB"
formatBytes(1234567890) → "1.15 GB"
```

**Algoritmo**:
1. Calcular potencia de 1024 necesaria (log)
2. Dividir bytes por 1024^potencia
3. Redondear a 2 decimales
4. Agregar unidad (KB, MB, GB)

**¿Qué hace `1 << (10 * $pow)`?**
- Operador bit shift: `1 << 10` = 1024
- `1 << 20` = 1024^2 = 1,048,576
- Más eficiente que `pow(1024, $pow)`

---

# VERIFICARSTOCKBAJO.PHP

**Ubicación**: `app/Console/Commands/VerificarStockBajo.php`
**Líneas totales**: 131
**Complejidad**: Media
**Propósito**: Generar alertas automáticas cuando el stock está bajo

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: CONFIGURACIÓN (Líneas 1-40)

```php
<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AlertaStock;
use App\Models\Producto;
use Illuminate\Console\Command;

/**
 * Comando Artisan para verificar niveles de stock bajo.
 *
 * Recorre todos los productos activos y genera alertas si el stock
 * está por debajo del umbral mínimo configurado.
 *
 * Uso: php artisan verificar:stock-bajo
 *      php artisan verificar:stock-bajo --umbral=20
 */
class VerificarStockBajo extends Command
{
    protected $signature = 'verificar:stock-bajo
                            {--umbral=10 : Umbral mínimo de stock para generar alerta}';

    protected $description = 'Verificar niveles de stock bajo y generar alertas automáticamente';

    public function handle(): int
    {
        $this->info('🔍 Iniciando verificación de stock bajo...');
        $this->newLine();

        // Obtener umbral de stock desde opciones
        $umbral = (int) $this->option('umbral');
        $verbose = $this->getOutput()->isVerbose();

        // Obtener todos los productos activos
        $productos = Producto::where('estado', 'activo')->get();

        if ($productos->isEmpty()) {
            $this->warn('⚠️  No hay productos activos para verificar.');
            return Command::SUCCESS;
        }
```
**¿Qué hace?** Configura comando y obtiene productos activos.
**¿De dónde sale?** Modelo Producto y configuración.
**¿Para qué sirve?** Iniciar verificación de stock.

**¿Qué es `--umbral=10`?**
- Opción con valor por defecto 10
- Se puede cambiar al ejecutar: `--umbral=20`
- Define el límite de stock para generar alerta

**¿Qué hace `$this->getOutput()->isVerbose()`?**
- Detecta si se usa flag `-v` (verbose)
- Permite mostrar más detalles en ejecución
- Ejemplo: `php artisan verificar:stock-bajo -v`

**Ejecución normal vs verbose**:
```bash
# Normal (sin -v):
🔍 Iniciando verificación de stock bajo...
📦 Verificando 15 productos...
[████████████████] 15/15
✅ Verificación completada

# Verbose (con -v):
🔍 Iniciando verificación de stock bajo...
📦 Verificando 15 productos...
⚠️  Nueva alerta: Botellones (Stock: 5, Urgencia: alta)
🔄 Alerta actualizada: Agua natural (Stock: 8)
[████████████████] 15/15
✅ Verificación completada
```

---

### 🟢 SECCIÓN 2: VERIFICACIÓN CON BARRA DE PROGRESO (Líneas 57-96)

```php
        $this->info("📦 Verificando {$productos->count()} productos...");
        $this->newLine();

        $alertasGeneradas = 0;
        $alertasActualizadas = 0;
        $productosSinProblemas = 0;

        // Crear barra de progreso
        $bar = $this->output->createProgressBar($productos->count());
        $bar->start();

        foreach ($productos as $producto) {
            // Generar alerta si es necesario
            $alerta = AlertaStock::generarSiNecesario($producto, $umbral);

            if ($alerta) {
                if ($alerta->wasRecentlyCreated) {
                    $alertasGeneradas++;

                    if ($verbose) {
                        $this->newLine();
                        $this->warn("⚠️  Nueva alerta: {$producto->nombre} (Stock: {$alerta->cantidad_actual}, Urgencia: {$alerta->nivel_urgencia})");
                    }
                } else {
                    $alertasActualizadas++;

                    if ($verbose) {
                        $this->newLine();
                        $this->info("🔄 Alerta actualizada: {$producto->nombre} (Stock: {$alerta->cantidad_actual})");
                    }
                }
            } else {
                $productosSinProblemas++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
```
**¿Qué hace?** Verifica cada producto y muestra progreso.
**¿De dónde sale?** Componente ProgressBar de Laravel.
**¿Para qué sirve?** Feedback visual del progreso.

**¿Qué es `createProgressBar()`?**
- Crea barra de progreso en consola
- Actualiza automáticamente con `advance()`
- Finaliza con `finish()`

**Ejemplo de barra de progreso**:
```bash
📦 Verificando 15 productos...

[████████          ] 8/15  # En progreso
[████████████████] 15/15  # Completada
```

**¿Qué es `AlertaStock::generarSiNecesario()`?**
- Método estático del modelo AlertaStock
- Verifica si stock < umbral
- Si es necesario, crea o actualiza alerta
- Retorna `null` si stock está OK

**Lógica de alertas**:
```php
// Modelo AlertaStock
public static function generarSiNecesario(Producto $producto, int $umbral)
{
    $stock = Inventario::stockDisponible($producto->id);

    if ($stock <= $umbral) {
        // Stock bajo: Crear o actualizar alerta
        $nivelUrgencia = $stock == 0 ? 'critica' : ($stock <= 5 ? 'alta' : 'media');

        return self::updateOrCreate(
            ['id_producto' => $producto->id],
            [
                'cantidad_actual' => $stock,
                'cantidad_minima' => $umbral,
                'nivel_urgencia' => $nivelUrgencia,
                'estado' => 'activa'
            ]
        );
    }

    // Stock OK: Marcar alerta como resuelta si existe
    self::where('id_producto', $producto->id)->update(['estado' => 'resuelta']);

    return null;
}
```

**¿Qué es `wasRecentlyCreated`?**
- Propiedad de Eloquent
- `true` si el modelo se acaba de crear
- `false` si ya existía y se actualizó

---

### 🟢 SECCIÓN 3: RESUMEN Y ALERTAS CRÍTICAS (Líneas 98-129)

```php
        // Mostrar resumen
        $this->info('✅ Verificación completada');
        $this->newLine();

        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Alertas generadas', $alertasGeneradas],
                ['Alertas actualizadas', $alertasActualizadas],
                ['Productos sin problemas', $productosSinProblemas],
                ['Total productos verificados', $productos->count()],
            ]
        );

        // Mostrar alertas críticas
        $alertasCriticas = AlertaStock::activas()
            ->porNivelUrgencia('critica')
            ->with('producto')
            ->get();

        if ($alertasCriticas->isNotEmpty()) {
            $this->newLine();
            $this->error("🚨 {$alertasCriticas->count()} ALERTA(S) CRÍTICA(S) DETECTADA(S):");
            $this->newLine();

            foreach ($alertasCriticas as $alerta) {
                $this->error("  • {$alerta->producto->nombre}: Stock AGOTADO (0 unidades)");
            }
        }

        return Command::SUCCESS;
    }
}
```
**¿Qué hace?** Muestra resumen y destaca alertas críticas.
**¿De dónde sale?** Helpers de consola de Laravel.
**¿Para qué sirve?** Informar resultado de verificación.

**¿Qué hace `$this->table()`?**
- Crea tabla formateada en consola
- Primer parámetro: headers
- Segundo parámetro: filas de datos

**Ejemplo de salida**:
```bash
✅ Verificación completada

┌──────────────────────────────┬──────────┐
│ Resultado                    │ Cantidad │
├──────────────────────────────┼──────────┤
│ Alertas generadas            │ 3        │
│ Alertas actualizadas         │ 2        │
│ Productos sin problemas      │ 10       │
│ Total productos verificados  │ 15       │
└──────────────────────────────┴──────────┘

🚨 1 ALERTA(S) CRÍTICA(S) DETECTADA(S):

  • Botellones: Stock AGOTADO (0 unidades)
```

**¿Qué son los scopes `activas()` y `porNivelUrgencia()`?**
```php
// Modelo AlertaStock
public function scopeActivas($query)
{
    return $query->where('estado', 'activa');
}

public function scopePorNivelUrgencia($query, $nivel)
{
    return $query->where('nivel_urgencia', $nivel);
}

// Uso:
AlertaStock::activas()->porNivelUrgencia('critica')->get();
// SQL: SELECT * FROM alertas_stock WHERE estado = 'activa' AND nivel_urgencia = 'critica'
```

---

# SINCRONIZARPRODUCCIONINVENTARIO.PHP

**Ubicación**: `app/Console/Commands/SincronizarProduccionInventario.php`
**Líneas totales**: 99
**Complejidad**: Media
**Propósito**: Sincronizar producciones antiguas con inventario (migración de datos)

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN COMPLETA (Líneas 1-99)

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Control\ProduccionDiaria;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class SincronizarProduccionInventario extends Command
{
    protected $signature = 'produccion:sincronizar-inventario';

    protected $description = 'Sincroniza las producciones existentes con el inventario general';

    public function handle()
    {
        $this->info('Sincronizando producciones con inventario...');

        $producciones = ProduccionDiaria::with('productos.producto')->get();

        if ($producciones->isEmpty()) {
            $this->warn('No hay producciones registradas para sincronizar.');
            return 0;
        }

        $sincronizados = 0;
        $errores = 0;

        DB::beginTransaction();

        try {
            foreach ($producciones as $produccion) {
                foreach ($produccion->productos as $productoProduccion) {
                    $producto = $productoProduccion->producto;

                    if (!$producto) {
                        $this->error("Producto no encontrado para producción #{$produccion->id}");
                        $errores++;
                        continue;
                    }

                    // Verificar si ya existe en inventario
                    $existe = Inventario::where('referencia', 'Producción #' . $produccion->id)
                        ->where('id_producto', $producto->id)
                        ->exists();

                    if (!$existe) {
                        // Crear entrada en inventario
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $productoProduccion->cantidad,
                            'origen' => 'Producción Diaria',
                            'referencia' => 'Producción #' . $produccion->id,
                            'id_usuario' => 1, // Usuario admin por defecto
                            'fecha_movimiento' => $produccion->fecha,
                            'observacion' => 'Entrada automática desde Control de Producción Diaria - Responsable: ' . $produccion->responsable,
                        ]);

                        $sincronizados++;
                        $this->line("✓ Sincronizado: Producción #{$produccion->id} - {$producto->nombre} ({$productoProduccion->cantidad} unidades)");
                    }
                }
            }

            DB::commit();

            $this->info("\n=================================");
            $this->info("Sincronización completada:");
            $this->info("- Entradas creadas: {$sincronizados}");
            if ($errores > 0) {
                $this->warn("- Errores: {$errores}");
            }
            $this->info("=================================");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error durante la sincronización: ' . $e->getMessage());
            return 1;
        }
    }
}
```

**¿Qué hace?** Migra producciones antiguas a tabla inventario.
**¿De dónde sale?** Necesidad de migración de datos.
**¿Para qué sirve?** Ejecutar UNA VEZ después de implementar sistema de inventario.

**Contexto histórico**:
```
Antes (sin inventario):
- Solo existía control_produccion_diaria
- No había tabla inventario
- No se calculaba stock disponible

Después (con inventario):
- Se agregó tabla inventario
- ProduccionDiariaController ahora crea entradas automáticas
- Pero producciones ANTIGUAS no están en inventario

Este comando:
- Recorre todas las producciones existentes
- Crea entradas de inventario retroactivas
- Solo si no existen ya (idempotente)
```

**¿Por qué `DB::beginTransaction()`?**
- Si falla a mitad, se revierten TODOS los cambios
- No queremos sincronización parcial
- Todo o nada

**¿Qué hace `where('referencia', 'Producción #' . $produccion->id)->exists()`?**
- Verifica si ya se sincronizó esta producción
- Previene duplicados
- Permite ejecutar comando múltiples veces

**Ejemplo de ejecución**:
```bash
$ php artisan produccion:sincronizar-inventario

Sincronizando producciones con inventario...
✓ Sincronizado: Producción #1 - Botellones (500 unidades)
✓ Sincronizado: Producción #1 - Agua natural (300 unidades)
✓ Sincronizado: Producción #2 - Botellones (450 unidades)
✓ Sincronizado: Producción #3 - Gelatina (200 unidades)

=================================
Sincronización completada:
- Entradas creadas: 4
=================================
```

**IMPORTANTE**: Este comando se ejecuta UNA VEZ en producción después de deploy. No está programado en cron.

---

# CLEARALLCACHES.PHP

**Ubicación**: `app/Console/Commands/ClearAllCaches.php`
**Líneas totales**: 97
**Complejidad**: Baja
**Propósito**: Limpiar todos los caches del sistema Laravel

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN COMPLETA (Líneas 1-97)

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ClearAllCaches extends Command
{
    protected $signature = 'cache:clear-all';

    protected $description = 'Limpia todos los caches del sistema (Laravel, vistas, configuración, logs, sesiones)';

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
```

**¿Qué hace?** Limpia TODOS los caches de Laravel.
**¿De dónde sale?** Comandos built-in de Laravel.
**¿Para qué sirve?** Resolver problemas de cach sin reiniciar servidor.

**¿Cuándo usar este comando?**
- Después de modificar archivos `.env`
- Después de cambiar rutas en `routes/web.php`
- Después de actualizar configuración en `config/`
- Cuando hay errores extraños que desaparecen al reiniciar

**Comandos de Laravel llamados**:

#### `cache:clear`
- Limpia cache de aplicación (Redis, Memcached, File)
- Usado por `Cache::put()`, `Cache::get()`

#### `config:clear`
- Limpia cache de configuración
- Laravel cachea todos los archivos `config/*.php`

#### `route:clear`
- Limpia cache de rutas
- Laravel cachea rutas para mejor performance

#### `view:clear`
- Limpia vistas Blade compiladas
- Laravel convierte `.blade.php` a PHP puro

#### `event:clear`
- Limpia cache de eventos
- Laravel cachea listeners de eventos

#### `optimize:clear`
- Limpia cache de optimización
- Incluye: compiled.php, services.php, packages.php

**Limpieza de archivos manualmente**:

```php
// Vaciar log
$logFile = storage_path('logs/laravel.log');
File::put($logFile, ''); // Truncar archivo a 0 bytes

// Eliminar sesiones
$sessionsPath = storage_path('framework/sessions');
$files = File::files($sessionsPath);
foreach ($files as $file) {
    File::delete($file); // Eliminar cada archivo de sesión
}

// Limpiar directorio de cache
$cachePath = storage_path('framework/cache/data');
File::cleanDirectory($cachePath); // Eliminar todo el contenido
```

**Ejemplo de ejecución**:
```bash
$ php artisan cache:clear-all

🧹 Iniciando limpieza completa de caches...

→ Limpiando cache de aplicación...
  ✓ Cache de aplicación limpiado
→ Limpiando cache de configuración...
  ✓ Cache de configuración limpiado
→ Limpiando cache de rutas...
  ✓ Cache de rutas limpiado
→ Limpiando vistas compiladas...
  ✓ Vistas compiladas limpiadas
→ Limpiando cache de eventos...
  ✓ Cache de eventos limpiado
→ Limpiando cache de optimización...
  ✓ Cache de optimización limpiado
→ Limpiando logs antiguos...
  ✓ Logs limpiados
→ Limpiando sesiones antiguas...
  ✓ Sesiones antiguas eliminadas
→ Limpiando cache de datos del framework...
  ✓ Cache de datos limpiado

✅ Limpieza completa finalizada exitosamente!
📅 Fecha: 02/12/2025 15:45:30
```

**¿Por qué NO usar en producción frecuentemente?**
- Elimina optimizaciones de performance
- Laravel debe reconstruir caches
- Puede causar lentitud temporal
- Usar solo cuando sea necesario

---

## 📊 RESUMEN DE FUNCIONALIDADES

| Comando | Propósito | Frecuencia | Automático |
|---------|-----------|------------|------------|
| backup:database | Backups de BD | Diario | ✅ Sí (Cron) |
| verificar:stock-bajo | Alertas de stock | Diario | ✅ Sí (Cron) |
| produccion:sincronizar-inventario | Migración de datos | Una vez | ❌ No |
| cache:clear-all | Limpieza de cache | Cuando sea necesario | ❌ No |

### Uso de Cada Comando

**BackupDatabase**:
```bash
# Manual
php artisan backup:database --compress --keep-days=15

# Programado (ver sección Cron)
0 2 * * * php artisan backup:database --compress --keep-days=30
```

**VerificarStockBajo**:
```bash
# Manual
php artisan verificar:stock-bajo --umbral=10

# Verbose (más detalles)
php artisan verificar:stock-bajo -v

# Programado
0 8 * * * php artisan verificar:stock-bajo --umbral=10
```

**SincronizarProduccionInventario**:
```bash
# Solo una vez después de deploy
php artisan produccion:sincronizar-inventario
```

**ClearAllCaches**:
```bash
# Después de cambios en .env o config
php artisan cache:clear-all

# O comandos individuales
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## ⏰ PROGRAMACIÓN CON CRON

### Archivo: `app/Console/Kernel.php`

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Comandos Artisan de la aplicación.
     */
    protected $commands = [
        Commands\BackupDatabase::class,
        Commands\VerificarStockBajo::class,
        Commands\SincronizarProduccionInventario::class,
        Commands\ClearAllCaches::class,
    ];

    /**
     * Programar comandos automáticos.
     */
    protected function schedule(Schedule $schedule)
    {
        // Backup diario a las 2 AM (comprimido, mantener 30 días)
        $schedule->command('backup:database --compress --keep-days=30')
            ->dailyAt('02:00')
            ->onSuccess(function () {
                \Log::info('Backup diario completado exitosamente');
            })
            ->onFailure(function () {
                \Log::error('Error al generar backup diario');
                // Enviar notificación a admin
            });

        // Verificar stock bajo a las 8 AM todos los días
        $schedule->command('verificar:stock-bajo --umbral=10')
            ->dailyAt('08:00')
            ->onSuccess(function () {
                \Log::info('Verificación de stock completada');
            });

        // Backup adicional los domingos a las 3 AM (semanal)
        $schedule->command('backup:database --compress --keep-days=90')
            ->weeklyOn(0, '03:00') // 0 = Domingo
            ->appendOutputTo(storage_path('logs/backup-weekly.log'));
    }
}
```

### Configurar Cron en el Servidor

**Windows (Programador de Tareas)**:
```
Tarea programada:
- Nombre: Laravel Schedule
- Trigger: Diariamente, repetir cada minuto
- Acción: C:\xampp\php\php.exe C:\xampp\htdocs\agua_colegial\artisan schedule:run
```

**Linux**:
```bash
# Editar crontab
crontab -e

# Agregar línea (ejecutar cada minuto)
* * * * * cd /var/www/html/agua_colegial && php artisan schedule:run >> /dev/null 2>&1
```

**¿Por qué cada minuto?**
- Laravel internamente verifica qué comandos deben ejecutarse
- Si no es hora, no hace nada (rápido)
- Si es hora, ejecuta el comando programado

---

## ✅ TODOS Y MEJORAS FUTURAS

### TODO 1: Notificaciones de Backup

**Problema**: Admin no sabe si backups fallan.
**Solución**: Enviar email/Slack cuando falla backup.

```php
// BackupDatabase.php
public function handle(): int
{
    try {
        // ... código de backup

        // Enviar notificación de éxito
        \Notification::send(
            User::role('admin')->get(),
            new BackupSuccessNotification($filePath, $fileSize)
        );

        return self::SUCCESS;
    } catch (\Exception $e) {
        // Enviar notificación de error
        \Notification::send(
            User::role('admin')->get(),
            new BackupFailureNotification($e->getMessage())
        );

        return self::FAILURE;
    }
}
```

---

### TODO 2: Verificar Integridad de Backups

**Problema**: No se verifica que el backup sea restaurable.
**Solución**: Intentar restaurar en BD de prueba.

```php
// BackupDatabase.php
protected function verificarIntegridadBackup(string $filePath): bool
{
    $testDb = 'agua_colegial_test';

    // Crear BD de prueba
    exec("mysql -u root -e 'CREATE DATABASE IF NOT EXISTS {$testDb}'");

    // Intentar restaurar
    $command = "mysql -u root {$testDb} < \"{$filePath}\"";
    exec($command, $output, $returnCode);

    // Eliminar BD de prueba
    exec("mysql -u root -e 'DROP DATABASE {$testDb}'");

    return $returnCode === 0;
}
```

---

### TODO 3: Alertas de Stock por Email

**Problema**: VerificarStockBajo solo guarda en BD, no notifica.
**Solución**: Enviar email a responsables.

```php
// VerificarStockBajo.php
public function handle(): int
{
    // ... código existente

    // Enviar email si hay alertas críticas
    if ($alertasCriticas->isNotEmpty()) {
        Mail::to('admin@aguacolegial.com')->send(
            new AlertaStockCriticoMail($alertasCriticas)
        );
    }

    return Command::SUCCESS;
}
```

---

### TODO 4: Backup Remoto (Nube)

**Problema**: Backups solo en servidor local (pérdida si falla disco).
**Solución**: Subir a Google Drive / AWS S3.

```php
// BackupDatabase.php
protected function subirBackupRemoto(string $filePath): void
{
    // Opción 1: AWS S3
    Storage::disk('s3')->put(
        'backups/' . basename($filePath),
        File::get($filePath)
    );

    // Opción 2: Google Drive
    Storage::disk('google')->put(
        'backups/' . basename($filePath),
        File::get($filePath)
    );

    $this->info('☁️  Backup subido a la nube');
}
```

---

### TODO 5: Comando para Restaurar Backup

**Problema**: No hay comando para restaurar backups fácilmente.
**Solución**: Crear `php artisan backup:restore`.

```php
// app/Console/Commands/RestoreBackup.php
class RestoreBackup extends Command
{
    protected $signature = 'backup:restore {file}';

    protected $description = 'Restaurar backup de base de datos';

    public function handle()
    {
        $file = $this->argument('file');
        $backupPath = storage_path('app/backups/' . $file);

        if (!File::exists($backupPath)) {
            $this->error('Archivo de backup no encontrado');
            return self::FAILURE;
        }

        // Confirmar antes de restaurar
        if (!$this->confirm('¿Está seguro de restaurar este backup? Se perderán los datos actuales.')) {
            return self::FAILURE;
        }

        // Crear backup de seguridad antes de restaurar
        $this->call('backup:database', ['--compress' => true]);

        // Restaurar
        $dbName = config('database.connections.mysql.database');
        $command = "mysql -u root {$dbName} < \"{$backupPath}\"";
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            $this->info('✅ Backup restaurado exitosamente');
        } else {
            $this->error('❌ Error al restaurar backup');
        }

        return $returnCode === 0 ? self::SUCCESS : self::FAILURE;
    }
}
```

---

## 🎯 CONCLUSIÓN

### Comandos Artisan - Importancia Crítica

**Responsabilidades**:
- Automatizar tareas administrativas
- Generar backups automáticos de BD
- Alertas proactivas de stock bajo
- Sincronización de datos
- Mantenimiento del sistema

**Importancia crítica**:
- Backups previenen pérdida de datos
- Alertas de stock previenen desabastecimiento
- Comandos programados liberan tiempo de admin
- Automatización reduce errores humanos

**Estado actual**:
- ✅ BackupDatabase: Completo con compresión y limpieza automática
- ✅ VerificarStockBajo: Funcional con barra de progreso
- ✅ SincronizarProduccionInventario: Para migración única
- ✅ ClearAllCaches: Útil para troubleshooting

**Mejoras prioritarias**:
1. Notificaciones de backup (email/Slack)
2. Backup remoto (nube)
3. Verificación de integridad de backups
4. Alertas de stock por email
5. Comando de restauración de backups

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Sistema**: Agua Colegial v1.0
**Archivo**: 13-Comandos-Artisan.md
