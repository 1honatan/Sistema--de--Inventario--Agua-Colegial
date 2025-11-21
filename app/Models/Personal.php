<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Personal.
 *
 * Gestiona tanto la información operativa como las credenciales de acceso.
 *
 * @property int $id
 * @property string $nombre_completo
 * @property string $email
 * @property string|null $telefono
 * @property string $cargo
 * @property string $area
 * @property string $estado (activo|inactivo)
 * @property bool $tiene_acceso
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Usuario|null $usuario
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Produccion[] $producciones
 */
class Personal extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'personal';

    /**
     * Atributos asignables en masa.
     */
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
        'foto_documento',
        'foto_licencia',
        'foto_id_chofer',
        'observaciones',
        'es_chofer',
        'estado',
        'tiene_acceso',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'es_chofer' => 'boolean',
        'tiene_acceso' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un personal puede tener un usuario (si tiene_acceso = true).
     */
    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class, 'id_personal');
    }

    /**
     * Relación: Un personal puede tener muchas producciones.
     * Nota: La tabla produccion usa id_personal.
     */
    public function producciones(): HasMany
    {
        return $this->hasMany(Produccion::class, 'id_personal');
    }

    /**
     * Verificar si el personal está activo.
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Verificar si el personal tiene acceso al sistema.
     */
    public function tieneAccesoSistema(): bool
    {
        return $this->tiene_acceso && $this->usuario !== null;
    }

    /**
     * Scope: Filtrar personal activo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar personal inactivo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }

    /**
     * Scope: Filtrar personal con acceso al sistema.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeConAcceso($query)
    {
        return $query->where('tiene_acceso', true)->has('usuario');
    }

    /**
     * Scope: Filtrar personal sin acceso al sistema.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeSinAcceso($query)
    {
        return $query->where('tiene_acceso', false);
    }

    /**
     * Scope: Filtrar por cargo.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $cargo
     */
    public function scopePorCargo($query, string $cargo)
    {
        return $query->where('cargo', $cargo);
    }

    /**
     * Scope: Filtrar por área.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $area
     */
    public function scopePorArea($query, string $area)
    {
        return $query->where('area', $area);
    }

    /**
     * Obtener el rol del usuario asociado (si existe).
     */
    public function rol(): ?string
    {
        return $this->usuario?->rol->nombre ?? null;
    }

    /**
     * Obtener badge HTML según el estado.
     */
    public function badgeEstado(): string
    {
        return $this->estado === 'activo'
            ? '<span class="badge badge-success">Activo</span>'
            : '<span class="badge badge-danger">Inactivo</span>';
    }

    /**
     * Obtener badge HTML según tiene_acceso.
     */
    public function badgeAcceso(): string
    {
        return $this->tiene_acceso
            ? '<span class="badge badge-primary">Sí</span>'
            : '<span class="badge badge-secondary">No</span>';
    }
}
