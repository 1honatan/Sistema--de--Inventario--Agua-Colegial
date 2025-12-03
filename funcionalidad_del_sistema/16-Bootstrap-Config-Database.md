# 16. BOOTSTRAP, CONFIG Y DATABASE

## 📁 CARPETA BOOTSTRAP/

### 📄 bootstrap/app.php (36 líneas)
**Propósito**: Inicialización de la aplicación Laravel

#### Configuración de Zona Horaria
```php
date_default_timezone_set('America/La_Paz');
```
- Establece zona horaria GMT-4 (Bolivia)
- Se ejecuta ANTES de que Laravel inicie

#### Configuración de Rutas
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',      // Rutas web con sesiones
    api: __DIR__.'/../routes/api.php',      // Rutas API stateless
    commands: __DIR__.'/../routes/console.php', // Comandos Artisan
    health: '/up',                          // Ruta de health check
)
```

#### Registro de Middleware
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
        'restrict.ip' => \App\Http\Middleware\RestrictIpAddress::class,
    ]);
})
```
**Middleware disponibles**:
- `role`: Verifica permisos por rol
- `restrict.ip`: Restringe acceso por IP

#### Service Providers
```php
->withProviders([
    \App\Providers\TimezoneServiceProvider::class,
])
```

### 📁 bootstrap/cache/
**Propósito**: Cache de configuración y servicios compilados

**Archivos importantes**:
- `packages.php`: Cache de paquetes registrados
- `services.php`: Cache de service providers
- `*.tmp`: Archivos temporales de compilación

**Uso**:
- Laravel cachea configuración para mejor rendimiento
- Se regenera con `php artisan config:cache`
- Se limpia con `php artisan cache:clear-all`

---

## ⚙️ CARPETA CONFIG/

### 📄 config/auth.php (65 líneas)
**Propósito**: Configuración de autenticación

