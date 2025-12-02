<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Control\SalidaProducto;

/**
 * Modelo de vehículo.
 *
 * @property int $id
 * @property string $placa
 * @property string|null $modelo
 * @property string $estado (activo|mantenimiento|inactivo)
 * @property int|null $capacidad
 * @property string|null $observacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Vehiculo extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'vehiculos';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'placa',
        'responsable',
        'modelo',
        'marca',
        'estado',
        'capacidad',
        'observacion',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'capacidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Verificar si el vehículo está disponible.
     */
    public function estaDisponible(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Verificar si el vehículo está en mantenimiento.
     */
    public function enMantenimiento(): bool
    {
        return $this->estado === 'mantenimiento';
    }

    /**
     * Scope: Filtrar vehículos activos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar vehículos disponibles (activos).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar vehículos en mantenimiento.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeEnMantenimiento($query)
    {
        return $query->where('estado', 'mantenimiento');
    }

    /**
     * Relación: Responsable del vehículo
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }

    /**
     * Relación: Salidas de productos que usaron este vehículo
     * Nota: Requiere que SalidaProducto tenga vehiculo_id
     * TODO: Requiere migración en control_salidas_productos
     */
    // public function salidas(): HasMany
    // {
    //     return $this->hasMany(SalidaProducto::class, 'vehiculo_id');
    // }
}
