<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\TipoProductoController;
use App\Http\Controllers\Admin\VehiculoController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\ConfiguracionController;
// use App\Http\Controllers\Produccion\ProduccionController; // Sistema antiguo - deshabilitado
// use App\Http\Controllers\Produccion\DashboardProduccionController; // Sistema antiguo - deshabilitado
use App\Http\Controllers\Inventario\InventarioController;
use App\Http\Controllers\Inventario\DashboardInventarioController;
use App\Http\Controllers\Admin\AsistenciaController as AdminAsistenciaController;
use App\Http\Controllers\Personal\AsistenciaController;

/*
|--------------------------------------------------------------------------
| Rutas Web - Sistema Agua Colegial
|--------------------------------------------------------------------------
|
| Arquitectura de rutas:
| - Rutas públicas: login
| - Rutas autenticadas con roles específicos usando middleware CheckRole
| - Agrupadas por módulo (Admin, Produccion, Inventario, Despacho)
|
*/

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login con rate limiting (máximo 5 intentos por minuto)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.post')
        ->middleware('throttle:5,1'); // 5 intentos por minuto
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Administrativas (solo rol: admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');

    // Gestión de Vehículos
    Route::resource('vehiculos', VehiculoController::class)->except(['show']);
    Route::post('vehiculos/{vehiculo}/toggle-estado', [VehiculoController::class, 'toggleEstado'])->name('vehiculos.toggle-estado');

    // Configuración del Sistema
    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('index');
        Route::post('/backup', [ConfiguracionController::class, 'generarBackup'])->name('backup');
        Route::get('/backup/{archivo}/descargar', [ConfiguracionController::class, 'descargarBackup'])->name('backup.descargar');
        Route::delete('/backup/{archivo}', [ConfiguracionController::class, 'eliminarBackup'])->name('backup.eliminar');
    });

    // Gestión de Asistencias
    Route::prefix('asistencia')->name('asistencia.')->group(function () {
        Route::get('/', [AdminAsistenciaController::class, 'index'])->name('index');
        Route::get('/personal/{personal}', [AdminAsistenciaController::class, 'verPorPersonal'])->name('ver-personal');
        Route::get('/reporte', [AdminAsistenciaController::class, 'reporte'])->name('reporte');
    });

    // Gestión de Asignaciones de Personal (Control Centralizado)
    Route::prefix('asignaciones')->name('asignaciones.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'store'])->name('store');
        Route::get('/{asignacion}', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'show'])->name('show');
        Route::get('/{asignacion}/editar', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'edit'])->name('edit');
        Route::put('/{asignacion}', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'update'])->name('update');
        Route::patch('/{asignacion}/suspender', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'suspend'])->name('suspend');
        Route::patch('/{asignacion}/finalizar', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'finalize'])->name('finalize');
        Route::patch('/{asignacion}/reactivar', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'reactivate'])->name('reactivate');
        Route::delete('/{asignacion}', [\App\Http\Controllers\Admin\AdminAsignacionController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Módulo de Reportes (roles: admin, produccion)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,produccion'])->prefix('admin')->name('admin.')->group(function () {
    // Reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/produccion', [ReporteController::class, 'produccion'])->name('produccion');
        Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('/salidas', [ReporteController::class, 'salidas'])->name('salidas');
        Route::get('/mantenimiento', [ReporteController::class, 'mantenimiento'])->name('mantenimiento');
        Route::get('/fumigacion', [ReporteController::class, 'fumigacion'])->name('fumigacion');
        Route::get('/fosa-septica', [ReporteController::class, 'fosaSeptica'])->name('fosa-septica');
        Route::get('/tanques', [ReporteController::class, 'tanques'])->name('tanques');
        Route::get('/insumos', [ReporteController::class, 'insumos'])->name('insumos');
        Route::get('/asistencia', [ReporteController::class, 'asistencia'])->name('asistencia');
        Route::get('/despachos', [ReporteController::class, 'salidas'])->name('despachos'); // Alias para salidas

        // Exportar a PDF
        Route::get('/produccion/pdf', [ReporteController::class, 'produccionPDF'])->name('produccion.pdf');
        Route::get('/inventario/pdf', [ReporteController::class, 'inventarioPDF'])->name('inventario.pdf');
    });
});

