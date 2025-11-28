<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
// use App\Models\TipoProducto; // ELIMINADO
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controlador de gestión de productos (solo admin).
 *
 * CRUD completo de productos del catálogo.
 */
class ProductoController extends Controller
{
    /**
     * Listar todos los productos con filtros.
     */
    public function index(Request $request): View
    {
        $query = Producto::query();

        // Filtrar por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Buscar por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos = $query->orderBy('nombre')
            ->paginate(12);

        // Calcular stock para cada producto
        foreach ($productos as $producto) {
            $producto->stock_actual = Inventario::stockDisponible($producto->id);
        }

        return view('admin.productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(): View
    {
        return view('admin.productos.create');
    }

    /**
     * Almacenar nuevo producto.
     */
    public function store(StoreProductoRequest $request): RedirectResponse
    {
        $validado = $request->validated();

        Producto::create($validado);

        return redirect()->route('inventario.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Producto $producto): View
    {
        return view('admin.productos.edit', compact('producto'));
    }

    /**
     * Actualizar producto existente.
     */
    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        $validado = $request->validated();

        $producto->update($validado);

        return redirect()->route('inventario.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Desactivar producto.
     */
    public function destroy(Producto $producto): RedirectResponse
    {
        // Verificar si el producto tiene movimientos
        $tieneMovimientos = Inventario::where('id_producto', $producto->id)->exists();

        if ($tieneMovimientos) {
            // Solo desactivar, no eliminar
            $producto->update(['estado' => 'inactivo']);
            return redirect()->route('inventario.index')
                ->with('success', 'Producto desactivado exitosamente');
        }

        // Si no tiene movimientos, se puede eliminar
        $producto->delete();

        return redirect()->route('inventario.index')
            ->with('success', 'Producto eliminado exitosamente');
    }
}
