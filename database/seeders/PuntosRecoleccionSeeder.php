<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuntosRecoleccionSeeder extends Seeder
{
    public function run(): void
    {
        $puntos = [
            [
                'nombre'    => 'Plaza Principal',
                'direccion' => 'Centro, frente al kiosco municipal',
                'lat'       => 20.5000,
                'lng'       => -100.3500,
            ],
            [
                'nombre'    => 'Mercado Municipal',
                'direccion' => 'Av. Hidalgo s/n, Col. Centro',
                'lat'       => 20.5020,
                'lng'       => -100.3480,
            ],
            [
                'nombre'    => 'Farmacia del Pueblo',
                'direccion' => 'Calle Morelos 45, Col. Centro',
                'lat'       => 20.4985,
                'lng'       => -100.3520,
            ],
            [
                'nombre'    => 'Entrada Norte (Gasolinera)',
                'direccion' => 'Carretera principal km 2, entrada norte',
                'lat'       => 20.5100,
                'lng'       => -100.3450,
            ],
            [
                'nombre'    => 'Clínica de Salud',
                'direccion' => 'Calle Juárez 10, Col. Salud',
                'lat'       => 20.4960,
                'lng'       => -100.3560,
            ],
        ];

        foreach ($puntos as $p) {
            DB::table('puntos_recoleccion')->insert([
                'nombre'    => $p['nombre'],
                'direccion' => $p['direccion'],
                'ubicacion' => DB::raw("ST_GeomFromText('POINT({$p['lng']} {$p['lat']})', 4326)"),
                'activo'    => true,
                'creado_en' => now(),
            ]);
        }

        $this->command->info('✅ 5 puntos de recolección creados.');
    }
}
