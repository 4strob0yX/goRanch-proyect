@extends('layouts.app')
@section('title', 'Admin - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }

    .layout { display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar { .sidebar { background: var(--verde-oscuro); position: sticky; top: 0; height: 100vh; width: 240px; min-width: 240px; display: flex; flex-direction: column; z-index: 50; overflow-y: auto; }
    .sb-brand { padding: 1.3rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .6rem; font-family: var(--font-display); font-weight: 700; color: white; font-size: 1.05rem; }
    .sb-brand-dot { width: 28px; height: 28px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; }
    .sb-label { padding: .8rem 1rem .3rem; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); }
    .sb-item { display: flex; align-items: center; gap: .7rem; padding: .6rem 1rem; border-radius: var(--r-sm); text-decoration: none; color: rgba(255,255,255,.5); font-size: .85rem; font-weight: 500; margin: .1rem .5rem; transition: all .15s; }
    .sb-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.85); }
    .sb-item.active { background: rgba(255,255,255,.1); color: white; }
    .sb-footer { margin-top: auto; padding: 1rem; border-top: 1px solid rgba(255,255,255,.08); }
    .sb-logout { display: flex; align-items: center; gap: .6rem; color: rgba(255,255,255,.35); font-size: .85rem; background: none; border: none; cursor: pointer; font-family: var(--font-body); width: 100%; padding: .5rem; border-radius: var(--r-sm); transition: all .15s; }
    .sb-logout:hover { color: #fca5a5; background: rgba(239,68,68,.08); }

    /* Main */
    .main { flex: 1; min-width: 0; }
    .main-header { background: var(--blanco); border-bottom: 1px solid var(--borde); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 40; }
    .main-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; }
    .main-sub { font-size: .82rem; color: var(--gris); }
    .admin-badge { background: var(--verde-bg); color: var(--verde-oscuro); font-size: .72rem; font-weight: 700; padding: .25rem .8rem; border-radius: var(--r-full); border: 1px solid var(--verde-claro); }

    .main-body { padding: 1.8rem 2rem; max-width: 1100px; }

    /* Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); padding: 1.3rem; display: flex; flex-direction: column; gap: .6rem; }
    .stat-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .stat-icon { width: 38px; height: 38px; border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; }
    .stat-num { font-family: var(--font-display); font-size: 2rem; font-weight: 700; line-height: 1; }
    .stat-lbl { font-size: .78rem; color: var(--gris); }
    .stat-delta { font-size: .72rem; font-weight: 600; display: flex; align-items: center; gap: .25rem; }
    .delta-up { color: var(--verde); }
    .delta-down { color: var(--rojo); }

    /* Grid 2 cols */
    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }

    /* Sección card */
    .section-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; }
    .sc-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--borde); display: flex; justify-content: space-between; align-items: center; }
    .sc-title { font-family: var(--font-display); font-weight: 700; font-size: .95rem; }
    .sc-link { font-size: .78rem; color: var(--verde); font-weight: 600; text-decoration: none; }

    /* Tabla servicios */
    .mini-table { width: 100%; border-collapse: collapse; }
    .mini-table th { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--gris); padding: .6rem 1.3rem; text-align: left; border-bottom: 1px solid var(--borde); background: var(--fondo); }
    .mini-table td { padding: .75rem 1.3rem; font-size: .85rem; border-bottom: 1px solid var(--gris-claro); }
    .mini-table tr:last-child td { border-bottom: none; }
    .mini-table tr:hover td { background: var(--fondo); }
    .tipo-chip { display: inline-flex; align-items: center; gap: .3rem; font-size: .75rem; font-weight: 600; }

    /* Conductores pendientes */
    .pend-item { padding: .9rem 1.3rem; border-bottom: 1px solid var(--borde); display: flex; align-items: center; gap: .8rem; }
    .pend-item:last-child { border-bottom: none; }
    .pend-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .82rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .pend-name { font-weight: 600; font-size: .875rem; }
    .pend-meta { font-size: .75rem; color: var(--gris); margin-top: .1rem; }
    .pend-actions { margin-left: auto; display: flex; gap: .4rem; }

    /* Gráfica simple */
    .bar-chart { padding: 1.2rem 1.3rem; display: flex; align-items: flex-end; gap: .5rem; height: 110px; }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: .35rem; }
    .bar { width: 100%; border-radius: 4px 4px 0 0; background: var(--verde-claro); transition: background .15s; min-height: 4px; }
    .bar:hover { background: var(--verde); }
    .bar-lbl { font-size: .65rem; color: var(--gris); }

    .empty-pend { padding: 2rem; text-align: center; color: var(--gris); font-size: .875rem; }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .two-col { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .sidebar { display: none; }
        .main { width: 100%; }
        .main-body { padding: 1rem; }
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
        <a href="{{ route('admin.dashboard') }}" class="sb-item active">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.conductores') }}" class="sb-item">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
            Conductores
        </a>
        <a href="{{ route('admin.usuarios') }}" class="sb-item">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Usuarios
        </a>
        <a href="{{ route('admin.servicios') }}" class="sb-item">
        <div class="sb-label">Configuración</div>
        <a href="{{ route('admin.puntos') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Puntos</a>
        <a href="{{ route('admin.admins') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Admins</a>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Servicios
        </a>
        <div class="sb-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="main-header">
            <div>
                <div class="main-title">Dashboard</div>
                <div class="main-sub">{{ now()->isoFormat('dddd, D [de] MMMM YYYY') }}</div>
            </div>
            <span class="admin-badge">Super Admin</span>
        </div>

        <div class="main-body">

            @php
                $totalUsuarios   = \App\Models\Usuario::where('rol','usuario')->count();
                $totalConductores = \App\Models\Conductor::count();
                $pendientes      = \App\Models\Conductor::where('estatus','pendiente')->count();
                $totalServicios  = \App\Models\Servicio::count();
                $hoy             = \App\Models\Servicio::whereDate('creado_en', today())->count();
                $ingresos        = \App\Models\Servicio::where('estatus','completado')->sum('total_final');
                $recientes       = \App\Models\Servicio::with(['cliente','conductor.usuario'])->orderByDesc('creado_en')->limit(6)->get();
                $conductoresPend = \App\Models\Conductor::with('usuario')->where('estatus','pendiente')->limit(4)->get();

                // Servicios por día últimos 7 días para gráfica
                $dias = collect(range(6,0))->map(fn($i) => [
                    'lbl' => now()->subDays($i)->format('D'),
                    'val' => \App\Models\Servicio::whereDate('creado_en', now()->subDays($i))->count(),
                ]);
                $maxVal = $dias->max('val') ?: 1;
            @endphp

            {{-- Stats --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-lbl">Usuarios</div>
                            <div class="stat-num">{{ $totalUsuarios }}</div>
                        </div>
                        <div class="stat-icon" style="background:var(--verde-bg);">
                            <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-lbl">Conductores</div>
                            <div class="stat-num">{{ $totalConductores }}</div>
                        </div>
                        <div class="stat-icon" style="background:var(--azul-bg);">
                            <svg width="18" height="18" fill="none" stroke="var(--azul)" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
                        </div>
                    </div>
                    @if($pendientes > 0)
                        <span class="badge badge-yellow">{{ $pendientes }} por revisar</span>
                    @endif
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-lbl">Servicios hoy</div>
                            <div class="stat-num">{{ $hoy }}</div>
                        </div>
                        <div class="stat-icon" style="background:#f3e8ff;">
                            <svg width="18" height="18" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div>
                            <div class="stat-lbl">Ingresos totales</div>
                            <div class="stat-num" style="font-size:1.5rem;">${{ number_format($ingresos, 0) }}</div>
                        </div>
                        <div class="stat-icon" style="background:var(--amarillo-bg);">
                            <svg width="18" height="18" fill="none" stroke="var(--amarillo)" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="two-col">
                {{-- Gráfica servicios últimos 7 días --}}
                <div class="section-card">
                    <div class="sc-header">
                        <span class="sc-title">Servicios — últimos 7 días</span>
                    </div>
                    <div class="bar-chart">
                        @foreach($dias as $d)
                            <div class="bar-col">
                                <div class="bar" style="height:{{ max(4, ($d['val'] / $maxVal) * 70) }}px;" title="{{ $d['val'] }} servicios"></div>
                                <div class="bar-lbl">{{ $d['lbl'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Conductores pendientes --}}
                <div class="section-card">
                    <div class="sc-header">
                        <span class="sc-title">Conductores pendientes</span>
                        <a href="{{ route('admin.conductores') }}" class="sc-link">Ver todos →</a>
                    </div>
                    @forelse($conductoresPend as $c)
                        <div class="pend-item">
                            <div class="pend-avatar">{{ strtoupper(substr($c->usuario->nombre, 0, 2)) }}</div>
                            <div>
                                <div class="pend-name">{{ $c->usuario->nombre }}</div>
                                <div class="pend-meta">{{ ucfirst($c->tipo_vehiculo) }} · {{ $c->placa }}</div>
                            </div>
                            <div class="pend-actions">
                                <form method="POST" action="{{ route('admin.conductores.aprobar', $c->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm" style="background:var(--verde-bg); color:var(--verde-oscuro); border-radius:var(--r-sm);">✓</button>
                                </form>
                                <form method="POST" action="{{ route('admin.conductores.rechazar', $c->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger" style="border-radius:var(--r-sm);">✕</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-pend">Sin pendientes ✓</div>
                    @endforelse
                </div>
            </div>

            {{-- Servicios recientes --}}
            <div class="section-card">
                <div class="sc-header">
                    <span class="sc-title">Servicios recientes</span>
                    <a href="{{ route('admin.servicios') }}" class="sc-link">Ver todos →</a>
                </div>
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Cliente</th><th>Ruta</th><th>Tipo</th><th>Estatus</th><th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recientes as $s)
                            @php
                                $tipoLabel = ['viaje'=>'🚗 Viaje','mandado_libre'=>'🛒 Mandado','delivery_tienda'=>'🏪 Delivery'];
                                $estClass  = ['completado'=>'badge-green','cancelado'=>'badge-red','buscando'=>'badge-yellow','aceptado'=>'badge-blue','en_ruta'=>'badge-blue','en_sitio'=>'badge-blue'];
                            @endphp
                            <tr>
                                <td style="color:var(--gris); font-size:.78rem;">#{{ $s->id }}</td>
                                <td>{{ $s->cliente->nombre ?? '—' }}</td>
                                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--texto-2);">{{ $s->direccion_origen }} → {{ $s->direccion_destino }}</td>
                                <td><span class="tipo-chip">{{ $tipoLabel[$s->tipo] ?? $s->tipo }}</span></td>
                                <td><span class="badge {{ $estClass[$s->estatus] ?? 'badge-gray' }}">{{ ucfirst($s->estatus) }}</span></td>
                                <td style="font-weight:700; font-family:var(--font-display);">${{ number_format($s->total_final, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align:center; padding:2rem; color:var(--gris);">Sin servicios aún</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection