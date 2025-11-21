<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\MantenimientoEquipo;
use App\Models\Personal;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    /**
     * Lista de productos de limpieza disponibles para mantenimiento
     */
    private function getProductosLimpieza(): array
    {
        return [
            'Detergente Industrial',
            'Cloro / Hipoclorito de Sodio',
            'Desinfectante para Superficies',
            'Alcohol al 70%',
            'Jabón Antibacterial',
            'Desengrasante',
            'Limpiador Multiusos',
            'Cepillos de Limpieza',
            'Esponjas Abrasivas',
            'Paños de Microfibra',
            'Ácido Cítrico',
            'Bicarbonato de Sodio',
            'Sanitizante de Grado Alimenticio',
            'Vinagre Blanco',
        ];
    }

    public function index()
    {
        $mantenimientos = MantenimientoEquipo::with('personal')->orderBy('fecha', 'desc')->paginate(15);
        return view('control.mantenimiento.index', compact('mantenimientos'));
    }

    public function create()
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        $productosLimpieza = $this->getProductosLimpieza();

        return view('control.mantenimiento.create', compact('personal', 'productosLimpieza'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'equipo' => 'required|array|min:1',
            'equipo.*' => 'required|string',
            'id_personal' => 'required|exists:personal,id',
            'productos_limpieza' => 'required|array|min:1',
            'productos_limpieza.*' => 'string',
            'proxima_fecha' => 'nullable|date|after:fecha',
            'supervisado_por' => 'required|string',
        ]);

        // El campo detalle_mantenimiento ahora es generado automáticamente
        $equiposTexto = implode(', ', $validated['equipo']);
        $productosTexto = implode(', ', $validated['productos_limpieza']);
        $validated['detalle_mantenimiento'] = "Equipos: {$equiposTexto} | Productos: {$productosTexto}";

        // Mantener realizado_por para compatibilidad, pero usar id_personal
        $personal = Personal::find($validated['id_personal']);
        $validated['realizado_por'] = $personal->nombre_completo;

        MantenimientoEquipo::create($validated);

        return redirect()->route('control.mantenimiento.index')
            ->with('success', 'Registro de mantenimiento creado exitosamente.');
    }

    public function show(MantenimientoEquipo $mantenimiento)
    {
        return view('control.mantenimiento.show', compact('mantenimiento'));
    }

    public function edit(MantenimientoEquipo $mantenimiento)
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        $productosLimpieza = $this->getProductosLimpieza();

        return view('control.mantenimiento.edit', compact('mantenimiento', 'personal', 'productosLimpieza'));
    }

    public function update(Request $request, MantenimientoEquipo $mantenimiento)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'equipo' => 'required|array|min:1',
            'equipo.*' => 'required|string',
            'id_personal' => 'required|exists:personal,id',
            'productos_limpieza' => 'required|array|min:1',
            'productos_limpieza.*' => 'string',
            'proxima_fecha' => 'nullable|date|after:fecha',
            'supervisado_por' => 'required|string',
        ]);

        // Actualizar detalle_mantenimiento
        $equiposTexto = implode(', ', $validated['equipo']);
        $productosTexto = implode(', ', $validated['productos_limpieza']);
        $validated['detalle_mantenimiento'] = "Equipos: {$equiposTexto} | Productos: {$productosTexto}";

        // Actualizar realizado_por
        $personal = Personal::find($validated['id_personal']);
        $validated['realizado_por'] = $personal->nombre_completo;

        $mantenimiento->update($validated);

        return redirect()->route('control.mantenimiento.index')
            ->with('success', 'Registro de mantenimiento actualizado exitosamente.');
    }

    public function destroy(MantenimientoEquipo $mantenimiento)
    {
        $mantenimiento->delete();

        return redirect()->route('control.mantenimiento.index')
            ->with('success', 'Registro de mantenimiento eliminado exitosamente.');
    }
}
