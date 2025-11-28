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
        'stock_actual',
        'stock_minimo',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_vencimiento' => 'date',
        'cantidad' => 'decimal:2',
        'stock_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
    ];

    protected $attributes = [
        'stock_actual' => 0,
        'stock_minimo' => 0,
    ];
}
