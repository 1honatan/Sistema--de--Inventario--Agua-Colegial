<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personal extends Model
{
    protected $table = 'personal';

    protected $fillable = [
        'nombre_completo',
        'cedula',
        'email',
        'telefono',
        'direccion',
        'cargo',
        'area',
        'fecha_ingreso',
        'salario',
        'foto',
        'documento_garantia',
        'foto_licencia',
        'observaciones',
        'estado',
        'tiene_acceso',
    ];

    protected $casts = [
        'tiene_acceso' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id_personal');
    }

    public function producciones(): HasMany
    {
        return $this->hasMany(Produccion::class, 'id_personal');
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function tieneAccesoSistema(): bool
    {
        return $this->tiene_acceso && $this->usuario !== null;
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }

    public function scopeConAcceso($query)
    {
        return $query->where('tiene_acceso', true)->has('usuario');
    }

    public function scopeSinAcceso($query)
    {
        return $query->where('tiene_acceso', false);
    }

    public function scopePorCargo($query, string $cargo)
    {
        return $query->where('cargo', $cargo);
    }

    public function scopePorArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    public function rol(): ?string
    {
        return $this->usuario?->rol->nombre ?? null;
    }

    public function badgeEstado(): string
    {
        return $this->estado === 'activo'
            ? '<span class="badge badge-success">Activo</span>'
            : '<span class="badge badge-danger">Inactivo</span>';
    }

    public function badgeAcceso(): string
    {
        return $this->tiene_acceso
            ? '<span class="badge badge-primary">Sí</span>'
            : '<span class="badge badge-secondary">No</span>';
    }
}
