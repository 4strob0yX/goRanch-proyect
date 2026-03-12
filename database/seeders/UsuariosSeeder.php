<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        // -----------------------------------------------
        // Super Admin
        // -----------------------------------------------
        Usuario::create([
            'nombre'   => 'Admin goRanch',
            'email'    => 'admin@goranch.com',
            'telefono' => '5500000000',
            'password' => Hash::make('admin1234'),
            'rol'      => 'super_admin',
            'estatus'  => 'activo',
        ]);

        // -----------------------------------------------
        // Clientes de prueba
        // -----------------------------------------------
        $clientes = [
            ['nombre' => 'María López',    'email' => 'maria@prueba.com',    'telefono' => '5511111111'],
            ['nombre' => 'Juan Pérez',     'email' => 'juan@prueba.com',     'telefono' => '5522222222'],
            ['nombre' => 'Ana Martínez',   'email' => 'ana@prueba.com',      'telefono' => '5533333333'],
            ['nombre' => 'Carlos Ruiz',    'email' => 'carlos@prueba.com',   'telefono' => '5544444444'],
            ['nombre' => 'Laura Gómez',    'email' => 'laura@prueba.com',    'telefono' => '5555555555'],
        ];

        foreach ($clientes as $c) {
            Usuario::create([
                'nombre'   => $c['nombre'],
                'email'    => $c['email'],
                'telefono' => $c['telefono'],
                'password' => Hash::make('password123'),
                'rol'      => 'usuario',
                'estatus'  => 'activo',
            ]);
        }

        $this->command->info('✅ 1 admin + 5 clientes creados.');
        $this->command->info('   Admin: admin@goranch.com / admin1234');
        $this->command->info('   Clientes: password123');
    }
}
