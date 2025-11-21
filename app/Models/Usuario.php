<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\CanResetPassword;

/**
 * Modelo de usuario del sistema con autenticación completa.
 *
 * @property int $id
 * @property string $nombre
 * @property string $email
 * @property string $password
 * @property int $id_rol
 * @property string $estado (activo|inactivo)
 * @property \Carbon\Carbon|null $ultimo_acceso
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Rol $rol
 * @property-read Personal|null $personal
 */
class Usuario extends Authenticatable implements CanResetPassword
{
    use Notifiable;

    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'usuarios';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'id_rol',
        'id_personal',
        'estado',
        'ultimo_acceso',
    ];

    /**
     * Atributos ocultos para serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación: Un usuario pertenece a un rol.
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    /**
     * Relación: Un usuario pertenece a un registro de personal.
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }

    /**
     * Verificar si el usuario tiene un rol específico.
     *
     * @param  string  $nombreRol  Nombre del rol a verificar
     */
    public function tieneRol(string $nombreRol): bool
    {
        return $this->rol && $this->rol->nombre === $nombreRol;
    }

    /**
     * Verificar si el usuario es administrador.
     */
    public function esAdmin(): bool
    {
        return $this->tieneRol('admin');
    }

    /**
     * Verificar si el usuario está activo.
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Obtener nombre del rol del usuario.
     */
    public function nombreRol(): string
    {
        return $this->rol ? $this->rol->nombre : 'sin_rol';
    }

    /**
     * Scope: Filtrar usuarios activos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Filtrar por rol.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $nombreRol
     */
    public function scopePorRol($query, string $nombreRol)
    {
        return $query->whereHas('rol', function ($q) use ($nombreRol) {
            $q->where('nombre', $nombreRol);
        });
    }

    /**
     * Mutator: Hashear la contraseña automáticamente.
     *
     * @param  string  $value
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    /**
     * Actualizar último acceso del usuario.
     */
    public function actualizarUltimoAcceso(): void
    {
        $this->ultimo_acceso = now();
        $this->save();
    }

    /**
     * Obtener el nombre del campo usado como identificador único.
     *
     * NOTA: Este método debe retornar 'id' para que Auth::id() funcione correctamente.
     * Para autenticación por email, usar getAuthIdentifierName() es incorrecto.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Obtener el nombre de la columna para autenticación (username field).
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Obtener el email para notificaciones de restablecimiento de contraseña.
     *
     * @return string
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }

    /**
     * Enviar notificación de restablecimiento de contraseña.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
