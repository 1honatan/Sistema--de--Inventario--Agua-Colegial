<?php

declare(strict_types=1);

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaSemanal;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador de asistencia para el personal.
 *
 * Permite que cada empleado registre su entrada, salida o ausencia.
 * Solo accesible para roles que NO sean admin (producción, inventario, despacho).
 */
class AsistenciaController extends Controller
{
    /**
     * Mostrar dashboard de asistencia del personal autenticado.
     */
    public function index(): View
    {
        // Verificar que el usuario NO sea admin
        $usuario = Auth::user();

        if ($usuario->rol && $usuario->rol->nombre === 'admin') {
            return redirect()->route('admin.asistencia.index')
                ->with('info', 'Los administradores no registran asistencia. Use el panel de Asistencia Personal para ver las asistencias.');
        }

        // Obtener el personal asociado al usuario autenticado
        $personal = $usuario->personal;

        if (!$personal) {
            abort(403, 'No se encontró información del personal asociado.');
        }

        // Obtener historial reciente (últimos 30 días) - solo los registrados por el admin
        $historial = AsistenciaSemanal::where('personal_id', $personal->id)
            ->whereBetween('fecha', [today()->subDays(30), today()])
            ->orderBy('fecha', 'desc')
            ->get();

        // Estadísticas del mes actual
        $asistenciasDelMes = AsistenciaSemanal::where('personal_id', $personal->id)
            ->delMes()
            ->get();

        $estadisticas = [
            'total_dias' => $asistenciasDelMes->count(),
            'dias_entrada' => $asistenciasDelMes->where('estado', 'entrada')->count(),
            'dias_salida' => $asistenciasDelMes->where('estado', 'salida')->count(),
            'dias_ausente' => $asistenciasDelMes->where('estado', 'ausente')->count(),
            'horas_trabajadas' => $asistenciasDelMes->sum(function ($asistencia) {
                return $asistencia->horasTrabajadas() ?? 0;
            }),
        ];

        return view('personal.asistencia.index', compact(
            'personal',
            'historial',
            'estadisticas'
        ));
    }

    /**
     * Registrar entrada del personal.
     */
    public function registrarEntrada(Request $request): RedirectResponse
    {
        $usuario = Auth::user();

        // Verificar que el usuario NO sea admin
        if ($usuario->rol && $usuario->rol->nombre === 'admin') {
            return redirect()->route('admin.asistencia.index')
                ->with('error', 'Los administradores no pueden registrar asistencia.');
        }

        $personal = $usuario->personal;

        if (!$personal) {
            return back()->with('error', 'No se encontró información del personal asociado.');
        }

        try {
            // Verificar si ya registró entrada hoy
            $asistenciaHoy = AsistenciaSemanal::obtenerAsistenciaHoy($personal->id);

            if ($asistenciaHoy) {
                return back()->with('warning', 'Ya registró su asistencia el día de hoy.');
            }

            // Registrar entrada
            AsistenciaSemanal::registrarEntrada(
                $personal->id,
                $request->input('observaciones')
            );

            return back()->with('success', 'Entrada registrada exitosamente a las ' . now()->format('H:i'));
        } catch (\Exception $e) {
            \Log::error('Error al registrar entrada: ' . $e->getMessage());

            return back()->with('error', 'Error al registrar entrada: ' . $e->getMessage());
        }
    }

    /**
     * Registrar salida del personal.
     */
    public function registrarSalida(Request $request): RedirectResponse
    {
        $usuario = Auth::user();

        // Verificar que el usuario NO sea admin
        if ($usuario->rol && $usuario->rol->nombre === 'admin') {
            return redirect()->route('admin.asistencia.index')
                ->with('error', 'Los administradores no pueden registrar asistencia.');
        }

        $personal = $usuario->personal;

        if (!$personal) {
            return back()->with('error', 'No se encontró información del personal asociado.');
        }

        try {
            // Verificar si ya registró entrada hoy
            $asistenciaHoy = AsistenciaSemanal::obtenerAsistenciaHoy($personal->id);

            if (!$asistenciaHoy) {
                return back()->with('error', 'Debe registrar su entrada primero.');
            }

            if ($asistenciaHoy->hora_salida) {
                return back()->with('warning', 'Ya registró su salida el día de hoy.');
            }

            // Registrar salida
            $asistenciaHoy->registrarSalida($request->input('observaciones'));

            $horasTrabajadas = $asistenciaHoy->fresh()->horasTrabajadas();

            return back()->with('success',
                'Salida registrada exitosamente a las ' . now()->format('H:i') .
                '. Horas trabajadas: ' . number_format($horasTrabajadas, 2) . ' hrs'
            );
        } catch (\Exception $e) {
            \Log::error('Error al registrar salida: ' . $e->getMessage());

            return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
        }
    }

    /**
     * Registrar ausencia del personal.
     */
    public function registrarAusencia(Request $request): RedirectResponse
    {
        $usuario = Auth::user();

        // Verificar que el usuario NO sea admin
        if ($usuario->rol && $usuario->rol->nombre === 'admin') {
            return redirect()->route('admin.asistencia.index')
                ->with('error', 'Los administradores no pueden registrar asistencia.');
        }

        $personal = $usuario->personal;

        if (!$personal) {
            return back()->with('error', 'No se encontró información del personal asociado.');
        }

        try {
            // Verificar si ya registró asistencia hoy
            $asistenciaHoy = AsistenciaSemanal::obtenerAsistenciaHoy($personal->id);

            if ($asistenciaHoy) {
                return back()->with('warning', 'Ya registró su asistencia el día de hoy.');
            }

            // Registrar ausencia
            AsistenciaSemanal::registrarAusencia(
                $personal->id,
                $request->input('observaciones')
            );

            return back()->with('success', 'Ausencia registrada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error al registrar ausencia: ' . $e->getMessage());

            return back()->with('error', 'Error al registrar ausencia: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar historial de asistencia del personal.
     */
    public function historial(Request $request): View
    {
        $usuario = Auth::user();

        // Verificar que el usuario NO sea admin
        if ($usuario->rol && $usuario->rol->nombre === 'admin') {
            return redirect()->route('admin.asistencia.index')
                ->with('info', 'Los administradores no registran asistencia. Use el panel de Asistencia Personal para ver las asistencias.');
        }

        $personal = $usuario->personal;

        if (!$personal) {
            abort(403, 'No se encontró información del personal asociado.');
        }

        $query = AsistenciaSemanal::where('personal_id', $personal->id);

        // Filtrar por rango de fechas si se proporciona
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [
                $request->fecha_inicio,
                $request->fecha_fin,
            ]);
        } else {
            // Por defecto, últimos 90 días
            $query->whereBetween('fecha', [today()->subDays(90), today()]);
        }

        $asistencias = $query->orderBy('fecha', 'desc')->paginate(20);

        return view('personal.asistencia.historial', compact('personal', 'asistencias'));
    }
}
