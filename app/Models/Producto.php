<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'imagen',
        'unidad_medida',
        'unidades_por_paquete',
        'stock_minimo',
        'estado',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'stock_minimo' => 'integer',
    ];

    public function producciones(): HasMany
    {
        return $this->hasMany(Produccion::class, 'id_producto');
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(Inventario::class, 'id_producto');
    }

    public function alertasStock(): HasMany
    {
        return $this->hasMany(AlertaStock::class, 'id_producto');
    }

    public function tipoProducto(): BelongsTo
    {
        return $this->belongsTo(TipoProducto::class, 'id_tipo_producto');
    }

    public function nombreTipo(): string
    {
        return $this->tipo ?? 'Sin tipo';
    }

    public function stockDisponible(): int
    {
        return Inventario::stockDisponible($this->id);
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeConStockBajo($query, int $umbral = 10)
    {
        return $query->whereHas('movimientosInventario', function ($q) use ($umbral) {
            // Subconsulta pendiente de optimizar
        });
    }
}
