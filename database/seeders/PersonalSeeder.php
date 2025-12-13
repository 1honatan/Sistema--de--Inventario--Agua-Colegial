<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personal;

class PersonalSeeder extends Seeder
{
    /**
     * Ejecutar los seeds de personal.
     */
    public function run(): void
    {
        $personalData = [
            [
                'nombre_completo' => 'Carlos Martínez',
                'email' => 'carlos.martinez@aguacolegial.com',
                'telefono' => '7890-1234',
                'cargo' => 'Supervisor de Producción',
                'area' => 'Producción',
                'estado' => 'activo',
                'tiene_acceso' => true,
            ],
            [
                'nombre_completo' => 'María López',
                'email' => 'maria.lopez@aguacolegial.com',
                'telefono' => '7123-4567',
                'cargo' => 'Encargada de Inventario',
                'area' => 'Almacén',
                'estado' => 'activo',
                'tiene_acceso' => true,
            ],
            [
                'nombre_completo' => 'José Ramírez',
                'email' => 'jose.ramirez@aguacolegial.com',
                'telefono' => '7234-5678',
                'cargo' => 'Chofer',
                'area' => 'Distribución',
                'estado' => 'activo',
                'tiene_acceso' => false,
            ],
            [
                'nombre_completo' => 'Ana Hernández',
                'email' => 'ana.hernandez@aguacolegial.com',
                'telefono' => '7345-6789',
                'cargo' => 'Operadora de Producción',
                'area' => 'Producción',
                'estado' => 'activo',
                'tiene_acceso' => false,
            ],
            [
                'nombre_completo' => 'Luis González',
                'email' => 'luis.gonzalez@aguacolegial.com',
                'telefono' => '7456-7890',
                'cargo' => 'Chofer',
                'area' => 'Distribución',
                'estado' => 'activo',
                'tiene_acceso' => false,
            ],
        ];

        foreach ($personalData as $data) {
            Personal::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
        }

        $this->command->info('✅ Personal creado exitosamente');
    }
}
