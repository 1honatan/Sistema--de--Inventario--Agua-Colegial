# 17. RUTAS DEL SISTEMA

## 📄 routes/web.php (342 líneas)

### 🔐 ARQUITECTURA DE RUTAS

**Middleware aplicados**:
- `guest`: Solo usuarios NO autenticados (login)
- `auth`: Solo usuarios autenticados
- `role:admin`: Solo rol admin
- `role:admin,produccion`: Admin O producción
- `role:admin,inventario,produccion`: Admin O inventario O producción

**Rate Limiting**: Login limitado a 5 intentos por minuto

---

## 📋 GRUPOS DE RUTAS

### 1. Rutas Públicas
```php
// Redirección raíz
Route::get('/', function () {
    return redirect()->route('login');
});
```

### 2. Autenticación (middleware: guest)
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1'); // MAX 5 intentos/minuto
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
```

**Rutas de autenticación**:
- `GET /login` → Formulario de login
- `POST /login` → Procesar login (con rate limiting)
- `POST /logout` → Cerrar sesión

---

### 3. Módulo Administrativo (role: admin)

**Prefijo**: `/admin`
**Middleware**: `auth`, `role:admin`

#### Dashboard
```php
GET  /admin/dashboard           → DashboardController@index
GET  /admin/dashboard/data      → DashboardController@getData (AJAX)
```

#### Vehículos (Resource)
```php
GET     /admin/vehiculos                → index
GET     /admin/vehiculos/create         → create
POST    /admin/vehiculos                → store
GET     /admin/vehiculos/{id}/edit      → edit
PUT     /admin/vehiculos/{id}           → update
DELETE  /admin/vehiculos/{id}           → destroy
POST    /admin/vehiculos/{id}/toggle-estado → toggleEstado
```

#### Configuración del Sistema
```php
GET     /admin/configuracion            → index
POST    /admin/configuracion/backup     → generarBackup
GET     /admin/configuracion/backup/{archivo}/descargar → descargarBackup
DELETE  /admin/configuracion/backup/{archivo} → eliminarBackup
```

#### Asistencia (Admin)
```php
GET /admin/asistencia                       → index (lista completa)
GET /admin/asistencia/personal/{personal}   → verPorPersonal
GET /admin/asistencia/reporte               → reporte
```

---

### 4. Módulo de Reportes (role: admin, produccion)

**Prefijo**: `/admin/reportes`
**Middleware**: `auth`, `role:admin,produccion`

```php
GET /admin/reportes/                → index
GET /admin/reportes/produccion      → produccion
GET /admin/reportes/inventario      → inventario
GET /admin/reportes/salidas         → salidas
GET /admin/reportes/mantenimiento   → mantenimiento
GET /admin/reportes/fumigacion      → fumigacion
GET /admin/reportes/fosa-septica    → fosaSeptica
GET /admin/reportes/tanques         → tanques
GET /admin/reportes/insumos         → insumos
GET /admin/reportes/asistencia      → asistencia
GET /admin/reportes/despachos       → salidas (alias)

// Exportar PDF
GET /admin/reportes/produccion/pdf  → produccionPDF
GET /admin/reportes/inventario/pdf  → inventarioPDF
```

**Total**: 12 rutas de reportes

---

### 5. Módulo de Almacén (role: admin, produccion)

**Prefijo**: `/almacen`
**Middleware**: `auth`, `role:admin,produccion`

```php
GET     /almacen                        → index
GET     /almacen/crear                  → create
POST    /almacen                        → store
GET     /almacen/{producto}/editar      → edit
PUT     /almacen/{producto}             → update
DELETE  /almacen/{producto}             → destroy

