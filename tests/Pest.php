<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class,
    RefreshDatabase::class
)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Crear un usuario autenticado con rol específico.
 *
 * @param  string  $rol  Nombre del rol (admin, produccion, inventario, despacho)
 * @return \App\Models\Usuario
 */
function autenticar(string $rol = 'admin'): \App\Models\Usuario
{
    $rolModel = \App\Models\Rol::firstOrCreate(['nombre' => $rol]);

    $usuario = \App\Models\Usuario::factory()->create([
        'id_rol' => $rolModel->id,
        'estado' => 'activo',
    ]);

    test()->actingAs($usuario);

    return $usuario;
}

/**
 * Crear datos de prueba básicos (roles, productos, empleados, etc.).
 */
function crearDatosPrueba(): void
{
    // Crear roles
    \App\Models\Rol::factory()->create(['nombre' => 'admin']);
    \App\Models\Rol::factory()->create(['nombre' => 'produccion']);
    \App\Models\Rol::factory()->create(['nombre' => 'inventario']);
    \App\Models\Rol::factory()->create(['nombre' => 'despacho']);

    // Crear productos de prueba
    \App\Models\Producto::factory()->count(5)->create();

    // Crear empleados de prueba
    \App\Models\Empleado::factory()->count(3)->create();

    // Crear vehículos de prueba
    \App\Models\Vehiculo::factory()->count(2)->create();
}
