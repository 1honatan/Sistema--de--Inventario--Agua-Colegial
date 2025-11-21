<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\FosaSeptica;
use App\Models\Personal;
use Illuminate\Http\Request;

class FosaSepticaController extends Controller
{
    public function index()
    {
        $registros = FosaSeptica::orderBy('fecha_limpieza', 'desc')->paginate(15);
        return view('control.fosa-septica.index', compact('registros'));
    }

    public function create()
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.fosa-septica.create', compact('personal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_limpieza' => 'required|date',
            'tipo_fosa' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'detalle_trabajo' => 'required|string',
            'empresa_contratada' => 'required|string|max:255',
            'proxima_limpieza' => 'required|date|after:fecha_limpieza',
            'observaciones' => 'nullable|string',
        ]);

        FosaSeptica::create($validated);

        return redirect()->route('control.fosa-septica.index')
            ->with('success', 'Registro de limpieza creado exitosamente.');
    }

    public function edit(FosaSeptica $fosa)
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.fosa-septica.edit', compact('fosa', 'personal'));
    }

    public function update(Request $request, FosaSeptica $fosa)
    {
        $validated = $request->validate([
            'fecha_limpieza' => 'required|date',
            'tipo_fosa' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'detalle_trabajo' => 'required|string',
            'empresa_contratada' => 'required|string|max:255',
            'proxima_limpieza' => 'required|date|after:fecha_limpieza',
            'observaciones' => 'nullable|string',
        ]);

        $fosa->update($validated);

        return redirect()->route('control.fosa-septica.index')
            ->with('success', 'Registro de limpieza actualizado exitosamente.');
    }

    public function destroy(FosaSeptica $fosa)
    {
        $fosa->delete();

        return redirect()->route('control.fosa-septica.index')
            ->with('success', 'Registro de limpieza eliminado exitosamente.');
    }
}
