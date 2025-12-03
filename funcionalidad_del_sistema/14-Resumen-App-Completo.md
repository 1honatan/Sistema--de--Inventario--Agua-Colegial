# 14. RESUMEN COMPLETO DE CARPETA APP/

## 📋 ESTRUCTURA COMPLETA DOCUMENTADA

### Controllers/Admin/
- **DashboardController**: Dashboard con KPIs (producción, stock, salidas)
- **ProductoController**: CRUD de productos
- **RolController**: Gestión de roles de usuarios
- **VehiculoController**: CRUD de vehículos
- **ReporteController**: Generación de reportes en Excel/PDF
- **ConfiguracionController**: Configuraciones del sistema

### Controllers/Auth/
- **LoginController**: Autenticación con rate limiting (5 intentos)
- **ForgotPasswordController**: Recuperar contraseña
- **ResetPasswordController**: Resetear contraseña

### Controllers/Control/
✅ YA DOCUMENTADOS (archivos 01-07):
- AsistenciaSemanalController
- EmpleadoController
- InsumosController
- MantenimientoController
- ProduccionDiariaController
- SalidasController
- FosaSepticaController, FumigacionController, TanquesController

### Controllers/Inventario/
- **InventarioController**: Movimientos de inventario manual
- **DashboardInventarioController**: Dashboard de inventario

### Controllers/Produccion/
- **ProduccionController**: Registro de producción
- **AlmacenController**: Gestión de almacén

### Models/
✅ YA DOCUMENTADOS (archivos 08-11):
- Personal, AsistenciaSemanal, Producto
- Vehiculo, Insumo, SalidaProducto, ProduccionDiaria
- Inventario, Usuario, Rol
- FosaSeptica, Fumigacion, TanqueAgua, MantenimientoEquipo

### Middleware/
✅ YA DOCUMENTADO (archivo 12):
- CheckRole: Control de permisos por rol
- RestrictIpAddress: Restricción por IP
- ValidateRequestIntegrity: Validación contra SQL injection

### Console/Commands/
✅ YA DOCUMENTADO (archivo 13):
- BackupDatabase: Backups automáticos MySQL
- VerificarStockBajo: Alertas de stock
- SincronizarProduccionInventario: Sincronización
- ClearAllCaches: Limpieza de cache

### Providers/
- **TimezoneServiceProvider**: Configura zona horaria Bolivia (America/La_Paz)

### Exports/
- **MovimientosExport**: Exportar movimientos de inventario a Excel

### Http/Requests/
- Validaciones personalizadas para formularios
- StoreProductoRequest, UpdateProductoRequest, etc.

---

## 🔑 FUNCIONALIDADES CLAVE

### 1. Sistema de Autenticación (LoginController)
```php
// Rate limiting: 5 intentos, luego bloqueo
ensureIsNotRateLimited() // Máximo 5 intentos
throttleKey() // Key: email|ip
redirigirSegunRol() // Admin, Producción, Inventario
```

**Flujo de login**:
1. Validar credenciales
2. Verificar rate limit (5 intentos)
3. Autenticar con `Auth::attempt()`
4. Verificar estado activo
5. Actualizar último acceso
6. Redirigir según rol

---

### 2. Dashboard Administrativo (DashboardController)
```php
index() // Vista principal con KPIs
getData() // JSON para actualización en tiempo real
```

**KPIs mostrados**:
- Producción del mes/hoy
- Stock total del sistema
- Entradas/Salidas del mes
- Personal activo
- Vehículos activos
- Últimos movimientos de inventario
- Salidas recientes
- Mantenimientos pendientes
- Estadísticas de todos los módulos

**Consultas importantes**:
```php
// Stock total
Inventario::where('tipo_movimiento', 'entrada')->sum('cantidad')
- Inventario::where('tipo_movimiento', 'salida')->sum('cantidad')

// Producción del mes
ProduccionProducto::whereHas('produccion', function($query) {
    $query->whereMonth('fecha', now()->month);
})->sum('cantidad')
```

---

### 3. Zona Horaria (TimezoneServiceProvider)
```php
boot() {
    date_default_timezone_set('America/La_Paz');
    config(['app.timezone' => 'America/La_Paz']);
}
```

**Impacto**: Todas las fechas y horas en GMT-4 (Bolivia)

---

## 📊 FLUJOS PRINCIPALES

### Flujo 1: Login y Redirección por Rol
```
Usuario ingresa credenciales
↓
Validar formato (email, min 6 chars)
↓
Verificar rate limit (¿< 5 intentos?)
↓ SÍ
Autenticar (Auth::attempt)
↓ ÉXITO
Verificar estado = 'activo'
↓
Actualizar ultimo_acceso
↓
Redirigir según rol:
- admin → /admin/dashboard
- produccion → /control/produccion
- inventario → /inventario/dashboard
```

