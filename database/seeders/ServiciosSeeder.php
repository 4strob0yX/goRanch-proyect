<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Conductor;

class ServiciosSeeder extends Seeder
{
    public function run(): void
    {
        $clientes   = Usuario::where('rol', 'usuario')->pluck('id')->toArray();
        $conductores = Conductor::where('estatus', 'activo')->pluck('id')->toArray();

        if (empty($clientes) || empty($conductores)) {
            $this->command->warn('⚠️  Corre UsuariosSeeder y ConductoresSeeder primero.');
            return;
        }

        $rutas = [
            [
                'origen'      => 'Plaza Principal',
                'destino'     => 'Colonia Las Flores',
                'lat_o'       => 20.5000, 'lng_o' => -100.3500,
                'lat_d'       => 20.4920, 'lng_d' => -100.3600,
                'distancia'   => 1.2,
            ],
            [
                'origen'      => 'Mercado Municipal',
                'destino'     => 'Rancho El Paraíso',
                'lat_o'       => 20.5020, 'lng_o' => -100.3480,
                'lat_d'       => 20.5200, 'lng_d' => -100.3300,
                'distancia'   => 3.5,
            ],
            [
                'origen'      => 'Farmacia del Pueblo',
                'destino'     => 'Col. Ejidal Norte',
                'lat_o'       => 20.4985, 'lng_o' => -100.3520,
                'lat_d'       => 20.5080, 'lng_d' => -100.3420,
                'distancia'   => 2.1,
            ],
            [
                'origen'      => 'Clínica de Salud',
                'destino'     => 'Carretera km 5',
                'lat_o'       => 20.4960, 'lng_o' => -100.3560,
                'lat_d'       => 20.5250, 'lng_d' => -100.3200,
                'distancia'   => 5.8,
            ],
        ];

        $tipos    = ['viaje', 'viaje', 'mandado_libre', 'viaje'];
        $estatus  = ['completado', 'completado', 'completado', 'cancelado'];
        $pagos    = ['efectivo', 'billetera', 'efectivo', 'efectivo'];

        foreach ($rutas as $i => $ruta) {
            $costoEnvio       = round(15 + ($ruta['distancia'] * 8), 2);
            $tarifaPlataforma = round($costoEnvio * 0.10, 2);
            $totalFinal       = $costoEnvio + $tarifaPlataforma;

            $clienteId   = $clientes[$i % count($clientes)];
            $conductorId = $conductores[$i % count($conductores)];

            $servicioId = DB::table('servicios')->insertGetId([
                'cliente_id'        => $clienteId,
                'conductor_id'      => $conductorId,
                'tipo'              => $tipos[$i],
                'estatus'           => $estatus[$i],
                'direccion_origen'  => $ruta['origen'],
                'direccion_destino' => $ruta['destino'],
                'ubicacion_origen'  => DB::raw("ST_GeomFromText('POINT({$ruta['lng_o']} {$ruta['lat_o']})', 4326)"),
                'ubicacion_destino' => DB::raw("ST_GeomFromText('POINT({$ruta['lng_d']} {$ruta['lat_d']})', 4326)"),
                'distancia_km'      => $ruta['distancia'],
                'costo_envio'       => $costoEnvio,
                'costo_productos'   => 0,
                'tarifa_plataforma' => $tarifaPlataforma,
                'total_final'       => $totalFinal,
                'metodo_pago'       => $pagos[$i],
                'notas'             => null,
                'iniciado_en'       => now()->subMinutes(rand(10, 60)),
                'finalizado_en'     => $estatus[$i] === 'completado' ? now()->subMinutes(rand(1, 9)) : null,
                'creado_en'         => now()->subDays($i),
            ]);

            // Si es mandado, agregar ítems de ejemplo
            // Nota: detalle_servicios es para delivery_tienda (productos con producto_id)
            // Los mandados libres usan el campo 'notas' para describir los artículos
        }

        $this->command->info('✅ 4 servicios de ejemplo creados (3 completados, 1 cancelado).');
    }
}
