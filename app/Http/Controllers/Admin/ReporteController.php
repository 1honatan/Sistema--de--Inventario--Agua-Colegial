<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produccion;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\HistorialReporte;
use App\Models\Control\SalidaProducto;
use App\Models\Control\MantenimientoEquipo;
use App\Models\Control\ProduccionDiaria;
use App\Models\Control\Fumigacion;
use App\Models\Control\FosaSeptica;
use App\Models\Control\TanqueAgua;
use App\Models\Control\Insumo;
use App\Models\AsistenciaSemanal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador de reportes (solo admin).
 *
 * Genera reportes de producción e inventario.
 * Soporta exportación a PDF y Excel.
 */
class ReporteController extends Controller
{
    /**
     * Mostrar formulario de reportes.
     */
    public function index(): View
    {
        // Obtener productos con su stock actual desde la vista optimizada
        $productos = \DB::table('v_stock_actual')
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'tipo', 'unidad_medida', 'stock_actual')
            ->get();

        // Obtener los últimos 10 reportes generados
        $reportesRecientes = HistorialReporte::with('usuario')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reportes.index', compact('productos', 'reportesRecientes'));
    }

    /**
     * Generar reporte de producción.
     */
    public function produccion(Request $request): View
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'id_producto' => ['nullable', 'exists:productos,id'],
        ]);

        // Obtener producciones diarias con sus productos y materiales
        $producciones = ProduccionDiaria::with(['productos.producto', 'materiales'])
            ->whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha', 'desc')
            ->get();

        // Filtrar por producto si se especifica
        if (!empty($validado['id_producto'])) {
            $producciones = $producciones->filter(function ($produccion) use ($validado) {
                return $produccion->productos->contains('producto_id', $validado['id_producto']);
            });
        }

        // Calcular estadísticas
        $totalProducido = 0;
        $productosUnicos = [];

        foreach ($producciones as $produccion) {
            foreach ($produccion->productos as $prod) {
                // Si hay filtro de producto, solo contar ese producto
                if (!empty($validado['id_producto']) && $prod->producto_id != $validado['id_producto']) {
                    continue;
                }

                $totalProducido += $prod->cantidad;
                if ($prod->producto) {
                    $productosUnicos[$prod->producto_id] = true;
                }
            }
        }

        $totalLotes = $producciones->count();
        $totalProductos = count($productosUnicos);

        // Calcular promedio diario
        $fechaInicio = \Carbon\Carbon::parse($validado['fecha_inicio']);
        $fechaFin = \Carbon\Carbon::parse($validado['fecha_fin']);
        $dias = $fechaInicio->diffInDays($fechaFin) + 1;
        $promedioDiario = $dias > 0 ? round($totalProducido / $dias, 2) : 0;

        // Obtener productos con su stock actual desde la vista optimizada
        $productos = DB::table('v_stock_actual')
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'tipo', 'unidad_medida', 'stock_actual')
            ->get();

        return view('admin.reportes.produccion', compact(
            'producciones',
            'productos',
            'totalProducido',
            'totalLotes',
            'totalProductos',
            'promedioDiario'
        ));
    }

    /**
     * Generar reporte de inventario.
     */
    public function inventario(Request $request): View
    {
        $validado = $request->validate([
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'tipo_movimiento' => ['nullable', 'in:entrada,salida'],
            'formato' => ['nullable', 'in:pdf,excel'],
        ]);

        // Si hay filtros de fecha o tipo de movimiento, mostrar movimientos de inventario
        if (!empty($validado['fecha_inicio']) || !empty($validado['fecha_fin']) || !empty($validado['tipo_movimiento'])) {
            $movimientos = collect();

            // Si se busca tipo "entrada" o sin especificar tipo, buscar en tabla inventario
            if (empty($validado['tipo_movimiento']) || $validado['tipo_movimiento'] === 'entrada') {
                $query = DB::table('inventario')
                    ->join('productos', 'inventario.id_producto', '=', 'productos.id')
                    ->select(
                        'inventario.id',
                        'inventario.fecha_movimiento',
                        'inventario.tipo_movimiento',
                        'inventario.cantidad',
                        'inventario.origen',
                        'inventario.observacion',
                        'productos.id as producto_id',
                        'productos.nombre as producto_nombre',
                        'productos.unidad_medida'
                    );

                // Aplicar filtros de fecha
                if (!empty($validado['fecha_inicio'])) {
                    $query->where('inventario.fecha_movimiento', '>=', $validado['fecha_inicio'] . ' 00:00:00');
                }
                if (!empty($validado['fecha_fin'])) {
                    $query->where('inventario.fecha_movimiento', '<=', $validado['fecha_fin'] . ' 23:59:59');
                }

                // Aplicar filtro de tipo de movimiento
                if (!empty($validado['tipo_movimiento'])) {
                    $query->where('inventario.tipo_movimiento', $validado['tipo_movimiento']);
                }

                $movimientos = $query->orderBy('inventario.fecha_movimiento', 'desc')->get();
            }

            // Si se busca tipo "salida" o sin especificar tipo, buscar en control_salidas_productos
            if (empty($validado['tipo_movimiento']) || $validado['tipo_movimiento'] === 'salida') {
                $query = DB::table('control_salidas_productos');

                // Aplicar filtros de fecha
                if (!empty($validado['fecha_inicio'])) {
                    $query->where('fecha', '>=', $validado['fecha_inicio']);
                }
                if (!empty($validado['fecha_fin'])) {
                    $query->where('fecha', '<=', $validado['fecha_fin']);
                }

                $salidas = $query->orderBy('fecha', 'desc')->get();

                // Mapeo de columnas a nombres de productos
                $productosMap = [
                    'botellones' => ['nombre' => 'Botellón 20 Litros', 'unidad' => 'unidad'],
                    'bolo_grande' => ['nombre' => 'Bolo Grande', 'unidad' => 'bolsa'],
                    'bolo_pequeño' => ['nombre' => 'Bolo Pequeño', 'unidad' => 'bolsa'],
                    'gelatina' => ['nombre' => 'Gelatina', 'unidad' => 'unidad'],
                    'agua_saborizada' => ['nombre' => 'Agua Saborizada', 'unidad' => 'bolsa'],
                    'agua_limon' => ['nombre' => 'Agua De Limon', 'unidad' => 'bolsa'],
                    'agua_natural' => ['nombre' => 'Agua Natural', 'unidad' => 'bolsa'],
                    'hielo' => ['nombre' => 'Hielo', 'unidad' => 'bolsa'],
                    'dispenser' => ['nombre' => 'Dispenser', 'unidad' => 'unidad'],
                ];

                // Convertir salidas a formato de movimientos
                $movimientosSalidas = collect();
                foreach ($salidas as $salida) {
                    foreach ($productosMap as $columna => $producto) {
                        $cantidad = $salida->$columna ?? 0;
                        if ($cantidad > 0) {
                            $movimientosSalidas->push((object)[
                                'id' => $salida->id,
                                'fecha_movimiento' => $salida->fecha,
                                'tipo_movimiento' => 'salida',
                                'cantidad' => $cantidad,
                                'origen' => $salida->nombre_distribuidor ?? 'Control de Salidas',
                                'observacion' => $salida->observaciones ?? '-',
                                'producto_id' => null,
                                'producto_nombre' => $producto['nombre'],
                                'unidad_medida' => $producto['unidad'],
                            ]);
                        }
                    }
                }

                // Combinar movimientos de entrada y salida
                if ($movimientos->isEmpty()) {
                    $movimientos = $movimientosSalidas;
                } else {
                    $movimientos = $movimientos->merge($movimientosSalidas);
                }

                // Ordenar por fecha descendente
                $movimientos = $movimientos->sortByDesc('fecha_movimiento')->values();
            }

            // Calcular estadísticas de movimientos
            $totalEntradas = $movimientos->where('tipo_movimiento', 'entrada')->sum('cantidad');
            $totalSalidas = $movimientos->where('tipo_movimiento', 'salida')->sum('cantidad');
            $stockActual = 0; // No aplica en vista de movimientos
            $totalMovimientos = $movimientos->count();

            return view('admin.reportes.inventario', compact(
                'movimientos',
                'validado',
                'totalEntradas',
                'totalSalidas',
                'stockActual',
                'totalMovimientos'
            ));
        } else {
            // Si no hay filtros, mostrar stock actual (reporte normal)
            $inventarios = DB::table('v_stock_actual')
                ->where('estado', 'activo')
                ->select('id', 'nombre', 'descripcion', 'tipo', 'unidad_medida', 'stock_actual')
                ->orderBy('nombre')
                ->get();

            // Calcular estadísticas
            $totalEntradas = DB::table('control_produccion_productos')->sum('cantidad');
            $totalSalidas = SalidaProducto::sum('botellones');
            $stockActual = $inventarios->sum('stock_actual');
            $totalMovimientos = ($totalEntradas + $totalSalidas);

            return view('admin.reportes.inventario', compact(
                'inventarios',
                'totalEntradas',
                'totalSalidas',
                'stockActual',
                'totalMovimientos'
            ));
        }
    }

    /**
     * Exportar reporte de producción a PDF.
     */
    public function produccionPDF(Request $request): Response
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date'],
            'id_producto' => ['nullable', 'exists:productos,id'],
        ]);

        // Obtener producciones diarias con sus productos y materiales
        $producciones = ProduccionDiaria::with(['productos.producto', 'materiales'])
            ->whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha', 'desc')
            ->get();

        // Filtrar por producto si se especifica
        if (!empty($validado['id_producto'])) {
            $producciones = $producciones->filter(function ($produccion) use ($validado) {
                return $produccion->productos->contains('producto_id', $validado['id_producto']);
            });
        }

        // Calcular total de productos
        $totalCantidad = 0;
        foreach ($producciones as $produccion) {
            foreach ($produccion->productos as $prod) {
                $totalCantidad += $prod->cantidad;
            }
        }

        // Registrar en historial
        HistorialReporte::create([
            'tipo' => 'produccion',
            'fecha_inicio' => $validado['fecha_inicio'],
            'fecha_fin' => $validado['fecha_fin'],
            'id_usuario' => auth()->id(),
            'formato' => 'pdf',
            'filtros' => ['id_producto' => $validado['id_producto'] ?? null],
        ]);

        $pdf = PDF::loadView('admin.reportes.produccion_pdf', compact(
            'producciones',
            'totalCantidad',
            'validado'
        ));

        $nombreArchivo = 'reporte_produccion_' . date('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Exportar reporte de inventario a PDF.
     */
    public function inventarioPDF(Request $request): Response
    {
        $validado = $request->validate([
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'tipo_movimiento' => ['nullable', 'in:entrada,salida'],
        ]);

        // Si hay filtros de fecha o tipo de movimiento, mostrar movimientos de inventario
        if (!empty($validado['fecha_inicio']) || !empty($validado['fecha_fin']) || !empty($validado['tipo_movimiento'])) {
            $movimientos = collect();

            // Si se busca tipo "entrada" o sin especificar tipo, buscar en tabla inventario
            if (empty($validado['tipo_movimiento']) || $validado['tipo_movimiento'] === 'entrada') {
                $query = DB::table('inventario')
                    ->join('productos', 'inventario.id_producto', '=', 'productos.id')
                    ->select(
                        'inventario.id',
                        'inventario.fecha_movimiento',
                        'inventario.tipo_movimiento',
                        'inventario.cantidad',
                        'inventario.origen',
                        'inventario.observacion',
                        'productos.id as producto_id',
                        'productos.nombre as producto_nombre',
                        'productos.unidad_medida'
                    );

                // Aplicar filtros de fecha
                if (!empty($validado['fecha_inicio'])) {
                    $query->where('inventario.fecha_movimiento', '>=', $validado['fecha_inicio'] . ' 00:00:00');
                }
                if (!empty($validado['fecha_fin'])) {
                    $query->where('inventario.fecha_movimiento', '<=', $validado['fecha_fin'] . ' 23:59:59');
                }

                // Aplicar filtro de tipo de movimiento
                if (!empty($validado['tipo_movimiento'])) {
                    $query->where('inventario.tipo_movimiento', $validado['tipo_movimiento']);
                }

                $movimientos = $query->orderBy('inventario.fecha_movimiento', 'desc')->get();
            }

            // Si se busca tipo "salida" o sin especificar tipo, buscar en control_salidas_productos
            if (empty($validado['tipo_movimiento']) || $validado['tipo_movimiento'] === 'salida') {
                $query = DB::table('control_salidas_productos');

                // Aplicar filtros de fecha
                if (!empty($validado['fecha_inicio'])) {
                    $query->where('fecha', '>=', $validado['fecha_inicio']);
                }
                if (!empty($validado['fecha_fin'])) {
                    $query->where('fecha', '<=', $validado['fecha_fin']);
                }

                $salidas = $query->orderBy('fecha', 'desc')->get();

                // Mapeo de columnas a nombres de productos
                $productosMap = [
                    'botellones' => ['nombre' => 'Botellón 20 Litros', 'unidad' => 'unidad'],
                    'bolo_grande' => ['nombre' => 'Bolo Grande', 'unidad' => 'bolsa'],
                    'bolo_pequeño' => ['nombre' => 'Bolo Pequeño', 'unidad' => 'bolsa'],
                    'gelatina' => ['nombre' => 'Gelatina', 'unidad' => 'unidad'],
                    'agua_saborizada' => ['nombre' => 'Agua Saborizada', 'unidad' => 'bolsa'],
                    'agua_limon' => ['nombre' => 'Agua De Limon', 'unidad' => 'bolsa'],
                    'agua_natural' => ['nombre' => 'Agua Natural', 'unidad' => 'bolsa'],
                    'hielo' => ['nombre' => 'Hielo', 'unidad' => 'bolsa'],
                    'dispenser' => ['nombre' => 'Dispenser', 'unidad' => 'unidad'],
                ];

                // Convertir salidas a formato de movimientos
                $movimientosSalidas = collect();
                foreach ($salidas as $salida) {
                    foreach ($productosMap as $columna => $producto) {
                        $cantidad = $salida->$columna ?? 0;
                        if ($cantidad > 0) {
                            $movimientosSalidas->push((object)[
                                'id' => $salida->id,
                                'fecha_movimiento' => $salida->fecha,
                                'tipo_movimiento' => 'salida',
                                'cantidad' => $cantidad,
                                'origen' => $salida->nombre_distribuidor ?? 'Control de Salidas',
                                'observacion' => $salida->observaciones ?? '-',
                                'producto_id' => null,
                                'producto_nombre' => $producto['nombre'],
                                'unidad_medida' => $producto['unidad'],
                            ]);
                        }
                    }
                }

                // Combinar movimientos de entrada y salida
                if ($movimientos->isEmpty()) {
                    $movimientos = $movimientosSalidas;
                } else {
                    $movimientos = $movimientos->merge($movimientosSalidas);
                }

                // Ordenar por fecha descendente
                $movimientos = $movimientos->sortByDesc('fecha_movimiento')->values();
            }

            // Registrar en historial
            HistorialReporte::create([
                'tipo' => 'inventario_movimientos',
                'fecha_inicio' => $validado['fecha_inicio'] ?? null,
                'fecha_fin' => $validado['fecha_fin'] ?? null,
                'id_usuario' => auth()->id(),
                'formato' => 'pdf',
                'filtros' => ['tipo_movimiento' => $validado['tipo_movimiento'] ?? null],
            ]);

            $pdf = PDF::loadView('admin.reportes.inventario_movimientos_pdf', compact('movimientos', 'validado'));

        } else {
            // Si no hay filtros, mostrar stock actual (reporte normal)
            $productos = Producto::where('estado', 'activo')->get();

            $inventario = $productos->map(function ($producto) {
                $stockActual = Inventario::stockDisponible($producto->id);
                $stockMinimo = $producto->stock_minimo ?? 10;

                return [
                    'producto' => $producto,
                    'stock_actual' => $stockActual,
                    'stock_minimo' => $stockMinimo,
                ];
            });

            // Registrar en historial
            HistorialReporte::create([
                'tipo' => 'inventario',
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'id_usuario' => auth()->id(),
                'formato' => 'pdf',
                'filtros' => null,
            ]);

            $pdf = PDF::loadView('admin.reportes.inventario_pdf', compact('inventario', 'validado'));
        }

        $nombreArchivo = 'reporte_inventario_' . date('Y-m-d') . '.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Reporte de Salidas de Productos.
     */
    public function salidas(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $salidas = SalidaProducto::whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalBotellones = $salidas->sum('botellones');
        $totalRegistros = $salidas->count();

        $pdf = PDF::loadView('admin.reportes.salidas_pdf', compact(
            'salidas',
            'totalBotellones',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_salidas_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Reporte de Mantenimiento de Equipos.
     */
    public function mantenimiento(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $mantenimientos = MantenimientoEquipo::whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalRegistros = $mantenimientos->count();

        $pdf = PDF::loadView('admin.reportes.mantenimiento_pdf', compact(
            'mantenimientos',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_mantenimiento_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Reporte de Fumigación.
     */
    public function fumigacion(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $fumigaciones = Fumigacion::whereBetween('fecha_fumigacion', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha_fumigacion', 'desc')
            ->get();

        $totalRegistros = $fumigaciones->count();

        $pdf = PDF::loadView('admin.reportes.fumigacion_pdf', compact(
            'fumigaciones',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_fumigacion_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Reporte de Fosa Séptica.
     */
    public function fosaSeptica(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $fosas = FosaSeptica::whereBetween('fecha_limpieza', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha_limpieza', 'desc')
            ->get();

        $totalRegistros = $fosas->count();

        $pdf = PDF::loadView('admin.reportes.fosa_septica_pdf', compact(
            'fosas',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_fosa_septica_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Reporte de Tanques de Agua.
     */
    public function tanques(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $tanques = TanqueAgua::whereBetween('fecha_limpieza', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha_limpieza', 'desc')
            ->get();

        $totalRegistros = $tanques->count();

        $pdf = PDF::loadView('admin.reportes.tanques_pdf', compact(
            'tanques',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_tanques_agua_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Reporte de Insumos.
     */
    public function insumos(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $insumos = Insumo::whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->orderBy('fecha', 'desc')
            ->get();

        $totalRegistros = $insumos->count();

        $pdf = PDF::loadView('admin.reportes.insumos_pdf', compact(
            'insumos',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_insumos_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Reporte de Asistencia.
     */
    public function asistencia(Request $request)
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $asistencias = AsistenciaSemanal::whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']])
            ->with('personal')
            ->orderBy('fecha', 'desc')
            ->get();

        $totalRegistros = $asistencias->count();

        $pdf = PDF::loadView('admin.reportes.asistencia_pdf', compact(
            'asistencias',
            'totalRegistros',
            'validado'
        ));

        return $pdf->download('reporte_asistencia_' . date('Y-m-d') . '.pdf');
    }

}
