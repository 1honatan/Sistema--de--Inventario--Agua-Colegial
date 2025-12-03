# 18. LANG, PUBLIC, STORAGE, TESTS Y ARCHIVOS RAÍZ

## 📁 CARPETA LANG/ (Traducciones)

### 📄 lang/es/validation.php
**Propósito**: Mensajes de validación en español

**Contenido**:
- 100+ reglas de validación traducidas
- Mensajes personalizados para cada tipo de error

**Ejemplos**:
```php
'required' => 'El campo :attribute es obligatorio.',
'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
'min' => [
    'numeric' => 'El campo :attribute debe ser al menos :min.',
    'string' => 'El campo :attribute debe tener al menos :min caracteres.',
],
'unique' => 'El :attribute ya está en uso.',
'confirmed' => 'La confirmación de :attribute no coincide.',
```

**Configuración en .env**:
```
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
```

---

## 📁 CARPETA PUBLIC/ (Archivos Públicos)

### 📄 public/index.php (21 líneas) - PUNTO DE ENTRADA
```php
define('LARAVEL_START', microtime(true));

// Verificar maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader de Composer
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Manejar request
$app->handleRequest(Request::capture());
```

**Flujo de ejecución**:
1. Marcar tiempo de inicio (para métricas)
2. Verificar modo mantenimiento
3. Cargar autoloader de Composer
4. Inicializar aplicación Laravel
5. Capturar y procesar request HTTP

### 📁 public/css/
- `global-styles.css`: Estilos globales personalizados

### 📁 public/js/
- `modern-components.js`: Componentes JavaScript personalizados

### 📁 public/build/ (Vite Assets Compilados)
```
build/
├── assets/
│   ├── app-B6W2gAiJ.js      (JavaScript compilado)
│   └── app-Ca3ve1MR.css     (CSS compilado)
└── manifest.json            (Manifest de Vite)
```

**Generados por**: `npm run build`
**Versionado**: Hash en nombre para cache busting

---

## 📁 CARPETA STORAGE/

### Estructura de Directorios
```
storage/
├── app/
│   ├── private/          (Archivos privados, no accesibles vía web)
│   └── public/           (Archivos públicos - symlink desde public/storage)
├── framework/
│   ├── cache/            (Cache del framework)
│   ├── sessions/         (Sesiones de usuarios)
│   ├── testing/          (Base de datos SQLite para tests)
│   ├── views/            (Vistas Blade compiladas)
│   └── maintenance.php   (Archivo de maintenance mode)
├── logs/
│   └── laravel.log       (Logs de la aplicación)
└── backups/              (Backups de base de datos)
```

