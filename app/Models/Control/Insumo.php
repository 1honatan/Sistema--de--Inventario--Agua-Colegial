<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'control_insumos';

    protected $fillable = [
        'fecha',
        'producto_insumo',
        'cantidad',
        'unidad_medida',
        'numero_lote',
        'fecha_vencimiento',
        'responsable',
        'proveedor',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_vencimiento' => 'date',
        'cantidad' => 'decimal:2',
    ];
}
