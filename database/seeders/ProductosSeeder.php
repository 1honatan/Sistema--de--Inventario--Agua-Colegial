<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductosSeeder extends Seeder
{
    /**
     * Ejecutar los seeds de productos.
     */
    public function run(): void
    {
        $productos = [
            // Botellones
            [
                'nombre' => 'Botellón 20L',
                'tipo' => 'botellón',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Agua en bolsa
            [
                'nombre' => 'Agua en Bolsa 500ml',
                'tipo' => 'bolsa',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Agua natural
            [
                'nombre' => 'Agua Natural 1L',
                'tipo' => 'agua_natural',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Agua saborizada
            [
                'nombre' => 'Agua Saborizada 500ml',
                'tipo' => 'agua_saborizada',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Agua sabor limón
            [
                'nombre' => 'Agua Limón 500ml',
                'tipo' => 'agua_limon',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Gelatinas
            [
                'nombre' => 'Gelatina',
                'tipo' => 'gelatina',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Bolitos
            [
                'nombre' => 'Bolitos',
                'tipo' => 'bolito',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],

            // Hielos
            [
                'nombre' => 'Hielo en Bolsa 3kg',
                'tipo' => 'hielo',
                'unidad_medida' => 'kilogramo',
                'estado' => 'activo',
            ],

            // Bolos
            [
                'nombre' => 'Bolo Grande',
                'tipo' => 'bolo_grande',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Bolo Pequeño',
                'tipo' => 'bolo_pequeño',
                'unidad_medida' => 'unidad',
                'estado' => 'activo',
            ],
        ];

        foreach ($productos as $productoData) {
            Producto::firstOrCreate(
                ['nombre' => $productoData['nombre']],
                $productoData
            );
        }

        $this->command->info('✅ Productos creados exitosamente');
    }
}