// Ajustes de Stock
GET     /almacen/{producto}/ajustar-stock     → ajustarStock
POST    /almacen/{producto}/procesar-ajuste   → procesarAjuste
```

---

### 6. Módulo de Inventario (role: admin, inventario, produccion)

**Prefijo**: `/inventario`
**Middleware**: `auth`, `role:admin,inventario,produccion`

#### Dashboard
```php
GET /inventario/dashboard → DashboardInventarioController@index
```

#### Movimientos de Inventario
```php
GET  /inventario                            → index
GET  /inventario/movimiento/crear           → createMovimiento
POST /inventario/movimiento                 → storeMovimiento
GET  /inventario/movimiento/historial       → historialMovimientos
POST /inventario/movimiento/exportar-pdf    → exportarMovimientosPDF
POST /inventario/movimiento/exportar-excel  → exportarMovimientosExcel
GET  /inventario/producto/{producto}/historial → historial
```

#### Productos
```php
GET     /inventario/productos/crear         → createProducto
POST    /inventario/productos               → storeProducto
GET     /inventario/productos/{id}/editar   → editProducto
PUT     /inventario/productos/{id}          → updateProducto
DELETE  /inventario/productos/{id}          → destroyProducto
```

#### Alertas de Stock
```php
GET  /inventario/api/verificar-alertas  → verificarAlertasStock (AJAX)
GET  /inventario/alertas                → alertas (lista)
POST /inventario/alertas/{id}/atender   → atenderAlerta
POST /inventario/alertas/{id}/ignorar   → ignorarAlerta
```

**Total**: 16 rutas de inventario

---

### 7. Módulo de Asistencia Personal (role: todos autenticados)

**Prefijo**: `/mi-asistencia`
**Middleware**: `auth` (sin restricción de rol)

```php
GET  /mi-asistencia             → index (mi panel)
POST /mi-asistencia/entrada     → registrarEntrada
POST /mi-asistencia/salida      → registrarSalida
POST /mi-asistencia/ausencia    → registrarAusencia
GET  /mi-asistencia/historial   → historial
```

---

### 8. Módulos de Control (role: admin, produccion)

**Prefijo**: `/control`
**Middleware**: `auth`, `role:admin,produccion`

#### 8.1 Control de Salidas "Colegial"
```php
GET     /control/salidas            → index
GET     /control/salidas/crear      → create
POST    /control/salidas            → store
GET     /control/salidas/{id}       → show
GET     /control/salidas/{id}/editar → edit
PUT     /control/salidas/{id}       → update
DELETE  /control/salidas/{id}       → destroy
GET     /control/salidas/{id}/pdf   → generarPDF
```

#### 8.2 Control de Producción Diaria
```php
GET     /control/produccion         → index
GET     /control/produccion/crear   → create
POST    /control/produccion         → store
GET     /control/produccion/{id}    → show
GET     /control/produccion/{id}/editar → edit
PUT     /control/produccion/{id}    → update
DELETE  /control/produccion/{id}    → destroy
```

#### 8.3 Control de Mantenimiento
```php
GET     /control/mantenimiento          → index
GET     /control/mantenimiento/crear    → create
POST    /control/mantenimiento          → store
GET     /control/mantenimiento/{id}     → show
GET     /control/mantenimiento/{id}/editar → edit
PUT     /control/mantenimiento/{id}     → update
DELETE  /control/mantenimiento/{id}     → destroy
```

#### 8.4 Control de Fosa Séptica
```php
GET     /control/fosa-septica           → index
GET     /control/fosa-septica/crear     → create
POST    /control/fosa-septica           → store
GET     /control/fosa-septica/{id}/editar → edit
PUT     /control/fosa-septica/{id}      → update
DELETE  /control/fosa-septica/{id}      → destroy
```

#### 8.5 Control de Insumos
```php
GET     /control/insumos            → index
GET     /control/insumos/crear      → create
POST    /control/insumos            → store
GET     /control/insumos/{id}/editar → edit
PUT     /control/insumos/{id}       → update
DELETE  /control/insumos/{id}       → destroy
```

#### 8.6 Control de Fumigación
```php
GET     /control/fumigacion         → index
GET     /control/fumigacion/crear   → create
POST    /control/fumigacion         → store
GET     /control/fumigacion/{id}/editar → edit
PUT     /control/fumigacion/{id}    → update
DELETE  /control/fumigacion/{id}    → destroy
```

#### 8.7 Control de Tanques de Agua
```php
GET     /control/tanques            → index
GET     /control/tanques/crear      → create
POST    /control/tanques            → store
GET     /control/tanques/{id}       → show
GET     /control/tanques/{id}/editar → edit
PUT     /control/tanques/{id}       → update
DELETE  /control/tanques/{id}       → destroy
```

#### 8.8 Control de Asistencia Semanal
```php
GET     /control/asistencia-semanal         → index
GET     /control/asistencia-semanal/crear   → create
POST    /control/asistencia-semanal         → store
GET     /control/asistencia-semanal/{id}/editar → edit
PUT     /control/asistencia-semanal/{id}    → update
DELETE  /control/asistencia-semanal/{id}    → destroy
GET     /control/asistencia-semanal/reporte → generarReporte

