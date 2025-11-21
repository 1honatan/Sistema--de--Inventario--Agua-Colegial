<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

class ProduccionMaterial extends Model
{
    protected $table = 'control_produccion_materiales';

    protected $fillable = [
        'produccion_id',
        'nombre_material',
        'cantidad',
        'unidad_medida',
    ];

    public function produccion()
    {
        return $this->belongsTo(ProduccionDiaria::class, 'produccion_id');
    }
}
