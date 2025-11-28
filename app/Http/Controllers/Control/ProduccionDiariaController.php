<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\ProduccionDiaria;
use App\Models\Producto;
use App\Models\Personal;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduccionDiariaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener la semana actual o la semana solicitada
        $semana = (int) $request->get('semana', 0); // 0 = semana actual, -1 = anterior, 1 = siguiente

        $inicioSemana = now()->addWeeks($semana)->startOfWeek(); // Lunes
        $finSemana = now()->addWeeks($semana)->endOfWeek(); // Domingo

        $producciones = ProduccionDiaria::with(['productos', 'materiales'])
            ->whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('control.produccion.index', compact('producciones', 'inicioSemana', 'finSemana', 'semana'));
    }

    public function create()
    {
        // Solo mostrar productos activos
        $productos = Producto::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        // Excluir choferes, distribuidores y supervisores
        $personal = Personal::where('estado', 'activo')
            ->whereNotIn('cargo', ['Chofer', 'Distribuidor', 'Supervisor'])
            ->orderBy('nombre_completo')
            ->get();

        return view('control.produccion.create', compact('productos', 'personal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'responsable' => 'required|string',
            'gasto_material' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.producto' => 'required|string',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'materiales' => 'nullable|array',
            'materiales.*.material' => 'nullable|string',
            'materiales.*.cantidad' => 'nullable|numeric|min:0',
        ]);

        // Usar transacción para asegurar consistencia de datos
        DB::beginTransaction();

        try {
            // Crear el registro principal de producción
            $produccion = ProduccionDiaria::create([
                'fecha' => $validated['fecha'],
                'responsable' => $validated['responsable'],
                'turno' => null,
                'preparacion' => null,
                'rollos_material' => 0,
                'gasto_material' => $validated['gasto_material'] ?? 0,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Guardar los productos y registrar en inventario
            foreach ($validated['productos'] as $productoData) {
                // Buscar el producto por nombre
                $producto = Producto::where('nombre', $productoData['producto'])->first();

                if ($producto) {
                    // Guardar en la tabla de producción
                    $produccion->productos()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => $productoData['cantidad'],
                    ]);

                    // Registrar entrada en el inventario general
                    Inventario::create([
                        'id_producto' => $producto->id,
                        'tipo_movimiento' => 'entrada',
                        'cantidad' => $productoData['cantidad'],
                        'origen' => 'Producción Diaria',
                        'referencia' => 'Producción #' . $produccion->id,
                        'id_usuario' => Auth::id(),
                        'fecha_movimiento' => $validated['fecha'],
                        'observacion' => 'Entrada automática desde Control de Producción Diaria - Responsable: ' . $validated['responsable'],
                    ]);
                }
            }

            // Guardar los materiales utilizados si existen
            if (isset($validated['materiales'])) {
                foreach ($validated['materiales'] as $materialData) {
                    if (!empty($materialData['material'])) {
                        $produccion->materiales()->create([
                            'nombre_material' => $materialData['material'],
                            'cantidad' => $materialData['cantidad'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('control.produccion.index')
                ->with('success', 'Registro de producción creado exitosamente y productos agregados al inventario.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar la producción: ' . $e->getMessage());
        }
    }

    public function show(ProduccionDiaria $produccion)
    {
        $produccion->load(['productos', 'materiales']);
        return view('control.produccion.show', compact('produccion'));
    }

    public function edit(ProduccionDiaria $produccion)
    {
        $productos = Producto::orderBy('nombre')->get();

        // Excluir choferes, distribuidores y supervisores
        $personal = Personal::where('estado', 'activo')
            ->whereNotIn('cargo', ['Chofer', 'Distribuidor', 'Supervisor'])
            ->orderBy('nombre_completo')
            ->get();

        $produccion->load(['productos', 'materiales']);
        return view('control.produccion.edit', compact('produccion', 'productos', 'personal'));
    }

    public function update(Request $request, ProduccionDiaria $produccion)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'responsable' => 'required|string',
            'gasto_material' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.producto' => 'required|string',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'materiales' => 'nullable|array',
            'materiales.*.material' => 'nullable|string',
            'materiales.*.cantidad' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar el registro principal de producción
            $produccion->update([
                'fecha' => $validated['fecha'],
                'responsable' => $validated['responsable'],
                'gasto_material' => $validated['gasto_material'] ?? 0,
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // Eliminar entradas de inventario antiguas relacionadas con esta producción
            Inventario::where('referencia', 'Producción #' . $produccion->id)->delete();

            // Eliminar productos antiguos
            $produccion->productos()->delete();

            // Crear los nuevos productos y registrar en inventario
            foreach ($validated['productos'] as $productoData) {
                $producto = Producto::where('nombre', $productoData['producto'])->first();

                if ($producto) {
                    // Guardar en la tabla de producción
                    $produccion->productos()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => $productoData['cantidad'],
                    ]);

                    // Registrar entrada en el inventario general
                    Inventario::create([
                        'id_producto' => $producto->id,
                        'tipo_movimiento' => 'entrada',
                        'cantidad' => $productoData['cantidad'],
                        'origen' => 'Producción Diaria',
                        'referencia' => 'Producción #' . $produccion->id,
                        'id_usuario' => Auth::id(),
                        'fecha_movimiento' => $validated['fecha'],
                        'observacion' => 'Entrada automática desde Control de Producción Diaria (Editado) - Responsable: ' . $validated['responsable'],
                    ]);
                }
            }

            // Eliminar materiales antiguos
            $produccion->materiales()->delete();

            // Crear los nuevos materiales si existen
            if (isset($validated['materiales'])) {
                foreach ($validated['materiales'] as $materialData) {
                    if (!empty($materialData['material'])) {
                        $produccion->materiales()->create([
                            'nombre_material' => $materialData['material'],
                            'cantidad' => $materialData['cantidad'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('control.produccion.index')
                ->with('success', 'Registro de producción actualizado exitosamente y cambios reflejados en inventario.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la producción: ' . $e->getMessage());
        }
    }

    public function destroy(ProduccionDiaria $produccion)
    {
        DB::beginTransaction();

        try {
            // Revertir entradas de inventario
            Inventario::where('referencia', 'Producción #' . $produccion->id)->delete();

            // Eliminar productos relacionados
            $produccion->productos()->delete();

            // Eliminar materiales relacionados
            $produccion->materiales()->delete();

            // Eliminar el registro de producción
            $produccion->delete();

            DB::commit();

            return redirect()->route('control.produccion.index')
                ->with('success', 'Registro de producción eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al eliminar la producción: ' . $e->getMessage());
        }
    }
}
