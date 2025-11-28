<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolProduccionSeeder extends Seeder
{
    /**
     * Seed roles del sistema.
     */
    public function run(): void
    {
        // Verificar si ya existen los roles
        $rolesExistentes = DB::table('roles')->pluck('nombre')->toArray();

        $roles = [
            [
                'nombre' => 'admin',
                'observacion' => 'Acceso total al sistema - Administrador',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'produccion',
                'observacion' => 'Acceso a módulos de producción, control e inventario',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $rol) {
            if (!in_array($rol['nombre'], $rolesExistentes)) {
                DB::table('roles')->insert($rol);
                $this->command->info("✅ Rol '{$rol['nombre']}' creado");
            } else {
                $this->command->info("⚠️  Rol '{$rol['nombre']}' ya existe");
            }
        }
    }
}
