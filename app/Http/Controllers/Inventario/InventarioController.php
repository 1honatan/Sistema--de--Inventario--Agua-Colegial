<?php

declare(strict_types=1);

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventarioRequest;
use App\Models\Inventario;
use App\Models\Producto;
// use App\Models\TipoProducto; // ELIMINADO - No se usa tipos_producto
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovimientosExport;

/**
 * Controlador del módulo de inventario.
 *
 * Roles permitidos: admin, inventario, produccion
 */
class InventarioController extends Controller
{
    /**
     * Mostrar inventario general con stock actual.
     */
    public function index(Request $request): View
    {
        $query = Producto::where('estado', 'activo');

        // Filtrar por tipo si se proporciona
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $productos = $query->orderBy('nombre')->get();

        // Calcular stock total del sistema
        $stockTotal = 0;
        foreach ($productos as $producto) {
            $stockTotal += Inventario::stockDisponible($producto->id);
        }

        // Calcular entradas y salidas del día
        $entradasHoy = Inventario::whereDate('fecha_movimiento', today())
            ->where('tipo_movimiento', 'entrada')
            ->sum('cantidad');

        $salidasHoy = Inventario::whereDate('fecha_movimiento', today())
            ->where('tipo_movimiento', 'salida')
            ->sum('cantidad');

        // Calcular stock actual para cada producto
        $inventario = $productos->map(function ($producto) {
            $stockActual = Inventario::stockDisponible($producto->id);

            return [
                'producto' => $producto,
                'stock_actual' => $stockActual,
                'estado_stock' => $this->determinarEstadoStock($stockActual),
                'alerta' => $stockActual < 10,
            ];
        });

        return view('inventario.index', compact(
            'productos',
            'inventario',
            'stockTotal',
            'entradasHoy',
            'salidasHoy'
        ));
    }

    /**
     * Mostrar formulario de registro de movimiento.
     */
    public function createMovimiento(): View
    {
        // Obtener productos con su stock actual desde la vista optimizada
        $productos = \DB::table('v_stock_actual')
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'tipo', 'unidad_medida', 'stock_actual')
            ->get();

        $usuarios = Usuario::where('estado', 'activo')->with('personal')->get();

