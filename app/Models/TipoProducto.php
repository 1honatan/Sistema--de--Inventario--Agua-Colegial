<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoProducto extends Model
{
    protected $table = 'tipos_producto';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_tipo_producto');
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeOrdenadoPorNombre($query)
    {
        return $query->orderBy('nombre', 'asc');
    }

    public function cantidadProductos(): int
    {
        return $this->productos()->count();
    }

    public function cantidadProductosActivos(): int
    {
        return $this->productos()->where('estado', 'activo')->count();
    }
}
