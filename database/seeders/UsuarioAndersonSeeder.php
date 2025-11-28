<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioAndersonSeeder extends Seeder
{
    /**
     * Seed usuario Anderson Aguilar - Encargado de Producción
     */
    public function run(): void
    {
        // 1. Buscar o crear el registro en Personal
        $personal = DB::table('personal')->where('nombre_completo', 'LIKE', '%Anderson%Aguilar%')->first();

        if (!$personal) {
            // Crear registro en personal si no existe
            $personalId = DB::table('personal')->insertGetId([
                'nombre_completo' => 'Anderson Aguilar',
                'cedula' => '0000000',
                'email' => 'anderson.aguilar@aguacolegial.com',
                'telefono' => '00000000',
                'cargo' => 'Encargado de Producción',
                'area' => 'Producción',
                'fecha_ingreso' => now(),
                'estado' => 'activo',
                'tiene_acceso' => true,
                'es_chofer' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("✅ Registro de personal creado: Anderson Aguilar (ID: {$personalId})");
        } else {
            $personalId = $personal->id;
            // Actualizar para que tenga acceso
            DB::table('personal')
                ->where('id', $personalId)
                ->update([
                    'tiene_acceso' => true,
                    'cargo' => 'Encargado de Producción',
                    'area' => 'Producción',
                    'updated_at' => now(),
                ]);
            $this->command->info("✅ Registro de personal actualizado: Anderson Aguilar (ID: {$personalId})");
        }

        // 2. Obtener el rol de producción
        $rolProduccion = DB::table('roles')->where('nombre', 'produccion')->first();

        if (!$rolProduccion) {
            $this->command->error("❌ Error: El rol 'produccion' no existe. Ejecute primero RolProduccionSeeder.");
            return;
        }

        // 3. Verificar si ya existe el usuario
        $usuarioExistente = DB::table('usuarios')->where('id_personal', $personalId)->first();

        if ($usuarioExistente) {
            $this->command->warn("⚠️  El usuario para Anderson Aguilar ya existe (ID: {$usuarioExistente->id})");
            $this->command->info("   Email: {$usuarioExistente->email}");
            return;
        }

        // 4. Crear usuario
        $usuarioId = DB::table('usuarios')->insertGetId([
            'nombre' => 'Anderson Aguilar',
            'email' => 'anderson.aguilar@aguacolegial.com',
            'password' => Hash::make('anderson123'), // Contraseña temporal
            'id_rol' => $rolProduccion->id,
            'id_personal' => $personalId,
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("✅ Usuario creado exitosamente!");
        $this->command->info("   ID: {$usuarioId}");
        $this->command->info("   Nombre: Anderson Aguilar");
        $this->command->info("   Email: anderson.aguilar@aguacolegial.com");
        $this->command->info("   Contraseña: anderson123");
        $this->command->info("   Rol: Producción");
        $this->command->warn("⚠️  IMPORTANTE: Cambie la contraseña después del primer inicio de sesión");
    }
}
