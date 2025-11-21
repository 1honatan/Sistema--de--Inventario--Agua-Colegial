<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Personal;

class UsuariosSeeder extends Seeder
{
    /**
     * Ejecutar los seeds de usuarios.
     */
    public function run(): void
    {
        // Obtener roles
        $adminRol = Rol::where('nombre', 'admin')->first();
        $produccionRol = Rol::where('nombre', 'produccion')->first();
        $inventarioRol = Rol::where('nombre', 'inventario')->first();
        $despachoRol = Rol::where('nombre', 'despacho')->first();

        // Obtener personal con acceso
        $carlosMartinez = Personal::where('email', 'carlos.martinez@aguacolegial.com')->first();
        $mariaLopez = Personal::where('email', 'maria.lopez@aguacolegial.com')->first();

        // Usuarios de prueba
        $usuarios = [
            [
                'nombre' => 'Administrador',
                'email' => 'admin@colegial.com',
                'password' => 'admin123',
                'id_rol' => $adminRol->id,
                'id_personal' => null,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Carlos Martínez',
                'email' => 'carlos.martinez@colegial.com',
                'password' => 'produccion123',
                'id_rol' => $produccionRol->id,
                'id_personal' => $carlosMartinez?->id,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'María López',
                'email' => 'maria.lopez@colegial.com',
                'password' => 'inventario123',
                'id_rol' => $inventarioRol->id,
                'id_personal' => $mariaLopez?->id,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Usuario Despacho',
                'email' => 'despacho@colegial.com',
                'password' => 'despacho123',
                'id_rol' => $despachoRol->id,
                'id_personal' => null,
                'estado' => 'activo',
            ],
        ];

        foreach ($usuarios as $userData) {
            Usuario::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ Usuarios creados exitosamente');
        $this->command->info('');
        $this->command->info('Credenciales de acceso:');
        $this->command->info('-------------------------------------------');
        $this->command->info('Admin:      admin@colegial.com / admin123');
        $this->command->info('Producción: carlos.martinez@colegial.com / produccion123');
        $this->command->info('Inventario: maria.lopez@colegial.com / inventario123');
        $this->command->info('Despacho:   despacho@colegial.com / despacho123');
    }
}
