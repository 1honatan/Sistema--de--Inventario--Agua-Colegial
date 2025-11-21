<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\SalidaProducto;
use App\Models\Personal;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalidasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salidas = SalidaProducto::orderBy('fecha', 'desc')->paginate(15);
        return view('control.salidas.index', compact('salidas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener empleados activos como distribuidores (con cargo para mostrar)
        $distribuidores = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        // Obtener responsables para venta directa (sin choferes ni distribuidores)
        $responsablesVenta = Personal::where('estado', 'activo')
            ->whereNotIn('cargo', ['Chofer', 'Distribuidor'])
            ->orderBy('nombre_completo')
            ->get();

        // Obtener vehículos activos con su responsable
        $vehiculos = Vehiculo::where('estado', 'activo')
            ->orderBy('placa')
            ->get();

        // Obtener personal para el mapeo responsable-vehículo
        $personal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        // Obtener productos activos con su stock disponible dinámicamente
        $productos = Producto::where('estado', 'activo')
            ->orderBy('nombre')
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'unidad_medida' => $producto->unidad_medida,
                    'stock' => Inventario::stockDisponible($producto->id),
                    'icono' => self::obtenerIconoProducto($producto->nombre),
                ];
            });

        return view('control.salidas.create', compact('distribuidores', 'responsablesVenta', 'vehiculos', 'personal', 'productos'));
    }

    /**
     * Obtener icono apropiado según el nombre del producto
     */
    private static function obtenerIconoProducto($nombre)
    {
        $nombre = strtolower($nombre);

        if (str_contains($nombre, 'botell')) return 'fa-water';
        if (str_contains($nombre, 'bolo')) return 'fa-shopping-bag';
        if (str_contains($nombre, 'gelatina')) return 'fa-cube';
        if (str_contains($nombre, 'agua') && str_contains($nombre, 'sabor')) return 'fa-tint';
        if (str_contains($nombre, 'agua') && str_contains($nombre, 'natural')) return 'fa-water';
        if (str_contains($nombre, 'agua') && str_contains($nombre, 'lim')) return 'fa-lemon';
        if (str_contains($nombre, 'hielo')) return 'fa-snowflake';
        if (str_contains($nombre, 'dispenser')) return 'fa-faucet';

        return 'fa-box'; // Icono por defecto
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar campos base según tipo de salida
        $rules = [
            'tipo_salida' => 'required|string|max:50',
            'fecha' => 'nullable|date',
        ];

        // Agregar validaciones específicas según el tipo
        if ($request->tipo_salida === 'Despacho Interno') {
            $rules['nombre_distribuidor'] = 'required|string|max:255';
            $rules['vehiculo_placa'] = 'nullable|string|max:255';
            $rules['hora_llegada'] = 'nullable|date_format:H:i';
            $rules['fecha'] = 'required|date';
        } elseif ($request->tipo_salida === 'Pedido Cliente') {
            $rules['nombre_cliente'] = 'required|string|max:255';
            $rules['direccion_entrega'] = 'required|string|max:500';
            $rules['telefono_cliente'] = 'nullable|string|max:20';
            $rules['fecha'] = 'required|date';
        } elseif ($request->tipo_salida === 'Venta Directa') {
            $rules['nombre_cliente'] = 'required|string|max:255';
            $rules['responsable_venta'] = 'required|string|max:255';
            $rules['fecha'] = 'nullable|date';
        }

        // Validaciones comunes para productos
        $rules = array_merge($rules, [
            'lunes' => 'nullable|integer|min:0',
            'martes' => 'nullable|integer|min:0',
            'miercoles' => 'nullable|integer|min:0',
            'jueves' => 'nullable|integer|min:0',
            'viernes' => 'nullable|integer|min:0',
            'sabado' => 'nullable|integer|min:0',
            'domingo' => 'nullable|integer|min:0',
            'productos' => 'nullable|array',
            'productos.*' => 'nullable|integer|min:0',
            'retornos' => 'nullable|array',
            'retornos.*' => 'nullable|integer|min:0',
            'choreados_retorno' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $validated = $request->validate($rules);

        // Normalizar nombre_distribuidor según el tipo de salida
        if ($request->tipo_salida === 'Pedido Cliente' || $request->tipo_salida === 'Venta Directa') {
            $validated['nombre_distribuidor'] = $validated['nombre_cliente'] ?? '';
        }

        // Obtener productos enviados
        $productosEnviados = $validated['productos'] ?? [];

        // Validar stock disponible ANTES de crear la salida
        $erroresStock = [];
        foreach ($productosEnviados as $productoId => $cantidad) {
            if ($cantidad > 0) {
                $producto = Producto::find($productoId);

                if ($producto) {
                    $stockDisponible = Inventario::stockDisponible($producto->id);

                    if ($stockDisponible < $cantidad) {
                        $erroresStock[] = "No hay suficiente stock de {$producto->nombre}. Disponible: {$stockDisponible}, Solicitado: {$cantidad}";
                    }
                }
            }
        }

        // Si hay errores de stock, retornar sin guardar
        if (!empty($erroresStock)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error de stock: ' . implode('. ', $erroresStock));
        }

        DB::beginTransaction();

        try {
            // Preparar datos para crear la salida (sin el array de productos)
            $datosBasicos = array_diff_key($validated, ['productos' => '', 'retornos' => '']);

            // Mapear retornos a campos específicos
            $retornosRecibidos = $validated['retornos'] ?? [];
            $productosMap = [
                12 => 'retorno_botellones',     // botellon de 20 litros
                9 => 'retorno_botellones',      // Botellón Grande
                10 => 'retorno_botellones',     // Botellón Mediano
                4 => 'retorno_bolo_grande',     // Bolo Grande
                5 => 'retorno_bolo_pequeno',    // Bolo Pequeño
                6 => 'retorno_gelatina',        // Gelatina
                3 => 'retorno_agua_saborizada', // Agua de Sabor
                2 => 'retorno_agua_limon',      // Agua de Limón
                1 => 'retorno_agua_natural',    // Agua Natural
                7 => 'retorno_hielo',           // Bolsa de Hielo Grande
                8 => 'retorno_hielo',           // Bolsa de Hielo Pequeño
                11 => 'retorno_dispenser',      // dispenser
            ];

            // Inicializar campos de retorno
            $datosBasicos['retorno_botellones'] = 0;
            $datosBasicos['retorno_bolo_grande'] = 0;
            $datosBasicos['retorno_bolo_pequeno'] = 0;
            $datosBasicos['retorno_gelatina'] = 0;
            $datosBasicos['retorno_agua_saborizada'] = 0;
            $datosBasicos['retorno_agua_limon'] = 0;
            $datosBasicos['retorno_agua_natural'] = 0;
            $datosBasicos['retorno_hielo'] = 0;
            $datosBasicos['retorno_dispenser'] = 0;

            // Sumar retornos por tipo de producto
            foreach ($retornosRecibidos as $productoId => $cantidad) {
                if ($cantidad > 0 && isset($productosMap[$productoId])) {
                    $campo = $productosMap[$productoId];
                    $datosBasicos[$campo] += $cantidad;
                }
            }

            // Calcular total de retornos
            $datosBasicos['retornos'] = array_sum([
                $datosBasicos['retorno_botellones'],
                $datosBasicos['retorno_bolo_grande'],
                $datosBasicos['retorno_bolo_pequeno'],
                $datosBasicos['retorno_gelatina'],
                $datosBasicos['retorno_agua_saborizada'],
                $datosBasicos['retorno_agua_limon'],
                $datosBasicos['retorno_agua_natural'],
                $datosBasicos['retorno_hielo'],
                $datosBasicos['retorno_dispenser'],
            ]);

            // Crear el registro de salida
            $salida = SalidaProducto::create($datosBasicos);

            // Registrar cada producto en el inventario como SALIDA
            foreach ($productosEnviados as $productoId => $cantidad) {
                if ($cantidad > 0) {
                    $producto = Producto::find($productoId);

                    if ($producto) {
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'salida',
                            'cantidad' => $cantidad,
                            'origen' => 'Almacén',
                            'destino' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                            'referencia' => 'Salida #' . $salida->id,
                            'id_usuario' => Auth::id(),
                            'fecha_movimiento' => $validated['fecha'],
                            'observacion' => 'Salida automática desde Control de Salidas - Distribuidor: ' . $validated['nombre_distribuidor'],
                        ]);
                    }
                }
            }

            // Registrar RETORNOS en el inventario como ENTRADA
            foreach ($retornosRecibidos as $productoId => $cantidad) {
                if ($cantidad > 0) {
                    $producto = Producto::find($productoId);

                    if ($producto) {
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $cantidad,
                            'origen' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                            'destino' => 'Almacén',
                            'referencia' => 'Retorno - Salida #' . $salida->id,
                            'id_usuario' => Auth::id(),
                            'fecha_movimiento' => $validated['fecha'],
                            'observacion' => 'Retorno automático desde Control de Salidas - Distribuidor: ' . $validated['nombre_distribuidor'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('control.salidas.index')
                ->with('success', 'Registro de salida creado exitosamente. Inventario actualizado con ' . count(array_filter($productosEnviados)) . ' salidas y ' . count(array_filter($retornosRecibidos)) . ' retornos.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalidaProducto $salida)
    {
        return view('control.salidas.show', compact('salida'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalidaProducto $salida)
    {
        // Obtener empleados activos como distribuidores (con cargo para mostrar)
        $distribuidores = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        // Obtener responsables para venta directa (sin choferes ni distribuidores)
        $responsablesVenta = Personal::where('estado', 'activo')
            ->whereNotIn('cargo', ['Chofer', 'Distribuidor'])
            ->orderBy('nombre_completo')
            ->get();

        // Obtener vehículos activos
        $vehiculos = Vehiculo::where('estado', 'activo')
            ->orderBy('placa')
            ->get();

        return view('control.salidas.edit', compact('salida', 'distribuidores', 'responsablesVenta', 'vehiculos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalidaProducto $salida)
    {
        // Validar campos base según tipo de salida
        $rules = [
            'tipo_salida' => 'required|string|max:50',
            'fecha' => 'nullable|date',
        ];

        // Agregar validaciones específicas según el tipo
        if ($request->tipo_salida === 'Despacho Interno') {
            $rules['nombre_distribuidor'] = 'required|string|max:255';
            $rules['vehiculo_placa'] = 'nullable|string|max:255';
            $rules['hora_llegada'] = 'nullable|date_format:H:i';
            $rules['fecha'] = 'required|date';
        } elseif ($request->tipo_salida === 'Pedido Cliente') {
            $rules['nombre_cliente'] = 'required|string|max:255';
            $rules['direccion_entrega'] = 'required|string|max:500';
            $rules['telefono_cliente'] = 'nullable|string|max:20';
            $rules['fecha'] = 'required|date';
        } elseif ($request->tipo_salida === 'Venta Directa') {
            $rules['nombre_cliente'] = 'required|string|max:255';
            $rules['responsable_venta'] = 'required|string|max:255';
            $rules['fecha'] = 'nullable|date';
        }

        // Validaciones comunes para productos
        $rules = array_merge($rules, [
            'lunes' => 'nullable|integer|min:0',
            'martes' => 'nullable|integer|min:0',
            'miercoles' => 'nullable|integer|min:0',
            'jueves' => 'nullable|integer|min:0',
            'viernes' => 'nullable|integer|min:0',
            'sabado' => 'nullable|integer|min:0',
            'domingo' => 'nullable|integer|min:0',
            'productos' => 'nullable|array',
            'productos.*' => 'nullable|integer|min:0',
            'retornos' => 'nullable|array',
            'retornos.*' => 'nullable|integer|min:0',
            'choreados_retorno' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $validated = $request->validate($rules);

        // Normalizar nombre_distribuidor según el tipo de salida
        if ($request->tipo_salida === 'Pedido Cliente' || $request->tipo_salida === 'Venta Directa') {
            $validated['nombre_distribuidor'] = $validated['nombre_cliente'] ?? '';
        }

        DB::beginTransaction();

        try {
            // Eliminar entradas de inventario antiguas relacionadas con esta salida
            Inventario::where('referencia', 'Salida #' . $salida->id)
                ->orWhere('referencia', 'Retorno - Salida #' . $salida->id)
                ->delete();

            // Preparar datos para actualizar (sin el array de productos)
            $datosBasicos = array_diff_key($validated, ['productos' => '', 'retornos' => '']);

            // Actualizar el registro de salida
            $salida->update($datosBasicos);

            // Obtener productos enviados
            $productosEnviados = $validated['productos'] ?? [];

            // Registrar cada producto en el inventario como SALIDA
            foreach ($productosEnviados as $productoId => $cantidad) {
                if ($cantidad > 0) {
                    $producto = Producto::find($productoId);

                    if ($producto) {
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'salida',
                            'cantidad' => $cantidad,
                            'origen' => 'Almacén',
                            'destino' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                            'referencia' => 'Salida #' . $salida->id,
                            'id_usuario' => Auth::id(),
                            'fecha_movimiento' => $validated['fecha'],
                            'observacion' => 'Salida automática desde Control de Salidas (Editado) - Distribuidor: ' . $validated['nombre_distribuidor'],
                        ]);
                    }
                }
            }

            // Registrar RETORNOS en el inventario como ENTRADA
            $retornosRecibidos = $validated['retornos'] ?? [];
            foreach ($retornosRecibidos as $productoId => $cantidad) {
                if ($cantidad > 0) {
                    $producto = Producto::find($productoId);

                    if ($producto) {
                        Inventario::create([
                            'id_producto' => $producto->id,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $cantidad,
                            'origen' => 'Distribuidor: ' . $validated['nombre_distribuidor'],
                            'destino' => 'Almacén',
                            'referencia' => 'Retorno - Salida #' . $salida->id,
                            'id_usuario' => Auth::id(),
                            'fecha_movimiento' => $validated['fecha'],
                            'observacion' => 'Retorno automático desde Control de Salidas (Editado) - Distribuidor: ' . $validated['nombre_distribuidor'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('control.salidas.index')
                ->with('success', 'Registro de salida actualizado exitosamente. Inventario actualizado con ' . count(array_filter($productosEnviados)) . ' salidas y ' . count(array_filter($retornosRecibidos)) . ' retornos.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalidaProducto $salida)
    {
        DB::beginTransaction();

        try {
            // Eliminar entradas de inventario relacionadas con esta salida (tanto salidas como retornos)
            Inventario::where('referencia', 'Salida #' . $salida->id)
                ->orWhere('referencia', 'Retorno - Salida #' . $salida->id)
                ->delete();

            // Eliminar el registro de salida
            $salida->delete();

            DB::commit();

            return redirect()->route('control.salidas.index')
                ->with('success', 'Registro de salida eliminado exitosamente e inventario actualizado (salidas y retornos revertidos).');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al eliminar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF del registro
     */
    public function generarPDF(SalidaProducto $salida)
    {
        // Por ahora retornamos a la vista show
        // Puedes implementar generación de PDF más adelante
        return redirect()->route('control.salidas.show', $salida);
    }
}