### Flujo 2: Dashboard Carga de Datos
```
GET /admin/dashboard
↓
Consultar KPIs:
- Producción mes/hoy (ProduccionProducto)
- Stock total (Inventario)
- Entradas/Salidas mes (Inventario)
- Personal activo (Personal)
- Vehículos (vehiculos table)
↓
Consultar listas:
- Últimos 8 movimientos inventario
- Últimas 5 salidas
- Próximos 5 mantenimientos
↓
Calcular totales de módulos:
- Salidas, Producción, Mantenimientos
- Fumigaciones, Fosa Séptica, Tanques
- Insumos, Asistencias
↓
Retornar vista con compact()
```

### Flujo 3: Actualización Tiempo Real Dashboard
```
Frontend llama: GET /admin/dashboard/data
↓
DashboardController::getData()
↓
Retornar JSON con:
{
  totales: {salidas, produccion, mantenimientos, ...},
  ultimas_salidas: [...],
  mantenimientos_pendientes: [...],
  timestamp: "14:30:45"
}
↓
Frontend actualiza cards sin recargar página
```

---

## 🗄️ TABLAS PRINCIPALES USADAS

| Tabla | Controlador Principal | Propósito |
|-------|----------------------|-----------|
| usuarios | LoginController | Autenticación |
| roles | CheckRole (Middleware) | Permisos |
| inventario | DashboardController, InventarioController | Movimientos de stock |
| personal | EmpleadoController | Gestión de empleados |
| productos | ProductoController | Catálogo de productos |
| vehiculos | VehiculoController | Vehículos de la empresa |
| control_produccion_diaria | ProduccionDiariaController | Producción |
| control_produccion_productos | DashboardController | Detalle de producción |
| control_salidas_productos | SalidasController | Despachos |
| control_mantenimiento_equipos | MantenimientoController | Mantenimientos |
| control_insumos | InsumosController | Insumos y materias primas |
| asistencias_semanal | AsistenciaSemanalController | Asistencias |

---

## ⚙️ CONFIGURACIONES CRÍTICAS

### Kernel.php - Comandos Programados
```php
protected function schedule(Schedule $schedule) {
    // Backup diario 2 AM
    $schedule->command('backup:database --compress')->dailyAt('02:00');

    // Verificar stock 8 AM
    $schedule->command('verificar:stock-bajo')->dailyAt('08:00');
}
```

### Middleware Registrados
```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    'role' => CheckRole::class,
    'ip.restrict' => RestrictIpAddress::class,
    'validate.integrity' => ValidateRequestIntegrity::class,
];
```

### Service Providers
```php
// config/app.php
'providers' => [
    App\Providers\TimezoneServiceProvider::class,
];
```

---

## 🎯 RESUMEN DE ARCHIVOS app/

| Categoría | Archivos | Estado |
|-----------|----------|--------|
| Controllers/Control | 9 archivos | ✅ Documentados |
| Controllers/Admin | 8 archivos | ⚠️ Resumen |
| Controllers/Auth | 3 archivos | ⚠️ Resumen |
| Controllers/Inventario | 2 archivos | ⚠️ Resumen |
| Controllers/Produccion | 2 archivos | ⚠️ Resumen |
| Models | 14 archivos | ✅ Documentados |
| Middleware | 3 archivos | ✅ Documentados |
| Commands | 4 archivos | ✅ Documentados |
| Providers | 1 archivo | ⚠️ Resumen |
| Exports | 1 archivo | ⚠️ Resumen |
| Requests | 11 archivos | ⚠️ Pendiente |

**Total**: ~60 archivos en app/
**Documentado completo**: ~30 archivos (50%)
**Resumido**: ~30 archivos (50%)

---

## 📝 PRÓXIMOS PASOS

### Carpeta bootstrap/
- app.php: Inicialización de Laravel
- cache/: Archivos de cache compilados

### Carpeta config/
- database.php: Configuración de BD
- auth.php: Configuración de autenticación
- app.php: Configuración general
- 15+ archivos de configuración

### Carpeta database/
- migrations/: ~30 migraciones de tablas
- seeders/: Datos iniciales del sistema

### Carpeta routes/
- web.php: Rutas del sistema
- api.php: Rutas API (si existen)

### Carpeta resources/
- views/: ~100+ archivos Blade
- js/: JavaScript del frontend
- css/: Estilos del sistema

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Archivo**: 14-Resumen-App-Completo.md
**Estado**: Carpeta app/ ~50% documentada detalladamente, 50% resumida
