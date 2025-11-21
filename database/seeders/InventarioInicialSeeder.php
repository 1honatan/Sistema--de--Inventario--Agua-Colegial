<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Inventario;
use App\Models\Usuario;

/**
 * Seeder para cargar stock inicial de inventario.
 *
 * Este seeder registra entradas de inventario para todos los productos activos
 * con cantidades iniciales realistas según el tipo de producto.
 */
class InventarioInicialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Iniciando carga de stock inicial...');

        // Obtener el primer usuario admin para asignar los movimientos
        $usuarioAdmin = Usuario::where('id_rol', 1)->first();

        if (!$usuarioAdmin) {
            $this->command->error('❌ No se encontró un usuario administrador. Por favor, ejecuta primero el seeder de usuarios.');
            return;
        }

        // Cantidades iniciales según el tipo de producto
        $stockInicial = [
            'Botellón 20L' => 150,
            'Agua en Bolsa 500ml' => 300,
            'Agua Natural 1L' => 200,
            'Agua Saborizada 500ml' => 150,
            'Agua Limón 500ml' => 120,
            'Gelatina' => 100,
            'Bolitos' => 250,
            'Bolo Grande' => 80,
            'Bolo Pequeño' => 100,
            'Hielo en Bolsa 3kg' => 75,
        ];

        $productosActivos = Producto::where('estado', 'activo')->get();

        if ($productosActivos->isEmpty()) {
            $this->command->warn('⚠️  No hay productos activos. Por favor, ejecuta primero el seeder de productos.');
            return;
        }

        $contador = 0;
        foreach ($productosActivos as $producto) {
            // Determinar cantidad inicial
            $cantidad = $stockInicial[$producto->nombre] ?? 100;

            // Registrar entrada de inventario inicial
            Inventario::create([
                'id_producto' => $producto->id,
                'tipo_movimiento' => 'entrada',
                'cantidad' => $cantidad,
                'origen' => 'Inventario Inicial',
                'destino' => 'Bodega Principal',
                'referencia' => 'STOCK-INICIAL-' . date('Ymd'),
                'id_usuario' => $usuarioAdmin->id,
                'fecha_movimiento' => now(),
                'observacion' => 'Carga inicial de stock en el sistema',
            ]);

            $contador++;
            $this->command->info("  ✓ {$producto->nombre}: {$cantidad} unidades");
        }

        $this->command->info("✅ Stock inicial cargado exitosamente para {$contador} productos");

        // Mostrar resumen del stock total
        $stockTotal = Inventario::where('tipo_movimiento', 'entrada')->sum('cantidad');
        $this->command->info("📦 Stock total en sistema: {$stockTotal} unidades");
    }
}
