<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use App\Models\Produccion; // SISTEMA ANTIGUO - DESHABILITADO
// use App\Models\Producto; // ELIMINADO - Solo se usa INVENTARIO
use App\Models\Inventario;
use App\Models\Personal;
use App\Models\Control\SalidaProducto;
use App\Models\Control\MantenimientoEquipo;
use App\Models\Control\ProduccionDiaria;
use App\Models\Control\ProduccionProducto;
use App\Models\Control\Fumigacion;
use App\Models\Control\FosaSeptica;
use App\Models\Control\TanqueAgua;
use App\Models\Control\Insumo;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del dashboard administrativo.
 *
 * Muestra KPIs y estadísticas principales del sistema.
 */
class DashboardController extends Controller
{
    /**
     * Mostrar dashboard con indicadores principales.
     */
    public function index(): View
    {
        // Producción del mes actual (desde control_produccion_productos)
        $produccionMes = ProduccionProducto::whereHas('produccion', function($query) {
                $query->whereMonth('fecha', now()->month)
                      ->whereYear('fecha', now()->year);
            })
            ->sum('cantidad') ?? 0;

        // Producción de hoy (desde control_produccion_productos)
        $produccionHoy = ProduccionProducto::whereHas('produccion', function($query) {
                $query->whereDate('fecha', today());
            })
            ->sum('cantidad') ?? 0;

        // Stock total del sistema (desde inventario)
        $stockTotal = Inventario::where('tipo_movimiento', 'entrada')->sum('cantidad')
                    - Inventario::where('tipo_movimiento', 'salida')->sum('cantidad');

        // Entradas del mes
        $entradasMes = Inventario::where('tipo_movimiento', 'entrada')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('cantidad') ?? 0;

        // Salidas del mes
        $salidasMes = Inventario::where('tipo_movimiento', 'salida')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('cantidad') ?? 0;

        // Personal activo
        $personalActivo = Personal::where('estado', 'activo')->count();

        // Vehículos activos
        $vehiculosActivos = DB::table('vehiculos')
            ->where('estado', 'activo')
            ->count();

        // Últimos movimientos de inventario
        $ultimosMovimientos = Inventario::orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Últimas salidas de productos
        $ultimasSalidas = SalidaProducto::orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Mantenimientos próximos (en los próximos 7 días o vencidos)
        $mantenimientosPendientes = MantenimientoEquipo::where(function($query) {
                $query->where('proxima_fecha', '<=', now()->addDays(7))
                      ->where('proxima_fecha', '>=', now()->subDays(30));
            })
            ->orWhereNull('proxima_fecha')
            ->orderBy('proxima_fecha', 'asc')
            ->limit(5)
            ->get();

        // Estadísticas de módulos de control
        $totalSalidas = SalidaProducto::count();
        $totalProduccionDiaria = ProduccionDiaria::count();
        $totalMantenimientos = MantenimientoEquipo::count();
        $totalFumigaciones = Fumigacion::count();
        $totalFosaSeptica = FosaSeptica::count();
        $totalTanques = TanqueAgua::count();
        $totalInsumos = Insumo::count();
        $totalAsistencias = DB::table('asistencias_semanales')->count();

        // Listado de personal activo
        $listaPersonal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->limit(8)
            ->get();

        // Listado de inventario (últimos movimientos)
        $listaInventario = Inventario::orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'produccionMes',
            'produccionHoy',
            'stockTotal',
            'entradasMes',
            'salidasMes',
            'personalActivo',
            'vehiculosActivos',
            'ultimosMovimientos',
            'ultimasSalidas',
            'mantenimientosPendientes',
            'totalSalidas',
            'totalProduccionDiaria',
            'totalMantenimientos',
            'totalFumigaciones',
            'totalFosaSeptica',
            'totalTanques',
            'totalInsumos',
            'totalAsistencias',
            'listaPersonal',
            'listaInventario'
        ));
    }

    /**
     * Obtener datos del dashboard en formato JSON para actualización en tiempo real.
     */
    public function getData(): JsonResponse
    {
        // Estadísticas de módulos de control
        $totalSalidas = SalidaProducto::count();
        $totalProduccionDiaria = ProduccionDiaria::count();
        $totalMantenimientos = MantenimientoEquipo::count();
        $totalFumigaciones = Fumigacion::count();
        $totalFosaSeptica = FosaSeptica::count();
        $totalTanques = TanqueAgua::count();
        $totalInsumos = Insumo::count();
        $totalAsistencias = DB::table('asistencias_semanales')->count();

        // Últimas salidas de productos
        $ultimasSalidas = SalidaProducto::orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($salida) {
                return [
                    'nombre_distribuidor' => $salida->nombre_distribuidor,
                    'fecha' => \Carbon\Carbon::parse($salida->fecha)->format('d/m/Y'),
                    'vehiculo_placa' => $salida->vehiculo_placa,
                    'botellones' => $salida->botellones ?? 0,
                ];
            });

        // Mantenimientos próximos
        $mantenimientosPendientes = MantenimientoEquipo::where(function($query) {
                $query->where('proxima_fecha', '<=', now()->addDays(7))
                      ->where('proxima_fecha', '>=', now()->subDays(30));
            })
            ->orWhereNull('proxima_fecha')
            ->orderBy('proxima_fecha', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($mantenimiento) {
                $diasRestantes = $mantenimiento->proxima_fecha
                    ? \Carbon\Carbon::parse($mantenimiento->proxima_fecha)->diffInDays(now(), false)
                    : null;

                return [
                    'equipo' => is_array($mantenimiento->equipo)
                        ? implode(', ', $mantenimiento->equipo)
                        : $mantenimiento->equipo,
                    'realizado_por' => $mantenimiento->realizado_por,
                    'proxima_fecha' => $mantenimiento->proxima_fecha
                        ? \Carbon\Carbon::parse($mantenimiento->proxima_fecha)->format('d/m/Y')
                        : null,
                    'estado' => $diasRestantes === null ? 'VIGENTE' : ($diasRestantes > 0 ? 'VENCIDO' : 'PRÓXIMO'),
                    'estado_clase' => $diasRestantes === null ? 'badge-success' : ($diasRestantes > 0 ? 'badge-danger' : 'badge-warning'),
                ];
            });

        return response()->json([
            'totales' => [
                'salidas' => $totalSalidas,
                'produccion' => $totalProduccionDiaria,
                'mantenimientos' => $totalMantenimientos,
                'fumigaciones' => $totalFumigaciones,
                'fosa_septica' => $totalFosaSeptica,
                'tanques' => $totalTanques,
                'insumos' => $totalInsumos,
                'asistencias' => $totalAsistencias,
            ],
            'ultimas_salidas' => $ultimasSalidas,
            'mantenimientos_pendientes' => $mantenimientosPendientes,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }
}
