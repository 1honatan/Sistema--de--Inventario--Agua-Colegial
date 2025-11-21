<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\TanqueAgua;
use App\Models\Personal;
use Illuminate\Http\Request;

class TanquesController extends Controller
{
    public function index()
    {
        $tanques = TanqueAgua::orderBy('fecha_limpieza', 'desc')->paginate(15);
        return view('control.tanques.index', compact('tanques'));
    }

    public function create()
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.tanques.create', compact('personal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_limpieza' => 'required|date',
            'nombre_tanque' => 'required|string|max:255',
            'capacidad_litros' => 'nullable|numeric|min:0',
            'procedimiento_limpieza' => 'nullable|string',
            'productos_desinfeccion' => 'nullable|string',
            'responsable' => 'required|string|max:255',
            'supervisado_por' => 'nullable|string|max:255',
            'proxima_limpieza' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        TanqueAgua::create($validated);

        return redirect()->route('control.tanques.index')
            ->with('success', 'Registro de limpieza de tanque creado exitosamente.');
    }

    public function show(TanqueAgua $tanque)
    {
        return view('control.tanques.show', compact('tanque'));
    }

    public function edit(TanqueAgua $tanque)
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.tanques.edit', compact('tanque', 'personal'));
    }

    public function update(Request $request, TanqueAgua $tanque)
    {
        $validated = $request->validate([
            'fecha_limpieza' => 'required|date',
            'nombre_tanque' => 'required|string|max:255',
            'capacidad_litros' => 'nullable|numeric|min:0',
            'procedimiento_limpieza' => 'nullable|string',
            'productos_desinfeccion' => 'nullable|string',
            'responsable' => 'required|string|max:255',
            'supervisado_por' => 'nullable|string|max:255',
            'proxima_limpieza' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $tanque->update($validated);

        return redirect()->route('control.tanques.index')
            ->with('success', 'Registro de limpieza de tanque actualizado exitosamente.');
    }

    public function destroy(TanqueAgua $tanque)
    {
        $tanque->delete();

        return redirect()->route('control.tanques.index')
            ->with('success', 'Registro de limpieza de tanque eliminado exitosamente.');
    }
}
