<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\Insumo;
use App\Models\Personal;
use Illuminate\Http\Request;

class InsumosController extends Controller
{
    public function index()
    {
        $insumos = Insumo::orderBy('fecha', 'desc')->paginate(15);
        return view('control.insumos.index', compact('insumos'));
    }

    public function create()
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.insumos.create', compact('personal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'producto_insumo' => 'required|string|max:255',
            'cantidad' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string',
            'numero_lote' => 'nullable|string|max:100',
            'fecha_vencimiento' => 'nullable|date',
            'responsable' => 'required|string|max:255',
            'proveedor' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        Insumo::create($validated);

        return redirect()->route('control.insumos.index')
            ->with('success', 'Registro de insumo creado exitosamente.');
    }

    public function edit(Insumo $insumo)
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('control.insumos.edit', compact('insumo', 'personal'));
    }

    public function update(Request $request, Insumo $insumo)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'producto_insumo' => 'required|string|max:255',
            'cantidad' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string',
            'numero_lote' => 'nullable|string|max:100',
            'fecha_vencimiento' => 'nullable|date',
            'responsable' => 'required|string|max:255',
            'proveedor' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $insumo->update($validated);

        return redirect()->route('control.insumos.index')
            ->with('success', 'Registro de insumo actualizado exitosamente.');
    }

    public function destroy(Insumo $insumo)
    {
        $insumo->delete();

        return redirect()->route('control.insumos.index')
            ->with('success', 'Registro de insumo eliminado exitosamente.');
    }
}
