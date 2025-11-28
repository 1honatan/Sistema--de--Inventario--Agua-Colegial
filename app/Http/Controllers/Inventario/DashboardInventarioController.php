<?php

declare(strict_types=1);

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del dashboard de inventario.
 *
 * Muestra KPIs y estadísticas relevantes para el módulo de inventario.
 */
class DashboardInventarioController extends Controller
{
    /**
     * Mostrar dashboard de inventario.
     */
    public function index(): View
    {
        // ===== KPIs PRINCIPALES =====

        // KPI: Stock total disponible (suma de todos los productos activos)
        $stockTotal = Producto::where('estado', 'activo')
            ->get()
            ->sum(function ($producto) {
                return Inventario::stockDisponible($producto->id);
            });

        // KPI: Entradas de hoy (solo las entradas del día actual con cantidad positiva)
        $entradasHoy = Inventario::where('tipo_movimiento', 'entrada')
            ->whereDate('fecha_movimiento', today())
            ->where('cantidad', '>', 0)
            ->sum('cantidad');

        // KPI: Salidas de hoy (solo las salidas del día actual con cantidad positiva)
        $salidasHoy = Inventario::where('tipo_movimiento', 'salida')
            ->whereDate('fecha_movimiento', today())
            ->where('cantidad', '>', 0)
            ->sum('cantidad');

        // KPI: Productos en riesgo (stock actual < cantidad mínima)
        $productosEnRiesgo = Producto::where('estado', 'activo')
            ->get()
            ->filter(function ($producto) {
                $stock = Inventario::stockDisponible($producto->id);
                $stockMinimo = $producto->stock_minimo ?? 10;
                return $stock < $stockMinimo && $stock >= 0;
            })
            ->count();

        // ===== TABLA DE STOCK POR PRODUCTO =====

        // Todos los productos con su stock actual
        $productos = Producto::where('estado', 'activo')
            ->get()
            ->map(function ($producto) {
                $stockActual = Inventario::stockDisponible($producto->id);
                $stockMinimo = $producto->stock_minimo ?? 10;

                $producto->stock_actual = $stockActual;
                $producto->en_riesgo = $stockActual < $stockMinimo;

                // Determinar nivel de urgencia
                if ($stockActual == 0) {
                    $producto->urgencia = 'crítica';
                } elseif ($stockActual < $stockMinimo / 2) {
                    $producto->urgencia = 'alta';
                } elseif ($stockActual < $stockMinimo) {
                    $producto->urgencia = 'media';
                } else {
                    $producto->urgencia = 'normal';
                }

                return $producto;
            })
            ->sortBy('stock_actual');

        // ===== PANEL DE ALERTAS =====

        // Alertas de stock (solo productos en riesgo)
        $alertas = $productos->filter(function ($producto) {
            return $producto->en_riesgo;
        })->values();

        // ===== INFORMACIÓN ADICIONAL =====

        // Movimientos del día
        $movimientosHoy = Inventario::whereDate('fecha_movimiento', today())->count();

        // Últimos movimientos registrados
        $ultimosMovimientos = Inventario::with(['producto', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('inventario.dashboard', compact(
            'stockTotal',
            'entradasHoy',
            'salidasHoy',
            'productosEnRiesgo',
            'productos',
            'alertas',
            'movimientosHoy',
            'ultimosMovimientos'
        ));
    }
}
