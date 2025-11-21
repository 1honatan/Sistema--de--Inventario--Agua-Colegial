<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador de gestión de vehículos (solo admin).
 *
 * Permite crear, editar, listar y eliminar vehículos de la flota.
 */
class VehiculoController extends Controller
{
    /**
     * Mostrar listado de vehículos.
     */
    public function index(): View
    {
        $vehiculos = Vehiculo::orderBy('placa')->get();

        // Calcular estadísticas
        $totalVehiculos = $vehiculos->count();
        $activos = $vehiculos->where('estado', 'activo')->count();
        $enMantenimiento = $vehiculos->where('estado', 'mantenimiento')->count();
        $inactivos = $vehiculos->where('estado', 'inactivo')->count();

        return view('admin.vehiculos.index', compact(
            'vehiculos',
            'totalVehiculos',
            'activos',
            'enMantenimiento',
            'inactivos'
        ));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create(): View
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('admin.vehiculos.create', compact('personal'));
    }

    /**
     * Guardar nuevo vehículo.
     */
    public function store(Request $request): RedirectResponse
    {
        $validado = $request->validate([
            'placa' => ['required', 'string', 'max:10', 'unique:vehiculos,placa'],
            'responsable' => ['nullable', 'string', 'max:255'],
            'modelo' => ['nullable', 'string', 'max:100'],
            'marca' => ['nullable', 'string', 'max:100'],
            'estado' => ['required', 'in:activo,mantenimiento,inactivo'],
            'capacidad' => ['nullable', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ], [
            'placa.required' => 'La placa es obligatoria',
            'placa.unique' => 'Esta placa ya está registrada',
            'estado.required' => 'El estado es obligatorio',
            'capacidad.min' => 'La capacidad debe ser mayor a 0',
        ]);

        try {
            Vehiculo::create($validado);

            return redirect()
                ->route('admin.vehiculos.index')
                ->with('success', 'Vehículo registrado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar vehículo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Vehiculo $vehiculo): View
    {
        $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
        return view('admin.vehiculos.edit', compact('vehiculo', 'personal'));
    }

    /**
     * Actualizar vehículo.
     */
    public function update(Request $request, Vehiculo $vehiculo): RedirectResponse
    {
        $validado = $request->validate([
            'placa' => ['required', 'string', 'max:10', 'unique:vehiculos,placa,' . $vehiculo->id],
            'responsable' => ['nullable', 'string', 'max:255'],
            'modelo' => ['nullable', 'string', 'max:100'],
            'marca' => ['nullable', 'string', 'max:100'],
            'estado' => ['required', 'in:activo,mantenimiento,inactivo'],
            'capacidad' => ['nullable', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ], [
            'placa.required' => 'La placa es obligatoria',
            'placa.unique' => 'Esta placa ya está registrada',
            'estado.required' => 'El estado es obligatorio',
            'capacidad.min' => 'La capacidad debe ser mayor a 0',
        ]);

        try {
            $vehiculo->update($validado);

            return redirect()
                ->route('admin.vehiculos.index')
                ->with('success', 'Vehículo actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar vehículo: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar vehículo.
     */
    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        try {
            $vehiculo->delete();

            return redirect()
                ->route('admin.vehiculos.index')
                ->with('success', 'Vehículo eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar vehículo: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del vehículo.
     */
    public function toggleEstado(Vehiculo $vehiculo): RedirectResponse
    {
        try {
            $nuevoEstado = match ($vehiculo->estado) {
                'activo' => 'mantenimiento',
                'mantenimiento' => 'inactivo',
                'inactivo' => 'activo',
                default => 'activo'
            };

            $vehiculo->update(['estado' => $nuevoEstado]);

            $mensaje = match ($nuevoEstado) {
                'activo' => 'Vehículo activado exitosamente',
                'mantenimiento' => 'Vehículo enviado a mantenimiento',
                'inactivo' => 'Vehículo desactivado',
                default => 'Estado actualizado'
            };

            return redirect()
                ->route('admin.vehiculos.index')
                ->with('success', $mensaje);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }
}
