<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Determinar el rol según el cargo del empleado
     */
    private function determinarRolPorCargo(string $cargo): string
    {
        if (str_contains($cargo, 'Inventario') || str_contains($cargo, 'Almacén')) {
            return 'inventario';
        } elseif (str_contains($cargo, 'Chofer') || str_contains($cargo, 'Despacho')) {
            return 'despacho';
        }
        // Por defecto: produccion (incluye Embolsador, Limpieza, Ayudante, etc.)
        return 'produccion';
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('control.empleados.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_ingreso' => 'required|date',
            'salario' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
            'foto_licencia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'ausente' => 'nullable|boolean',
            'acceso_sistema' => 'nullable|boolean',
            'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email',
            'password_acceso' => 'nullable|min:6',
        ]);

        // Si está ausente, no puede tener acceso al sistema
        $validated['estado'] = $request->has('ausente') ? 'inactivo' : 'activo';
        $validated['area'] = 'Producción';
        $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';

        // Procesar imagen de licencia de conducir
        if ($request->hasFile('foto_licencia')) {
            $imagen = $request->file('foto_licencia');
            $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
            $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
        }


        // Guardar estado de tiene_acceso
        $validated['tiene_acceso'] = $request->has('acceso_sistema');

        $empleado = Personal::create($validated);

        // Crear usuario si se activó acceso al sistema
        if ($request->has('acceso_sistema') && $request->email_acceso) {
            // Determinar rol según cargo
            $nombreRol = $this->determinarRolPorCargo($validated['cargo']);
            $rol = \App\Models\Rol::where('nombre', $nombreRol)->first();

            if (!$rol) {
                return redirect()->back()->with('error', "El rol \"{$nombreRol}\" no existe en el sistema.");
            }

            // Crear nuevo usuario
            \App\Models\Usuario::create([
                'nombre' => $empleado->nombre_completo,
                'email' => $request->email_acceso,
                'password' => $request->password_acceso ?? 'password123',
                'id_personal' => $empleado->id,
                'id_rol' => $rol->id,
                'estado' => 'activo',
            ]);
        }

        return redirect()->route('control.asistencia-semanal.registro-rapido')
            ->with('success', 'Empleado registrado exitosamente.');
    }

    /**
     * Display the specified employee.
     */
    public function show($id)
    {
        $empleado = Personal::findOrFail($id);
        return view('control.empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit($id)
    {
        $empleado = Personal::findOrFail($id);

        // Sincronizar tiene_acceso si el empleado tiene usuario pero el campo no está actualizado
        if ($empleado->usuario && !$empleado->tiene_acceso) {
            $empleado->update(['tiene_acceso' => true]);
            $empleado->refresh();
        }

        return view('control.empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, $id)
    {
        $empleado = Personal::findOrFail($id);

        $validated = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_ingreso' => 'required|date',
            'salario' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
            'foto_licencia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'ausente' => 'nullable|boolean',
            'acceso_sistema' => 'nullable|boolean',
            'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email,' . ($empleado->usuario->id ?? 'NULL'),
            'password_acceso' => 'nullable|min:6',
        ]);

        // Si está ausente, marcar como inactivo y deshabilitar acceso
        $validated['estado'] = $request->has('ausente') ? 'inactivo' : 'activo';

        // Actualizar email si cambió el nombre
        $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';

        // Si está ausente, también desactivar usuario si existe
        if ($request->has('ausente') && $empleado->usuario) {
            $empleado->usuario->update(['estado' => 'inactivo']);
        }

        // Procesar imagen de licencia de conducir
        if ($request->hasFile('foto_licencia')) {
            $imagen = $request->file('foto_licencia');
            $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
            $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
        }


        // Guardar estado de tiene_acceso
        $validated['tiene_acceso'] = $request->has('acceso_sistema');

        $empleado->update($validated);

        // Gestionar acceso al sistema
        if ($request->has('acceso_sistema') && $request->email_acceso) {
            // Determinar rol según cargo
            $nombreRol = $this->determinarRolPorCargo($validated['cargo']);
            $rol = \App\Models\Rol::where('nombre', $nombreRol)->first();

            if (!$rol) {
                return redirect()->back()->with('error', "El rol \"{$nombreRol}\" no existe en el sistema.");
            }

            // Buscar usuario existente por id_personal
            $usuarioExistente = \App\Models\Usuario::where('id_personal', $empleado->id)->first();

            if ($usuarioExistente) {
                // Actualizar usuario existente
                $usuarioExistente->email = $request->email_acceso;
                $usuarioExistente->nombre = $empleado->nombre_completo;
                $usuarioExistente->id_rol = $rol->id; // Actualizar rol según cargo

                if ($request->filled('password_acceso')) {
                    $usuarioExistente->password = $request->password_acceso;
                }

                $usuarioExistente->save();
            } else {
                // Crear nuevo usuario
                $user = \App\Models\Usuario::create([
                    'nombre' => $empleado->nombre_completo,
                    'email' => $request->email_acceso,
                    'password' => $request->password_acceso ?? 'password123',
                    'id_personal' => $empleado->id,
                    'id_rol' => $rol->id,
                    'estado' => 'activo',
                ]);
            }
        } elseif (!$request->has('acceso_sistema')) {
            // Desactivar acceso - eliminar usuario si existe
            $usuarioExistente = \App\Models\Usuario::where('id_personal', $empleado->id)->first();
            if ($usuarioExistente) {
                $usuarioExistente->delete();
            }
        }

        return redirect()->route('control.asistencia-semanal.registro-rapido')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy($id)
    {
        $personal = Personal::findOrFail($id);

        $nombre = $personal->nombre_completo;

        // Cambiar estado a inactivo en lugar de eliminar
        // Así no aparecerá en ningún select de responsables
        $personal->update(['estado' => 'inactivo']);

        return redirect()->route('control.asistencia-semanal.registro-rapido')
            ->with('success', "Empleado '{$nombre}' eliminado exitosamente.");
    }

    /**
     * Deshabilitar acceso al sistema (marcar como ausente)
     */
    public function deshabilitarAcceso($id)
    {
        $personal = Personal::findOrFail($id);

        if ($personal->usuario) {
            $personal->usuario->update(['estado' => 'inactivo']);

            return redirect()->route('control.empleados.edit', $id)
                ->with('success', "Acceso al sistema deshabilitado para '{$personal->nombre_completo}'. El empleado ha sido marcado como AUSENTE.");
        }

        return redirect()->route('control.empleados.edit', $id)
            ->with('error', "El empleado no tiene acceso al sistema configurado.");
    }

    /**
     * Habilitar acceso al sistema (reactivar)
     */
    public function habilitarAcceso($id)
    {
        $personal = Personal::findOrFail($id);

        if ($personal->usuario) {
            $personal->usuario->update(['estado' => 'activo']);

            return redirect()->route('control.empleados.edit', $id)
                ->with('success', "Acceso al sistema reactivado para '{$personal->nombre_completo}'. El empleado puede iniciar sesión nuevamente.");
        }

        return redirect()->route('control.empleados.edit', $id)
            ->with('error', "El empleado no tiene acceso al sistema configurado.");
    }
}
