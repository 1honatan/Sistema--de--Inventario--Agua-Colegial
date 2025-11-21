<?php

declare(strict_types=1);

use App\Models\Usuario;
use App\Models\Rol;

beforeEach(function () {
    // Crear roles necesarios
    Rol::factory()->create(['nombre' => 'admin']);
    Rol::factory()->create(['nombre' => 'produccion']);
});

/**
 * ===========================================
 * Tests de Validación de Usuarios
 * ===========================================
 */

test('admin puede crear usuario con datos válidos', function () {
    $admin = autenticar('admin');

    $datos = [
        'nombre_usuario' => 'nuevo@colegial.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_rol' => Rol::where('nombre', 'produccion')->first()->id,
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertRedirect(route('admin.usuarios.index'));
    $this->assertDatabaseHas('usuarios', [
        'nombre_usuario' => 'nuevo@colegial.com',
        'estado' => 'activo',
    ]);
});

test('falla crear usuario sin nombre_usuario', function () {
    autenticar('admin');

    $datos = [
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_rol' => Rol::first()->id,
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertSessionHasErrors(['nombre_usuario']);
});

test('falla crear usuario con nombre_usuario duplicado', function () {
    autenticar('admin');

    Usuario::factory()->create(['nombre_usuario' => 'existente@colegial.com']);

    $datos = [
        'nombre_usuario' => 'existente@colegial.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_rol' => Rol::first()->id,
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertSessionHasErrors(['nombre_usuario']);
});

test('falla crear usuario con contraseña menor a 6 caracteres', function () {
    autenticar('admin');

    $datos = [
        'nombre_usuario' => 'nuevo@colegial.com',
        'password' => '12345', // Solo 5 caracteres
        'password_confirmation' => '12345',
        'id_rol' => Rol::first()->id,
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertSessionHasErrors(['password']);
});

test('falla crear usuario con contraseñas que no coinciden', function () {
    autenticar('admin');

    $datos = [
        'nombre_usuario' => 'nuevo@colegial.com',
        'password' => 'password123',
        'password_confirmation' => 'password456',
        'id_rol' => Rol::first()->id,
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertSessionHasErrors(['password']);
});

test('falla crear usuario con rol inexistente', function () {
    autenticar('admin');

    $datos = [
        'nombre_usuario' => 'nuevo@colegial.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_rol' => 9999, // ID que no existe
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertSessionHasErrors(['id_rol']);
});

test('falla crear usuario con estado inválido', function () {
    autenticar('admin');

    $datos = [
        'nombre_usuario' => 'nuevo@colegial.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_rol' => Rol::first()->id,
        'estado' => 'estado_invalido',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertSessionHasErrors(['estado']);
});

test('usuario no admin no puede crear usuarios', function () {
    autenticar('produccion');

    $datos = [
        'nombre_usuario' => 'nuevo@colegial.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'id_rol' => Rol::first()->id,
        'estado' => 'activo',
    ];

    $response = $this->post(route('admin.usuarios.store'), $datos);

    $response->assertStatus(403);
});

test('admin puede actualizar usuario sin cambiar contraseña', function () {
    autenticar('admin');

    $usuario = Usuario::factory()->create(['nombre_usuario' => 'original@colegial.com']);

    $datos = [
        'nombre_usuario' => 'actualizado@colegial.com',
        'id_rol' => Rol::first()->id,
        'estado' => 'activo',
    ];

    $response = $this->put(route('admin.usuarios.update', $usuario), $datos);

    $response->assertRedirect(route('admin.usuarios.index'));
    $this->assertDatabaseHas('usuarios', [
        'id' => $usuario->id,
        'nombre_usuario' => 'actualizado@colegial.com',
    ]);
});

test('admin puede actualizar usuario y cambiar contraseña', function () {
    autenticar('admin');

    $usuario = Usuario::factory()->create();
    $passwordAntigua = $usuario->password;

    $datos = [
        'nombre_usuario' => $usuario->nombre_usuario,
        'password' => 'nueva_password123',
        'password_confirmation' => 'nueva_password123',
        'id_rol' => $usuario->id_rol,
        'estado' => 'activo',
    ];

    $response = $this->put(route('admin.usuarios.update', $usuario), $datos);

    $response->assertRedirect();

    $usuario->refresh();
    expect($usuario->password)->not->toBe($passwordAntigua);
});
