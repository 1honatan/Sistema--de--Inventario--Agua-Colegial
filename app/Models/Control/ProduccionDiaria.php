<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

class ProduccionDiaria extends Model
{
    protected $table = 'control_produccion_diaria';

    protected $fillable = [
        'fecha',
        'responsable',
        'turno',
        'preparacion',
        'rollos_material',
        'gasto_material',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(ProduccionProducto::class, 'produccion_id');
    }

    public function materiales()
    {
        return $this->hasMany(ProduccionMaterial::class, 'produccion_id');
    }
}
