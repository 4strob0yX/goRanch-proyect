@extends('layouts.app')
@section('title', 'Servicios - Admin goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }

    .sidebar { background: var(--verde-oscuro); position: fixed; top: 0; left: 0; height: 100vh; width: 240px; display: flex; flex-direction: column; z-index: 50; overflow-y: auto; }
    .sb-brand { padding: 1.3rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .6rem; font-family: var(--font-display); font-weight: 700; color: white; font-size: 1.05rem; }
    .sb-brand-dot { width: 28px; height: 28px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .sb-label { padding: .8rem 1rem .3rem; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); }
    .sb-item { display: flex; align-items: center; gap: .7rem; padding: .6rem 1rem; border-radius: var(--r-sm); text-decoration: none; color: rgba(255,255,255,.5); font-size: .85rem; font-weight: 500; margin: .1rem .5rem; transition: all .15s; }
    .sb-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.85); }
    .sb-item.active { background: rgba(255,255,255,.1); color: white; }

    .main { margin-left: 240px; }
    .main-header { background: var(--blanco); border-bottom: 1px solid var(--borde); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 40; }
    .main-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; }
    .main-sub { font-size: .82rem; color: var(--gris); margin-top: .1rem; }
    .main-body { padding: 1.8rem 2rem; }

    /* Stats */
    .stats-strip { display: grid; grid-template-columns: repeat(4, 1fr); gap: .8rem; margin-bottom: 1.5rem; }
    .stat-mini { background: var(--blanco); border-radius: var(--r-md); border: 1px solid var(--borde); padding: .9rem 1rem; }
    .stat-mini-val { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; line-height: 1; }
    .stat-mini-lbl { font-size: .72rem; color: var(--gris); margin-top: .3rem; }

    /* Filtros */
    .filter-row { display: flex; gap: .5rem; margin-bottom: 1.2rem; flex-wrap: wrap; align-items: center; }
    .chip { padding: .35rem .9rem; border-radius: var(--r-full); font-size: .8rem; font-weight: 600; border: 1.5px solid var(--borde); background: var(--blanco); color: var(--gris); text-decoration: none; transition: all .15s; white-space: nowrap; }
    .chip:hover { border-color: var(--verde-claro); color: var(--verde-oscuro); }
    .chip.active { background: var(--verde-oscuro); color: white; border-color: var(--verde-oscuro); }
    .sep { color: var(--borde); }

    /* Tabla */
    .table-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .table-wrap { overflow-x: auto; }
    thead th { padding: .75rem 1.1rem; text-align: left; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); background: var(--fondo); border-bottom: 1px solid var(--borde); white-space: nowrap; }
    tbody td { padding: .8rem 1.1rem; font-size: .85rem; border-bottom: 1px solid var(--gris-claro); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: var(--fondo); }

    .ruta-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--texto-2); }
    .tipo-chip { display: inline-flex; align-items: center; gap: .3rem; font-size: .78rem; font-weight: 600; }
    .monto-cell { font-family: var(--font-display); font-weight: 700; color: var(--verde-oscuro); }

    .paginacion { padding: 1rem 1.2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--borde); font-size: .82rem; color: var(--gris); }

    @media (max-width: 900px) {
        .layout { grid-template-columns: 1fr; }
        .sidebar { display: none; }
        .main { margin-left: 0; }
        .main-body { padding: 1rem; }
        .stats-strip { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')
<div class="layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg></div>
            goRanch Admin
        </div>
        <div class="sb-label">Gestión</div>
        <a href="{{ route('admin.dashboard') }}"   class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
        <a href="{{ route('admin.conductores') }}"  class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>Conductores</a>
        <a href="{{ route('admin.usuarios') }}"    class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Usuarios</a>
        <a href="{{ route('admin.servicios') }}"   class="sb-item active"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Servicios</a>
        <div style="margin-top:auto; padding:1rem; border-top:1px solid rgba(255,255,255,.08);">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="sb-item" style="width:100%; background:none; border:none; cursor:pointer; color:rgba(255,255,255,.35);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="main-header">
            <div>
                <div class="main-title">Servicios</div>
                <div class="main-sub">Historial completo de viajes y mandados</div>
            </div>
        </div>

        <div class="main-body">
            @php
                $filtroTipo    = request('tipo', 'todos');
                $filtroEstatus = request('estatus', 'todos');

                $query = \App\Models\Servicio::with(['cliente','conductor.usuario'])->orderByDesc('creado_en');
                if($filtroTipo    !== 'todos') $query->where('tipo',    $filtroTipo);
                if($filtroEstatus !== 'todos') $query->where('estatus', $filtroEstatus);
                $servicios = $query->paginate(25);

                $total      = \App\Models\Servicio::count();
                $completados = \App\Models\Servicio::where('estatus','completado')->count();
                $activos    = \App\Models\Servicio::whereNotIn('estatus',['completado','cancelado'])->count();
                $ingresos   = \App\Models\Servicio::where('estatus','completado')->sum('total_final');

                $tipoLabel  = ['viaje'=>'🚗 Viaje','mandado_libre'=>'🛒 Mandado','delivery_tienda'=>'🏪 Delivery'];
                $estClass   = ['completado'=>'badge-green','cancelado'=>'badge-red','buscando'=>'badge-yellow','aceptado'=>'badge-blue','en_ruta'=>'badge-blue','en_sitio'=>'badge-blue'];
            @endphp

            {{-- Stats --}}
            <div class="stats-strip">
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ $total }}</div>
                    <div class="stat-mini-lbl">Total servicios</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--verde-oscuro);">{{ $completados }}</div>
                    <div class="stat-mini-lbl">Completados</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--azul);">{{ $activos }}</div>
                    <div class="stat-mini-lbl">En curso</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--verde-oscuro); font-size:1.2rem;">${{ number_format($ingresos, 0) }}</div>
                    <div class="stat-mini-lbl">Ingresos totales</div>
                </div>
            </div>

            {{-- Filtros tipo --}}
            <div class="filter-row">
                <a href="?tipo=todos&estatus={{ $filtroEstatus }}"          class="chip {{ $filtroTipo==='todos'           ?'active':'' }}">Todos</a>
                <a href="?tipo=viaje&estatus={{ $filtroEstatus }}"          class="chip {{ $filtroTipo==='viaje'           ?'active':'' }}">🚗 Viajes</a>
                <a href="?tipo=mandado_libre&estatus={{ $filtroEstatus }}"  class="chip {{ $filtroTipo==='mandado_libre'   ?'active':'' }}">🛒 Mandados</a>
                <a href="?tipo=delivery_tienda&estatus={{ $filtroEstatus }}" class="chip {{ $filtroTipo==='delivery_tienda'?'active':'' }}">🏪 Delivery</a>
                <span class="sep">|</span>
                <a href="?tipo={{ $filtroTipo }}&estatus=todos"         class="chip {{ $filtroEstatus==='todos'       ?'active':'' }}">Todos</a>
                <a href="?tipo={{ $filtroTipo }}&estatus=buscando"      class="chip {{ $filtroEstatus==='buscando'    ?'active':'' }}">Buscando</a>
                <a href="?tipo={{ $filtroTipo }}&estatus=en_ruta"       class="chip {{ $filtroEstatus==='en_ruta'     ?'active':'' }}">En ruta</a>
                <a href="?tipo={{ $filtroTipo }}&estatus=completado"    class="chip {{ $filtroEstatus==='completado'  ?'active':'' }}">Completados</a>
                <a href="?tipo={{ $filtroTipo }}&estatus=cancelado"     class="chip {{ $filtroEstatus==='cancelado'   ?'active':'' }}">Cancelados</a>
            </div>

            {{-- Tabla --}}
            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo</th>
                                <th>Cliente</th>
                                <th>Conductor</th>
                                <th>Ruta</th>
                                <th>Estatus</th>
                                <th>Total</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servicios as $s)
                                <tr>
                                    <td style="color:var(--gris); font-size:.78rem; font-weight:600;">#{{ $s->id }}</td>
                                    <td><span class="tipo-chip">{{ $tipoLabel[$s->tipo] ?? $s->tipo }}</span></td>
                                    <td>
                                        <div style="font-weight:600; font-size:.85rem;">{{ $s->cliente->nombre ?? '—' }}</div>
                                        <div style="font-size:.72rem; color:var(--gris);">{{ $s->cliente->telefono ?? '' }}</div>
                                    </td>
                                    <td style="font-size:.82rem; color:var(--texto-2);">{{ $s->conductor?->usuario?->nombre ?? '—' }}</td>
                                    <td class="ruta-cell" title="{{ $s->direccion_origen }} → {{ $s->direccion_destino }}">
                                        {{ $s->direccion_origen }} → {{ $s->direccion_destino }}
                                    </td>
                                    <td><span class="badge {{ $estClass[$s->estatus] ?? 'badge-gray' }}">{{ ucfirst(str_replace('_',' ',$s->estatus)) }}</span></td>
                                    <td class="monto-cell">${{ number_format($s->total_final, 2) }}</td>
                                    <td style="font-size:.75rem; color:var(--gris); white-space:nowrap;">{{ \Carbon\Carbon::parse($s->creado_en)->format('d/m/y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align:center; padding:3rem; color:var(--gris);">
                                        Sin servicios con estos filtros.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($servicios->hasPages())
                    <div class="paginacion">
                        <span>{{ $servicios->firstItem() }}–{{ $servicios->lastItem() }} de {{ $servicios->total() }}</span>
                        <div>{{ $servicios->withQueryString()->links() }}</div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
