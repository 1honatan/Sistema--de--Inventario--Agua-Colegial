<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\Personal;

/**
 * Seeder para crear usuario de prueba para el módulo de producción.
 *
 * Credenciales:
 * - Email: produccion@aguacolegial.com
 * - Password: Produccion123
 */
class UsuarioProduccionSeeder extends Seeder
{
    /**
     * Ejecutar el seeder.
     */
    public function run(): void
    {
        // Buscar rol de producción
        $rolProduccion = Rol::where('nombre', 'produccion')->first();

        if (!$rolProduccion) {
            $this->command->error('❌ Error: El rol "produccion" no existe. Ejecute primero RolesSeeder');
            return;
        }

        // Crear o actualizar el personal
        $personal = Personal::firstOrCreate(
            ['email' => 'produccion@aguacolegial.com'],
            [
                'nombre_completo' => 'Personal de Producción',
                'telefono' => '3001234567',
                'cargo' => 'Supervisor de Producción',
                'area' => 'produccion',
                'estado' => 'activo',
                'tiene_acceso' => true,
            ]
        );

        // Crear o actualizar el usuario
        $usuario = Usuario::firstOrCreate(
            ['email' => 'produccion@aguacolegial.com'],
            [
                'nombre' => 'Personal de Producción',
                'password' => 'Produccion123', // Se hasheará automáticamente por el mutator
                'id_rol' => $rolProduccion->id,
                'id_personal' => $personal->id,
                'estado' => 'activo',
            ]
        );

        // Si ya existe, actualizar la contraseña
        if (!$usuario->wasRecentlyCreated) {
            $usuario->password = 'Produccion123';
            $usuario->id_personal = $personal->id;
            $usuario->save();
        }

        $this->command->info('✅ Usuario de producción creado exitosamente');
        $this->command->info('📧 Email: produccion@aguacolegial.com');
        $this->command->info('🔑 Password: Produccion123');
    }
}