#### Guard Web (Sesiones)
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'usuarios',  // Usa tabla usuarios
    ],
],
```

#### Provider de Usuarios
```php
'providers' => [
    'usuarios' => [
        'driver' => 'eloquent',
        'model' => App\Models\Usuario::class,  // Modelo Usuario
    ],
],
```

#### Reset de Contraseñas
```php
'passwords' => [
    'usuarios' => [
        'provider' => 'usuarios',
        'table' => 'password_reset_tokens',
        'expire' => 60,      // Tokens válidos por 60 minutos
        'throttle' => 60,    // 1 intento por minuto
    ],
],
```

#### Timeout de Confirmación de Contraseña
```php
'password_timeout' => 10800,  // 3 horas
```

### 📄 config/cache.php (62 líneas)
**Propósito**: Configuración de cache

#### Driver por Defecto
```php
'default' => env('CACHE_DRIVER', 'file'),
```

#### Almacenes Disponibles
```php
'stores' => [
    'array' => [...],    // Cache en memoria (solo request actual)
    'file' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data'),
    ],
    'redis' => [...],    // Redis (no usado actualmente)
],
```

#### Prefijo de Cache
```php
'prefix' => env('CACHE_PREFIX', 'agua_colegial_cache_'),
```
- Evita colisiones entre aplicaciones

---

## 🗄️ CARPETA DATABASE/

### 📊 ESTRUCTURA DE MIGRACIONES (42 archivos)

#### Tablas Base (2024_01_01_*)
```
000001 → roles (4 roles del sistema)
000002 → usuarios (autenticación)
000003 → personal (empleados)
000004 → productos (catálogo)
000005 → produccion (registros maestros)
000006 → inventario (movimientos stock)
000007 → vehiculos (transporte)
000010 → password_reset_tokens (reset contraseñas)
```

#### Módulo Control (2025_11_10_125*)
```
528 → control_salidas_productos (despachos)
535 → control_produccion_diaria (producción diaria)
537 → control_mantenimiento_equipos (mantenimientos)
538 → control_fosa_septica (BPM)
540 → control_insumos (materias primas)
542 → control_fumigacion (BPM)
543 → control_tanques_agua (BPM)
```

#### Migraciones de Mejora
```
2025_10_25_033425 → tipos_producto_table (categorías)
2025_10_25_033436 → alertas_stock_table (alertas)
2025_11_06_112240 → v_stock_actual_view (vista SQL)
2025_11_12_142920 → asistencias_semanales_table
2025_11_19_010000 → retornos_detalle (devoluciones)
```

#### Migraciones ALTER TABLE
```
2025_10_28_193706 → add_id_tipo_producto_to_productos
2025_10_29_150103 → add_id_personal_to_usuarios
2025_10_30_154125 → add_marca_to_vehiculos
2025_10_31_141807 → add_imagen_to_productos
2025_11_02_235627 → add_es_chofer_to_personal
2025_11_10_184218 → add_fields_to_control_produccion_diaria
2025_11_12_153548 → add_foto_garantia_to_personal
2025_11_13_022330 → add_responsable_to_vehiculos
2025_11_13_022716 → add_vehiculo_to_control_salidas
2025_11_21_194050 → add_missing_fields_to_personal
2025_11_26_145936 → add_customer_fields_to_salidas
2025_11_26_151028 → add_responsable_to_salidas
2025_11_30_223929 → add_id_personal_to_mantenimiento
2025_11_30_224743 → add_unidades_por_paquete_to_productos
2025_12_01_000536 → remove_es_chofer_from_personal
```

#### Infraestructura Laravel
```
2025_10_22_132516 → cache_table
2025_10_22_132517 → jobs_table
2025_10_22_132708 → personal_access_tokens_table
```

---

### 🌱 SEEDERS (16 archivos)

#### DatabaseSeeder.php (Seeder Principal)
```php
public function run(): void
{
    $this->call([
        RolesSeeder::class,        // 1. Roles del sistema
        PersonalSeeder::class,     // 2. Empleados
        UsuariosSeeder::class,     // 3. Usuarios
        ProductosSeeder::class,    // 4. Productos
    ]);
}
```

#### Seeders de Datos Iniciales
**RolesSeeder.php**
- Crea 4 roles: admin, produccion, inventario, despacho

**UsuariosSeeder.php**
- Crea usuarios iniciales del sistema
- Asigna roles

**PersonalSeeder.php**
- Carga empleados iniciales

**ProductosSeeder.php**
- Catálogo de productos inicial

**TipoProductoSeeder.php**
- Tipos/categorías de productos

#### Seeders de Datos Específicos
**ProduccionDiariaSeeder.php**
- Datos de producción históricos

**SalidasProductosSeeder.php**
- Registro de salidas históricas

**InventarioInicialSeeder.php**
- Stock inicial del sistema

**MovimientosNoviembreSeeder.php**
- Movimientos de noviembre 2025

**ProduccionSalidasNov22_25Seeder.php**
- Datos específicos 22-25 noviembre

#### Seeders de Mantenimiento
**SincronizarProduccionInventarioSeeder.php**
- Sincroniza producción → inventario
- Migración de datos

**ActualizarUnidadesPorPaqueteSeeder.php**
- Actualiza campo unidades_por_paquete
- Datos de productos

#### Seeders de Usuarios Específicos
**UsuarioProduccionSeeder.php**
- Usuario para rol producción

**UsuarioAndersonSeeder.php**
- Usuario específico Anderson

**RolProduccionSeeder.php**
- Asegura existencia rol producción

---

## 🔑 MIGRACIONES CLAVE EXPLICADAS

### 1. roles (2024_01_01_000001)
```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 100)->unique();  // admin, produccion, inventario, despacho
    $table->text('observacion')->nullable();
    $table->timestamps();
});
```

### 2. usuarios (2024_01_01_000002)
```php
Schema::create('usuarios', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 100);
    $table->string('email', 100)->unique();
    $table->string('password');               // bcrypt
    $table->foreignId('id_rol')->constrained('roles');
    $table->enum('estado', ['activo', 'inactivo'])->default('activo');
    $table->timestamp('ultimo_acceso')->nullable();
    $table->rememberToken();
    $table->timestamps();

    // Índices
    $table->index('estado');
    $table->index('id_rol');
    $table->index('email');
});
```

### 3. inventario (2024_01_01_000006)
```php
Schema::create('inventario', function (Blueprint $table) {
    $table->id();
    $table->foreignId('id_producto')->constrained('productos');
    $table->enum('tipo_movimiento', ['entrada', 'salida']);
    $table->integer('cantidad');
    $table->string('motivo', 200);
    $table->date('fecha');
    $table->string('lote', 50)->nullable();
    $table->string('origen', 100)->nullable();
    $table->string('destino', 100)->nullable();
    $table->timestamps();

    // Índices para rendimiento
    $table->index('id_producto');
    $table->index('tipo_movimiento');
    $table->index('fecha');
});
```

### 4. control_produccion_diaria (2025_11_10_125535)
```php
Schema::create('control_produccion_diaria', function (Blueprint $table) {
    $table->id();
    $table->date('fecha');
    $table->string('turno', 20);              // mañana, tarde, noche
    $table->string('supervisor', 100);
    $table->integer('botellones_producidos')->default(0);
    $table->integer('bolsas_20l_producidas')->default(0);
    $table->integer('bolsas_10l_producidas')->default(0);
    $table->integer('botellas_500ml_producidas')->default(0);
    // ... 50+ campos más
    $table->timestamps();
});
```

### 5. control_salidas_productos (2025_11_10_125528)
```php
Schema::create('control_salidas_productos', function (Blueprint $table) {
    $table->id();
    $table->date('fecha');
    $table->foreignId('id_vehiculo')->constrained('vehiculos');
    $table->string('cliente_nombre', 100);
    $table->string('cliente_direccion', 200);
    $table->string('cliente_telefono', 20)->nullable();
    $table->integer('botellones')->default(0);
    $table->integer('bolsas_20l')->default(0);
    // ... campos por tipo de producto
    $table->integer('retornos_botellones')->default(0);
    $table->integer('retornos_bolsas_20l')->default(0);
    // ... más retornos
    $table->text('observaciones')->nullable();
    $table->timestamps();
});
```

---

## 📋 ORDEN DE EJECUCIÓN MIGRACIONES

**Orden correcto** (por foreign keys):
1. roles
2. usuarios (FK: id_rol)
3. personal
4. productos
5. tipos_producto
6. vehiculos
7. inventario (FK: id_producto)
8. produccion
9. control_* (dependen de productos, personal, vehiculos)

Laravel ejecuta por nombre de archivo (timestamp), lo cual respeta dependencias.

---

## 🚀 COMANDOS ÚTILES

### Migraciones
```bash
php artisan migrate              # Ejecutar migraciones pendientes
php artisan migrate:fresh        # Drop all + migrar
php artisan migrate:fresh --seed # Drop all + migrar + seeders
php artisan migrate:rollback     # Revertir última migración
php artisan migrate:status       # Ver estado de migraciones
```

### Seeders
```bash
php artisan db:seed                          # Ejecutar DatabaseSeeder
php artisan db:seed --class=RolesSeeder      # Ejecutar seeder específico
```

### Cache
```bash
php artisan config:cache     # Cachear configuración
php artisan config:clear     # Limpiar cache configuración
php artisan cache:clear-all  # Comando personalizado del sistema
```

---

## 📊 ESTADÍSTICAS

| Categoría | Cantidad |
|-----------|----------|
| Migraciones totales | 42 |
| Migraciones CREATE TABLE | 18 |
| Migraciones ALTER TABLE | 15 |
| Migraciones OTRAS | 9 |
| Seeders | 16 |
| Archivos config/ | 3+ |
| Archivos bootstrap/ | 2 |

---

## 🔐 SEGURIDAD EN MIGRACIONES

### Foreign Keys con Restricciones
```php
$table->foreignId('id_rol')
    ->constrained('roles')
    ->onDelete('restrict');  // No permitir borrar si hay usuarios
```

### Índices para Rendimiento
```php
$table->index('estado');        // Búsquedas rápidas por estado
$table->index('fecha');         // Filtrado por fecha
$table->index(['id_producto', 'tipo_movimiento']); // Índice compuesto
```

### Valores por Defecto
```php
$table->enum('estado', ['activo', 'inactivo'])->default('activo');
$table->integer('cantidad')->default(0);
```

---

## ⚠️ PUNTOS CRÍTICOS

### 1. Orden de Migraciones
- SIEMPRE respetar foreign keys
- Nombre de archivo con timestamp determina orden

### 2. Seeders en Producción
- NO ejecutar seeders de datos de prueba en producción
- Solo RolesSeeder es necesario siempre

### 3. Cache de Configuración
- En producción: SIEMPRE cachear config
- En desarrollo: NO cachear (o limpiar frecuentemente)

### 4. Zona Horaria
- Configurada 3 veces para garantía:
  - bootstrap/app.php
  - TimezoneServiceProvider
  - config/app.php (implícito)

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Archivo**: 16-Bootstrap-Config-Database.md
**Estado**: Bootstrap, Config y Database documentados completamente
