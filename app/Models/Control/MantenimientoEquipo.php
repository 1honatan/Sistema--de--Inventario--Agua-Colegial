<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class MantenimientoEquipo extends Model
{
    protected $table = 'control_mantenimiento_equipos';

    protected $fillable = [
        'fecha',
        'equipo',
        'id_personal',
        'detalle_mantenimiento',
        'productos_limpieza',
        'proxima_fecha',
        'realizado_por',
        'supervisado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
        'proxima_fecha' => 'date',
        'equipo' => 'array',
        'productos_limpieza' => 'array',
    ];

    /**
     * Relación con Personal (quien realizó el mantenimiento)
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }
}
