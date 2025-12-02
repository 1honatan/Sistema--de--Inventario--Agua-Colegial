<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaSemanal;
use App\Models\Personal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AsistenciaSemanalController extends Controller
{
    /**
     * Mostrar vista semanal de asistencias (estilo cuaderno)
     */
    public function index(Request $request)
    {
        // Obtener la semana actual o la seleccionada
        $fechaSeleccionada = $request->get('semana')
            ? Carbon::parse($request->get('semana'))
            : Carbon::now();

        $inicioSemana = $fechaSeleccionada->copy()->startOfWeek();
        $finSemana = $fechaSeleccionada->copy()->endOfWeek();

        // Obtener todo el personal activo
        $personal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        // Obtener todas las asistencias de la semana
        $asistencias = AsistenciaSemanal::with('personal')
            ->whereBetween('fecha', [$inicioSemana, $finSemana])
            ->get()
            ->groupBy(function ($item) {
                return $item->personal_id . '_' . $item->fecha->format('Y-m-d');
            });

        // Crear array de días de la semana
        $diasSemana = [];
        for ($i = 0; $i < 7; $i++) {
            $dia = $inicioSemana->copy()->addDays($i);
            $diasSemana[] = [
                'fecha' => $dia,
                'nombre' => AsistenciaSemanal::obtenerDiaSemana($dia),
                'numero' => $dia->day,
            ];
        }

        return view('control.asistencia-semanal.index', compact(
            'personal',
            'asistencias',
            'diasSemana',
            'inicioSemana',
            'finSemana',
            'fechaSeleccionada'
        ));
    }

    /**
     * Mostrar formulario para registrar asistencia
     */
    public function create(Request $request)
    {
        $personal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        $fechaSeleccionada = $request->get('fecha')
            ? Carbon::parse($request->get('fecha'))
            : Carbon::now();

        $personalId = $request->get('personal_id');

        return view('control.asistencia-semanal.create', compact(
            'personal',
            'fechaSeleccionada',
            'personalId'
        ));
    }

    /**
     * Guardar nuevo registro de asistencia
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personal,id',
            'fecha' => 'required|date',
            'entrada_hora' => 'required|date_format:H:i',
            'salida_hora' => 'nullable|date_format:H:i|after:entrada_hora',
            'observaciones' => 'nullable|string|max:500',
            'estado' => 'required|in:presente,ausente,permiso,tardanza',
        ], [
            'personal_id.required' => 'Debe seleccionar un empleado',
            'personal_id.exists' => 'El empleado seleccionado no existe',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha no es válida',
            'entrada_hora.required' => 'La hora de entrada es obligatoria',
            'entrada_hora.date_format' => 'El formato de hora de entrada no es válido (HH:MM)',
            'salida_hora.date_format' => 'El formato de hora de salida no es válido (HH:MM)',
            'salida_hora.after' => 'La hora de salida debe ser posterior a la hora de entrada',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado seleccionado no es válido',
        ]);

        $fecha = Carbon::parse($validated['fecha']);
        $validated['dia_semana'] = AsistenciaSemanal::obtenerDiaSemana($fecha);

        // Si el usuario autenticado tiene relación con personal, guardar ese ID
        $usuario = auth()->user();
        if ($usuario && isset($usuario->personal_id)) {
            $validated['registrado_por'] = $usuario->personal_id;
        } else {
            $validated['registrado_por'] = null;
        }

        try {
            AsistenciaSemanal::create($validated);

            return redirect()
                ->route('control.asistencia-semanal.index', ['semana' => $fecha->format('Y-m-d')])
                ->with('success', 'Asistencia registrada correctamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al registrar la asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $asistencia = AsistenciaSemanal::with('personal')->findOrFail($id);
        $personal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        return view('control.asistencia-semanal.edit', compact('asistencia', 'personal'));
    }

    /**
     * Actualizar registro de asistencia
     */
    public function update(Request $request, $id)
    {
        $asistencia = AsistenciaSemanal::findOrFail($id);

        $validated = $request->validate([
            'personal_id' => 'required|exists:personal,id',
            'fecha' => 'required|date',
            'entrada_hora' => 'required|date_format:H:i',
            'salida_hora' => 'nullable|date_format:H:i|after:entrada_hora',
            'observaciones' => 'nullable|string|max:500',
            'estado' => 'required|in:presente,ausente,permiso,tardanza',
        ]);

        $fecha = Carbon::parse($validated['fecha']);
        $validated['dia_semana'] = AsistenciaSemanal::obtenerDiaSemana($fecha);

        try {
            $asistencia->update($validated);

            return redirect()
                ->route('control.asistencia-semanal.index', ['semana' => $fecha->format('Y-m-d')])
                ->with('success', 'Asistencia actualizada correctamente');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar registro de asistencia
     */
    public function destroy($id)
    {
        try {
            $asistencia = AsistenciaSemanal::findOrFail($id);
            $fecha = $asistencia->fecha;
            $asistencia->delete();

            return redirect()
                ->route('control.asistencia-semanal.index', ['semana' => $fecha->format('Y-m-d')])
                ->with('success', 'Asistencia eliminada correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la asistencia: ' . $e->getMessage());
        }
    }

    /**
     * Generar reporte PDF de la semana
     */
    public function generarReporte(Request $request)
    {
        // Implementar generación de PDF si es necesario
        return back()->with('info', 'Funcionalidad de reporte en desarrollo');
    }

    /**
     * Vista para registro rápido de entrada/salida
     */
    public function registroRapido()
    {
        $personal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        // Obtener asistencias de hoy
        $hoy = Carbon::today();
        $asistenciasHoy = AsistenciaSemanal::whereDate('fecha', $hoy)
            ->with('personal')
            ->get()
            ->keyBy('personal_id');

        return view('control.asistencia-semanal.registro-rapido', compact(
            'personal',
            'asistenciasHoy',
            'hoy'
        ));
    }

    /**
     * Registrar entrada rápida
     */
    public function registrarEntrada(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personal,id',
        ]);

        $hoy = Carbon::today();
        $horaEntrada = Carbon::now()->format('H:i'); // Hora actual del sistema

        // Verificar si ya tiene entrada hoy
        $asistenciaExistente = AsistenciaSemanal::where('personal_id', $validated['personal_id'])
            ->whereDate('fecha', $hoy)
            ->whereNotNull('entrada_hora')
            ->whereNull('salida_hora')
            ->first();

        if ($asistenciaExistente) {
            return back()->with('warning', 'El personal ya tiene una entrada registrada hoy sin salida');
        }

        // Crear registro de entrada
        try {
            AsistenciaSemanal::create([
                'personal_id' => $validated['personal_id'],
                'fecha' => $hoy,
                'dia_semana' => AsistenciaSemanal::obtenerDiaSemana($hoy),
                'entrada_hora' => $horaEntrada,
                'salida_hora' => null,
                'estado' => 'presente',
                'observaciones' => 'Entrada registrada automáticamente',
                'registrado_por' => auth()->user()->personal_id ?? null,
            ]);

            $personal = Personal::find($validated['personal_id']);
            return back()->with('success', "Entrada registrada para {$personal->nombre_completo} a las {$horaEntrada}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar entrada: ' . $e->getMessage());
        }
    }

    /**
     * Registrar salida rápida
     */
    public function registrarSalida(Request $request)
    {
        $validated = $request->validate([
            'personal_id' => 'required|exists:personal,id',
        ]);

        $hoy = Carbon::today();
        $horaActual = Carbon::now()->format('H:i');

        // Buscar entrada sin salida de hoy
        $asistencia = AsistenciaSemanal::where('personal_id', $validated['personal_id'])
            ->whereDate('fecha', $hoy)
            ->whereNotNull('entrada_hora')
            ->whereNull('salida_hora')
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No hay entrada registrada hoy para este personal');
        }

        // Registrar salida
        try {
            $asistencia->update([
                'salida_hora' => $horaActual,
            ]);

            $personal = Personal::find($validated['personal_id']);
            return back()->with('success', "Salida registrada para {$personal->nombre_completo} a las {$horaActual}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }

    /**
     * Registro rápido para el personal (entrada/salida propia)
     */
    public function miRegistro()
    {
        $usuario = auth()->user();

        if (!$usuario->personal_id) {
            return back()->with('error', 'No tiene un perfil de personal asociado');
        }

        $personal = Personal::find($usuario->personal_id);
        $hoy = Carbon::today();

        // Obtener última asistencia de hoy
        $asistenciaHoy = AsistenciaSemanal::where('personal_id', $personal->id)
            ->whereDate('fecha', $hoy)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('control.asistencia-semanal.mi-registro', compact(
            'personal',
            'asistenciaHoy',
            'hoy'
        ));
    }

    /**
     * Marcar mi entrada (para el propio personal)
     */
    public function marcarMiEntrada()
    {
        $usuario = auth()->user();

        if (!$usuario->personal_id) {
            return back()->with('error', 'No tiene un perfil de personal asociado');
        }

        $hoy = Carbon::today();
        $horaEntrada = Carbon::now()->format('H:i'); // Hora actual del sistema

        // Verificar si ya tiene entrada hoy sin salida
        $asistenciaExistente = AsistenciaSemanal::where('personal_id', $usuario->personal_id)
            ->whereDate('fecha', $hoy)
            ->whereNotNull('entrada_hora')
            ->whereNull('salida_hora')
            ->first();

        if ($asistenciaExistente) {
            return back()->with('warning', 'Ya tiene una entrada registrada hoy sin salida');
        }

        try {
            AsistenciaSemanal::create([
                'personal_id' => $usuario->personal_id,
                'fecha' => $hoy,
                'dia_semana' => AsistenciaSemanal::obtenerDiaSemana($hoy),
                'entrada_hora' => $horaEntrada,
                'salida_hora' => null,
                'estado' => 'presente',
                'observaciones' => 'Entrada auto-registrada',
                'registrado_por' => $usuario->personal_id,
            ]);

            return back()->with('success', "Entrada registrada exitosamente a las {$horaEntrada}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar entrada: ' . $e->getMessage());
        }
    }

    /**
     * Marcar mi salida (para el propio personal)
     */
    public function marcarMiSalida()
    {
        $usuario = auth()->user();

        if (!$usuario->personal_id) {
            return back()->with('error', 'No tiene un perfil de personal asociado');
        }

        $hoy = Carbon::today();
        $horaActual = Carbon::now()->format('H:i');

        // Buscar entrada sin salida de hoy
        $asistencia = AsistenciaSemanal::where('personal_id', $usuario->personal_id)
            ->whereDate('fecha', $hoy)
            ->whereNotNull('entrada_hora')
            ->whereNull('salida_hora')
            ->first();

        if (!$asistencia) {
            return back()->with('error', 'No hay entrada registrada hoy');
        }

        try {
            $asistencia->update([
                'salida_hora' => $horaActual,
            ]);

            return back()->with('success', "Salida registrada exitosamente a las {$horaActual}");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }
}
