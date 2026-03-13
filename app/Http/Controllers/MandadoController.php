<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Models\PuntoRecoleccion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MandadoController extends Controller
{
    // -----------------------------------------------
    // Formulario nuevo mandado
    // -----------------------------------------------

    public function nuevo()
    {
        $puntos = DB::table('puntos_recoleccion')
            ->where('activo', true)
            ->select('id', 'nombre', 'direccion',
                DB::raw('ST_Y(ubicacion) as lat'),
                DB::raw('ST_X(ubicacion) as lng'))
            ->get();

        return view('mandados.nuevo', compact('puntos'));
    }

    // -----------------------------------------------
    // Crear mandado
    // Los ítems se guardan en JSON dentro del campo 'notas'
    // (detalles_servicio es exclusivo para delivery_tienda con producto_id)
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
            'items'               => 'required|array|min:1',
            'items.*.nombre'      => 'required|string|max:100',
            'items.*.cantidad'    => 'required|integer|min:1',
            'items.*.precio_est'  => 'nullable|numeric|min:0',
        ], [
            'items.required'            => 'Agrega al menos un artículo.',
            'items.*.nombre.required'   => 'El nombre del artículo es obligatorio.',
            'items.*.cantidad.required' => 'La cantidad es obligatoria.',
        ]);

        $latOrigen = $request->lat_origen;
        $lngOrigen = $request->lng_origen;

        // 1. Punto más cercano
        $puntoMasCercano = PuntoRecoleccion::masCercanoA($latOrigen, $lngOrigen);

        if (!$puntoMasCercano) {
            return back()->withErrors(['sin_cobertura' => 'No hay puntos de recolección activos en tu zona.']);
        }

        // 2. Conductor disponible
        $conductor = Conductor::where('punto_recoleccion_id', $puntoMasCercano->id)
            ->where('esta_conectado', true)
            ->where('estatus', 'activo')
            ->orderByDesc('calificacion_promedio')
            ->first();

        if (!$conductor) {
            $conductor = Conductor::where('esta_conectado', true)
                ->where('estatus', 'activo')
                ->whereNotNull('punto_recoleccion_id')
                ->orderByDesc('calificacion_promedio')
                ->first();
        }

        // 3. Costos
        $distanciaKm      = $this->calcularDistanciaKm($latOrigen, $lngOrigen, $request->lat_destino, $request->lng_destino);
        $costoEnvio       = $this->calcularTarifa($distanciaKm);
        $costoProductos   = collect($request->items)->sum(fn($i) => ($i['precio_est'] ?? 0) * $i['cantidad']);
        $tarifaPlataforma = round($costoEnvio * 0.10, 2);
        $totalFinal       = $costoEnvio + $costoProductos + $tarifaPlataforma;

        // 4. Serializar ítems en notas (JSON legible)
        $notasItems = collect($request->items)->map(fn($i) => [
            'nombre'     => $i['nombre'],
            'cantidad'   => $i['cantidad'],
            'precio_est' => $i['precio_est'] ?? 0,
        ])->toArray();

        $notasJson = json_encode($notasItems, JSON_UNESCAPED_UNICODE);

        // 5. Crear servicio
        DB::beginTransaction();
        try {
            $servicio = Servicio::create([
                'cliente_id'        => Auth::id(),
                'conductor_id'      => $conductor?->id,
                'tipo'              => 'mandado_libre',
                'estatus'           => $conductor ? 'aceptado' : 'buscando',
                'direccion_origen'  => $request->direccion_origen,
                'direccion_destino' => $request->direccion_destino,
                'ubicacion_origen'  => DB::raw("ST_GeomFromText('POINT({$lngOrigen} {$latOrigen})', 4326)"),
                'ubicacion_destino' => DB::raw("ST_GeomFromText('POINT({$request->lng_destino} {$request->lat_destino})', 4326)"),
                'distancia_km'      => $distanciaKm,
                'costo_envio'       => $costoEnvio,
                'costo_productos'   => $costoProductos,
                'tarifa_plataforma' => $tarifaPlataforma,
                'total_final'       => $totalFinal,
                'metodo_pago'       => $request->metodo_pago,
                'notas'             => $notasJson,
                'iniciado_en'       => $conductor ? now() : null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el mandado. Intenta de nuevo.']);
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

        // Decodificar ítems desde notas JSON
        $items = json_decode($servicio->notas, true) ?? [];

        return view('mandados.en-proceso', compact('servicio', 'items'));
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
