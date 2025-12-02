<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class FosaSeptica extends Model
{
    protected $table = 'control_fosa_septica';

    protected $fillable = [
        'fecha_limpieza',
        'tipo_fosa',
        'responsable',
        'detalle_trabajo',
        'empresa_contratada',
        'proxima_limpieza',
        'observaciones',
    ];

    protected $casts = [
        'fecha_limpieza' => 'date',
        'proxima_limpieza' => 'date',
    ];

    /**
     * Relación: Responsable de la limpieza de fosa séptica
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }
}
