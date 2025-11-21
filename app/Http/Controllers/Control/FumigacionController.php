<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\Fumigacion;
use App\Models\Personal;
use Illuminate\Http\Request;

class FumigacionController extends Controller
{
    public function index()
    {
        $fumigaciones = Fumigacion::orderBy('fecha_fumigacion', 'desc')->paginate(15);
        return view('control.fumigacion.index', compact('fumigaciones'));
    }

    public function create()
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.fumigacion.create', compact('personal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_fumigacion' => 'required|date',
            'area_fumigada' => 'required|string|max:255',
            'producto_utilizado' => 'required|string|max:255',
            'cantidad_producto' => 'required|numeric|min:0',
            'responsable' => 'required|string|max:255',
            'empresa_contratada' => 'nullable|string|max:255',
            'proxima_fumigacion' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        Fumigacion::create($validated);

        return redirect()->route('control.fumigacion.index')
            ->with('success', 'Registro de fumigación creado exitosamente.');
    }

    public function edit(Fumigacion $fumigacion)
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.fumigacion.edit', compact('fumigacion', 'personal'));
    }

    public function update(Request $request, Fumigacion $fumigacion)
    {
        $validated = $request->validate([
            'fecha_fumigacion' => 'required|date',
            'area_fumigada' => 'required|string|max:255',
            'producto_utilizado' => 'required|string|max:255',
            'cantidad_producto' => 'required|numeric|min:0',
            'responsable' => 'required|string|max:255',
            'empresa_contratada' => 'nullable|string|max:255',
            'proxima_fumigacion' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $fumigacion->update($validated);

        return redirect()->route('control.fumigacion.index')
            ->with('success', 'Registro de fumigación actualizado exitosamente.');
    }

    public function destroy(Fumigacion $fumigacion)
    {
        $fumigacion->delete();

        return redirect()->route('control.fumigacion.index')
            ->with('success', 'Registro de fumigación eliminado exitosamente.');
    }
}