/*
|--------------------------------------------------------------------------
| Módulo de Producción (roles: admin, produccion)
|--------------------------------------------------------------------------
*/

// SISTEMA ANTIGUO DE PRODUCCIÓN - DESHABILITADO (reemplazado por control_produccion_diaria)
// Route::middleware(['auth', 'role:admin,produccion'])->prefix('produccion')->name('produccion.')->group(function () {
//     // Dashboard de Producción
//     Route::get('/dashboard', [DashboardProduccionController::class, 'index'])->name('dashboard');
//
//     // Gestión de Producción
//     Route::get('/', [ProduccionController::class, 'index'])->name('index');
//     Route::get('/crear', [ProduccionController::class, 'create'])->name('create');
//     Route::post('/', [ProduccionController::class, 'store'])->name('store');
//     Route::get('/{produccion}', [ProduccionController::class, 'show'])->name('show');
//     Route::get('/reporte/generar', [ProduccionController::class, 'reporte'])->name('reporte');
// });

/*
|--------------------------------------------------------------------------
| Módulo de Almacén (roles: admin, produccion)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,produccion'])->prefix('almacen')->name('almacen.')->group(function () {
    // Gestión de Productos en Almacén
    Route::get('/', [\App\Http\Controllers\Produccion\AlmacenController::class, 'index'])->name('index');
    Route::get('/crear', [\App\Http\Controllers\Produccion\AlmacenController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Produccion\AlmacenController::class, 'store'])->name('store');
    Route::get('/{producto}/editar', [\App\Http\Controllers\Produccion\AlmacenController::class, 'edit'])->name('edit');
    Route::put('/{producto}', [\App\Http\Controllers\Produccion\AlmacenController::class, 'update'])->name('update');
    Route::delete('/{producto}', [\App\Http\Controllers\Produccion\AlmacenController::class, 'destroy'])->name('destroy');

    // Ajustes de Stock
    Route::get('/{producto}/ajustar-stock', [\App\Http\Controllers\Produccion\AlmacenController::class, 'ajustarStock'])->name('ajustar-stock');
    Route::post('/{producto}/procesar-ajuste', [\App\Http\Controllers\Produccion\AlmacenController::class, 'procesarAjuste'])->name('procesar-ajuste');
});

/*
|--------------------------------------------------------------------------
| Módulo de Inventario (roles: admin, inventario, produccion)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,inventario,produccion'])->prefix('inventario')->name('inventario.')->group(function () {
    // Dashboard de Inventario
    Route::get('/dashboard', [DashboardInventarioController::class, 'index'])->name('dashboard');

    // Gestión de Inventario
    Route::get('/', [InventarioController::class, 'index'])->name('index');
    Route::get('/movimiento/crear', [InventarioController::class, 'createMovimiento'])->name('movimiento.create');
    Route::post('/movimiento', [InventarioController::class, 'storeMovimiento'])->name('movimiento.store');
    Route::get('/movimiento/historial', [InventarioController::class, 'historialMovimientos'])->name('movimiento.historial');
    Route::post('/movimiento/exportar-pdf', [InventarioController::class, 'exportarMovimientosPDF'])->name('movimiento.exportar-pdf');
    Route::post('/movimiento/exportar-excel', [InventarioController::class, 'exportarMovimientosExcel'])->name('movimiento.exportar-excel');
    Route::get('/producto/{producto}/historial', [InventarioController::class, 'historial'])->name('historial');

    // Gestión de Productos
    Route::get('/productos/crear', [InventarioController::class, 'createProducto'])->name('productos.create');
    Route::post('/productos', [InventarioController::class, 'storeProducto'])->name('productos.store');
    Route::get('/productos/{producto}/editar', [InventarioController::class, 'editProducto'])->name('productos.edit');
    Route::put('/productos/{producto}', [InventarioController::class, 'updateProducto'])->name('productos.update');
    Route::delete('/productos/{producto}', [InventarioController::class, 'destroyProducto'])->name('productos.destroy');

    // API para verificar alertas de stock (AJAX)
    Route::get('/api/verificar-alertas', [InventarioController::class, 'verificarAlertasStock'])->name('api.verificar-alertas');

    // Gestión de Alertas de Stock
    Route::get('/alertas', [InventarioController::class, 'alertas'])->name('alertas');
    Route::post('/alertas/{alerta}/atender', [InventarioController::class, 'atenderAlerta'])->name('alertas.atender');
    Route::post('/alertas/{alerta}/ignorar', [InventarioController::class, 'ignorarAlerta'])->name('alertas.ignorar');
});


/*
|--------------------------------------------------------------------------
| Módulo de Asistencia Personal
|--------------------------------------------------------------------------
| Permite al personal registrar su propia asistencia (entrada/salida/ausencia)
*/

