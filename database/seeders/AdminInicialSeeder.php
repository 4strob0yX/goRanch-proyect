<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminInicialSeeder extends Seeder
{
    public function run(): void
    {
        // Evita crear duplicado si ya existe
        if (Usuario::where('email', 'admin@goranch.com')->exists()) {
            $this->command->warn('⚠️  El admin inicial ya existe. No se creó duplicado.');
            return;
        }

        Usuario::create([
            'nombre'   => 'Admin goRanch',
            'email'    => 'admin@goranch.com',
            'telefono' => '0000000000',
            'password' => Hash::make('admin1234'),
            'rol'      => 'super_admin',
            'estatus'  => 'activo',
        ]);

        $this->command->info('✅ Admin inicial creado.');
        $this->command->info('   Email:    admin@goranch.com');
        $this->command->info('   Password: admin1234');
        $this->command->warn('   ⚠️  Cambia la contraseña al entrar por primera vez.');
    }
}
