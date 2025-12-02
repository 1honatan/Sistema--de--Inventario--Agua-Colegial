<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

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

    /**
     * Relación: Productos producidos en este turno
     */
    public function productos(): HasMany
    {
        return $this->hasMany(ProduccionProducto::class, 'produccion_id');
    }

    /**
     * Relación: Materiales utilizados en este turno
     */
    public function materiales(): HasMany
    {
        return $this->hasMany(ProduccionMaterial::class, 'produccion_id');
    }

    /**
     * Relación: Responsable del turno de producción
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }
}
