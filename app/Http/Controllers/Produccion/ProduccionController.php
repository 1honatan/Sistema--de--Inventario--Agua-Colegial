<?php

declare(strict_types=1);

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Personal;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

/**
 * Controlador del módulo de producción.
 *
 * Roles permitidos: admin, produccion
 */
class ProduccionController extends Controller
{
    /**
     * Listar registros de producción.
     */
    public function index(Request $request): View
    {
        $query = Produccion::with(['producto', 'personal']);

        // Filtrar por producto si se proporciona
        if ($request->filled('producto') && $request->producto !== '') {
            $query->where('id_producto', $request->producto);
        }

        // Filtrar por rango de fechas
        if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
            // Si hay ambas fechas, buscar en el rango
            $query->whereBetween('fecha_produccion', [
                $request->fecha_desde . ' 00:00:00',
                $request->fecha_hasta . ' 23:59:59',
            ]);
        } elseif ($request->filled('fecha_desde')) {
            // Si solo hay fecha_desde, filtrar por esa fecha exacta (todo el día)
            $query->whereDate('fecha_produccion', '=', $request->fecha_desde);
        } elseif ($request->filled('fecha_hasta')) {
            // Si solo hay fecha_hasta, filtrar hasta esa fecha
            $query->whereDate('fecha_produccion', '<=', $request->fecha_hasta);
        }

        $producciones = $query->orderBy('fecha_produccion', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Obtener productos con su stock actual para el filtro
        $productos = DB::table('v_stock_actual')
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'tipo', 'unidad_medida', 'stock_actual')
            ->get();

        // Calcular estadísticas
        $produccionHoy = Produccion::whereDate('fecha_produccion', today())->sum('cantidad');
        $produccionMes = Produccion::whereMonth('fecha_produccion', now()->month)
            ->whereYear('fecha_produccion', now()->year)
            ->sum('cantidad');
        $lotesHoy = Produccion::whereDate('fecha_produccion', today())->count();

        return view('produccion.index', compact(
            'producciones',
            'productos',
            'produccionHoy',
            'produccionMes',
            'lotesHoy'
        ));
    }

    /**
     * Mostrar formulario de registro de producción.
     */
    public function create(): View
    {
        // Obtener productos con su stock actual
        $productos = DB::table('v_stock_actual')
            ->where('estado', 'activo')
            ->select('id', 'nombre', 'tipo', 'unidad_medida', 'stock_actual')
            ->get();

        $personal = Personal::where('estado', 'activo')->get();

        return view('produccion.create', compact('productos', 'personal'));
    }

    /**
     * Almacenar nuevo registro de producción.
     */
    public function store(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'id_producto' => ['required', 'exists:productos,id'],
            'id_personal' => ['required', 'exists:personal,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha_produccion' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'id_producto.required' => 'Debe seleccionar un producto',
            'id_producto.exists' => 'El producto seleccionado no es válido',
            'id_personal.required' => 'Debe seleccionar un personal responsable',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'fecha_produccion.required' => 'La fecha de producción es obligatoria',
            'fecha_produccion.before_or_equal' => 'La fecha no puede ser futura',
        ]);

        try {
            DB::beginTransaction();

            // Generar código de lote único (CRITICAL: con transacción)
            $lote = Produccion::generarCodigoLote();

            // Crear registro de producción
            $produccion = Produccion::create([
                'id_producto' => $validado['id_producto'],
                'id_personal' => $validado['id_personal'],
                'lote' => $lote,
                'cantidad' => $validado['cantidad'],
                'fecha_produccion' => $validado['fecha_produccion'],
            ]);

            // Registrar entrada en inventario automáticamente
            Inventario::registrarEntrada(
                $validado['id_producto'],
                $validado['cantidad'],
                "Producción lote {$lote}"
            );

            DB::commit();

            return redirect()->route('produccion.index')
                ->with('success', "Producción registrada exitosamente. Lote: {$lote}");
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Error al registrar producción: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Error al registrar producción: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de una producción.
     */
    public function show(Produccion $produccion): View
    {
        $produccion->load(['producto', 'personal']);

        return view('produccion.show', compact('produccion'));
    }

    /**
     * Generar reporte de producción por rango de fechas.
     */
    public function reporte(Request $request): View
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'id_producto' => ['nullable', 'exists:productos,id'],
        ]);

        $query = Produccion::with(['producto', 'personal'])
            ->whereBetween('fecha_produccion', [$validado['fecha_inicio'], $validado['fecha_fin']]);

        if (!empty($validado['id_producto'])) {
            $query->where('id_producto', $validado['id_producto']);
        }

        $producciones = $query->orderBy('fecha_produccion', 'desc')->get();

        // CRITICAL: Especificar tabla en suma para evitar ambigüedad
        $totalCantidad = $query->sum('produccion.cantidad');

        $productos = Producto::where('estado', 'activo')->get();

        return view('produccion.reporte', compact(
            'producciones',
            'totalCantidad',
            'validado',
            'productos'
        ));
    }
}