        return view('inventario.create_movimiento', compact('productos', 'usuarios'));
    }

    /**
     * Registrar movimiento de inventario (entrada o salida manual) con trazabilidad.
     */
    public function storeMovimiento(StoreInventarioRequest $request): RedirectResponse
    {
        $validado = $request->validated();

        // Registrar movimiento con trazabilidad completa
        Inventario::create([
            'id_producto' => $validado['id_producto'],
            'tipo_movimiento' => $validado['tipo_movimiento'],
            'cantidad' => $validado['cantidad'],
            'origen' => $validado['origen'] ?? null,
            'destino' => $validado['destino'] ?? null,
            'referencia' => $validado['referencia'] ?? null,
            'id_usuario' => $validado['id_usuario'],
            'fecha_movimiento' => $validado['fecha_movimiento'],
            'observacion' => $validado['observacion'] ?? null,
        ]);

        $tipoTexto = $validado['tipo_movimiento'] === 'entrada' ? 'Entrada' : 'Salida';

        return redirect()->route('inventario.index')
            ->with('success', "{$tipoTexto} registrada exitosamente");
    }

    /**
     * Mostrar historial general de todos los movimientos de inventario.
     */
    public function historialMovimientos(Request $request): View
    {
        // Construir consulta unificada de todos los movimientos
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $tipoMovimiento = $request->input('tipo_movimiento');

        // Movimientos del inventario directo
        $queryInventario = \DB::table('inventario')
            ->join('productos', 'inventario.id_producto', '=', 'productos.id')
            ->leftJoin('usuarios', 'inventario.id_usuario', '=', 'usuarios.id')
            ->select(
                'inventario.fecha_movimiento as fecha',
                'inventario.tipo_movimiento as tipo',
                'inventario.cantidad',
                'productos.nombre as producto',
                \DB::raw("COALESCE(usuarios.nombre, 'Sistema') as responsable"),
                \DB::raw("COALESCE(inventario.observacion, inventario.referencia, 'Movimiento de inventario') as motivo"),
                \DB::raw("'inventario' as origen")
            );

        // Movimientos de producción (entradas)
        $queryProduccion = \DB::table('control_produccion_diaria')
            ->join('control_produccion_productos', 'control_produccion_diaria.id', '=', 'control_produccion_productos.produccion_id')
            ->join('productos', 'control_produccion_productos.producto_id', '=', 'productos.id')
            ->select(
                \DB::raw("CONCAT(control_produccion_diaria.fecha, ' 08:00:00') as fecha"),
                \DB::raw("'entrada' as tipo"),
                'control_produccion_productos.cantidad',
                'productos.nombre as producto',
                \DB::raw("COALESCE(control_produccion_diaria.responsable, 'Producción') as responsable"),
                \DB::raw("CONCAT('Producción diaria - Turno: ', COALESCE(control_produccion_diaria.turno, 'N/A')) as motivo"),
                \DB::raw("'produccion' as origen")
            );

        // Movimientos de salidas (salidas)
        $querySalidas = \DB::table('control_salidas_productos')
            ->select(
                \DB::raw("CONCAT(control_salidas_productos.fecha, ' ', COALESCE(control_salidas_productos.hora_llegada, '12:00:00')) as fecha"),
                \DB::raw("'salida' as tipo"),
                'control_salidas_productos.botellones as cantidad',
                \DB::raw("'Botellón 20L' as producto"),
                'control_salidas_productos.nombre_distribuidor as responsable',
                \DB::raw("CONCAT('Salida a distribuidor - Vehículo: ', COALESCE(control_salidas_productos.vehiculo_placa, 'N/A')) as motivo"),
                \DB::raw("'salidas' as origen")
            )
            ->where('control_salidas_productos.botellones', '>', 0);

        // Aplicar filtros de fecha
        if ($fechaInicio && $fechaFin) {
            $queryInventario->whereBetween('inventario.fecha_movimiento', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
            $queryProduccion->whereBetween('control_produccion_diaria.fecha', [$fechaInicio, $fechaFin]);
            $querySalidas->whereBetween('control_salidas_productos.fecha', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $queryInventario->where('inventario.fecha_movimiento', '>=', $fechaInicio . ' 00:00:00');
            $queryProduccion->where('control_produccion_diaria.fecha', '>=', $fechaInicio);
            $querySalidas->where('control_salidas_productos.fecha', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $queryInventario->where('inventario.fecha_movimiento', '<=', $fechaFin . ' 23:59:59');
            $queryProduccion->where('control_produccion_diaria.fecha', '<=', $fechaFin);
            $querySalidas->where('control_salidas_productos.fecha', '<=', $fechaFin);
        }

        // Aplicar filtro de tipo de movimiento
        if ($tipoMovimiento) {
            $queryInventario->where('inventario.tipo_movimiento', $tipoMovimiento);
            if ($tipoMovimiento === 'entrada') {
                // Solo mostrar producción
                $querySalidas = \DB::table('control_salidas_productos')->whereRaw('1 = 0');
            } else {
                // Solo mostrar salidas
                $queryProduccion = \DB::table('control_produccion_diaria')->whereRaw('1 = 0');
            }
        }

        // Unir todas las consultas
        $movimientos = $queryInventario
            ->union($queryProduccion)
            ->union($querySalidas)
            ->orderBy('fecha', 'desc')
            ->paginate(30)
            ->withQueryString();

        // Obtener productos activos con stock para filtro
        $productos = \DB::table('v_stock_actual')
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'tipo', 'unidad_medida', 'stock_actual')
            ->orderBy('nombre')
            ->get();

        // Obtener usuarios activos para filtro
        $usuarios = Usuario::with('personal')
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $idProducto = $request->input('id_producto');
        $idUsuario = $request->input('id_usuario');

        return view('inventario.movimientos', compact(
            'movimientos',
            'productos',
            'usuarios',
            'fechaInicio',
            'fechaFin',
            'tipoMovimiento',
            'idProducto',
            'idUsuario'
        ));
    }

    /**
     * Mostrar historial de movimientos de un producto.
     */
    public function historial(Request $request, Producto $producto): View
    {
        $query = Inventario::where('id_producto', $producto->id);

        // Filtrar por tipo de movimiento
        if ($request->filled('tipo_movimiento')) {
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        // Filtrar por rango de fechas
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_movimiento', [
                $request->fecha_inicio,
                $request->fecha_fin,
            ]);
        }

        $movimientos = $query->orderBy('fecha_movimiento', 'desc')
            ->paginate(30);

        $stockActual = Inventario::stockDisponible($producto->id);

        return view('inventario.historial', compact('producto', 'movimientos', 'stockActual'));
    }

    /**
     * Verificar alertas de stock bajo (para notificaciones en tiempo real).
     * Umbrales personalizados por tipo de producto:
     * - Agua de sabor, agua de limón, agua natural: < 50 unidades
     * - Bolos grande, bolos pequeño, gelatina: < 25 unidades
     * - Botellones: < 5 unidades
     * - Hielo: < 5 unidades (bolsas)
     */
    public function verificarAlertasStock(): \Illuminate\Http\JsonResponse
    {
        $productos = Producto::where('estado', 'activo')->get();
        $alertas = [];

        foreach ($productos as $producto) {
            $stockActual = Inventario::stockDisponible($producto->id);
            $nombreProducto = strtolower($producto->nombre);

            // Determinar umbral según el tipo de producto
            $umbral = $this->obtenerUmbralProducto($nombreProducto);

            // Si el stock está por debajo del umbral, generar alerta
            if ($stockActual < $umbral) {
                // Calcular nivel de urgencia basado en el porcentaje del umbral
                $porcentaje = $umbral > 0 ? ($stockActual / $umbral) * 100 : 0;
                $nivelUrgencia = $this->calcularNivelUrgencia($porcentaje);

                $alertas[] = [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'stock_actual' => $stockActual,
                    'umbral' => $umbral,
                    'nivel_urgencia' => $nivelUrgencia,
                ];
            }
        }

        return response()->json([
            'alertas' => $alertas,
            'total' => count($alertas),
        ]);
    }

    /**
     * Obtener el umbral de alerta según el nombre del producto.
     */
    private function obtenerUmbralProducto(string $nombreProducto): int
    {
        // Agua de sabor, agua de limón, agua natural: < 50 unidades
        if (
            str_contains($nombreProducto, 'agua de sabor') ||
            str_contains($nombreProducto, 'agua saborizada') ||
            str_contains($nombreProducto, 'agua de limon') ||
            str_contains($nombreProducto, 'agua de limón') ||
            str_contains($nombreProducto, 'agua limon') ||
            str_contains($nombreProducto, 'agua limón') ||
            str_contains($nombreProducto, 'agua natural')
        ) {
            return 50;
        }

        // Bolos grande, bolos pequeño, gelatina: < 25 unidades
        if (
            str_contains($nombreProducto, 'bolo') ||
            str_contains($nombreProducto, 'gelatina')
        ) {
            return 25;
        }

        // Botellones: < 5 unidades
        if (
            str_contains($nombreProducto, 'botellon') ||
            str_contains($nombreProducto, 'botellón')
        ) {
            return 5;
        }

        // Hielo: < 5 unidades (bolsas)
        if (str_contains($nombreProducto, 'hielo')) {
            return 5;
        }

        // Umbral por defecto para otros productos
        return 10;
    }

    /**
     * Calcular nivel de urgencia según el porcentaje del umbral.
     * - Crítico: Stock <= 20% del umbral
     * - Alto: Stock <= 50% del umbral
     * - Medio: Stock > 50% del umbral
     */
    private function calcularNivelUrgencia(float $porcentaje): string
    {
        if ($porcentaje <= 20) {
            return 'critico';
        }

        if ($porcentaje <= 50) {
            return 'alto';
        }

        return 'medio';
    }

    /**
     * Exportar movimientos a PDF.
     */
    public function exportarMovimientosPDF(Request $request)
    {
        try {
            // Construir consulta con los filtros
            $query = Inventario::with(['producto.tipoProducto', 'usuario.personal']);

            // Aplicar filtros
            if ($request->filled('tipo_movimiento')) {
                $query->where('tipo_movimiento', $request->tipo_movimiento);
            }

            if ($request->filled('id_producto')) {
                $query->where('id_producto', $request->id_producto);
            }

            if ($request->filled('id_usuario')) {
                $query->where('id_usuario', $request->id_usuario);
            }

            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha_movimiento', [
                    $request->fecha_inicio . ' 00:00:00',
                    $request->fecha_fin . ' 23:59:59',
                ]);
            } elseif ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha_movimiento', '>=', $request->fecha_inicio);
            } elseif ($request->filled('fecha_fin')) {
                $query->whereDate('fecha_movimiento', '<=', $request->fecha_fin);
            }

            $movimientos = $query->orderBy('fecha_movimiento', 'desc')->get();

            // Obtener nombres para mostrar en el PDF
            $productoNombre = null;
            if ($request->filled('id_producto')) {
                $producto = Producto::find($request->id_producto);
                $productoNombre = $producto ? $producto->nombre : null;
            }

            $usuarioNombre = null;
            if ($request->filled('id_usuario')) {
                $usuario = Usuario::find($request->id_usuario);
                $usuarioNombre = $usuario ? $usuario->nombre : null;
            }

            // Datos para la vista
            $data = [
                'movimientos' => $movimientos,
                'fechaInicio' => $request->input('fecha_inicio'),
                'fechaFin' => $request->input('fecha_fin'),
                'tipoMovimiento' => $request->input('tipo_movimiento'),
                'idProducto' => $request->input('id_producto'),
                'idUsuario' => $request->input('id_usuario'),
                'productoNombre' => $productoNombre,
                'usuarioNombre' => $usuarioNombre,
            ];

            // Generar PDF
            $pdf = Pdf::loadView('inventario.pdf.movimientos', $data);

            // Configurar PDF
            $pdf->setPaper('A4', 'landscape');

            // Descargar PDF
            $filename = 'Movimientos_Inventario_' . date('Y-m-d_His') . '.pdf';
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error al exportar movimientos a PDF: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el archivo PDF: ' . $e->getMessage());
        }
    }

    /**
     * Exportar movimientos a Excel.
     */
    public function exportarMovimientosExcel(Request $request)
    {
        try {
            // Construir consulta con los filtros
            $query = Inventario::with(['producto.tipoProducto', 'usuario.personal']);

            // Aplicar filtros
            if ($request->filled('tipo_movimiento')) {
                $query->where('tipo_movimiento', $request->tipo_movimiento);
            }

            if ($request->filled('id_producto')) {
                $query->where('id_producto', $request->id_producto);
            }

            if ($request->filled('id_usuario')) {
                $query->where('id_usuario', $request->id_usuario);
            }

            if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha_movimiento', [
                    $request->fecha_inicio . ' 00:00:00',
                    $request->fecha_fin . ' 23:59:59',
                ]);
            } elseif ($request->filled('fecha_inicio')) {
                $query->whereDate('fecha_movimiento', '>=', $request->fecha_inicio);
            } elseif ($request->filled('fecha_fin')) {
                $query->whereDate('fecha_movimiento', '<=', $request->fecha_fin);
            }

            $movimientos = $query->orderBy('fecha_movimiento', 'desc')->get();

            // Generar nombre de archivo
            $filename = 'Movimientos_Inventario_' . date('Y-m-d_His') . '.xlsx';

            // Descargar Excel usando la clase Export
            return Excel::download(new MovimientosExport($movimientos), $filename);

        } catch (\Exception $e) {
            \Log::error('Error al exportar movimientos a Excel: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el archivo Excel: ' . $e->getMessage());
        }
    }

    /**
     * Determinar estado del stock según cantidad.
     *
     * @param  int  $stock
     * @return string (bajo|medio|alto)
     */
    protected function determinarEstadoStock(int $stock): string
    {
        if ($stock < 10) {
            return 'bajo';
        }

        if ($stock < 50) {
            return 'medio';
        }

        return 'alto';
    }

    /**
     * Mostrar alertas de stock bajo.
     *
     * Sincroniza automáticamente las alertas con el stock real del inventario.
     */
    public function alertas(Request $request): View
    {
        // ===== SINCRONIZACIÓN AUTOMÁTICA CON INVENTARIO REAL =====
        // Actualizar o crear alertas basadas en el stock actual de cada producto
        $productosActivos = Producto::where('estado', 'activo')->get();

        foreach ($productosActivos as $producto) {
            $stockActual = Inventario::stockDisponible($producto->id);
            $stockMinimo = 10; // Umbral por defecto

            // Si el stock está bajo el mínimo, generar o actualizar alerta
            if ($stockActual < $stockMinimo) {
                \App\Models\AlertaStock::generarSiNecesario($producto, $stockMinimo);
            } else {
                // Si el stock es suficiente, marcar alertas activas como atendidas
                \App\Models\AlertaStock::where('id_producto', $producto->id)
                    ->where('estado_alerta', 'activa')
                    ->update([
                        'estado_alerta' => 'atendida',
                        'fecha_atencion' => now(),
                        'observaciones' => 'Stock reabastecido automáticamente'
                    ]);
            }
        }

        // ===== CONSULTA DE ALERTAS CON FILTROS =====
        $query = \App\Models\AlertaStock::with('producto.tipoProducto');

        // Filtrar por estado de alerta
        if ($request->filled('estado_alerta')) {
            $query->where('estado_alerta', $request->estado_alerta);
        } else {
            // Por defecto, mostrar solo alertas activas
            $query->where('estado_alerta', 'activa');
        }

        // Filtrar por nivel de urgencia
        if ($request->filled('nivel_urgencia')) {
            $query->where('nivel_urgencia', $request->nivel_urgencia);
        }

        // Filtrar por producto
        if ($request->filled('id_producto')) {
            $query->where('id_producto', $request->id_producto);
        }

        // Ordenar por urgencia y fecha
        $alertas = $query->ordenadoPorUrgencia()
            ->orderBy('fecha_alerta', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Obtener productos activos para filtro
        $productos = Producto::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        // Estadísticas (solo alertas activas)
        $totalAlertas = \App\Models\AlertaStock::activas()->count();
        $alertasCriticas = \App\Models\AlertaStock::activas()->porNivelUrgencia('critica')->count();
        $alertasAltas = \App\Models\AlertaStock::activas()->porNivelUrgencia('alta')->count();

        return view('inventario.alertas', compact(
            'alertas',
            'productos',
            'totalAlertas',
            'alertasCriticas',
            'alertasAltas'
        ));
    }

    /**
     * Marcar alerta como atendida.
     */
    public function atenderAlerta(\App\Models\AlertaStock $alerta): RedirectResponse
    {
        $alerta->marcarComoAtendida();

        return redirect()->route('inventario.alertas')
            ->with('success', 'Alerta marcada como atendida exitosamente');
    }

    /**
     * Marcar alerta como ignorada.
     */
    public function ignorarAlerta(\App\Models\AlertaStock $alerta): RedirectResponse
    {
        $alerta->marcarComoIgnorada();

        return redirect()->route('inventario.alertas')
            ->with('success', 'Alerta marcada como ignorada');
    }

    /**
     * Mostrar formulario para crear producto.
     */
    public function createProducto(): View
    {
        return view('inventario.productos.create');
    }

    /**
     * Guardar nuevo producto.
     */
    public function storeProducto(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        $validated['estado'] = 'activo';
        $validated['tipo'] = 'General'; // Valor por defecto

        Producto::create($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar formulario para editar producto.
     */
    public function editProducto(Producto $producto): View
    {
        return view('inventario.productos.edit', compact('producto'));
    }

    /**
     * Actualizar producto.
     */
    public function updateProducto(Request $request, Producto $producto): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        $validated['tipo'] = 'General'; // Valor por defecto

        $producto->update($validated);

        return redirect()->route('inventario.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar producto.
     */
    public function destroyProducto(Producto $producto): RedirectResponse
    {
        $producto->update(['estado' => 'inactivo']);

        return redirect()->route('inventario.index')
            ->with('success', 'Producto desactivado exitosamente');
    }
}
