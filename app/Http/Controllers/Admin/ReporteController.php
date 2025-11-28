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

        // Calcular estadísticas
        $totalProducido = 0;
        $productosUnicos = [];

        foreach ($producciones as $produccion) {
            foreach ($produccion->productos as $prod) {
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

        // Obtener productos con su stock actual desde la vista optimizada
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
    public function inventarioPDF(): Response
    {
        $productos = Producto::where('estado', 'activo')->get();

        $inventario = $productos->map(function ($producto) {
            $stockActual = Inventario::stockDisponible($producto->id);
            $stockMinimo = $producto->stock_minimo ?? 10; // Valor predeterminado de 10

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

        $pdf = PDF::loadView('admin.reportes.inventario_pdf', compact('inventario'));

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
