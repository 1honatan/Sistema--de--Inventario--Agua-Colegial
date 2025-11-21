<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoProductoRequest;
use App\Http\Requests\UpdateTipoProductoRequest;
use App\Models\TipoProducto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador de gestión de tipos de producto.
 *
 * Roles permitidos: admin
 */
class TipoProductoController extends Controller
{
    /**
     * Mostrar listado de tipos de producto.
     */
    public function index(Request $request): View
    {
        $query = TipoProducto::query();

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Buscar por nombre o código
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        // Ordenar por nombre
        $tiposProducto = $query->orderBy('nombre', 'asc')->paginate(15);

        // Estadísticas
        $totalActivos = TipoProducto::where('estado', 'activo')->count();
        $totalInactivos = TipoProducto::where('estado', 'inactivo')->count();
        $totalTipos = TipoProducto::count();

        return view('admin.tipos_producto.index', compact(
            'tiposProducto',
            'totalActivos',
            'totalInactivos',
            'totalTipos'
        ));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(): View
    {
        return view('admin.tipos_producto.create');
    }

    /**
     * Guardar nuevo tipo de producto.
     */
    public function store(StoreTipoProductoRequest $request): RedirectResponse
    {
        $validado = $request->validated();

        TipoProducto::create($validado);

        return redirect()
            ->route('admin.tipos-producto.index')
            ->with('success', 'Tipo de producto creado exitosamente');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(TipoProducto $tiposProducto): View
    {
        return view('admin.tipos_producto.edit', [
            'tipoProducto' => $tiposProducto
        ]);
    }

    /**
     * Actualizar tipo de producto existente.
     */
    public function update(UpdateTipoProductoRequest $request, TipoProducto $tiposProducto): RedirectResponse
    {
        $validado = $request->validated();

        $tiposProducto->update($validado);

        return redirect()
            ->route('admin.tipos-producto.index')
            ->with('success', 'Tipo de producto actualizado exitosamente');
    }

    /**
     * Desactivar tipo de producto (soft delete).
     */
    public function destroy(TipoProducto $tiposProducto): RedirectResponse
    {
        // Verificar si hay productos asociados activos
        $productosActivos = $tiposProducto->productos()->where('estado', 'activo')->count();

        if ($productosActivos > 0) {
            return back()->with('error', "No se puede desactivar. Hay {$productosActivos} producto(s) activo(s) asociado(s) a este tipo.");
        }

        // Cambiar estado a inactivo
        $tiposProducto->update(['estado' => 'inactivo']);

        return redirect()
            ->route('admin.tipos-producto.index')
            ->with('success', 'Tipo de producto desactivado exitosamente');
    }

    /**
     * Reactivar tipo de producto.
     */
    public function activar(TipoProducto $tiposProducto): RedirectResponse
    {
        $tiposProducto->update(['estado' => 'activo']);

        return redirect()
            ->route('admin.tipos-producto.index')
            ->with('success', 'Tipo de producto activado exitosamente');
    }
}
