<?php

declare(strict_types=1);

use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Empleado;
use App\Models\Inventario;

beforeEach(function () {
    crearDatosPrueba();
});

/**
 * ===========================================
 * Tests de Validación de Producción
 * ===========================================
 */

test('usuario con rol produccion puede registrar producción válida', function () {
    autenticar('produccion');

    $producto = Producto::factory()->create();
    $empleado = Empleado::factory()->create();

    $datos = [
        'id_producto' => $producto->id,
        'id_empleado' => $empleado->id,
        'cantidad' => 100,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertRedirect(route('produccion.index'));
    $this->assertDatabaseHas('produccion', [
        'id_producto' => $producto->id,
        'cantidad' => 100,
    ]);
});

test('producción genera lote único automáticamente', function () {
    autenticar('produccion');

    $producto = Producto::factory()->create();
    $empleado = Empleado::factory()->create();

    $datos = [
        'id_producto' => $producto->id,
        'id_empleado' => $empleado->id,
        'cantidad' => 50,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $this->post(route('produccion.store'), $datos);

    $produccion = Produccion::latest()->first();

    expect($produccion->lote)
        ->toStartWith('PROD-')
        ->toContain(date('Ymd'));
});

test('producción registra entrada automática en inventario', function () {
    autenticar('produccion');

    $producto = Producto::factory()->create();
    $empleado = Empleado::factory()->create();

    $datos = [
        'id_producto' => $producto->id,
        'id_empleado' => $empleado->id,
        'cantidad' => 75,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $this->post(route('produccion.store'), $datos);

    $this->assertDatabaseHas('inventario', [
        'id_producto' => $producto->id,
        'tipo_movimiento' => 'entrada',
        'cantidad' => 75,
    ]);
});

test('falla registrar producción con cantidad cero', function () {
    autenticar('produccion');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'id_empleado' => Empleado::factory()->create()->id,
        'cantidad' => 0,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertSessionHasErrors(['cantidad']);
});

test('falla registrar producción con cantidad negativa', function () {
    autenticar('produccion');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'id_empleado' => Empleado::factory()->create()->id,
        'cantidad' => -10,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertSessionHasErrors(['cantidad']);
});

test('falla registrar producción con fecha futura', function () {
    autenticar('produccion');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'id_empleado' => Empleado::factory()->create()->id,
        'cantidad' => 50,
        'fecha_produccion' => now()->addDays(1)->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertSessionHasErrors(['fecha_produccion']);
});

test('falla registrar producción con producto inexistente', function () {
    autenticar('produccion');

    $datos = [
        'id_producto' => 9999,
        'id_empleado' => Empleado::factory()->create()->id,
        'cantidad' => 50,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertSessionHasErrors(['id_producto']);
});

test('falla registrar producción con empleado inexistente', function () {
    autenticar('produccion');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'id_empleado' => 9999,
        'cantidad' => 50,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertSessionHasErrors(['id_empleado']);
});

test('usuario sin rol produccion no puede registrar producción', function () {
    autenticar('inventario');

    $datos = [
        'id_producto' => Producto::factory()->create()->id,
        'id_empleado' => Empleado::factory()->create()->id,
        'cantidad' => 50,
        'fecha_produccion' => today()->format('Y-m-d'),
    ];

    $response = $this->post(route('produccion.store'), $datos);

    $response->assertStatus(403);
});

test('lotes generados son únicos incluso con múltiples registros simultáneos', function () {
    autenticar('produccion');

    $producto = Producto::factory()->create();
    $empleado = Empleado::factory()->create();

    // Crear 3 producciones en el mismo día
    for ($i = 0; $i < 3; $i++) {
        $this->post(route('produccion.store'), [
            'id_producto' => $producto->id,
            'id_empleado' => $empleado->id,
            'cantidad' => 10 + $i,
            'fecha_produccion' => today()->format('Y-m-d'),
        ]);
    }

    $lotes = Produccion::pluck('lote')->toArray();

    // Verificar que todos los lotes son únicos
    expect(count($lotes))->toBe(count(array_unique($lotes)));
});
