<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminAsignacion extends Model
{
    use HasFactory;

    protected $table = 'admin_asignaciones';

    protected $fillable = [
        'id_personal',
        'tipo_asignacion',
        'modulo',
        'id_referencia',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'descripcion',
        'observaciones',
        'asignado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Relación con Personal (empleado asignado)
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }

    /**
     * Relación con Usuario (quien asignó)
     */
    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'asignado_por');
    }

    /**
     * Relación con historial
     */
    public function historial(): HasMany
    {
        return $this->hasMany(AdminHistorialAsignacion::class, 'id_asignacion');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_asignacion', $tipo);
    }

    /**
     * Verificar si está vigente
     */
    public function estaVigente(): bool
    {
        if ($this->estado !== 'activa') {
            return false;
        }

        $hoy = now()->startOfDay();

        if ($this->fecha_inicio > $hoy) {
            return false;
        }

        if ($this->fecha_fin && $this->fecha_fin < $hoy) {
            return false;
        }

        return true;
    }
}
