<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class TanqueAgua extends Model
{
    protected $table = 'control_tanques_agua';

    protected $fillable = [
        'fecha_limpieza',
        'nombre_tanque',
        'capacidad_litros',
        'procedimiento_limpieza',
        'productos_desinfeccion',
        'responsable',
        'supervisado_por',
        'proxima_limpieza',
        'observaciones',
    ];

    protected $casts = [
        'fecha_limpieza' => 'date',
        'proxima_limpieza' => 'date',
        'capacidad_litros' => 'decimal:2',
    ];

    /**
     * Relación: Responsable de la limpieza del tanque
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }

    /**
     * Relación: Supervisor de la limpieza
     * Nota: Actualmente 'supervisado_por' es un string.
     * TODO: Migrar a supervisado_por_id (foreignId a personal)
     */
    // public function supervisor(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'supervisado_por_id');
    // }
}
