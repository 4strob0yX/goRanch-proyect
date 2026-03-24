<?php

namespace App\Http\Controllers;

use App\Models\PuntoRecoleccion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ViajeController extends Controller
{
    // -----------------------------------------------
    // Formulario nuevo viaje
    // -----------------------------------------------

    public function nuevo()
    {
        $puntos = PuntoRecoleccion::where('activo', true)
            ->selectRaw('*, ST_Y(ubicacion) as lat, ST_X(ubicacion) as lng')
            ->get();
        return view('viajes.nuevo', compact('puntos'));
    }

    // -----------------------------------------------
    // Crear viaje y asignar conductor
    // -----------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'direccion_origen'  => 'required|string|max:255',
            'direccion_destino' => 'required|string|max:255',
            'lat_origen'        => 'required|numeric',
            'lng_origen'        => 'required|numeric',
            'lat_destino'       => 'required|numeric',
            'lng_destino'       => 'required|numeric',
            'metodo_pago'       => 'required|in:efectivo,billetera',
            'notas'             => 'nullable|string|max:500',
        ]);

        $latOrigen = $request->lat_origen;
        $lngOrigen = $request->lng_origen;

        // 1. Calcular tarifa
        $distanciaKm      = $this->calcularDistanciaKm($latOrigen, $lngOrigen, $request->lat_destino, $request->lng_destino);
        $costoEnvio       = $this->calcularTarifa($distanciaKm);
        $tarifaPlataforma = round($costoEnvio * 0.10, 2);
        $totalFinal       = $costoEnvio + $tarifaPlataforma;

        // 4. Crear servicio
        DB::beginTransaction();
        try {
            $servicio = Servicio::create([
                'cliente_id'        => Auth::id(),
                'conductor_id'      => null,
                'tipo'              => 'viaje',
                'estatus'           => 'buscando',
                'direccion_origen'  => $request->direccion_origen,
                'direccion_destino' => $request->direccion_destino,
                'ubicacion_origen'  => DB::raw("ST_GeomFromText('POINT({$lngOrigen} {$latOrigen})', 4326)"),
                'ubicacion_destino' => DB::raw("ST_GeomFromText('POINT({$request->lng_destino} {$request->lat_destino})', 4326)"),
                'distancia_km'      => $distanciaKm,
                'costo_envio'       => $costoEnvio,
                'costo_productos'   => 0,
                'tarifa_plataforma' => $tarifaPlataforma,
                'total_final'       => $totalFinal,
                'metodo_pago'       => $request->metodo_pago,
                'notas'             => $request->notas,
                'iniciado_en'       => null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear viaje: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el viaje: ' . $e->getMessage()]);
        }

        return redirect()->route('viaje.en-camino', $servicio->id);
    }

    // -----------------------------------------------
    // En camino
    // -----------------------------------------------

    public function enCamino($id)
    {
        $servicio = Servicio::with(['conductor.usuario', 'conductor.puntoRecoleccion'])
            ->where('cliente_id', Auth::id())
            ->findOrFail($id);

        return view('viajes.en-camino', compact('servicio'));
    }

    // -----------------------------------------------
    // Finalizado
    // -----------------------------------------------

    public function finalizado($id)
    {
        $servicio = Servicio::where('cliente_id', Auth::id())->findOrFail($id);

        if ($servicio->estatus !== 'completado') {
            $servicio->update([
                'estatus'       => 'completado',
                'finalizado_en' => now(),
            ]);
        }

        return view('viajes.finalizado', compact('servicio'));
    }

    // -----------------------------------------------
    // Calificar
    // -----------------------------------------------

    public function calificar(Request $request, $id)
    {
        $request->validate(['calificacion' => 'required|integer|min:1|max:5']);

        $servicio = Servicio::where('cliente_id', Auth::id())->findOrFail($id);

        if ($servicio->conductor_id) {
            $conductor   = $servicio->conductor;
            $totalViajes = Servicio::where('conductor_id', $conductor->id)->where('estatus', 'completado')->count();
            $sumaActual  = $conductor->calificacion_promedio * max($totalViajes - 1, 1);
            $nuevo       = round(($sumaActual + $request->calificacion) / max($totalViajes, 1), 2);
            $conductor->update(['calificacion_promedio' => min($nuevo, 5.00)]);
        }

        return redirect()->route('dashboard')->with('success', '¡Gracias por tu calificación!');
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
        return round(15.00 + ($km * 8.00), 2); // $15 base + $8/km — ajusta a tu zona
    }
}
