<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

class ProduccionProducto extends Model
{
    protected $table = 'control_produccion_productos';

    protected $fillable = [
        'produccion_id',
        'producto_id',
        'cantidad',
        'unidad_medida',
    ];

    public function produccion()
    {
        return $this->belongsTo(ProduccionDiaria::class, 'produccion_id');
    }

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class);
    }
}
