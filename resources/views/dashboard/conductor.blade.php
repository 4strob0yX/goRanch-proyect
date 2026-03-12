@extends('layouts.app')
@section('title', 'Dashboard Conductor - goRanch')

@push('styles')
<style>
    body { background: #111a10; color: white; min-height: 100vh; }

    /* Layout */
    .conductor-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

    /* Sidebar */
    .sidebar { background: #0d130c; border-right: 1px solid rgba(255,255,255,.06); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; width: 260px; z-index: 50; }
    .sidebar-header { padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.06); }
    .sidebar-brand { display: flex; align-items: center; gap: .6rem; font-family: 'Syne', sans-serif; font-weight: 700; color: white; font-size: 1.1rem; text-decoration: none; }
    .sidebar-role { font-size: .7rem; color: var(--verde); font-weight: 600; letter-spacing: .08em; text-transform: uppercase; margin-top: .2rem; }

    .conductor-profile { padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; gap: .9rem; }
    .conductor-avatar { width: 44px; height: 44px; border-radius: 50%; background: var(--verde); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; color: white; flex-shrink: 0; }
    .conductor-name { font-weight: 700; font-size: .95rem; }
    .conductor-vehicle { font-size: .78rem; color: rgba(255,255,255,.5); margin-top: .1rem; }

    /* Status toggle */
    .status-card { margin: 1rem; background: rgba(255,255,255,.04); border-radius: 14px; padding: 1.1rem; border: 1px solid rgba(255,255,255,.07); }
    .status-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .8rem; }
    .status-label { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.5); }
    .status-dot-wrap { display: flex; align-items: center; gap: .4rem; }
    .status-dot { width: 9px; height: 9px; border-radius: 50%; }
    .dot-active { background: #4ade80; box-shadow: 0 0 8px #4ade80; }
    .dot-inactive { background: #6b7280; }
    .status-text { font-size: .8rem; font-weight: 600; }

    /* Punto selector */
    .punto-select { width: 100%; padding: .55rem .8rem; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 9px; color: white; font-family: 'DM Sans', sans-serif; font-size: .85rem; outline: none; margin-bottom: .8rem; }
    .punto-select option { background: #1a2118; color: white; }
    .punto-select:disabled { opacity: .4; cursor: not-allowed; }

    .btn-conectar { width: 100%; padding: .65rem; border-radius: 10px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: .875rem; border: none; cursor: pointer; transition: all .2s; }
    .btn-conectar-on  { background: var(--verde); color: white; }
    .btn-conectar-on:hover  { background: var(--verde-oscuro); }
    .btn-conectar-off { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.3); }
    .btn-conectar-off:hover { background: rgba(239,68,68,.25); }

    .sidebar-nav { flex: 1; padding: .8rem .7rem; overflow-y: auto; }
    .sidebar-item { display: flex; align-items: center; gap: .75rem; padding: .7rem .9rem; border-radius: 10px; text-decoration: none; color: rgba(255,255,255,.45); font-size: .875rem; font-weight: 500; margin-bottom: .15rem; transition: all .15s; }
    .sidebar-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.8); }
    .sidebar-item.active { background: rgba(74,222,128,.12); color: #4ade80; }

    .sidebar-footer { padding: 1rem 1.2rem; border-top: 1px solid rgba(255,255,255,.06); }
    .sidebar-logout { display: flex; align-items: center; gap: .6rem; color: rgba(255,255,255,.4); font-size: .875rem; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; width: 100%; transition: color .15s; }
    .sidebar-logout:hover { color: #f87171; }

    /* Main */
    .main-content { margin-left: 260px; }
    .top-bar { padding: 1.2rem 2rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,.06); background: rgba(255,255,255,.02); }
    .top-bar-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; }
    .top-bar-date { font-size: .85rem; color: rgba(255,255,255,.4); }

    .page-body { padding: 2rem; }

    /* Stats */
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07); border-radius: 16px; padding: 1.3rem; }
    .stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: .9rem; }
    .stat-value { font-family: 'Syne', sans-serif; font-size: 1.9rem; font-weight: 800; }
    .stat-label { font-size: .8rem; color: rgba(255,255,255,.45); margin-top: .2rem; }

    /* Punto activo info */
    .punto-info-card { background: rgba(74,222,128,.07); border: 1px solid rgba(74,222,128,.2); border-radius: 14px; padding: 1rem 1.3rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; }
    .punto-info-icon { width: 40px; height: 40px; border-radius: 50%; background: rgba(74,222,128,.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .punto-info-name { font-weight: 700; font-size: .95rem; color: #4ade80; }
    .punto-info-dir { font-size: .8rem; color: rgba(255,255,255,.5); margin-top: .1rem; }

    /* Servicios recientes */
    .section-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
    .section-title span { font-size: .8rem; color: rgba(255,255,255,.4); font-family: 'DM Sans', sans-serif; font-weight: 400; }

    .servicio-item { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07); border-radius: 14px; padding: 1rem 1.2rem; margin-bottom: .7rem; display: flex; justify-content: space-between; align-items: center; }
    .servicio-left { display: flex; align-items: center; gap: .9rem; }
    .servicio-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .servicio-ruta { font-weight: 600; font-size: .9rem; }
    .servicio-meta { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: .2rem; }
    .servicio-monto { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; color: #4ade80; }

    /* Actualizar estatus */
    .estatus-form { display: flex; gap: .5rem; align-items: center; margin-top: .5rem; }
    .estatus-select { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 8px; color: white; font-family: 'DM Sans', sans-serif; font-size: .78rem; padding: .3rem .6rem; outline: none; }
    .estatus-select option { background: #1a2118; }
    .btn-estatus { background: var(--verde); color: white; border: none; border-radius: 8px; padding: .3rem .7rem; font-size: .78rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; }

    .empty-msg { text-align: center; padding: 2.5rem; color: rgba(255,255,255,.3); font-size: .9rem; }

    @media (max-width: 900px) {
        .stats-row { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="conductor-layout">

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('conductor.dashboard') }}" class="sidebar-brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
                goRanch
            </a>
            <div class="sidebar-role">Panel Conductor</div>
        </div>

        {{-- Perfil --}}
        <div class="conductor-profile">
            <div class="conductor-avatar">{{ strtoupper(substr(auth()->user()->nombre, 0, 2)) }}</div>
            <div>
                <div class="conductor-name">{{ explode(' ', auth()->user()->nombre)[0] }}</div>
                <div class="conductor-vehicle">
                    {{ ucfirst($conductor->tipo_vehiculo) }} • {{ $conductor->marca }} {{ $conductor->modelo }}
                </div>
            </div>
        </div>

        {{-- Toggle conexión --}}
        <div class="status-card">
            <div class="status-top">
                <span class="status-label">Estado</span>
                <div class="status-dot-wrap">
                    <div class="status-dot {{ $conductor->esta_conectado ? 'dot-active' : 'dot-inactive' }}"></div>
                    <span class="status-text">{{ $conductor->esta_conectado ? 'Conectado' : 'Desconectado' }}</span>
                </div>
            </div>

            @if(!$conductor->esta_conectado)
                {{-- Formulario conectarse --}}
                <form method="POST" action="{{ route('conductor.conectar') }}">
                    @csrf
                    <select name="punto_recoleccion_id" class="punto-select" required>
                        <option value="">Selecciona tu punto...</option>
                        @foreach($puntos as $punto)
                            <option value="{{ $punto->id }}">📍 {{ $punto->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-conectar btn-conectar-on">
                        ⚡ Conectarme
                    </button>
                </form>
            @else
                {{-- Punto actual --}}
                <div style="font-size:.8rem; color:rgba(255,255,255,.5); margin-bottom:.6rem;">
                    📍 {{ $conductor->puntoRecoleccion->nombre ?? 'Sin punto' }}
                </div>
                <form method="POST" action="{{ route('conductor.desconectar') }}">
                    @csrf
                    <button type="submit" class="btn-conectar btn-conectar-off">
                        ✕ Desconectarme
                    </button>
                </form>
            @endif
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('conductor.dashboard') }}" class="sidebar-item active">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Inicio
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main-content">
        <div class="top-bar">
            <div class="top-bar-title">Mi Dashboard</div>
            <div class="top-bar-date">{{ now()->format('l, d M Y') }}</div>
        </div>

        <div class="page-body">

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1.5rem; background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.3); color:#4ade80; border-radius:12px; padding:.8rem 1.2rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(74,222,128,.12);">
                        <svg width="18" height="18" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="stat-value">{{ $stats['viajes_hoy'] }}</div>
                    <div class="stat-label">Viajes hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(74,222,128,.12);">
                        <svg width="18" height="18" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div class="stat-value">${{ number_format($stats['ganado_hoy'], 0) }}</div>
                    <div class="stat-label">Ganado hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(250,204,21,.1);">
                        <svg width="18" height="18" fill="#facc15" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div class="stat-value">{{ $stats['calificacion'] }}</div>
                    <div class="stat-label">Calificación</div>
                </div>
            </div>

            {{-- Punto activo --}}
            @if($conductor->esta_conectado && $conductor->puntoRecoleccion)
                <div class="punto-info-card">
                    <div class="punto-info-icon">
                        <svg width="20" height="20" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="punto-info-name">{{ $conductor->puntoRecoleccion->nombre }}</div>
                        <div class="punto-info-dir">{{ $conductor->puntoRecoleccion->direccion }}</div>
                    </div>
                    <div style="margin-left:auto; font-size:.78rem; color:#4ade80; background:rgba(74,222,128,.1); padding:.3rem .7rem; border-radius:999px; font-weight:700;">
                        Activo
                    </div>
                </div>
            @endif

            {{-- Servicios recientes --}}
            <div class="section-title">
                Servicios recientes
                <span>Últimos 5</span>
            </div>

            @if($serviciosRecientes->count() > 0)
                @foreach($serviciosRecientes as $s)
                    @php
                        $iconos  = ['viaje'=>'🚗','mandado_libre'=>'🛒','delivery_tienda'=>'🏪'];
                        $colores = ['completado'=>'rgba(74,222,128,.12)','cancelado'=>'rgba(239,68,68,.1)','buscando'=>'rgba(250,204,21,.1)','aceptado'=>'rgba(59,130,246,.1)','en_ruta'=>'rgba(59,130,246,.1)'];
                        $color   = $colores[$s->estatus] ?? 'rgba(255,255,255,.05)';
                    @endphp
                    <div class="servicio-item" style="background:{{ $color }};">
                        <div class="servicio-left">
                            <div class="servicio-icon" style="background:rgba(255,255,255,.06); font-size:1.2rem;">
                                {{ $iconos[$s->tipo] ?? '📦' }}
                            </div>
                            <div>
                                <div class="servicio-ruta">
                                    {{ Str::limit($s->direccion_origen, 20) }} → {{ Str::limit($s->direccion_destino, 20) }}
                                </div>
                                <div class="servicio-meta">
                                    {{ $s->cliente->nombre ?? 'Cliente' }} •
                                    {{ \Carbon\Carbon::parse($s->creado_en)->format('d/m H:i') }}
                                </div>

                                {{-- Actualizar estatus si está activo --}}
                                @if(!in_array($s->estatus, ['completado','cancelado']))
                                    <form method="POST" action="{{ route('conductor.servicio.estatus', $s->id) }}" class="estatus-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="estatus" class="estatus-select">
                                            <option value="en_sitio"   {{ $s->estatus === 'en_sitio' ? 'selected' : '' }}>En sitio</option>
                                            <option value="en_ruta"    {{ $s->estatus === 'en_ruta'  ? 'selected' : '' }}>En ruta</option>
                                            <option value="completado">Completado ✓</option>
                                        </select>
                                        <button type="submit" class="btn-estatus">Actualizar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div class="servicio-monto">${{ number_format($s->total_final, 2) }}</div>
                            <div style="font-size:.75rem; color:rgba(255,255,255,.35); margin-top:.2rem;">
                                {{ ucfirst(str_replace('_',' ',$s->estatus)) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-msg">
                    Aún no tienes servicios.<br>
                    <span style="font-size:.8rem;">Conéctate para empezar a recibir solicitudes.</span>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
