<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * Tabla: usuarios
     * Descripción: Almacena los usuarios del sistema con autenticación y roles
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Nombre completo del usuario');
            $table->string('email', 100)->unique()->comment('Email para login');
            $table->string('password')->comment('Contraseña hasheada con bcrypt');
            $table->foreignId('id_rol')->constrained('roles')->onDelete('restrict')->comment('Rol del usuario');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->comment('Estado del usuario');
            $table->timestamp('ultimo_acceso')->nullable()->comment('Última vez que inició sesión');
            $table->rememberToken();
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('estado');
            $table->index('id_rol');
            $table->index('email');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
