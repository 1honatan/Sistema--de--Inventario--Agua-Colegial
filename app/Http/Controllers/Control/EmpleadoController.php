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
            'foto_documento' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'foto_licencia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'foto_id_chofer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['estado'] = 'activo';
        $validated['area'] = 'Producción';
        $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';

        // Si es chofer, marcar es_chofer
        if ($validated['cargo'] === 'Chofer') {
            $validated['es_chofer'] = true;
        }

        // Procesar imagen del documento
        if ($request->hasFile('foto_documento')) {
            $imagen = $request->file('foto_documento');
            $nombreArchivo = 'doc_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/documentos'), $nombreArchivo);
            $validated['foto_documento'] = 'uploads/documentos/' . $nombreArchivo;
        }

        // Procesar imagen de licencia de conducir
        if ($request->hasFile('foto_licencia')) {
            $imagen = $request->file('foto_licencia');
            $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
            $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
        }

        // Procesar imagen de ID chofer
        if ($request->hasFile('foto_id_chofer')) {
            $imagen = $request->file('foto_id_chofer');
            $nombreArchivo = 'id_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
            $imagen->move(public_path('uploads/documentos'), $nombreArchivo);
            $validated['foto_id_chofer'] = 'uploads/documentos/' . $nombreArchivo;
        }

        Personal::create($validated);

        return redirect()->route('control.asistencia-semanal.registro-rapido')
            ->with('success', 'Empleado registrado exitosamente.');
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
