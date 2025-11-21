<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para la tabla de tokens de restablecimiento de contraseña.
 *
 * Esta tabla almacena los tokens temporales generados cuando un usuario
 * solicita restablecer su contraseña.
 */
return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary()->comment('Email del usuario');
            $table->string('token')->comment('Token de restablecimiento (hasheado)');
            $table->timestamp('created_at')->nullable()->comment('Fecha de creación del token');

            // Índice para búsquedas rápidas
            $table->index('email');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
