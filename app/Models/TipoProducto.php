<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de tipo de producto.
 *
 * @property int $id
 * @property string $nombre
 * @property string $codigo
 * @property string|null $descripcion
 * @property string $estado (activo|inactivo)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Producto[] $productos
 */
class TipoProducto extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'tipos_producto';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'estado',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un tipo de producto puede tener muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_tipo_producto');
    }

    /**
     * Verificar si el tipo de producto está activo.
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Scope: Filtrar tipos activos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Ordenar por nombre.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeOrdenadoPorNombre($query)
    {
        return $query->orderBy('nombre', 'asc');
    }

    /**
     * Obtener cantidad de productos asociados a este tipo.
     */
    public function cantidadProductos(): int
    {
        return $this->productos()->count();
    }

    /**
     * Obtener cantidad de productos activos asociados a este tipo.
     */
    public function cantidadProductosActivos(): int
    {
        return $this->productos()->where('estado', 'activo')->count();
    }
}
