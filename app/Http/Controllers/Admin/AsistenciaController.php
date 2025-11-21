<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador de asistencia para administración.
 *
 * Permite al administrador visualizar las asistencias de todo el personal.
 */
class AsistenciaController extends Controller
{
    /**
     * Mostrar listado general de asistencias.
     */
    public function index(Request $request): View
    {
        // Obtener fecha de consulta
        $fechaConsulta = $request->filled('fecha') ? $request->fecha : today()->toDateString();

        // Obtener todo el personal activo
        $personalActivo = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        // Obtener asistencias registradas para la fecha
        $asistenciasRegistradas = Asistencia::with('personal')
            ->whereDate('fecha', $fechaConsulta)
            ->get()
            ->keyBy('id_personal');

        // Crear lista completa con ausentes
        $listaCompleta = $personalActivo->map(function ($empleado) use ($asistenciasRegistradas, $fechaConsulta) {
            if (isset($asistenciasRegistradas[$empleado->id])) {
                // Tiene registro de asistencia
                return $asistenciasRegistradas[$empleado->id];
            } else {
                // No tiene registro - marcar como ausente
                $ausente = new Asistencia();
                $ausente->id_personal = $empleado->id;
                $ausente->personal = $empleado;
                $ausente->fecha = $fechaConsulta;
                $ausente->estado = 'ausente';
                $ausente->hora_entrada = null;
                $ausente->hora_salida = null;
                $ausente->observaciones = 'Sin registro de asistencia';
                $ausente->es_ausente_sin_registro = true; // Flag para identificar
                return $ausente;
            }
        });

        // Filtrar por personal específico si se solicita
        if ($request->filled('id_personal')) {
            $listaCompleta = $listaCompleta->where('id_personal', $request->id_personal);
        }

        // Filtrar por estado si se solicita
        if ($request->filled('estado')) {
            $listaCompleta = $listaCompleta->where('estado', $request->estado);
        }

        // Estadísticas del día
        $totalPersonal = $personalActivo->count();
        $asistenciasDelDia = Asistencia::whereDate('fecha', $fechaConsulta)->get();

        $estadisticas = [
            'total_personal' => $totalPersonal,
            'total_registrados' => $asistenciasDelDia->count(),
            'presentes' => $asistenciasDelDia->whereIn('estado', ['entrada', 'salida'])->count(),
            'ausentes_sin_registro' => $totalPersonal - $asistenciasDelDia->count(),
            'ausentes_justificados' => $asistenciasDelDia->where('estado', 'ausente')->count(),
        ];

        return view('admin.asistencia.index', compact(
            'listaCompleta',
            'personalActivo',
            'estadisticas',
            'fechaConsulta'
        ));
    }

    /**
     * Ver asistencias de un personal específico.
     */
    public function verPorPersonal(Personal $personal, Request $request): View
    {
        $query = Asistencia::where('id_personal', $personal->id);

        // Filtrar por rango de fechas
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [
                $request->fecha_inicio,
                $request->fecha_fin,
            ]);
        } else {
            // Por defecto, mes actual
            $query->delMes();
        }

        $asistencias = $query->orderBy('fecha', 'desc')->paginate(30);

        // Estadísticas del personal
        $estadisticas = [
            'total_dias' => $asistencias->total(),
            'dias_entrada' => Asistencia::where('id_personal', $personal->id)
                ->delMes()
                ->where('estado', 'entrada')
                ->count(),
            'dias_salida' => Asistencia::where('id_personal', $personal->id)
                ->delMes()
                ->where('estado', 'salida')
                ->count(),
            'dias_ausente' => Asistencia::where('id_personal', $personal->id)
                ->delMes()
                ->where('estado', 'ausente')
                ->count(),
            'horas_trabajadas' => Asistencia::where('id_personal', $personal->id)
                ->delMes()
                ->get()
                ->sum(function ($asistencia) {
                    return $asistencia->horasTrabajadas() ?? 0;
                }),
        ];

        return view('admin.asistencia.ver_personal', compact(
            'personal',
            'asistencias',
            'estadisticas'
        ));
    }

    /**
     * Generar reporte de asistencias.
     */
    public function reporte(Request $request): View
    {
        $validado = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'id_personal' => ['nullable', 'exists:personal,id'],
        ]);

        $query = Asistencia::with('personal')
            ->whereBetween('fecha', [$validado['fecha_inicio'], $validado['fecha_fin']]);

        if (!empty($validado['id_personal'])) {
            $query->where('id_personal', $validado['id_personal']);
        }

        $asistencias = $query->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'asc')
            ->get();

        // Estadísticas del reporte
        $estadisticas = [
            'total_registros' => $asistencias->count(),
            'total_entradas' => $asistencias->where('estado', 'entrada')->count(),
            'total_salidas' => $asistencias->where('estado', 'salida')->count(),
            'total_ausencias' => $asistencias->where('estado', 'ausente')->count(),
            'total_horas_trabajadas' => $asistencias->sum(function ($asistencia) {
                return $asistencia->horasTrabajadas() ?? 0;
            }),
        ];

        $personal = Personal::where('estado', 'activo')
            ->orderBy('nombre_completo')
            ->get();

        return view('admin.asistencia.reporte', compact(
            'asistencias',
            'estadisticas',
            'validado',
            'personal'
        ));
    }
}
