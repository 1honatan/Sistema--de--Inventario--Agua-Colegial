<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\CanResetPassword;

class Usuario extends Authenticatable implements CanResetPassword
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'id_rol',
        'id_personal',
        'estado',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }

    public function tieneRol(string $nombreRol): bool
    {
        return $this->rol && $this->rol->nombre === $nombreRol;
    }

    public function esAdmin(): bool
    {
        return $this->tieneRol('admin');
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function nombreRol(): string
    {
        return $this->rol ? $this->rol->nombre : 'sin_rol';
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePorRol($query, string $nombreRol)
    {
        return $query->whereHas('rol', function ($q) use ($nombreRol) {
            $q->where('nombre', $nombreRol);
        });
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function actualizarUltimoAcceso(): void
    {
        $this->ultimo_acceso = now();
        $this->save();
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function username(): string
    {
        return 'email';
    }

    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
