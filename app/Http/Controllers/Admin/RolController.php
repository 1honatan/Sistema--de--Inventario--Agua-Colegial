<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RolController extends Controller
{
    /**
     * Mostrar listado de roles.
     */
    public function index(): View
    {
        $roles = Rol::withCount('usuarios')->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Mostrar formulario para crear nuevo rol.
     */
    public function create(): View
    {
        return view('admin.roles.create');
    }

    /**
     * Guardar nuevo rol en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre',
            'observacion' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Ya existe un rol con este nombre',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
            'observacion.required' => 'La descripción es obligatoria',
            'observacion.max' => 'La descripción no puede tener más de 255 caracteres',
        ]);

        Rol::create($validated);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Mostrar formulario para editar rol.
     */
    public function edit(Rol $rol): View
    {
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Actualizar rol en la base de datos.
     */
    public function update(Request $request, Rol $rol): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre,' . $rol->id,
            'observacion' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio',
            'nombre.unique' => 'Ya existe un rol con este nombre',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres',
            'observacion.required' => 'La descripción es obligatoria',
            'observacion.max' => 'La descripción no puede tener más de 255 caracteres',
        ]);

        $rol->update($validated);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Eliminar rol de la base de datos.
     */
    public function destroy(Rol $rol): RedirectResponse
    {
        // Verificar que el rol no tenga usuarios asignados
        if ($rol->usuarios()->count() > 0) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados');
        }

        // Prevenir eliminación de roles del sistema
        $rolesProtegidos = ['admin', 'produccion', 'inventario', 'despacho'];
        if (in_array($rol->nombre, $rolesProtegidos)) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'No se puede eliminar un rol del sistema');
        }

        $rol->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}
