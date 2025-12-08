<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAsignacion;
use App\Models\AdminHistorialAsignacion;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador para gestión centralizada de asignaciones del administrador.
 *
 * Permite al admin asignar empleados a cualquier tarea/módulo del sistema.
 */
class AdminAsignacionController extends Controller
{
    /**
     * Mostrar panel de asignaciones
     */
    public function index(): View
    {
        $asignaciones = AdminAsignacion::with(['personal', 'asignadoPor'])
            ->orderBy('estado')
            ->orderByDesc('fecha_inicio')
            ->paginate(20);

        $estadisticas = [
            'activas' => AdminAsignacion::where('estado', 'activa')->count(),
            'suspendidas' => AdminAsignacion::where('estado', 'suspendida')->count(),
            'finalizadas' => AdminAsignacion::where('estado', 'finalizada')->count(),
            'total' => AdminAsignacion::count(),
        ];

        return view('admin.asignaciones.index', compact('asignaciones', 'estadisticas'));
    }

    /**
     * Formulario para crear nueva asignación
     */
    public function create(): View
    {
        $empleados = Personal::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $tiposAsignacion = [
            'chofer' => 'Chofer de Vehículo',
            'responsable_vehiculo' => 'Responsable de Vehículo',
            'mantenimiento' => 'Técnico de Mantenimiento',
            'produccion' => 'Personal de Producción',
            'fumigacion' => 'Responsable de Fumigación',
            'tanques' => 'Limpieza de Tanques',
            'fosa_septica' => 'Limpieza de Fosa Séptica',
            'insumos' => 'Control de Insumos',
            'supervisor' => 'Supervisor General',
            'otro' => 'Otra Asignación',
        ];

        return view('admin.asignaciones.create', compact('empleados', 'tiposAsignacion'));
    }

    /**
     * Guardar nueva asignación
     */
    public function store(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'id_personal' => ['required', 'exists:personal,id'],
            'tipo_asignacion' => ['required', 'in:chofer,responsable_vehiculo,mantenimiento,produccion,fumigacion,tanques,fosa_septica,insumos,supervisor,otro'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'descripcion' => ['required', 'string', 'max:500'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $validado['asignado_por'] = auth()->id();
        $validado['estado'] = 'activa';

        $asignacion = AdminAsignacion::create($validado);

        // Registrar en historial
        AdminHistorialAsignacion::create([
            'id_asignacion' => $asignacion->id,
            'accion' => 'creada',
            'detalles' => 'Asignación creada por ' . auth()->user()->email,
            'realizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.asignaciones.index')
            ->with('success', 'Asignación creada exitosamente');
    }

    /**
     * Ver detalles de una asignación
     */
    public function show(AdminAsignacion $asignacion): View
    {
        $asignacion->load(['personal', 'asignadoPor', 'historial.realizadoPor']);

        return view('admin.asignaciones.show', compact('asignacion'));
    }

    /**
     * Formulario para editar asignación
     */
    public function edit(AdminAsignacion $asignacion): View
    {
        $empleados = Personal::where('estado', 'activo')
            ->orderBy('nombre')
            ->get();

        $tiposAsignacion = [
            'chofer' => 'Chofer de Vehículo',
            'responsable_vehiculo' => 'Responsable de Vehículo',
            'mantenimiento' => 'Técnico de Mantenimiento',
            'produccion' => 'Personal de Producción',
            'fumigacion' => 'Responsable de Fumigación',
            'tanques' => 'Limpieza de Tanques',
            'fosa_septica' => 'Limpieza de Fosa Séptica',
            'insumos' => 'Control de Insumos',
            'supervisor' => 'Supervisor General',
            'otro' => 'Otra Asignación',
        ];

        return view('admin.asignaciones.edit', compact('asignacion', 'empleados', 'tiposAsignacion'));
    }

    /**
     * Actualizar asignación
     */
    public function update(Request $request, AdminAsignacion $asignacion): RedirectResponse
    {
        $validado = $request->validate([
            'id_personal' => ['required', 'exists:personal,id'],
            'tipo_asignacion' => ['required', 'in:chofer,responsable_vehiculo,mantenimiento,produccion,fumigacion,tanques,fosa_septica,insumos,supervisor,otro'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:activa,suspendida,finalizada'],
            'descripcion' => ['required', 'string', 'max:500'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $asignacion->update($validado);

        // Registrar en historial
        AdminHistorialAsignacion::create([
            'id_asignacion' => $asignacion->id,
            'accion' => 'modificada',
            'detalles' => 'Asignación modificada por ' . auth()->user()->email,
            'realizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.asignaciones.index')
            ->with('success', 'Asignación actualizada exitosamente');
    }

    /**
     * Suspender asignación
     */
    public function suspend(AdminAsignacion $asignacion): RedirectResponse
    {
        $asignacion->update(['estado' => 'suspendida']);

        AdminHistorialAsignacion::create([
            'id_asignacion' => $asignacion->id,
            'accion' => 'suspendida',
            'detalles' => 'Asignación suspendida por ' . auth()->user()->email,
            'realizado_por' => auth()->id(),
        ]);

        return back()->with('success', 'Asignación suspendida');
    }

    /**
     * Finalizar asignación
     */
    public function finalize(AdminAsignacion $asignacion): RedirectResponse
    {
        $asignacion->update([
            'estado' => 'finalizada',
            'fecha_fin' => now(),
        ]);

        AdminHistorialAsignacion::create([
            'id_asignacion' => $asignacion->id,
            'accion' => 'finalizada',
            'detalles' => 'Asignación finalizada por ' . auth()->user()->email,
            'realizado_por' => auth()->id(),
        ]);

        return back()->with('success', 'Asignación finalizada');
    }

    /**
     * Reactivar asignación
     */
    public function reactivate(AdminAsignacion $asignacion): RedirectResponse
    {
        $asignacion->update(['estado' => 'activa']);

        AdminHistorialAsignacion::create([
            'id_asignacion' => $asignacion->id,
            'accion' => 'reactivada',
            'detalles' => 'Asignación reactivada por ' . auth()->user()->email,
            'realizado_por' => auth()->id(),
        ]);

        return back()->with('success', 'Asignación reactivada');
    }

    /**
     * Eliminar asignación
     */
    public function destroy(AdminAsignacion $asignacion): RedirectResponse
    {
        $asignacion->delete();

        return redirect()->route('admin.asignaciones.index')
            ->with('success', 'Asignación eliminada exitosamente');
    }
}
