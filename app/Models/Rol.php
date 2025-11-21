<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de roles del sistema.
 *
 * Roles disponibles:
 * - admin: Acceso total al sistema
 * - produccion: Módulo de producción
 * - inventario: Módulo de inventario
 * - despacho: Módulo de despachos
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $observacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Rol extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'roles';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'nombre',
        'observacion',
    ];

    /**
     * Relación: Un rol tiene muchos usuarios.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }

    /**
     * Verificar si el rol es administrador.
     */
    public function esAdmin(): bool
    {
        return $this->nombre === 'admin';
    }
}
