<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
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
            'acceso_sistema' => 'nullable|boolean',
            'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email',
            'password_acceso' => 'nullable|min:6',
        ]);

        $validated['estado'] = 'activo';
        $validated['area'] = 'Producción';
        $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';

        // Procesar imagen de licencia de conducir
        if ($request->hasFile('foto_licencia')) {
            $imagen = $request->file('foto_licencia');
            $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
            $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
        }


        // Guardar estado de acceso_sistema
        $validated['acceso_sistema'] = $request->has('acceso_sistema');

        $empleado = Personal::create($validated);

        // Crear usuario si se activó acceso al sistema
        if ($request->has('acceso_sistema') && $request->email_acceso) {
            // Obtener rol por defecto (produccion)
            $rolProduccion = \App\Models\Rol::where('nombre', 'produccion')->first();

            if (!$rolProduccion) {
                return redirect()->back()->with('error', 'El rol "produccion" no existe en el sistema.');
            }

            // Crear nuevo usuario
            \App\Models\Usuario::create([
                'nombre' => $empleado->nombre_completo,
                'email' => $request->email_acceso,
                'password' => $request->password_acceso ?? 'password123',
                'id_personal' => $empleado->id,
                'id_rol' => $rolProduccion->id,
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
            'acceso_sistema' => 'nullable|boolean',
            'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email,' . ($empleado->usuario->id ?? 'NULL'),
            'password_acceso' => 'nullable|min:6',
        ]);

        // Actualizar email si cambió el nombre
        $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';

        // Procesar imagen de licencia de conducir
        if ($request->hasFile('foto_licencia')) {
            $imagen = $request->file('foto_licencia');
            $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
            $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
        }


        // Guardar estado de acceso_sistema
        $validated['acceso_sistema'] = $request->has('acceso_sistema');

        $empleado->update($validated);

        // Gestionar acceso al sistema
        if ($request->has('acceso_sistema') && $request->email_acceso) {
            // Obtener rol por defecto (produccion)
            $rolProduccion = \App\Models\Rol::where('nombre', 'produccion')->first();

            if (!$rolProduccion) {
                return redirect()->back()->with('error', 'El rol "produccion" no existe en el sistema.');
            }

            // Buscar usuario existente por id_personal
            $usuarioExistente = \App\Models\Usuario::where('id_personal', $empleado->id)->first();

            if ($usuarioExistente) {
                // Actualizar usuario existente
                $usuarioExistente->email = $request->email_acceso;
                $usuarioExistente->nombre = $empleado->nombre_completo;

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
                    'id_rol' => $rolProduccion->id,
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
}
