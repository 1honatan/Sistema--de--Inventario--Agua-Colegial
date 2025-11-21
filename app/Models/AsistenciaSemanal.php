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
        'entrada_hora' => 'datetime:H:i',
        'salida_hora' => 'datetime:H:i',
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
}
