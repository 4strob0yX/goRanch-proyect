<?php

namespace App\Http\Controllers;

use App\Models\PuntoRecoleccion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MandadoController extends Controller
{
    // -----------------------------------------------
    // Formulario nuevo mandado
    // -----------------------------------------------

    public function nuevo()
    {
        $puntos = PuntoRecoleccion::where('activo', true)
            ->selectRaw('*, ST_Y(ubicacion) as lat, ST_X(ubicacion) as lng')
            ->get();
        return view('mandados.nuevo', compact('puntos'));
    }

    // -----------------------------------------------
    // Crear mandado
    // -----------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'direccion_origen'    => 'required|string|max:255',
            'direccion_destino'   => 'required|string|max:255',
            'lat_origen'          => 'required|numeric',
            'lng_origen'          => 'required|numeric',
            'lat_destino'         => 'required|numeric',
            'lng_destino'         => 'required|numeric',
            'metodo_pago'         => 'required|in:efectivo,billetera',
            'notas'               => 'nullable|string|max:500',
            // Items del mandado (array)
            'items'               => 'required|array|min:1',
            'items.*.nombre'      => 'required|string|max:100',
            'items.*.cantidad'    => 'required|integer|min:1',
            'items.*.precio_est'  => 'nullable|numeric|min:0',
        ], [
            'items.required'           => 'Agrega al menos un artículo.',
            'items.*.nombre.required'  => 'El nombre del artículo es obligatorio.',
            'items.*.cantidad.required'=> 'La cantidad es obligatoria.',
        ]);

        $latOrigen = $request->lat_origen;
        $lngOrigen = $request->lng_origen;

        // 1. Punto de recolección más cercano al origen
        $puntoMasCercano = PuntoRecoleccion::masCercanoA($latOrigen, $lngOrigen);

        if (!$puntoMasCercano) {
            return back()->withInput()->withErrors(['sin_cobertura' => 'No hay puntos de recolección activos en tu zona. Intenta más tarde.']);
        }

        // 2. Calcular costos
        $distanciaKm      = $this->calcularDistanciaKm($latOrigen, $lngOrigen, $request->lat_destino, $request->lng_destino);
        $costoEnvio       = $this->calcularTarifa($distanciaKm);
        $costoProductos   = collect($request->items)->sum(fn($i) => ($i['precio_est'] ?? 0) * $i['cantidad']);
        $tarifaPlataforma = round($costoEnvio * 0.10, 2);
        $totalFinal       = $costoEnvio + $costoProductos + $tarifaPlataforma;

        // 4. Crear servicio + detalles
        DB::beginTransaction();
        try {
            $servicio = Servicio::create([
                'cliente_id'        => Auth::id(),
                'conductor_id'      => null,
                'punto_recoleccion_id' => $puntoMasCercano?->id,
                'tipo'              => 'mandado_libre',
                'estatus'           => 'buscando',
                'direccion_origen'  => $request->direccion_origen,
                'direccion_destino' => $request->direccion_destino,
                'ubicacion_origen'  => DB::raw("ST_GeomFromText('POINT({$latOrigen} {$lngOrigen})', 4326)"),
'ubicacion_destino' => DB::raw("ST_GeomFromText('POINT({$request->lat_destino} {$request->lng_destino})', 4326)"),
                'distancia_km'      => $distanciaKm,
                'costo_envio'       => $costoEnvio,
                'costo_productos'   => $costoProductos,
                'tarifa_plataforma' => $tarifaPlataforma,
                'total_final'       => $totalFinal,
                'metodo_pago'       => $request->metodo_pago,
                'notas'             => $request->notas,
                'iniciado_en'       => null,
            ]);

            // Insertar ítems en detalle_servicios
            foreach ($request->items as $item) {
                DB::table('detalle_servicios')->insert([
                    'servicio_id'  => $servicio->id,
                    'nombre'       => $item['nombre'],
                    'cantidad'     => $item['cantidad'],
                    'precio_est'   => $item['precio_est'] ?? 0,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear mandado: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el mandado: ' . $e->getMessage()]);
        }

        return redirect()->route('mandado.en-proceso', $servicio->id);
    }

    // -----------------------------------------------
    // En proceso
    // -----------------------------------------------

    public function enProceso($id)
    {
        $servicio = Servicio::with([
                'conductor.usuario',
                'conductor.puntoRecoleccion',
            ])
            ->where('cliente_id', Auth::id())
            ->findOrFail($id);

        // Cargar ítems del mandado
        $items = DB::table('detalle_servicios')->where('servicio_id', $id)->get();

        return view('mandados.en-proceso', compact('servicio', 'items'));
    }

    // -----------------------------------------------
    // Cancelar mandado
    // -----------------------------------------------

    public function cancelar($id)
    {
        $servicio = Servicio::where('cliente_id', Auth::id())
            ->whereIn('estatus', ['buscando', 'aceptado'])
            ->findOrFail($id);

        $servicio->update([
            'estatus'      => 'cancelado',
            'finalizado_en' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Mandado cancelado.');
    }

    // -----------------------------------------------
    // Helpers
    // -----------------------------------------------

    private function calcularDistanciaKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return round($r * 2 * atan2(sqrt($a), sqrt(1 - $a)), 2);
    }

    private function calcularTarifa(float $km): float
    {
        return round(15.00 + ($km * 8.00), 2);
    }
}
