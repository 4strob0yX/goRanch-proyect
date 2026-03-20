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
        $filtro = $request->get('estatus', 'todos');

        $conductores = Conductor::with(['usuario', 'documentos'])
            ->when($filtro !== 'todos', fn($q) => $q->where('estatus', $filtro))
            ->orderByDesc('id')
            ->get();

        $stats = [
            'totales'    => Conductor::count(),
            'activos'    => Conductor::where('estatus', 'activo')->count(),
            'pendientes' => Conductor::where('estatus', 'pendiente')->count(),
            'conectados' => Conductor::where('esta_conectado', true)->count(),
        ];

        return view('admin.conductores', compact('conductores', 'filtro', 'stats'));
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

    // -----------------------------------------------
    // Puntos de Recolección
    // -----------------------------------------------

    public function puntos()
    {
        $puntos = \App\Models\PuntoRecoleccion::orderBy('nombre')->get();
        return view('admin.puntos', compact('puntos'));
    }

    public function storePunto(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'direccion' => 'required|string',
            'lat'       => 'required|numeric',
            'lng'       => 'required|numeric',
        ]);

        \Illuminate\Support\Facades\DB::statement(
            "INSERT INTO puntos_recoleccion (nombre, direccion, ubicacion, activo) VALUES (?, ?, ST_GeomFromText(?), 1)",
            [$request->nombre, $request->direccion, "POINT({$request->lng} {$request->lat})"]
        );

        return back()->with('success', "Punto '{$request->nombre}' creado correctamente.");
    }

    public function updatePunto(Request $request, $id)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'direccion' => 'required|string',
        ]);

        $punto = \App\Models\PuntoRecoleccion::findOrFail($id);
        $punto->update([
            'nombre'    => $request->nombre,
            'direccion' => $request->direccion,
        ]);

        return back()->with('success', "Punto actualizado correctamente.");
    }

    public function togglePunto($id)
    {
        $punto = \App\Models\PuntoRecoleccion::findOrFail($id);
        $punto->update(['activo' => !$punto->activo]);
        $estado = $punto->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Punto '{$punto->nombre}' {$estado}.");
    }

    // -----------------------------------------------
    // Administradores
    // -----------------------------------------------

    public function admins()
    {
        $admins = Usuario::where('rol', 'super_admin')->orderByDesc('id')->get();
        return view('admin.admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:191',
            'email'    => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string|max:20|unique:usuarios,telefono',
            'password' => 'required|min:8',
        ]);

        Usuario::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => bcrypt($request->password),
            'rol'      => 'super_admin',
            'estatus'  => 'activo',
        ]);

        return back()->with('success', "Administrador '{$request->nombre}' creado correctamente.");
    }

    public function toggleAdmin(Usuario $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }
        $estatus = $usuario->estatus === 'activo' ? 'bloqueado' : 'activo';
        $usuario->update(['estatus' => $estatus]);
        $msg = $estatus === 'activo' ? 'activado' : 'desactivado';
        return back()->with('success', "Admin '{$usuario->nombre}' {$msg}.");
    }

}