<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Iniciando seeders de goRanch...');
        $this->command->info('');

        $this->call([
            PuntosRecoleccionSeeder::class, // 1ro — los conductores los necesitan
            UsuariosSeeder::class,           // 2do — admin + clientes
            ConductoresSeeder::class,        // 3ro — necesita usuarios + puntos
            ServiciosSeeder::class,          // 4to — necesita usuarios + conductores
        ]);

        $this->command->info('');
        $this->command->info('🎉 ¡Base de datos lista para pruebas!');
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════╗');
        $this->command->info('║         CREDENCIALES DE PRUEBA       ║');
        $this->command->info('╠══════════════════════════════════════╣');
        $this->command->info('║  ADMIN                               ║');
        $this->command->info('║  admin@goranch.com / admin1234       ║');
        $this->command->info('╠══════════════════════════════════════╣');
        $this->command->info('║  CLIENTE                             ║');
        $this->command->info('║  maria@prueba.com / password123      ║');
        $this->command->info('╠══════════════════════════════════════╣');
        $this->command->info('║  CONDUCTOR                           ║');
        $this->command->info('║  roberto@conductor.com / conductor123║');
        $this->command->info('╚══════════════════════════════════════╝');
        $this->command->info('');
    }
}