// Registro rápido (encargados)
GET     /control/asistencia-semanal/registro-rapido   → registroRapido
POST    /control/asistencia-semanal/registrar-entrada → registrarEntrada
POST    /control/asistencia-semanal/registrar-salida  → registrarSalida
```

#### 8.9 Gestión de Empleados
```php
GET     /control/empleados/crear    → create
POST    /control/empleados          → store
GET     /control/empleados/{id}     → show
GET     /control/empleados/{id}/editar → edit
PUT     /control/empleados/{id}     → update
DELETE  /control/empleados/{id}     → destroy
```

---

### 9. Asistencia Semanal Personal (role: todos autenticados)

**Prefijo**: `/mi-asistencia-semanal`
**Middleware**: `auth`

```php
GET  /mi-asistencia-semanal             → miRegistro
POST /mi-asistencia-semanal/marcar-entrada → marcarMiEntrada
POST /mi-asistencia-semanal/marcar-salida  → marcarMiSalida
```

---

### 10. Ruta de Fallback (404)
```php
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
```

---

## 📄 routes/api.php (8 líneas)

### Rutas API (Sanctum)
```php
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
```

**Actualmente**: Solo 1 ruta API para obtener usuario autenticado
**Middleware**: Sanctum para autenticación stateless

---

## 📄 routes/console.php (117 líneas)

### Comandos Artisan Registrados

#### Comando de Ejemplo
```php
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
```

### ⏰ Tareas Programadas (Schedule)

#### 1. Backup Semanal (Domingos 2:00 AM)
```php
Schedule::command('backup:database --compress --keep-days=90')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->timezone('America/Bogota')
    ->onSuccess(function () {
        info('✅ Backup semanal completado exitosamente.');
    })
    ->onFailure(function () {
        info('❌ Error en backup semanal.');
    });
```

#### 2. Limpieza de Logs (Mensual)
```php
Schedule::command('log:clear --days=30')
    ->monthly()
    ->onSuccess(function () {
        info('✅ Logs antiguos eliminados.');
    });
```

#### 3. Verificación de Stock Bajo (Diario 2:00 AM)
```php
Schedule::command('verificar:stock-bajo --umbral=10')
    ->dailyAt('02:00')
    ->timezone('America/La_Paz')
    ->onSuccess(function () {
        info('✅ Verificación de stock bajo completada.');
    })
    ->onFailure(function () {
        info('❌ Error en verificación de stock bajo.');
    });
```

#### 4. Limpieza Automática de Cache (Diario 3:00 AM)
```php
Schedule::command('cache:clear-all')
    ->dailyAt('03:00')
    ->timezone('America/La_Paz')
    ->onSuccess(function () {
        info('✅ Limpieza automática de cache completada.');
    })
    ->onFailure(function () {
        info('❌ Error en limpieza de cache.');
    });
