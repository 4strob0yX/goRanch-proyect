<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function usuario()
    {
        return view('dashboard.usuario');
    }

    public function misViajes(Request $request)
    {
        $filtro = $request->get('filtro', 'todos');

        $query = Servicio::with(['conductor.usuario'])
            ->where('cliente_id', Auth::id())
            ->orderByDesc('creado_en');

        if (in_array($filtro, ['viaje', 'mandado_libre', 'delivery_tienda'])) {
            $query->where('tipo', $filtro);
        } elseif (in_array($filtro, ['completado', 'cancelado', 'buscando'])) {
            $query->where('estatus', $filtro);
        }

        $servicios = $query->paginate(8)->appends(['filtro' => $filtro]);

        $stats = [
            'total'       => Servicio::where('cliente_id', Auth::id())->count(),
            'completados' => Servicio::where('cliente_id', Auth::id())->where('estatus', 'completado')->count(),
            'gastado'     => Servicio::where('cliente_id', Auth::id())->where('estatus', 'completado')->sum('total_final'),
        ];

        return view('usuario.mis-viajes', compact('servicios', 'stats', 'filtro'));
    }

    public function perfil()
    {
        return view('perfil.index');
    }

    public function conductor()
    {
        return view('dashboard.conductor');
    }

    public function adminTienda()
    {
        return view('dashboard.admin_tienda');
    }

    public function superAdmin()
    {
        return view('dashboard.super_admin');
    }
}