<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class Fumigacion extends Model
{
    protected $table = 'control_fumigacion';

    protected $fillable = [
        'fecha_fumigacion',
        'area_fumigada',
        'producto_utilizado',
        'cantidad_producto',
        'responsable',
        'empresa_contratada',
        'proxima_fumigacion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_fumigacion' => 'date',
        'proxima_fumigacion' => 'date',
        'cantidad_producto' => 'decimal:2',
    ];

    /**
     * Relación: Responsable de la fumigación
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }
}
