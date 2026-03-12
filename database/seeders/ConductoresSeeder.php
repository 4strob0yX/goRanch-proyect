<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Conductor;

class ConductoresSeeder extends Seeder
{
    public function run(): void
    {
        $puntos = DB::table('puntos_recoleccion')->pluck('id')->toArray();

        if (empty($puntos)) {
            $this->command->warn('⚠️  Corre PuntosRecoleccionSeeder primero.');
            return;
        }

        $conductoresData = [
            [
                'nombre'        => 'Roberto Sánchez',
                'email'         => 'roberto@conductor.com',
                'telefono'      => '5561111111',
                'tipo_vehiculo' => 'moto',
                'marca'         => 'Honda',
                'modelo'        => 'CB125',
                'placa'         => 'ABC-001',
                'calificacion'  => 4.90,
                'conectado'     => true,
                'punto_idx'     => 0, // Plaza Principal
            ],
            [
                'nombre'        => 'Pedro Hernández',
                'email'         => 'pedro@conductor.com',
                'telefono'      => '5562222222',
                'tipo_vehiculo' => 'moto',
                'marca'         => 'Yamaha',
                'modelo'        => 'FZ150',
                'placa'         => 'ABC-002',
                'calificacion'  => 4.75,
                'conectado'     => true,
                'punto_idx'     => 1, // Mercado
            ],
            [
                'nombre'        => 'Miguel Torres',
                'email'         => 'miguel@conductor.com',
                'telefono'      => '5563333333',
                'tipo_vehiculo' => 'auto',
                'marca'         => 'Nissan',
                'modelo'        => 'Versa',
                'placa'         => 'ABC-003',
                'calificacion'  => 4.85,
                'conectado'     => false,
                'punto_idx'     => null,
            ],
            [
                'nombre'        => 'Luis Ramírez',
                'email'         => 'luis@conductor.com',
                'telefono'      => '5564444444',
                'tipo_vehiculo' => 'bici',
                'marca'         => 'Trek',
                'modelo'        => 'Marlin 5',
                'placa'         => 'ABC-004',
                'calificacion'  => 4.60,
                'conectado'     => true,
                'punto_idx'     => 2, // Farmacia
            ],
            [
                'nombre'        => 'Javier Cruz',
                'email'         => 'javier@conductor.com',
                'telefono'      => '5565555555',
                'tipo_vehiculo' => 'moto',
                'marca'         => 'Italika',
                'modelo'        => 'DS150',
                'placa'         => 'ABC-005',
                'calificacion'  => 4.70,
                'conectado'     => true,
                'punto_idx'     => 0, // Plaza Principal
            ],
        ];

        foreach ($conductoresData as $d) {
            // Crear usuario con rol conductor
            $usuario = Usuario::create([
                'nombre'   => $d['nombre'],
                'email'    => $d['email'],
                'telefono' => $d['telefono'],
                'password' => Hash::make('conductor123'),
                'rol'      => 'conductor',
                'estatus'  => 'activo',
            ]);

            // Crear perfil conductor
            $puntoId = $d['punto_idx'] !== null ? $puntos[$d['punto_idx']] : null;

            Conductor::create([
                'usuario_id'           => $usuario->id,
                'tipo_vehiculo'        => $d['tipo_vehiculo'],
                'marca'                => $d['marca'],
                'modelo'               => $d['modelo'],
                'placa'                => $d['placa'],
                'esta_conectado'       => $d['conectado'],
                'punto_recoleccion_id' => $puntoId,
                'estatus'              => 'activo',
                'calificacion_promedio'=> $d['calificacion'],
            ]);

            // Documento de licencia aprobado (para no quedar pendiente)
            DB::table('documentos_conductor')->insert([
                'conductor_id'   => Conductor::where('usuario_id', $usuario->id)->value('id'),
                'tipo_documento' => 'licencia',
                'url_archivo'    => 'documentos/conductores/licencia_prueba.pdf',
                'estatus'        => 'aprobado',
                'subido_en'      => now(),
                'revisado_en'    => now(),
            ]);
        }

        $this->command->info('✅ 5 conductores creados (4 conectados, 1 offline).');
        $this->command->info('   Password: conductor123');
    }
}
