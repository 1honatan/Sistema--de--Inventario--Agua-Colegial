<?php

declare(strict_types=1);

namespace App\Http\Controllers\Produccion;

use App\Http\Controllers\Controller;
use App\Models\Producto;
// use App\Models\TipoProducto; // ELIMINADO
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador del módulo de almacén.
 *
 * Gestiona productos en el almacén con funciones de:
 * - Ver todos los productos con stock
 * - Agregar nuevo producto
 * - Editar producto existente
 * - Eliminar producto
 * - Ajustar stock (agregar/quitar)
 */
class AlmacenController extends Controller
{
    /**
     * Mostrar todos los productos del almacén.
     */
    public function index(Request $request): View
    {
        $query = Producto::query();

        // Filtrar por tipo si se proporciona
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $productos = $query->orderBy('nombre', 'asc')->get();

        // Calcular stock para cada producto
        foreach ($productos as $producto) {
            $producto->stock_disponible = Inventario::stockDisponible($producto->id);
        }

        $tipos = Producto::select('tipo')->distinct()->pluck('tipo');

        return view('produccion.almacen.index', compact('productos', 'tipos'));
    }

    /**
     * Mostrar formulario para agregar nuevo producto.
     */
    public function create(): View
    {
        return view('produccion.almacen.create');
    }

    /**
     * Guardar nuevo producto.
     */
    public function store(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'string', 'max:50'],
            'id_tipo_producto' => ['nullable', 'exists:tipo_producto,id'],
            'unidad_medida' => ['required', 'string', 'max:20'],
            'imagen' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio',
            'tipo.required' => 'El tipo de producto es obligatorio',
            'unidad_medida.required' => 'La unidad de medida es obligatoria',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.max' => 'La imagen no debe pesar más de 2MB',
        ]);

        // Manejar subida de imagen
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $validado['imagen'] = $imagenPath;
        }

        $validado['estado'] = 'activo';

        Producto::create($validado);

        return redirect()->route('almacen.index')
            ->with('success', 'Producto agregado exitosamente al almacén');
    }

    /**
     * Mostrar formulario para editar producto.
     */
    public function edit(Producto $producto): View
    {
        $producto->stock_disponible = Inventario::stockDisponible($producto->id);

        return view('produccion.almacen.edit', compact('producto'));
    }

    /**
     * Actualizar producto.
     */
    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $validado = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'tipo' => ['required', 'string', 'max:50'],
            'id_tipo_producto' => ['nullable', 'exists:tipo_producto,id'],
            'unidad_medida' => ['required', 'string', 'max:20'],
            'estado' => ['required', 'in:activo,inactivo'],
            'imagen' => ['nullable', 'image', 'max:2048'],
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio',
            'tipo.required' => 'El tipo de producto es obligatorio',
            'unidad_medida.required' => 'La unidad de medida es obligatoria',
            'estado.required' => 'El estado es obligatorio',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.max' => 'La imagen no debe pesar más de 2MB',
        ]);

        // Manejar subida de nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $validado['imagen'] = $imagenPath;
        }

        $producto->update($validado);

        return redirect()->route('almacen.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Eliminar producto (cambiar estado a inactivo).
     */
    public function destroy(Producto $producto): RedirectResponse
    {
        // Validar que no tenga producciones activas
        if ($producto->producciones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un producto que tiene registros de producción');
        }

        // Cambiar estado a inactivo en lugar de eliminar
        $producto->update(['estado' => 'inactivo']);

        return redirect()->route('almacen.index')
            ->with('success', 'Producto marcado como inactivo');
    }

    /**
     * Mostrar formulario para ajustar stock (agregar/quitar).
     */
    public function ajustarStock(Producto $producto): View
    {
        $producto->stock_disponible = Inventario::stockDisponible($producto->id);

        return view('produccion.almacen.ajustar_stock', compact('producto'));
    }

    /**
     * Procesar ajuste de stock.
     */
    public function procesarAjuste(Request $request, Producto $producto): RedirectResponse
    {
        $validado = $request->validate([
            'tipo_movimiento' => ['required', 'in:entrada,salida'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'motivo' => ['required', 'string', 'max:200'],
        ], [
            'tipo_movimiento.required' => 'Debe seleccionar el tipo de movimiento',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'motivo.required' => 'El motivo es obligatorio',
        ]);

        try {
            if ($validado['tipo_movimiento'] === 'entrada') {
                Inventario::registrarEntrada(
                    $producto->id,
                    $validado['cantidad'],
                    'Ajuste manual: ' . $validado['motivo']
                );
            } else {
                // Validar stock disponible
                $stockDisponible = Inventario::stockDisponible($producto->id);
                if ($validado['cantidad'] > $stockDisponible) {
                    return back()->with('error', "Stock insuficiente. Disponible: {$stockDisponible} unidades");
                }

                Inventario::registrarSalida(
                    $producto->id,
                    $validado['cantidad'],
                    'Ajuste manual: ' . $validado['motivo']
                );
            }

            return redirect()->route('almacen.index')
                ->with('success', 'Stock ajustado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al ajustar stock: ' . $e->getMessage());
            return back()->with('error', 'Error al ajustar stock: ' . $e->getMessage());
        }
    }
}
