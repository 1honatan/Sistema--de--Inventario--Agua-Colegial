<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

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
}
