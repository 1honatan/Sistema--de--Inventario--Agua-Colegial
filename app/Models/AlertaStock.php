<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de alerta de stock bajo.
 *
 * @property int $id
 * @property int $id_producto
 * @property int $cantidad_minima
 * @property int|null $cantidad_actual
 * @property string $estado_alerta (activa|atendida|ignorada)
 * @property string $nivel_urgencia (baja|media|alta|critica)
 * @property \Carbon\Carbon $fecha_alerta
 * @property \Carbon\Carbon|null $fecha_atencion
 * @property string|null $observaciones
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Producto $producto
 */
class AlertaStock extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'alertas_stock';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'id_producto',
        'cantidad_minima',
        'cantidad_actual',
        'estado_alerta',
        'nivel_urgencia',
        'fecha_alerta',
        'fecha_atencion',
        'observaciones',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'cantidad_minima' => 'integer',
        'cantidad_actual' => 'integer',
        'fecha_alerta' => 'datetime',
        'fecha_atencion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Una alerta pertenece a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    /**
     * Generar alerta de stock bajo si es necesario.
     *
     * @param  Producto  $producto
     * @param  int  $cantidadMinima  Cantidad mínima de stock (por defecto 10)
     * @return self|null  Retorna la alerta creada o null si no es necesaria
     */
    public static function generarSiNecesario(Producto $producto, int $cantidadMinima = 10): ?self
    {
        // Obtener stock actual del producto
        $stockActual = Inventario::stockDisponible($producto->id);

        // Si el stock es suficiente, no generar alerta
        if ($stockActual >= $cantidadMinima) {
            return null;
        }

        // Verificar si ya existe una alerta activa para este producto
        $alertaExistente = self::where('id_producto', $producto->id)
            ->where('estado_alerta', 'activa')
            ->first();

        // Si ya existe una alerta activa, no crear duplicados
        if ($alertaExistente) {
            // Actualizar la cantidad actual de la alerta existente
            $alertaExistente->update([
                'cantidad_actual' => $stockActual,
                'nivel_urgencia' => self::determinarNivelUrgencia($stockActual, $cantidadMinima),
            ]);

            return $alertaExistente;
        }

        // Crear nueva alerta
        return self::create([
            'id_producto' => $producto->id,
            'cantidad_minima' => $cantidadMinima,
            'cantidad_actual' => $stockActual,
            'estado_alerta' => 'activa',
            'nivel_urgencia' => self::determinarNivelUrgencia($stockActual, $cantidadMinima),
            'fecha_alerta' => now(),
        ]);
    }

    /**
     * Determinar nivel de urgencia según stock actual y mínimo.
     *
     * @param  int  $stockActual
     * @param  int  $cantidadMinima
     * @return string (baja|media|alta|critica)
     */
    protected static function determinarNivelUrgencia(int $stockActual, int $cantidadMinima): string
    {
        $porcentaje = ($stockActual / $cantidadMinima) * 100;

        if ($stockActual <= 0) {
            return 'critica'; // Stock agotado
        }

        if ($porcentaje < 25) {
            return 'alta'; // Menos del 25% del mínimo
        }

        if ($porcentaje < 50) {
            return 'media'; // Entre 25% y 50% del mínimo
        }

        return 'baja'; // Entre 50% y 100% del mínimo
    }

    /**
     * Marcar alerta como atendida.
     *
     * @param  string|null  $observaciones
     * @return bool
     */
    public function marcarComoAtendida(?string $observaciones = null): bool
    {
        return $this->update([
            'estado_alerta' => 'atendida',
            'fecha_atencion' => now(),
            'observaciones' => $observaciones ?? $this->observaciones,
        ]);
    }

    /**
     * Marcar alerta como ignorada.
     *
     * @param  string|null  $observaciones
     * @return bool
     */
    public function marcarComoIgnorada(?string $observaciones = null): bool
    {
        return $this->update([
            'estado_alerta' => 'ignorada',
            'fecha_atencion' => now(),
            'observaciones' => $observaciones ?? $this->observaciones,
        ]);
    }

    /**
     * Verificar si la alerta está activa.
     */
    public function estaActiva(): bool
    {
        return $this->estado_alerta === 'activa';
    }

    /**
     * Scope: Filtrar alertas activas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActivas($query)
    {
        return $query->where('estado_alerta', 'activa');
    }

    /**
     * Scope: Filtrar por nivel de urgencia.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $nivel
     */
    public function scopePorNivelUrgencia($query, string $nivel)
    {
        return $query->where('nivel_urgencia', $nivel);
    }

    /**
     * Scope: Ordenar por urgencia (crítica primero).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeOrdenadoPorUrgencia($query)
    {
        return $query->orderByRaw("FIELD(nivel_urgencia, 'critica', 'alta', 'media', 'baja')");
    }

    /**
     * Obtener color CSS para el nivel de urgencia.
     */
    public function colorNivelUrgencia(): string
    {
        return match ($this->nivel_urgencia) {
            'critica' => 'bg-red-600 text-white',
            'alta' => 'bg-orange-500 text-white',
            'media' => 'bg-yellow-500 text-gray-900',
            'baja' => 'bg-blue-500 text-white',
            default => 'bg-gray-500 text-white',
        };
    }

    /**
     * Obtener ícono FontAwesome para el nivel de urgencia.
     */
    public function iconoNivelUrgencia(): string
    {
        return match ($this->nivel_urgencia) {
            'critica' => 'fa-solid fa-circle-exclamation',
            'alta' => 'fa-solid fa-triangle-exclamation',
            'media' => 'fa-solid fa-exclamation',
            'baja' => 'fa-solid fa-info-circle',
            default => 'fa-solid fa-bell',
        };
    }
}
