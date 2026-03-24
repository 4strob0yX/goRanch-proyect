<?php

namespace App\Http\Controllers;

use App\Models\PuntoRecoleccion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConductorController extends Controller
{
    // -----------------------------------------------
    // Dashboard conductor
    // -----------------------------------------------

    public function dashboard()
    {
        $conductor = Auth::user()->conductor;

        $puntos = PuntoRecoleccion::where('activo', true)->get();

        $serviciosRecientes = Servicio::where('conductor_id', $conductor->id)
            ->with('cliente')
            ->orderByDesc('creado_en')
            ->limit(5)
            ->get();

        $stats = [
            'viajes_hoy'   => Servicio::where('conductor_id', $conductor->id)
                                ->whereDate('creado_en', today())
                                ->where('estatus', 'completado')
                                ->count(),
            'ganado_hoy'   => Servicio::where('conductor_id', $conductor->id)
                                ->whereDate('creado_en', today())
                                ->where('estatus', 'completado')
                                ->sum('costo_envio'),
            'calificacion' => $conductor->calificacion_promedio,
        ];

        return view('dashboard.conductor', compact('conductor', 'puntos', 'serviciosRecientes', 'stats'));
    }

    // -----------------------------------------------
    // Conectar/desconectar + elegir punto
    // -----------------------------------------------

    public function conectar(Request $request)
    {
        $request->validate([
            'punto_recoleccion_id' => 'required|exists:puntos_recoleccion,id',
        ]);

        $conductor = Auth::user()->conductor;
        $conductor->update([
            'esta_conectado'       => true,
            'punto_recoleccion_id' => $request->punto_recoleccion_id,
        ]);

        return back()->with('success', '¡Estás conectado y listo para recibir servicios!');
    }

    public function desconectar()
    {
        $conductor = Auth::user()->conductor;
        $conductor->update([
            'esta_conectado'       => false,
            'punto_recoleccion_id' => null,
        ]);

        return back()->with('success', 'Te desconectaste correctamente.');
    }

    // -----------------------------------------------
    // Vista de conductor suspendido
    // -----------------------------------------------

    public function suspendido()
    {
        return view('conductor.suspendido');
    }

    // -----------------------------------------------
    // Actualizar estatus de un servicio
    // -----------------------------------------------

    public function actualizarServicio(Request $request, $id)
    {
        $request->validate([
            'estatus' => 'required|in:en_sitio,en_ruta,completado',
        ]);

        $servicio = Servicio::where('conductor_id', Auth::user()->conductor->id)
            ->findOrFail($id);

        $data = ['estatus' => $request->estatus];

        if ($request->estatus === 'completado') {
            $data['finalizado_en'] = now();
        }

        $servicio->update($data);

        return back()->with('success', 'Estatus actualizado.');
    }

    // -----------------------------------------------
    // API: Servicios pendientes para el conductor
    // -----------------------------------------------

    public function serviciosPendientes()
    {
        $conductor = Auth::user()->conductor;

        if (!$conductor || !$conductor->esta_conectado) {
            return response()->json(['servicios' => []]);
        }

        $servicios = Servicio::where('estatus', 'buscando')
            ->whereNull('conductor_id')
            ->with('cliente')
            ->orderBy('creado_en', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($s) {
                return [
                    'id'               => $s->id,
                    'tipo'             => $s->tipo,
                    'cliente_nombre'   => $s->cliente->nombre ?? 'Cliente',
                    'direccion_origen' => $s->direccion_origen,
                    'direccion_destino'=> $s->direccion_destino,
                    'distancia_km'     => $s->distancia_km,
                    'total_final'      => $s->total_final,
                    'creado_en'        => $s->creado_en,
                ];
            });

        return response()->json(['servicios' => $servicios]);
    }

    // -----------------------------------------------
    // API: Conductor acepta un servicio
    // -----------------------------------------------

    public function aceptarServicio($id)
    {
        $conductor = Auth::user()->conductor;

        $servicio = Servicio::where('id', $id)
            ->where('estatus', 'buscando')
            ->whereNull('conductor_id')
            ->first();

        if (!$servicio) {
            return response()->json(['ok' => false, 'msg' => 'El servicio ya fue tomado por otro conductor.'], 409);
        }

        $servicio->update([
            'conductor_id' => $conductor->id,
            'estatus'      => 'aceptado',
            'iniciado_en'  => now(),
        ]);

        return response()->json(['ok' => true, 'msg' => 'Servicio aceptado.', 'servicio_id' => $servicio->id]);
    }

    // -----------------------------------------------
    // API: Conductor rechaza un servicio (simplemente lo ignora)
    // -----------------------------------------------

    public function rechazarServicio($id)
    {
        return response()->json(['ok' => true, 'msg' => 'Servicio ignorado.']);
    }
}
