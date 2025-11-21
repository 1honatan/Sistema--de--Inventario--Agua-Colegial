<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produccion;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Personal;
use App\Models\Control\SalidaProducto;
use App\Models\Control\MantenimientoEquipo;
use App\Models\Control\ProduccionDiaria;
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
        // Total de productos activos
        $totalProductos = Producto::where('estado', 'activo')->count();

        // Producción del mes actual
        $produccionMes = Produccion::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('cantidad') ?? 0;

        // Producción de hoy
        $produccionHoy = Produccion::whereDate('created_at', today())
            ->sum('cantidad') ?? 0;

        // Stock total del sistema
        $stockTotal = 0;
        $productos = Producto::where('estado', 'activo')->get();
        foreach ($productos as $producto) {
            $stockTotal += Inventario::stockDisponible($producto->id);
        }

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

        // Productos con stock bajo
        $productosStockBajo = $this->obtenerProductosStockBajo(50);

        // Últimos movimientos
        $ultimosMovimientos = Inventario::with(['producto', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Productos más producidos este mes
        $productosMasProducidos = Produccion::select('id_producto', DB::raw('SUM(cantidad) as total'))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('id_producto')
            ->orderBy('total', 'desc')
            ->with('producto')
            ->limit(5)
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

        // Listado de productos con stock
        $listaProductos = Producto::where('estado', 'activo')
            ->orderBy('nombre')
            ->limit(8)
            ->get()
            ->map(function ($producto) {
                $producto->stock_actual = Inventario::stockDisponible($producto->id);
                return $producto;
            });

        // Listado de personal activo
        $listaPersonal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->limit(8)
            ->get();

        // Listado de inventario (últimos movimientos)
        $listaInventario = Inventario::with(['producto', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalProductos',
            'produccionMes',
            'produccionHoy',
            'stockTotal',
            'entradasMes',
            'salidasMes',
            'personalActivo',
            'vehiculosActivos',
            'productosStockBajo',
            'ultimosMovimientos',
            'productosMasProducidos',
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
            'listaProductos',
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

        // Productos con stock bajo
        $productosStockBajo = $this->obtenerProductosStockBajo(50)->take(6)->map(function ($producto) {
            return [
                'nombre' => $producto->nombre,
                'tipo' => $producto->tipo ?? 'General',
                'stock_actual' => $producto->stock_actual,
            ];
        })->values();

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
            'productos_stock_bajo' => $productosStockBajo,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    /**
     * Obtener productos con stock bajo.
     *
     * @param  int  $umbral  Umbral de stock bajo
     * @return \Illuminate\Support\Collection
     */
    protected function obtenerProductosStockBajo(int $umbral)
    {
        $productos = Producto::where('estado', 'activo')->get();

        return $productos->filter(function ($producto) use ($umbral) {
            $stock = Inventario::stockDisponible($producto->id);
            return $stock < $umbral && $stock >= 0;
        })->map(function ($producto) {
            $producto->stock_actual = Inventario::stockDisponible($producto->id);
            return $producto;
        });
    }
}