```

---

## 📊 ESTADÍSTICAS DE RUTAS

| Módulo | Rutas | Middleware |
|--------|-------|------------|
| Autenticación | 3 | guest, auth |
| Dashboard Admin | 2 | admin |
| Vehículos | 7 | admin |
| Configuración | 4 | admin |
| Asistencia Admin | 3 | admin |
| Reportes | 12 | admin, produccion |
| Almacén | 8 | admin, produccion |
| Inventario | 16 | admin, inventario, produccion |
| Asistencia Personal | 5 | auth |
| Control Salidas | 8 | admin, produccion |
| Control Producción | 7 | admin, produccion |
| Control Mantenimiento | 7 | admin, produccion |
| Control Fosa Séptica | 6 | admin, produccion |
| Control Insumos | 6 | admin, produccion |
| Control Fumigación | 6 | admin, produccion |
| Control Tanques | 7 | admin, produccion |
| Control Asistencia Semanal | 10 | admin, produccion |
| Empleados | 6 | admin, produccion |
| Mi Asistencia Semanal | 3 | auth |
| Fallback | 1 | - |
| **TOTAL** | **~127** | - |

---

## 🔑 PERMISOS POR ROL

### Admin
- ✅ Acceso a TODAS las rutas del sistema
- ✅ Dashboard administrativo
- ✅ Gestión de vehículos
- ✅ Configuración del sistema
- ✅ Asistencia administrativa
- ✅ Reportes
- ✅ Almacén
- ✅ Inventario
- ✅ Todos los módulos de Control
- ✅ Gestión de empleados

### Produccion
- ✅ Reportes
- ✅ Almacén
- ✅ Inventario
- ✅ Todos los módulos de Control
- ✅ Gestión de empleados
- ❌ Dashboard admin
- ❌ Gestión de vehículos
- ❌ Configuración del sistema

### Inventario
- ✅ Inventario completo
- ❌ Resto de módulos

### Despacho
- (No tiene rutas específicas actualmente)

### Todos autenticados
- ✅ Mi Asistencia Personal
- ✅ Mi Asistencia Semanal
- ✅ Logout

---

## 🚀 COMANDOS PROGRAMADOS (CRON)

### Configuración del Servidor
Para que los comandos programados funcionen en producción:

```bash
# Editar crontab
crontab -e

# Agregar esta línea (ejecuta el scheduler cada minuto)
* * * * * cd /path/to/agua_colegial && php artisan schedule:run >> /dev/null 2>&1
```

### Horarios de Ejecución
- **02:00 AM Domingos**: Backup semanal (compress, 90 días)
- **02:00 AM Diario**: Verificación stock bajo (umbral: 10)
- **03:00 AM Diario**: Limpieza automática cache
- **Mensual**: Limpieza logs (>30 días)
- **Cada hora**: Quote inspiracional (ejemplo)

---

## ⚠️ NOTAS IMPORTANTES

### 1. Sistema Antiguo de Producción DESHABILITADO
```php
// SISTEMA ANTIGUO - COMENTADO
// Route::middleware(['auth', 'role:admin,produccion'])
//     ->prefix('produccion')
//     ->name('produccion.')
//     ->group(function () {
//         Route::get('/dashboard', [DashboardProduccionController::class, 'index']);
//         Route::resource('/', ProduccionController::class);
//     });
```
**Reemplazado por**: `/control/produccion` (ProduccionDiariaController)

### 2. Rate Limiting en Login
- **Límite**: 5 intentos por minuto
- **Throttle Key**: Email + IP
- **Implementado en**: LoginController::ensureIsNotRateLimited()

### 3. Zonas Horarias
- **console.php**: Usa `America/Bogota` (GMT-5) ⚠️ INCONSISTENTE
- **Resto del sistema**: Usa `America/La_Paz` (GMT-4)
- **Recomendación**: Cambiar console.php a America/La_Paz

### 4. Rutas API
- **Actualmente**: Solo 1 ruta (/user)
- **Potencial**: Expandir para app móvil o integraciones

### 5. Resource Routes
Algunos módulos usan `Route::resource()` implícitamente:
- Vehículos: `->except(['show'])` (no tiene vista show)
- Resto: Rutas declaradas manualmente

---

## 🔍 RUTAS MÁS USADAS

1. `GET /admin/dashboard` - Dashboard principal
2. `GET /control/salidas` - Listar salidas de productos
3. `POST /control/salidas` - Registrar nueva salida
4. `GET /control/produccion` - Listar producción diaria
5. `POST /control/produccion` - Registrar producción
6. `GET /inventario` - Ver inventario
7. `POST /mi-asistencia/entrada` - Marcar entrada personal
8. `GET /admin/reportes/produccion` - Reporte de producción

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Archivo**: 17-Rutas-Sistema.md
**Estado**: Todas las rutas del sistema documentadas
