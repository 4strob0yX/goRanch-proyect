<?php

namespace App\Http\Controllers;

use App\Models\PuntoRecoleccion;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
