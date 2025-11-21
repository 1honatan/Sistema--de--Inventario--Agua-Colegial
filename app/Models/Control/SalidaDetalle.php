<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

class SalidaDetalle extends Model
{
    protected $table = 'control_salida_detalles';

    protected $fillable = [
        'salida_id',
        'producto_id',
        'cantidad',
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaProducto::class, 'salida_id');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class);
    }
}
