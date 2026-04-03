<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use App\Models\PuntoRecoleccion;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicioApiController extends Controller
{
    // ═══════════════════════════════════════
    // USUARIO: crear viaje
    // ═══════════════════════════════════════

    public function storeViaje(Request $request)
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

        $latO = $request->lat_origen;
        $lngO = $request->lng_origen;

        $punto = PuntoRecoleccion::masCercanoA($latO, $lngO);
        if (!$punto) {
            return response()->json(['message' => 'No hay cobertura en tu zona.'], 422);
        }

        $dist   = $this->haversine($latO, $lngO, $request->lat_destino, $request->lng_destino);
        $envio  = $this->tarifa($dist);
        $plat   = round($envio * 0.10, 2);
        $total  = $envio + $plat;

        DB::beginTransaction();
        try {
            $servicio = Servicio::create([
                'cliente_id'           => Auth::id(),
                'conductor_id'         => null,
                'punto_recoleccion_id' => $punto->id,
                'tipo'                 => 'viaje',
                'estatus'              => 'buscando',
                'direccion_origen'     => $request->direccion_origen,
                'direccion_destino'    => $request->direccion_destino,
                'ubicacion_origen'     => DB::raw("ST_GeomFromText('POINT({$lngO} {$latO})', 4326)"),
                'ubicacion_destino'    => DB::raw("ST_GeomFromText('POINT({$request->lng_destino} {$request->lat_destino})', 4326)"),
                'distancia_km'         => $dist,
                'costo_envio'          => $envio,
                'costo_productos'      => 0,
                'tarifa_plataforma'    => $plat,
                'total_final'          => $total,
                'metodo_pago'          => $request->metodo_pago,
                'notas'                => $request->notas,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API storeViaje: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear viaje.'], 500);
        }

        return response()->json([
            'servicio_id' => $servicio->id,
            'total_final' => $total,
            'distancia_km' => $dist,
        ], 201);
    }

    // ═══════════════════════════════════════
    // USUARIO: crear mandado
    // ═══════════════════════════════════════

    public function storeMandado(Request $request)
    {
        $request->validate([
            'direccion_origen'   => 'required|string|max:255',
            'direccion_destino'  => 'required|string|max:255',
            'lat_origen'         => 'required|numeric',
            'lng_origen'         => 'required|numeric',
            'lat_destino'        => 'required|numeric',
            'lng_destino'        => 'required|numeric',
            'metodo_pago'        => 'required|in:efectivo,billetera',
            'notas'              => 'nullable|string|max:500',
            'items'              => 'required|array|min:1',
            'items.*.nombre'     => 'required|string|max:100',
            'items.*.cantidad'   => 'required|integer|min:1',
            'items.*.precio_est' => 'nullable|numeric|min:0',
        ]);

        $latO = $request->lat_origen;
        $lngO = $request->lng_origen;

        $punto = PuntoRecoleccion::masCercanoA($latO, $lngO);
        if (!$punto) {
            return response()->json(['message' => 'No hay cobertura en tu zona.'], 422);
        }

        $dist   = $this->haversine($latO, $lngO, $request->lat_destino, $request->lng_destino);
        $envio  = $this->tarifa($dist);
        $prods  = collect($request->items)->sum(fn($i) => ($i['precio_est'] ?? 0) * $i['cantidad']);
        $plat   = round($envio * 0.10, 2);
        $total  = $envio + $prods + $plat;

        DB::beginTransaction();
        try {
            $servicio = Servicio::create([
                'cliente_id'           => Auth::id(),
                'conductor_id'         => null,
                'punto_recoleccion_id' => $punto->id,
                'tipo'                 => 'mandado_libre',
                'estatus'              => 'buscando',
                'direccion_origen'     => $request->direccion_origen,
                'direccion_destino'    => $request->direccion_destino,
                'ubicacion_origen'     => DB::raw("ST_GeomFromText('POINT({$lngO} {$latO})', 4326)"),
                'ubicacion_destino'    => DB::raw("ST_GeomFromText('POINT({$request->lng_destino} {$request->lat_destino})', 4326)"),
                'distancia_km'         => $dist,
                'costo_envio'          => $envio,
                'costo_productos'      => $prods,
                'tarifa_plataforma'    => $plat,
                'total_final'          => $total,
                'metodo_pago'          => $request->metodo_pago,
                'notas'                => $request->notas,
            ]);

            foreach ($request->items as $item) {
                DB::table('detalle_servicios')->insert([
                    'servicio_id' => $servicio->id,
                    'nombre'      => $item['nombre'],
                    'cantidad'    => $item['cantidad'],
                    'precio_est'  => $item['precio_est'] ?? 0,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API storeMandado: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear mandado.'], 500);
        }

        return response()->json([
            'servicio_id'  => $servicio->id,
            'total_final'  => $total,
            'distancia_km' => $dist,
        ], 201);
    }

    // ═══════════════════════════════════════
    // USUARIO: estatus de un servicio
    // ═══════════════════════════════════════

    public function status($id)
    {
        $servicio = Servicio::with('conductor.usuario')
            ->where('cliente_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'id'           => $servicio->id,
            'tipo'         => $servicio->tipo,
            'estatus'      => $servicio->estatus,
            'conductor_id' => $servicio->conductor_id,
            'conductor'    => $servicio->conductor ? [
                'nombre'       => $servicio->conductor->usuario->nombre ?? 'Conductor',
                'vehiculo'     => ucfirst($servicio->conductor->tipo_vehiculo) . ' ' . $servicio->conductor->placa,
                'calificacion' => $servicio->conductor->calificacion_promedio,
            ] : null,
            'direccion_origen'  => $servicio->direccion_origen,
            'direccion_destino' => $servicio->direccion_destino,
            'distancia_km'      => $servicio->distancia_km,
            'total_final'       => $servicio->total_final,
            'metodo_pago'       => $servicio->metodo_pago,
            'creado_en'         => $servicio->creado_en,
        ]);
    }

    // ═══════════════════════════════════════
    // USUARIO: servicio activo (para resumir tracking)
    // ═══════════════════════════════════════

    public function servicioActivo()
    {
        $servicio = Servicio::with('conductor.usuario')
            ->where('cliente_id', Auth::id())
            ->whereNotIn('estatus', ['completado', 'cancelado'])
            ->orderByDesc('creado_en')
            ->first();

        if (!$servicio) {
            return response()->json(['activo' => null]);
        }

        return response()->json(['activo' => [
            'id'               => $servicio->id,
            'tipo'             => $servicio->tipo,
            'estatus'          => $servicio->estatus,
            'direccion_origen' => $servicio->direccion_origen,
            'direccion_destino'=> $servicio->direccion_destino,
            'total_final'      => $servicio->total_final,
            'conductor'        => $servicio->conductor ? [
                'nombre' => $servicio->conductor->usuario->nombre ?? 'Conductor',
            ] : null,
            'creado_en'        => $servicio->creado_en,
        ]]);
    }

    // ═══════════════════════════════════════
    // USUARIO: cancelar servicio
    // ═══════════════════════════════════════

    public function cancelar($id)
    {
        $servicio = Servicio::where('cliente_id', Auth::id())
            ->whereIn('estatus', ['buscando', 'aceptado'])
            ->findOrFail($id);

        $servicio->update([
            'estatus'       => 'cancelado',
            'finalizado_en' => now(),
        ]);

        return response()->json(['message' => 'Servicio cancelado.']);
    }

    // ═══════════════════════════════════════
    // USUARIO: historial de servicios
    // ═══════════════════════════════════════

    public function misServicios(Request $request)
    {
        $query = Servicio::with('conductor.usuario')
            ->where('cliente_id', Auth::id())
            ->orderByDesc('creado_en');

        if ($request->has('tipo') && in_array($request->tipo, ['viaje', 'mandado_libre'])) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->has('estatus') && in_array($request->estatus, ['buscando', 'aceptado', 'en_ruta', 'completado', 'cancelado'])) {
            $query->where('estatus', $request->estatus);
        }

        $servicios = $query->paginate(15);

        return response()->json($servicios);
    }

    // ═══════════════════════════════════════
    // USUARIO: conductores disponibles check
    // ═══════════════════════════════════════

    public function conductoresDisponibles(Request $request)
    {
        $lat = (float) $request->query('lat', 0);
        $lng = (float) $request->query('lng', 0);
        if (!$lat || !$lng) return response()->json(['disponibles' => 0]);

        $punto = PuntoRecoleccion::masCercanoA($lat, $lng);
        if (!$punto) return response()->json(['disponibles' => 0]);

        $count = Conductor::where('punto_recoleccion_id', $punto->id)
            ->where('esta_conectado', true)
            ->where('estatus', 'activo')
            ->count();

        return response()->json(['disponibles' => $count, 'punto' => $punto->nombre]);
    }

    // ═══════════════════════════════════════
    // USUARIO: puntos de recoleccion
    // ═══════════════════════════════════════

    public function puntos()
    {
        $puntos = PuntoRecoleccion::where('activo', true)
            ->selectRaw('*, ST_Y(ubicacion) as lat, ST_X(ubicacion) as lng')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'direccion']);

        return response()->json(['puntos' => $puntos]);
    }

    // ═══════════════════════════════════════
    // CONDUCTOR: dashboard data
    // ═══════════════════════════════════════

    public function conductorDashboard()
    {
        $conductor = Auth::user()->conductor;

        $servicioActivo = Servicio::where('conductor_id', $conductor->id)
            ->whereNotIn('estatus', ['completado', 'cancelado'])
            ->with('cliente')
            ->orderByDesc('creado_en')
            ->first();

        $stats = [
            'viajes_hoy'  => Servicio::where('conductor_id', $conductor->id)
                ->whereDate('creado_en', today())
                ->where('estatus', 'completado')
                ->count(),
            'ganado_hoy'  => Servicio::where('conductor_id', $conductor->id)
                ->whereDate('creado_en', today())
                ->where('estatus', 'completado')
                ->sum('costo_envio'),
            'calificacion' => $conductor->calificacion_promedio,
        ];

        $recientes = Servicio::where('conductor_id', $conductor->id)
            ->with('cliente')
            ->orderByDesc('creado_en')
            ->limit(5)
            ->get()
            ->map(fn($s) => [
                'id'               => $s->id,
                'tipo'             => $s->tipo,
                'estatus'          => $s->estatus,
                'cliente_nombre'   => $s->cliente->nombre ?? 'Cliente',
                'direccion_origen' => $s->direccion_origen,
                'direccion_destino'=> $s->direccion_destino,
                'total_final'      => $s->total_final,
                'creado_en'        => $s->creado_en,
            ]);

        return response()->json([
            'conductor'      => $conductor->load('puntoRecoleccion'),
            'servicioActivo' => $servicioActivo ? [
                'id'               => $servicioActivo->id,
                'tipo'             => $servicioActivo->tipo,
                'estatus'          => $servicioActivo->estatus,
                'cliente_nombre'   => $servicioActivo->cliente->nombre ?? 'Cliente',
                'direccion_origen' => $servicioActivo->direccion_origen,
                'direccion_destino'=> $servicioActivo->direccion_destino,
                'total_final'      => $servicioActivo->total_final,
            ] : null,
            'stats'     => $stats,
            'recientes' => $recientes,
        ]);
    }

    // ═══════════════════════════════════════
    // CONDUCTOR: conectar/desconectar
    // ═══════════════════════════════════════

    public function conductorConectar(Request $request)
    {
        $request->validate([
            'punto_recoleccion_id' => 'required|exists:puntos_recoleccion,id',
        ]);

        Auth::user()->conductor->update([
            'esta_conectado'       => true,
            'punto_recoleccion_id' => $request->punto_recoleccion_id,
        ]);

        return response()->json(['message' => 'Conectado.']);
    }

    public function conductorDesconectar()
    {
        Auth::user()->conductor->update([
            'esta_conectado'       => false,
            'punto_recoleccion_id' => null,
        ]);

        return response()->json(['message' => 'Desconectado.']);
    }

    // ═══════════════════════════════════════
    // CONDUCTOR: polling pendientes
    // ═══════════════════════════════════════

    public function conductorPendientes()
    {
        $conductor = Auth::user()->conductor;

        if (!$conductor || !$conductor->esta_conectado) {
            return response()->json(['servicios' => []]);
        }

        $query = Servicio::where('estatus', 'buscando')
            ->whereNull('conductor_id')
            ->with('cliente')
            ->limit(10);

        if ($conductor->punto_recoleccion_id) {
            $query->orderByRaw("CASE WHEN punto_recoleccion_id = ? THEN 0 ELSE 1 END", [$conductor->punto_recoleccion_id])
                  ->orderBy('creado_en', 'desc');
        } else {
            $query->orderBy('creado_en', 'desc');
        }

        $servicios = $query->get()->map(fn($s) => [
            'id'               => $s->id,
            'tipo'             => $s->tipo,
            'cliente_nombre'   => $s->cliente->nombre ?? 'Cliente',
            'direccion_origen' => $s->direccion_origen,
            'direccion_destino'=> $s->direccion_destino,
            'distancia_km'     => $s->distancia_km,
            'total_final'      => $s->total_final,
            'creado_en'        => $s->creado_en,
        ]);

        return response()->json(['servicios' => $servicios]);
    }

    // ═══════════════════════════════════════
    // CONDUCTOR: aceptar / rechazar
    // ═══════════════════════════════════════

    public function conductorAceptar($id)
    {
        $conductor = Auth::user()->conductor;

        $servicio = Servicio::where('id', $id)
            ->where('estatus', 'buscando')
            ->whereNull('conductor_id')
            ->first();

        if (!$servicio) {
            return response()->json(['ok' => false, 'message' => 'Servicio ya tomado.'], 409);
        }

        $servicio->update([
            'conductor_id' => $conductor->id,
            'estatus'      => 'aceptado',
            'iniciado_en'  => now(),
        ]);

        return response()->json(['ok' => true, 'servicio_id' => $servicio->id]);
    }

    public function conductorRechazar($id)
    {
        return response()->json(['ok' => true]);
    }

    // ═══════════════════════════════════════
    // CONDUCTOR: actualizar estatus
    // ═══════════════════════════════════════

    public function conductorActualizarEstatus(Request $request, $id)
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

        return response()->json(['message' => 'Estatus actualizado.', 'estatus' => $request->estatus]);
    }

    // ═══════════════════════════════════════
    // ADMIN: dashboard
    // ═══════════════════════════════════════

    public function adminDashboard()
    {
        $user = Auth::user();
        if ($user->rol !== 'super_admin') {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $stats = [
            'total_ingresos'        => Servicio::where('estatus', 'completado')->sum('total_final'),
            'conductores_activos'   => Conductor::where('esta_conectado', true)->count(),
            'conductores_total'     => Conductor::where('estatus', 'activo')->count(),
            'solicitudes_pendientes'=> Conductor::where('estatus', 'pendiente')->count(),
            'total_usuarios'        => Usuario::where('rol', 'usuario')->count(),
            'viajes_hoy'            => Servicio::whereDate('creado_en', today())->count(),
            'viajes_mes'            => Servicio::whereMonth('creado_en', now()->month)->count(),
            'calificacion_promedio' => round(Conductor::where('estatus', 'activo')->avg('calificacion_promedio') ?? 0, 1),
        ];

        $ganancias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $ganancias[] = [
                'fecha' => $fecha->format('d M'),
                'total' => (float) Servicio::where('estatus', 'completado')
                    ->whereDate('creado_en', $fecha)->sum('total_final'),
            ];
        }

        $serviciosRecientes = Servicio::with(['cliente', 'conductor.usuario'])
            ->orderByDesc('creado_en')->limit(10)->get()->map(fn($s) => [
                'id'               => $s->id,
                'tipo'             => $s->tipo,
                'estatus'          => $s->estatus,
                'cliente_nombre'   => $s->cliente->nombre ?? '-',
                'conductor_nombre' => $s->conductor?->usuario?->nombre ?? 'Sin asignar',
                'total_final'      => $s->total_final,
                'direccion_origen' => $s->direccion_origen,
                'creado_en'        => $s->creado_en,
            ]);

        $conductores = Conductor::with('usuario')->orderByDesc('id')->limit(20)->get()->map(fn($c) => [
            'id'               => $c->id,
            'nombre'           => $c->usuario->nombre ?? '-',
            'vehiculo'         => ucfirst($c->tipo_vehiculo) . ' ' . $c->placa,
            'estatus'          => $c->estatus,
            'esta_conectado'   => $c->esta_conectado,
            'calificacion'     => $c->calificacion_promedio,
        ]);

        $usuarios = Usuario::where('rol', 'usuario')->orderByDesc('creado_en')->limit(20)->get()->map(fn($u) => [
            'id'       => $u->id,
            'nombre'   => $u->nombre,
            'email'    => $u->email,
            'telefono' => $u->telefono,
            'estatus'  => $u->estatus,
        ]);

        return response()->json(compact('stats', 'ganancias', 'serviciosRecientes', 'conductores', 'usuarios'));
    }

    public function adminAprobarConductor($id)
    {
        if (Auth::user()->rol !== 'super_admin') {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        $conductor = Conductor::findOrFail($id);
        $conductor->update(['estatus' => 'activo']);
        return response()->json(['message' => 'Conductor aprobado.']);
    }

    public function adminRechazarConductor($id)
    {
        if (Auth::user()->rol !== 'super_admin') {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        $conductor = Conductor::findOrFail($id);
        $conductor->update(['estatus' => 'suspendido']);
        return response()->json(['message' => 'Conductor rechazado.']);
    }

    public function adminToggleUsuario($id)
    {
        if (Auth::user()->rol !== 'super_admin') {
            return response()->json(['message' => 'No autorizado.'], 403);
        }
        $usuario = Usuario::findOrFail($id);
        $estatus = $usuario->estatus === 'activo' ? 'bloqueado' : 'activo';
        $usuario->update(['estatus' => $estatus]);
        return response()->json(['message' => "Usuario {$estatus}.", 'estatus' => $estatus]);
    }

    // ═══════════════════════════════════════
    // Helpers
    // ═══════════════════════════════════════

    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return round($r * 2 * atan2(sqrt($a), sqrt(1 - $a)), 2);
    }

    private function tarifa(float $km): float
    {
        return round(15.00 + ($km * 8.00), 2);
    }
}
