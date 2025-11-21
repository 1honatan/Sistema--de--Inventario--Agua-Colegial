<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    /**
     * Ejecutar los seeds de roles.
     */
    public function run(): void
    {
        $roles = [
            ['nombre' => 'admin', 'observacion' => 'Administrador del sistema'],
            ['nombre' => 'produccion', 'observacion' => 'Encargado de producción'],
            ['nombre' => 'inventario', 'observacion' => 'Encargado de inventario'],
            ['nombre' => 'despacho', 'observacion' => 'Encargado de despachos'],
        ];

        foreach ($roles as $rol) {
            Rol::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }

        $this->command->info('✅ Roles creados exitosamente');
    }
}
