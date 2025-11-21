<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecutar los database seeds.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PersonalSeeder::class,
            UsuariosSeeder::class,
            ProductosSeeder::class,
        ]);
    }
}
