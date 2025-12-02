<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AsistenciaSemanal extends Model
{
    protected $table = 'asistencias_semanales';

    protected $fillable = [
        'personal_id',
        'fecha',
        'dia_semana',
        'entrada_hora',
        'salida_hora',
        'observaciones',
        'estado',
        'registrado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Relación con el personal
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    /**
     * Relación con el usuario que registró
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'registrado_por');
    }

    /**
     * Calcular horas trabajadas
     */
    public function getHorasTrabajadasAttribute(): ?float
    {
        if (!$this->entrada_hora || !$this->salida_hora) {
            return null;
        }

        $entrada = Carbon::parse($this->entrada_hora);
        $salida = Carbon::parse($this->salida_hora);

        return $entrada->diffInHours($salida, true);
    }

    /**
     * Obtener el día de la semana en español
     */
    public static function obtenerDiaSemana(Carbon $fecha): string
    {
        $dias = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];

        return $dias[$fecha->dayOfWeek];
    }

    /**
     * Scope para filtrar por semana
     */
    public function scopePorSemana($query, Carbon $fecha)
    {
        $inicioSemana = $fecha->copy()->startOfWeek();
        $finSemana = $fecha->copy()->endOfWeek();

        return $query->whereBetween('fecha', [$inicioSemana, $finSemana]);
    }

    /**
     * Scope para filtrar por personal
     */
    public function scopePorPersonal($query, $personalId)
    {
        return $query->where('personal_id', $personalId);
    }

    /**
     * Scope para filtrar por mes actual
     */
    public function scopeDelMes($query)
    {
        return $query->whereBetween('fecha', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    /**
     * Calcular horas trabajadas total
     */
    public function horasTrabajadas(): float
    {
        if (!$this->entrada_hora || !$this->salida_hora) {
            return 0;
        }

        // Parsear entrada y salida directamente
        $entrada = Carbon::parse($this->entrada_hora);
        $salida = Carbon::parse($this->salida_hora);

        return $entrada->diffInHours($salida, true);
    }

    /**
     * Obtener asistencia de hoy para un personal específico
     */
    public static function obtenerAsistenciaHoy($personalId)
    {
        return self::where('personal_id', $personalId)
            ->whereDate('fecha', today())
            ->first();
    }

    /**
     * Registrar entrada de asistencia
     */
    public static function registrarEntrada($personalId, $observaciones = null)
    {
        return self::updateOrCreate(
            [
                'personal_id' => $personalId,
                'fecha' => today(),
            ],
            [
                'dia_semana' => self::obtenerDiaSemana(now()),
                'entrada_hora' => now()->format('H:i'),
                'observaciones' => $observaciones,
                'estado' => 'presente',
            ]
        );
    }

    /**
     * Registrar salida de asistencia
     */
    public static function registrarSalida($personalId)
    {
        $asistencia = self::where('personal_id', $personalId)
            ->whereDate('fecha', today())
            ->first();

        if ($asistencia) {
            $asistencia->update([
                'salida_hora' => now()->format('H:i'),
            ]);
        }

        return $asistencia;
    }

    /**
     * Registrar ausencia
     */
    public static function registrarAusencia($personalId, $tipo, $observaciones = null)
    {
        return self::updateOrCreate(
            [
                'personal_id' => $personalId,
                'fecha' => today(),
            ],
            [
                'dia_semana' => self::obtenerDiaSemana(now()),
                'estado' => $tipo, // 'ausencia', 'permiso', 'enfermedad'
                'observaciones' => $observaciones,
            ]
        );
    }
}
