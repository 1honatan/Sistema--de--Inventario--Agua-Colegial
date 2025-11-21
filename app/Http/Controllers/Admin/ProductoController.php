<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use App\Models\TipoProducto;
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
     * Redirigir al inventario general (catálogo deshabilitado).
     */
    public function index(Request $request): RedirectResponse
    {
        // El catálogo de productos ha sido deshabilitado
        // Redirigir al inventario general donde se pueden ver todos los productos
        return redirect()->route('inventario.index')
            ->with('info', 'El catálogo de productos se ha movido al Inventario General');
    }

    /**
     * MÉTODO DESHABILITADO - Listar todos los productos.
     */
    private function index_OLD(Request $request): View
    {
        $query = Producto::query();

        // Filtrar por tipo si se proporciona
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtrar por estado si se proporciona
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $productos = $query->orderBy('tipo')
            ->orderBy('nombre')
            ->paginate(20);

        // Calcular stock para cada producto
        foreach ($productos as $producto) {
            $producto->stock_actual = Inventario::stockDisponible($producto->id);
        }

        // Obtener tipos únicos para filtro
        $tipos = Producto::distinct()->pluck('tipo');

        return view('admin.productos.index', compact('productos', 'tipos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(): View
    {
        $tiposProducto = TipoProducto::activos()->ordenadoPorNombre()->get();
        return view('admin.productos.create', compact('tiposProducto'));
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
        $tiposProducto = TipoProducto::activos()->ordenadoPorNombre()->get();
        return view('admin.productos.edit', compact('producto', 'tiposProducto'));
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
