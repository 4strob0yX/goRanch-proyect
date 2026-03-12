<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // -----------------------------------------------
    // Dashboard principal
    // -----------------------------------------------

    public function dashboard()
    {
        $stats = [
            'total_ingresos'       => Servicio::where('estatus', 'completado')->sum('total_final'),
            'conductores_activos'  => Conductor::where('esta_conectado', true)->count(),
            'conductores_total'    => Conductor::where('estatus', 'activo')->count(),
            'solicitudes_pendientes' => Conductor::where('estatus', 'pendiente')->count(),
            'calificacion_promedio'=> round(Conductor::where('estatus', 'activo')->avg('calificacion_promedio'), 1),
            'total_usuarios'       => Usuario::where('rol', 'usuario')->count(),
            'viajes_hoy'           => Servicio::whereDate('creado_en', today())->count(),
            'viajes_mes'           => Servicio::whereMonth('creado_en', now()->month)->count(),
        ];

        // Ganancias últimos 7 días para la gráfica
        $ganancias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $ganancias[] = [
                'fecha'  => $fecha->format('d M'),
                'total'  => Servicio::where('estatus', 'completado')
                                ->whereDate('creado_en', $fecha)
                                ->sum('total_final'),
            ];
        }

        $serviciosRecientes = Servicio::with(['cliente', 'conductor.usuario'])
            ->orderByDesc('creado_en')
            ->limit(8)
            ->get();

        return view('dashboard.super_admin', compact('stats', 'ganancias', 'serviciosRecientes'));
    }

    // -----------------------------------------------
    // Conductores
    // -----------------------------------------------

    public function conductores(Request $request)
    {
        $estatus = $request->get('estatus', 'pendiente');

        $conductores = Conductor::with(['usuario', 'documentos'])
            ->when($estatus !== 'todos', fn($q) => $q->where('estatus', $estatus))
            ->orderByDesc('creado_en')
            ->paginate(15);

        return view('admin.conductores', compact('conductores', 'estatus'));
    }

    public function aprobarConductor(Conductor $conductor)
    {
        $conductor->update(['estatus' => 'activo']);
        return back()->with('success', "Conductor {$conductor->usuario->nombre} aprobado.");
    }

    public function rechazarConductor(Conductor $conductor)
    {
        $conductor->update(['estatus' => 'suspendido']);
        return back()->with('success', "Conductor {$conductor->usuario->nombre} rechazado.");
    }

    // -----------------------------------------------
    // Usuarios
    // -----------------------------------------------

    public function usuarios(Request $request)
    {
        $busqueda = $request->get('q');

        $usuarios = Usuario::where('rol', 'usuario')
            ->when($busqueda, fn($q) => $q->where('nombre', 'like', "%$busqueda%")
                ->orWhere('email', 'like', "%$busqueda%")
                ->orWhere('telefono', 'like', "%$busqueda%"))
            ->orderByDesc('creado_en')
            ->paginate(15);

        return view('admin.usuarios', compact('usuarios', 'busqueda'));
    }

    public function bloquearUsuario(Usuario $usuario)
    {
        $estatus = $usuario->estatus === 'activo' ? 'bloqueado' : 'activo';
        $usuario->update(['estatus' => $estatus]);
        $msg = $estatus === 'bloqueado' ? 'bloqueado' : 'desbloqueado';
        return back()->with('success', "Usuario {$usuario->nombre} {$msg}.");
    }

    // -----------------------------------------------
    // Servicios
    // -----------------------------------------------

    public function servicios(Request $request)
    {
        $tipo   = $request->get('tipo', 'todos');
        $estatus = $request->get('estatus', 'todos');

        $servicios = Servicio::with(['cliente', 'conductor.usuario'])
            ->when($tipo !== 'todos', fn($q) => $q->where('tipo', $tipo))
            ->when($estatus !== 'todos', fn($q) => $q->where('estatus', $estatus))
            ->orderByDesc('creado_en')
            ->paginate(15);

        return view('admin.servicios', compact('servicios', 'tipo', 'estatus'));
    }
}
