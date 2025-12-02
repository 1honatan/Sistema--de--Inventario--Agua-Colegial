<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ActualizarUnidadesPorPaqueteSeeder extends Seeder
{
    /**
     * Actualizar las unidades por paquete de los productos existentes.
     */
    public function run(): void
    {
        // Configuración de unidades por paquete para cada producto
        $configuracion = [
            'Agua Saborizada' => 10,         // 10 unidades por paquete
            'Agua De Limon' => 10,           // 10 unidades por paquete
            'Agua Natural' => 10,            // 10 unidades por paquete
            'Gelatina' => 25,                // 25 gelatinas por paquete
            'Bolo Grande' => 50,             // 50 bolos por paquete
            'Bolo Pequeño' => 25,            // 25 bolos por paquete
            // Botellón: null (se venden individualmente)
            // Hielo en Bolsa: null (se vende por peso)
            // Dispenser: null (se vende individualmente)
        ];

        foreach ($configuracion as $nombreProducto => $unidadesPorPaquete) {
            $producto = Producto::where('nombre', $nombreProducto)->first();

            if ($producto) {
                $producto->update(['unidades_por_paquete' => $unidadesPorPaquete]);
                $this->command->info("✅ Actualizado: {$nombreProducto} - {$unidadesPorPaquete} unidades por paquete");
            } else {
                $this->command->warn("⚠️  Producto no encontrado: {$nombreProducto}");
            }
        }

        $this->command->info('✅ Unidades por paquete actualizadas exitosamente');
    }
}
