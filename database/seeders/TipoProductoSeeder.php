<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TipoProducto;
use Illuminate\Database\Seeder;

/**
 * Seeder de tipos de producto iniciales.
 *
 * Crea los tipos de producto básicos para el sistema Agua Colegial.
 */
class TipoProductoSeeder extends Seeder
{
    /**
     * Ejecutar el seeder.
     */
    public function run(): void
    {
        $tiposProducto = [
            [
                'nombre' => 'Botellón',
                'codigo' => 'BOT',
                'descripcion' => 'Botellones de agua de 20L',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Bolsa',
                'codigo' => 'BOL',
                'descripcion' => 'Agua en bolsa de 500ml',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Saborizada',
                'codigo' => 'SAB',
                'descripcion' => 'Agua con sabor artificial',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Limón',
                'codigo' => 'LIM',
                'descripcion' => 'Agua sabor limón',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Gelatina',
                'codigo' => 'GEL',
                'descripcion' => 'Postres de gelatina',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Bolito',
                'codigo' => 'BTO',
                'descripcion' => 'Postres congelados tipo bolito',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Hielo',
                'codigo' => 'HIE',
                'descripcion' => 'Hielo en bolsa de 3kg',
                'estado' => 'activo',
            ],
        ];

        foreach ($tiposProducto as $tipo) {
            TipoProducto::firstOrCreate(
                ['codigo' => $tipo['codigo']], // Buscar por código
                $tipo // Crear con todos los datos si no existe
            );
        }

        $this->command->info('✅ Tipos de producto creados exitosamente');
    }
}