Route::middleware(['auth'])->prefix('mi-asistencia')->name('personal.asistencia.')->group(function () {
    // Panel de asistencia personal
    Route::get('/', [AsistenciaController::class, 'index'])->name('index');

    // Registrar entrada
    Route::post('/entrada', [AsistenciaController::class, 'registrarEntrada'])->name('entrada');

    // Registrar salida
    Route::post('/salida', [AsistenciaController::class, 'registrarSalida'])->name('salida');

    // Registrar ausencia/permiso
    Route::post('/ausencia', [AsistenciaController::class, 'registrarAusencia'])->name('ausencia');

    // Historial de asistencias
    Route::get('/historial', [AsistenciaController::class, 'historial'])->name('historial');
});

/*
|--------------------------------------------------------------------------
| Módulos de Control (roles: admin, produccion)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,produccion'])->prefix('control')->name('control.')->group(function () {

    // 1. Control de Salidas de Productos "Colegial"
    Route::prefix('salidas')->name('salidas.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\SalidasController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\SalidasController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\SalidasController::class, 'store'])->name('store');
        Route::get('/{salida}', [\App\Http\Controllers\Control\SalidasController::class, 'show'])->name('show');
        Route::get('/{salida}/editar', [\App\Http\Controllers\Control\SalidasController::class, 'edit'])->name('edit');
        Route::put('/{salida}', [\App\Http\Controllers\Control\SalidasController::class, 'update'])->name('update');
        Route::delete('/{salida}', [\App\Http\Controllers\Control\SalidasController::class, 'destroy'])->name('destroy');
        Route::get('/{salida}/pdf', [\App\Http\Controllers\Control\SalidasController::class, 'generarPDF'])->name('pdf');
    });

    // 2. Control de Productos Producidos Diarios
    Route::prefix('produccion')->name('produccion.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'store'])->name('store');
        Route::get('/{produccion}', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'show'])->name('show');
        Route::get('/{produccion}/editar', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'edit'])->name('edit');
        Route::put('/{produccion}', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'update'])->name('update');
        Route::delete('/{produccion}', [\App\Http\Controllers\Control\ProduccionDiariaController::class, 'destroy'])->name('destroy');
    });

    // 4. Control de Mantenimiento de Equipos
    Route::prefix('mantenimiento')->name('mantenimiento.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\MantenimientoController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\MantenimientoController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\MantenimientoController::class, 'store'])->name('store');
        Route::get('/{mantenimiento}', [\App\Http\Controllers\Control\MantenimientoController::class, 'show'])->name('show');
        Route::get('/{mantenimiento}/editar', [\App\Http\Controllers\Control\MantenimientoController::class, 'edit'])->name('edit');
        Route::put('/{mantenimiento}', [\App\Http\Controllers\Control\MantenimientoController::class, 'update'])->name('update');
        Route::delete('/{mantenimiento}', [\App\Http\Controllers\Control\MantenimientoController::class, 'destroy'])->name('destroy');
    });

    // 5. Control de Limpieza de Fosa Séptica
    Route::prefix('fosa-septica')->name('fosa-septica.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\FosaSepticaController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\FosaSepticaController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\FosaSepticaController::class, 'store'])->name('store');
        Route::get('/{fosa}/editar', [\App\Http\Controllers\Control\FosaSepticaController::class, 'edit'])->name('edit');
        Route::put('/{fosa}', [\App\Http\Controllers\Control\FosaSepticaController::class, 'update'])->name('update');
        Route::delete('/{fosa}', [\App\Http\Controllers\Control\FosaSepticaController::class, 'destroy'])->name('destroy');
    });

    // 6. Control de Insumos
    Route::prefix('insumos')->name('insumos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\InsumosController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\InsumosController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\InsumosController::class, 'store'])->name('store');
        Route::get('/{insumo}/editar', [\App\Http\Controllers\Control\InsumosController::class, 'edit'])->name('edit');
        Route::put('/{insumo}', [\App\Http\Controllers\Control\InsumosController::class, 'update'])->name('update');
        Route::delete('/{insumo}', [\App\Http\Controllers\Control\InsumosController::class, 'destroy'])->name('destroy');
    });

    // 7. Control de Fumigación
    Route::prefix('fumigacion')->name('fumigacion.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\FumigacionController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\FumigacionController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\FumigacionController::class, 'store'])->name('store');
        Route::get('/{fumigacion}/editar', [\App\Http\Controllers\Control\FumigacionController::class, 'edit'])->name('edit');
        Route::put('/{fumigacion}', [\App\Http\Controllers\Control\FumigacionController::class, 'update'])->name('update');
        Route::delete('/{fumigacion}', [\App\Http\Controllers\Control\FumigacionController::class, 'destroy'])->name('destroy');
    });

    // 8. Control de Limpieza y Desinfección de Tanques de Agua
    Route::prefix('tanques')->name('tanques.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\TanquesController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\TanquesController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\TanquesController::class, 'store'])->name('store');
        Route::get('/{tanque}', [\App\Http\Controllers\Control\TanquesController::class, 'show'])->name('show');
        Route::get('/{tanque}/editar', [\App\Http\Controllers\Control\TanquesController::class, 'edit'])->name('edit');
        Route::put('/{tanque}', [\App\Http\Controllers\Control\TanquesController::class, 'update'])->name('update');
        Route::delete('/{tanque}', [\App\Http\Controllers\Control\TanquesController::class, 'destroy'])->name('destroy');
    });

    // 9. Control de Asistencia Semanal del Personal
    Route::prefix('asistencia-semanal')->name('asistencia-semanal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'index'])->name('index');
        Route::get('/crear', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'store'])->name('store');
        Route::get('/{asistencia}/editar', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'edit'])->name('edit');
        Route::put('/{asistencia}', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'update'])->name('update');
        Route::delete('/{asistencia}', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'destroy'])->name('destroy');
        Route::get('/reporte', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'generarReporte'])->name('reporte');

        // Registro rápido de entrada/salida (para encargados)
        Route::get('/registro-rapido', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'registroRapido'])->name('registro-rapido');
        Route::post('/registrar-entrada', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'registrarEntrada'])->name('registrar-entrada');
        Route::post('/registrar-salida', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'registrarSalida'])->name('registrar-salida');
    });

    // Gestión de Empleados
    Route::prefix('empleados')->name('empleados.')->group(function () {
        Route::get('/crear', [\App\Http\Controllers\Control\EmpleadoController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Control\EmpleadoController::class, 'store'])->name('store');
        Route::get('/{id}/editar', [\App\Http\Controllers\Control\EmpleadoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Control\EmpleadoController::class, 'update'])->name('update');
        Route::get('/{id}', [\App\Http\Controllers\Control\EmpleadoController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Control\EmpleadoController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Registro de Asistencia Personal (acceso para todo el personal)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('mi-asistencia-semanal')->name('control.asistencia-semanal.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'miRegistro'])->name('mi-registro');
    Route::post('/marcar-entrada', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'marcarMiEntrada'])->name('marcar-mi-entrada');
    Route::post('/marcar-salida', [\App\Http\Controllers\Control\AsistenciaSemanalController::class, 'marcarMiSalida'])->name('marcar-mi-salida');
});

/*
|--------------------------------------------------------------------------
| Ruta de fallback para errores 404
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
