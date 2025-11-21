<?php

declare(strict_types=1);

use App\Models\Inventario;
use App\Models\Producto;

beforeEach(function () {
    crearDatosPrueba();
});

/**
 * ===========================================
 * Tests de Validación de Inventario
 * ===========================================
 */

test('usuario con rol inventario puede registrar entrada', function () {
    autenticar('inventario');

    $producto = Producto::factory()->create();

    $datos = [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 50,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
        'observacion' => 'Compra de proveedor',
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertRedirect(route('inventario.index'));
    $this->assertDatabaseHas('inventario', [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 50,
    ]);
});

test('usuario con rol inventario puede registrar salida con stock disponible', function () {
    autenticar('inventario');

    $producto = Producto::factory()->create();

    // Primero registrar entrada
    Inventario::create([
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 100,
        'fecha_movimiento' => now(),
    ]);

    // Luego registrar salida
    $datos = [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'salida',
        'cantidad' => 30,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
        'observacion' => 'Venta manual',
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertRedirect(route('inventario.index'));
    $this->assertDatabaseHas('inventario', [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'salida',
        'cantidad' => 30,
    ]);
});

test('falla registrar salida sin stock disponible', function () {
    autenticar('inventario');

    $producto = Producto::factory()->create();

    // Intentar salida sin stock
    $datos = [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'salida',
        'cantidad' => 50,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertSessionHasErrors(['cantidad']);
});

test('falla registrar salida mayor al stock disponible', function () {
    autenticar('inventario');

    $producto = Producto::factory()->create();

    // Registrar entrada de 100
    Inventario::create([
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 100,
        'fecha_movimiento' => now(),
    ]);

    // Intentar salida de 150
    $datos = [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'salida',
        'cantidad' => 150,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertSessionHasErrors(['cantidad']);
    expect($response->getSession()->get('errors')->first('cantidad'))
        ->toContain('Stock insuficiente');
});

test('calcula stock disponible correctamente', function () {
    $producto = Producto::factory()->create();

    Inventario::create([
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 100,
        'fecha_movimiento' => now(),
    ]);

    Inventario::create([
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 50,
        'fecha_movimiento' => now(),
    ]);

    Inventario::create([
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'salida',
        'cantidad' => 30,
        'fecha_movimiento' => now(),
    ]);

    $stockDisponible = Inventario::stockDisponible($producto->id);

    expect($stockDisponible)->toBe(120); // 100 + 50 - 30
});

test('falla registrar movimiento con cantidad cero', function () {
    autenticar('inventario');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 0,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertSessionHasErrors(['cantidad']);
});

test('falla registrar movimiento con tipo inválido', function () {
    autenticar('inventario');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'tipo_movimiento' => 'tipo_invalido',
        'cantidad' => 50,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertSessionHasErrors(['tipo_movimiento']);
});

test('falla registrar movimiento con fecha futura', function () {
    autenticar('inventario');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 50,
        'fecha_movimiento' => now()->addDay()->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertSessionHasErrors(['fecha_movimiento']);
});

test('usuario sin rol inventario no puede registrar movimiento', function () {
    autenticar('despacho');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 50,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertStatus(403);
});

test('stock disponible es cero para producto sin movimientos', function () {
    $producto = Producto::factory()->create();

    $stockDisponible = Inventario::stockDisponible($producto->id);

    expect($stockDisponible)->toBe(0);
});

test('observación puede ser opcional', function () {
    autenticar('inventario');

    $producto = Producto::factory()->create();

    $datos = [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 50,
        'fecha_movimiento' => today()->format('Y-m-d H:i:s'),
        // Sin observación
    ];

    $response = $this->post(route('inventario.movimiento.store'), $datos);

    $response->assertRedirect();
    $this->assertDatabaseHas('inventario', [
        'id_producto' => $producto->id,
        'cantidad' => 50,
        'observacion' => null,
    ]);
});
