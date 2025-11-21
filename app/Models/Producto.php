<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de producto.
 *
 * Tipos de producto: botellón, bolsa, saborizada, limón, gelatina, bolito, hielo
 *
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property string|null $imagen
 * @property int|null $id_tipo_producto
 * @property string $unidad_medida
 * @property string $estado (activo|inactivo)
 * @property \Carbon\Carbon $fecha_registro
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read TipoProducto|null $tipoProducto
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Produccion[] $producciones
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inventario[] $movimientosInventario
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AlertaStock[] $alertasStock
 */
class Producto extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'productos';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'nombre',
        'tipo',
        'imagen',
        'id_tipo_producto',
        'unidad_medida',
        'estado',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'fecha_registro' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un producto puede tener muchas producciones.
     */
    public function producciones(): HasMany
    {
        return $this->hasMany(Produccion::class, 'id_producto');
    }

    /**
     * Relación: Un producto puede tener muchos movimientos de inventario.
     */
    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(Inventario::class, 'id_producto');
    }

    /**
     * Relación: Un producto pertenece a un tipo de producto.
     */
    public function tipoProducto(): BelongsTo
    {
        return $this->belongsTo(TipoProducto::class, 'id_tipo_producto');
    }

    /**
     * Relación: Un producto puede tener muchas alertas de stock.
     */
    public function alertasStock(): HasMany
    {
        return $this->hasMany(AlertaStock::class, 'id_producto');
    }

    /**
     * Obtener nombre del tipo de producto.
     *
     * @return string Nombre del tipo o "Sin tipo" si no tiene asignado
     */
    public function nombreTipo(): string
    {
        return $this->tipoProducto->nombre ?? 'Sin tipo';
    }

    /**
     * Obtener stock disponible del producto.
     *
     * @return int Stock disponible (entradas - salidas)
     */
    public function stockDisponible(): int
    {
        return Inventario::stockDisponible($this->id);
    }

    /**
     * Verificar si el producto está activo.
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Scope: Filtrar productos activos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar por tipo de producto.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $tipo
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope: Filtrar productos con stock bajo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $umbral  Umbral de stock bajo (por defecto 10)
     */
    public function scopeConStockBajo($query, int $umbral = 10)
    {
        return $query->whereHas('movimientosInventario', function ($q) use ($umbral) {
            // NOTA: Esto requiere una subconsulta más compleja
            // Por ahora retornamos todos, se puede optimizar después
        });
    }
}
