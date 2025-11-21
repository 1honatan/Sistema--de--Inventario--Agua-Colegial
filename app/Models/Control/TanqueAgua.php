<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

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
}
