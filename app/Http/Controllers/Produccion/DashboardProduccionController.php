<?php

declare(strict_types=1);

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del dashboard de producción.
 *
 * Muestra KPIs y estadísticas relevantes para el módulo de producción.
 */
class DashboardProduccionController extends Controller
{
    /**
     * Mostrar dashboard de producción.
     */
    public function index(): View
    {
        // KPI: Producción del día
        $produccionHoy = Produccion::whereDate('fecha_produccion', today())
            ->sum('cantidad');

        // KPI: Producción de la semana
        $produccionSemana = Produccion::whereBetween('fecha_produccion', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('cantidad');

        // KPI: Producción del mes
        $produccionMes = Produccion::whereYear('fecha_produccion', date('Y'))
            ->whereMonth('fecha_produccion', date('m'))
            ->sum('cantidad');

        // Últimas producciones registradas (10 más recientes)
        $ultimasProducciones = Produccion::with('producto')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Productos más producidos del mes
        $productosMasProducidos = Produccion::select('id_producto', DB::raw('SUM(cantidad) as total'))
            ->whereYear('fecha_produccion', date('Y'))
            ->whereMonth('fecha_produccion', date('m'))
            ->groupBy('id_producto')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('producto')
            ->get();

        // Producción por día de la semana actual
        $produccionPorDia = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha = now()->startOfWeek()->addDays($i);
            $produccionPorDia[] = [
                'fecha' => $fecha->format('d/m'),
                'dia' => $fecha->locale('es')->isoFormat('ddd'),
                'cantidad' => Produccion::whereDate('fecha_produccion', $fecha)->sum('cantidad')
            ];
        }

        // Stock actual de productos (para referencia)
        $stockProductos = Producto::where('estado', 'activo')
            ->get()
            ->map(function ($producto) {
                $producto->stock_actual = Inventario::stockDisponible($producto->id);
                return $producto;
            })
            ->sortByDesc('stock_actual')
            ->take(5);

        return view('produccion.dashboard', compact(
            'produccionHoy',
            'produccionSemana',
            'produccionMes',
            'ultimasProducciones',
            'productosMasProducidos',
            'produccionPorDia',
            'stockProductos'
        ));
    }
}