### storage/app/
- **private/**: Archivos internos del sistema
- **public/**: Archivos accesibles públicamente (fotos, documentos)

**Crear symlink**:
```bash
php artisan storage:link
# Crea: public/storage → storage/app/public
```

### storage/framework/
- **cache/data/**: Cache de aplicación
- **sessions/**: Archivos de sesión (cuando SESSION_DRIVER=file)
- **views/**: Vistas Blade compiladas (.php)
- **maintenance.php**: Activado por `php artisan down`

### storage/logs/
```php
// Configuración en .env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

**Archivos**:
- `laravel.log`: Log principal (rotado diariamente en producción)
- Formato: `[YYYY-MM-DD HH:MM:SS] environment.LEVEL: mensaje`

**Niveles de log**:
- emergency, alert, critical, error, warning, notice, info, debug

### storage/backups/
**Generados por**: `php artisan backup:database`
**Formato**: `backup_YYYYMMDD_HHMMSS.sql` o `.sql.gz`
**Configuración**:
```
BACKUP_PATH=storage/backups
BACKUP_SCHEDULE=weekly
```

---

## 📁 CARPETA TESTS/ (Testing con Pest PHP)

### 📄 tests/Pest.php (89 líneas) - Configuración de Tests
```php
uses(
    Tests\TestCase::class,
    RefreshDatabase::class  // Resetea BD en cada test
)->in('Feature', 'Unit');
```

#### Helpers Personalizados

**autenticar()**: Crear usuario autenticado
```php
function autenticar(string $rol = 'admin'): \App\Models\Usuario
{
    $rolModel = \App\Models\Rol::firstOrCreate(['nombre' => $rol]);

    $usuario = \App\Models\Usuario::factory()->create([
        'id_rol' => $rolModel->id,
        'estado' => 'activo',
    ]);

    test()->actingAs($usuario);

    return $usuario;
}
```

**crearDatosPrueba()**: Crear datos base
```php
function crearDatosPrueba(): void
{
    \App\Models\Rol::factory()->create(['nombre' => 'admin']);
    \App\Models\Rol::factory()->create(['nombre' => 'produccion']);
    \App\Models\Producto::factory()->count(5)->create();
    \App\Models\Empleado::factory()->count(3)->create();
    \App\Models\Vehiculo::factory()->count(2)->create();
}
```

### 📄 tests/Feature/ (Tests de Integración)

#### InventarioValidationTest.php
- Valida reglas de negocio de inventario
- Verifica stock disponible
- Prueba validaciones de cantidad

#### UsuarioValidationTest.php
- Valida creación de usuarios
- Prueba autenticación
- Verifica roles y permisos

#### ProduccionValidationTest.php
- Valida registros de producción
- Verifica integración con inventario
- Prueba cálculos de producción

### Ejecutar Tests
```bash
php artisan test                    # Todos los tests
php artisan test --filter=Inventario # Tests específicos
php artisan test --coverage         # Con cobertura
```

---

## 📄 ARCHIVOS RAÍZ

### composer.json (94 líneas)
**Propósito**: Gestor de dependencias PHP

#### Dependencias Principales
```json
"require": {
    "php": "^8.2",
    "barryvdh/laravel-dompdf": "^3.1",      // Generación PDF
    "doctrine/dbal": "^4.3",                 // Abstracción BD
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.0",               // API auth
    "laravel/tinker": "^2.10.1",             // REPL PHP
    "maatwebsite/excel": "^3.1"              // Exportar Excel
}
```

#### Dependencias de Desarrollo
```json
"require-dev": {
    "fakerphp/faker": "^1.23",               // Datos falsos
    "laravel/pail": "^1.2.2",                // Log viewer
    "laravel/pint": "^1.24",                 // Code style
    "laravel/sail": "^1.41",                 // Docker
    "mockery/mockery": "^1.6",               // Mocking
    "nunomaduro/collision": "^8.6",          // Error formatting
    "phpunit/phpunit": "^11.5.3"             // Testing
}
```

#### Scripts Personalizados
```json
"scripts": {
    "setup": [
        "composer install",
        "@php artisan key:generate",
        "@php artisan migrate --force",
        "npm install",
        "npm run build"
    ],
    "dev": [
        "npx concurrently \"php artisan serve\" \"php artisan queue:listen\" \"php artisan pail\" \"npm run dev\""
    ],
    "test": [
        "@php artisan config:clear --ansi",
        "@php artisan test"
    ]
}
```

**Ejecutar scripts**:
```bash
composer setup  # Instalar proyecto completo
composer dev    # Iniciar entorno de desarrollo (4 procesos)
composer test   # Ejecutar tests
```

#### Autoloading PSR-4
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
}
```

#### Configuración Platform
```json
"config": {
    "platform": {
        "php": "8.2.12"  // Versión PHP requerida
    },
    "platform-check": false  // Desactivar verificación de versión
}
```

---

### package.json (17 líneas)
**Propósito**: Gestor de dependencias JavaScript

```json
{
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",    // Compilar para producción
        "dev": "vite"              // Servidor desarrollo
    },
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",      // TailwindCSS v4
        "axios": "^1.11.0",                  // HTTP client
        "concurrently": "^9.0.1",            // Múltiples comandos
        "laravel-vite-plugin": "^2.0.0",     // Integración Vite
        "tailwindcss": "^4.0.0",             // Utility CSS
        "vite": "^7.0.7"                     // Build tool
    }
}
```

**Comandos**:
```bash
npm install        # Instalar dependencias
npm run dev        # Servidor Vite (hot reload)
npm run build      # Compilar para producción
```

---

### .env.example (67 líneas)
**Propósito**: Plantilla de configuración de entorno

#### Configuración de Aplicación
```env
APP_NAME="Agua Colegial"
APP_ENV=local                    # local, production, testing
APP_KEY=                         # Generado con: php artisan key:generate
APP_DEBUG=true                   # SIEMPRE false en producción
APP_TIMEZONE=America/El_Salvador # ⚠️ Debe ser America/La_Paz
APP_URL=http://127.0.0.1:8001
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
```

#### Base de Datos
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306                     # 3307 en producción (XAMPP)
DB_DATABASE=agua_colegial_bd
DB_USERNAME=root
DB_PASSWORD=
```

#### Drivers
```env
CACHE_DRIVER=file               # file, redis, array
QUEUE_CONNECTION=sync           # sync, database, redis
SESSION_DRIVER=file             # file, cookie, database, redis
SESSION_LIFETIME=120            # Minutos
```

#### Email
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@aguacolegial.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Backups
```env
BACKUP_PATH=storage/backups
BACKUP_SCHEDULE=weekly          # daily, weekly
```

#### Logs
```env
LOG_CHANNEL=stack               # single, daily, slack, syslog, stack
LOG_LEVEL=debug                 # debug, info, warning, error
```

**Crear .env real**:
```bash
cp .env.example .env
php artisan key:generate  # Genera APP_KEY
```

---

### Otros Archivos Raíz

#### artisan (CLI)
```bash
#!/usr/bin/env php
```
**Uso**: `php artisan <comando>`

#### vite.config.js
**Propósito**: Configuración de Vite
```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

#### tailwind.config.js
**Propósito**: Configuración de TailwindCSS v4
```js
// TailwindCSS v4 usa CSS nativo, configuración mínima
```

#### phpunit.xml
**Propósito**: Configuración de PHPUnit para tests
```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_DATABASE" value=":memory:"/>
```

#### .gitignore
**Excluye**:
- `/vendor/`
- `/node_modules/`
- `.env`
- `storage/*.key`
- `/public/hot`
- `/public/storage`

#### README.md
**Contenido**: Documentación básica del proyecto

---

## 🚀 COMANDOS DE INSTALACIÓN

### Primera Instalación
```bash
# 1. Clonar repositorio
git clone <repo>

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Base de datos
php artisan migrate
php artisan db:seed

# 5. Symlink storage
php artisan storage:link

# 6. Compilar assets
npm run build
```

### Desarrollo
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Vite (hot reload)
npm run dev

# O usar script de composer (4 procesos):
composer dev
```

### Producción
```bash
# Compilar assets
npm run build

# Optimizar Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurar .env
APP_ENV=production
APP_DEBUG=false
```

---

## 📊 ESTADÍSTICAS DEL PROYECTO

### Dependencias PHP (composer.json)
| Tipo | Cantidad |
|------|----------|
| Principales | 6 |
| Desarrollo | 6 |
| **Total** | **12** |

### Dependencias JavaScript (package.json)
| Tipo | Cantidad |
|------|----------|
| Desarrollo | 6 |

### Tests
| Tipo | Archivos | Ubicación |
|------|----------|-----------|
| Feature | 3 | tests/Feature/ |
| Unit | 0 | tests/Unit/ |
| Config | 1 | tests/Pest.php |

### Tamaño del Proyecto
```
vendor/       ~500 MB  (dependencias PHP)
node_modules/ ~300 MB  (dependencias JS)
public/build/ ~2 MB    (assets compilados)
storage/logs/ <1 MB    (logs)
```

---

## ⚠️ NOTAS IMPORTANTES

### 1. Zona Horaria Inconsistente
```env
# .env.example
APP_TIMEZONE=America/El_Salvador  ❌ INCORRECTO

# Debería ser:
APP_TIMEZONE=America/La_Paz       ✅ CORRECTO
```

### 2. Puerto MySQL
```env
# Desarrollo (.env.example)
DB_PORT=3306

# Producción (XAMPP actual)
DB_PORT=3307  ⚠️ Cambiar en .env real
```

### 3. Seguridad en Producción
```env
APP_ENV=production
APP_DEBUG=false          # NUNCA true en producción
```

### 4. Storage Symlink
```bash
# SIEMPRE ejecutar después de clonar:
php artisan storage:link
```

### 5. Tests con RefreshDatabase
- Cada test resetea la BD
- Usa SQLite en memoria
- NO afecta BD de desarrollo

---

## 🔐 SEGURIDAD

### .env NUNCA debe estar en Git
```gitignore
.env         # ✅ Ignorado
.env.example # ✅ Incluido (sin datos sensibles)
```

### APP_KEY Único
```bash
php artisan key:generate
# Genera: base64:random_string_de_32_bytes
```
**Propósito**: Encriptar sesiones, cookies, contraseñas

### Permisos de Directorios
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

## 📝 PAQUETES IMPORTANTES EXPLICADOS

### barryvdh/laravel-dompdf
**Propósito**: Generar PDFs desde HTML
**Uso**:
```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('reportes.produccion', $data);
return $pdf->download('reporte.pdf');
```

### maatwebsite/excel
**Propósito**: Exportar/importar Excel
**Uso**:
```php
use Maatwebsite\Excel\Facades\Excel;

return Excel::download(new MovimientosExport, 'inventario.xlsx');
```

### laravel/sanctum
**Propósito**: Autenticación API (tokens)
**Uso**: API stateless para apps móviles

### laravel/tinker
**Propósito**: REPL PHP (consola interactiva)
```bash
php artisan tinker
>>> User::count()
=> 5
```

### laravel/pail
**Propósito**: Visualizar logs en tiempo real
```bash
php artisan pail
```

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Archivo**: 18-Lang-Public-Storage-Tests-Root.md
**Estado**: Lang, Public, Storage, Tests y archivos raíz documentados
