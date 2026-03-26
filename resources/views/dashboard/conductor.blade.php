@extends('layouts.app')
@section('title', 'Panel Conductor - goRanch')

@push('styles')
<style>
    body { background: #111c0e; color: white; }
    body::before { display: none; }

    .layout { display: flex; min-height: 100vh; }

    /* ── Sidebar ── */
    .sidebar { background: #0c1509; border-right: 1px solid rgba(255,255,255,.06); position: sticky; top: 0; height: 100vh; width: 260px; min-width: 260px; display: flex; flex-direction: column; z-index: 50; overflow-y: auto; }

    .sb-header { padding: 1.4rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; gap: .7rem; }
    .sb-logo { width: 34px; height: 34px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .sb-brand { font-family: var(--font-display); font-weight: 700; color: white; font-size: 1rem; }
    .sb-role { font-size: .68rem; color: rgba(255,255,255,.35); letter-spacing: .08em; text-transform: uppercase; }

    .sb-profile { padding: 1rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; gap: .8rem; }
    .sb-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--verde); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: .95rem; color: white; flex-shrink: 0; }
    .sb-name { font-weight: 600; font-size: .875rem; color: white; }
    .sb-vehicle { font-size: .75rem; color: rgba(255,255,255,.4); margin-top: .1rem; }

    /* Conexión toggle */
    .sb-status { margin: .8rem; background: rgba(255,255,255,.04); border-radius: var(--r-md); padding: 1rem; border: 1px solid rgba(255,255,255,.07); }
    .sb-status-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .8rem; }
    .sb-status-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.4); }
    .status-pill { display: flex; align-items: center; gap: .35rem; font-size: .78rem; font-weight: 600; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-on  { background: #4ade80; box-shadow: 0 0 6px #4ade80; }
    .dot-off { background: rgba(255,255,255,.25); }

    .punto-select { width: 100%; padding: .55rem .8rem; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: var(--r-sm); color: white; font-family: var(--font-body); font-size: .82rem; outline: none; margin-bottom: .7rem; }
    .punto-select option { background: #1a2d14; }
    .punto-actual { font-size: .8rem; color: rgba(255,255,255,.5); margin-bottom: .7rem; display: flex; align-items: center; gap: .4rem; }
    .punto-actual strong { color: #4ade80; }

    .btn-conectar { width: 100%; padding: .6rem; border-radius: var(--r-sm); font-family: var(--font-body); font-weight: 700; font-size: .82rem; border: none; cursor: pointer; transition: all .18s; }
    .btn-on  { background: var(--verde); color: white; }
    .btn-on:hover  { background: var(--verde-hover); }
    .btn-off { background: rgba(239,68,68,.15); color: #fca5a5; border: 1px solid rgba(239,68,68,.25); }
    .btn-off:hover { background: rgba(239,68,68,.25); }

    .sb-nav { flex: 1; padding: .6rem; overflow-y: auto; }
    .sb-item { display: flex; align-items: center; gap: .7rem; padding: .65rem .9rem; border-radius: var(--r-sm); text-decoration: none; color: rgba(255,255,255,.4); font-size: .85rem; font-weight: 500; margin-bottom: .15rem; transition: all .15s; }
    .sb-item:hover { background: rgba(255,255,255,.05); color: rgba(255,255,255,.75); }
    .sb-item.active { background: rgba(74,222,128,.1); color: #4ade80; }

    .sb-footer { padding: .9rem 1.2rem; border-top: 1px solid rgba(255,255,255,.06); }
    .sb-logout { display: flex; align-items: center; gap: .6rem; color: rgba(255,255,255,.35); font-size: .85rem; background: none; border: none; cursor: pointer; font-family: var(--font-body); width: 100%; padding: 0; transition: color .15s; }
    .sb-logout:hover { color: #fca5a5; }

    /* ── Main ── */
    .main { flex: 1; min-width: 0; min-height: 100vh; }

    .main-header { padding: 1.2rem 2rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,.06); background: rgba(255,255,255,.02); }
    .main-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; color: white; }
    .main-date { font-size: .82rem; color: rgba(255,255,255,.35); }

    .main-body { padding: 1.8rem 2rem; }

    /* Stats */
    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07); border-radius: var(--r-lg); padding: 1.3rem; }
    .stat-icon-wrap { width: 36px; height: 36px; border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; margin-bottom: .9rem; }
    .stat-val { font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: white; line-height: 1; }
    .stat-lbl { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: .3rem; }

    /* Punto activo banner */
    .punto-banner { background: rgba(74,222,128,.08); border: 1px solid rgba(74,222,128,.2); border-radius: var(--r-md); padding: .9rem 1.2rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .9rem; }
    .punto-banner-icon { width: 36px; height: 36px; border-radius: 50%; background: rgba(74,222,128,.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .punto-banner-name { font-weight: 700; color: #4ade80; font-size: .9rem; }
    .punto-banner-dir { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: .1rem; }
    .punto-banner-badge { margin-left: auto; background: rgba(74,222,128,.15); color: #4ade80; font-size: .72rem; font-weight: 700; padding: .25rem .7rem; border-radius: var(--r-full); }

    /* Servicios recientes */
    .section-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .section-title { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: white; }
    .section-sub { font-size: .78rem; color: rgba(255,255,255,.35); }

    .srv-item { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07); border-radius: var(--r-md); padding: 1rem 1.2rem; margin-bottom: .7rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; transition: background .15s; }
    .srv-item:hover { background: rgba(255,255,255,.07); }
    .srv-left { display: flex; align-items: flex-start; gap: .8rem; flex: 1; min-width: 0; }
    .srv-ico { width: 36px; height: 36px; border-radius: var(--r-sm); background: rgba(255,255,255,.07); display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .srv-ruta { font-weight: 600; font-size: .875rem; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 320px; }
    .srv-meta { font-size: .75rem; color: rgba(255,255,255,.35); margin-top: .2rem; }
    .srv-right { text-align: right; flex-shrink: 0; }
    .srv-monto { font-family: var(--font-display); font-weight: 700; font-size: 1rem; color: #4ade80; }

    /* Estatus form */
    .estatus-form { display: flex; gap: .4rem; margin-top: .5rem; }
    .estatus-select { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1); border-radius: var(--r-sm); color: white; font-family: var(--font-body); font-size: .75rem; padding: .3rem .6rem; outline: none; }
    .estatus-select option { background: #1a2d14; }
    .btn-update { background: var(--verde); color: white; border: none; border-radius: var(--r-sm); padding: .3rem .7rem; font-size: .75rem; font-weight: 600; cursor: pointer; font-family: var(--font-body); }

    .empty-srv { text-align: center; padding: 2.5rem; color: rgba(255,255,255,.25); font-size: .875rem; }

    @media (max-width: 900px) {
        .sidebar { display: none; }
        .main { width: 100%; }
        .stats-row { grid-template-columns: 1fr 1fr; }
        .main-body { padding: 1.2rem; }
    }
</style>
@endpush

@section('content')
<div class="layout">

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sb-header">
            <div class="sb-logo">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
            </div>
            <div>
                <div class="sb-brand">goRanch</div>
                <div class="sb-role">Conductor</div>
            </div>
        </div>

        <div class="sb-profile">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->nombre, 0, 2)) }}</div>
            <div>
                <div class="sb-name">{{ explode(' ', auth()->user()->nombre)[0] }}</div>
                <div class="sb-vehicle">{{ ucfirst($conductor->tipo_vehiculo) }} · {{ $conductor->marca }} {{ $conductor->modelo }}</div>
            </div>
        </div>

        {{-- Toggle conexión --}}
        <div class="sb-status">
            <div class="sb-status-top">
                <span class="sb-status-label">Estado</span>
                <div class="status-pill">
                    <div class="status-dot {{ $conductor->esta_conectado ? 'dot-on' : 'dot-off' }}"></div>
                    <span>{{ $conductor->esta_conectado ? 'Conectado' : 'Desconectado' }}</span>
                </div>
            </div>

            @if(!$conductor->esta_conectado)
                <form method="POST" action="{{ route('conductor.conectar') }}">
                    @csrf
                    <select name="punto_recoleccion_id" class="punto-select" required>
                        <option value="">Elige tu punto...</option>
                        @foreach($puntos as $p)
                            <option value="{{ $p->id }}">📍 {{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-conectar btn-on">⚡ Conectarme</button>
                </form>
            @else
                <div class="punto-actual">
                    <svg width="12" height="12" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <strong>{{ $conductor->puntoRecoleccion->nombre ?? 'Sin punto' }}</strong>
                </div>
                <form method="POST" action="{{ route('conductor.desconectar') }}">
                    @csrf
                    <button type="submit" class="btn-conectar btn-off">✕ Desconectarme</button>
                </form>
            @endif
        </div>

        <nav class="sb-nav">
            <a href="{{ route('conductor.dashboard') }}" class="sb-item active">
                <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Inicio
            </a>
        </nav>

        <div class="sb-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main">
        <div class="main-header">
            <div class="main-title">Mi Panel</div>
            <div class="main-date">{{ now()->isoFormat('dddd, D MMM') }}</div>
        </div>

        <div class="main-body">

            @if(session('success'))
                <div style="background:rgba(74,222,128,.1); border:1px solid rgba(74,222,128,.2); border-radius:var(--r-sm); padding:.8rem 1.1rem; color:#4ade80; font-size:.875rem; margin-bottom:1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:rgba(74,222,128,.1);">
                        <svg width="17" height="17" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="stat-val">{{ $stats['viajes_hoy'] }}</div>
                    <div class="stat-lbl">Viajes hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:rgba(74,222,128,.1);">
                        <svg width="17" height="17" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div class="stat-val">${{ number_format($stats['ganado_hoy'], 0) }}</div>
                    <div class="stat-lbl">Ganado hoy</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:rgba(250,204,21,.1);">
                        <svg width="17" height="17" fill="#facc15" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <div class="stat-val">{{ $stats['calificacion'] }}</div>
                    <div class="stat-lbl">Calificación</div>
                </div>
            </div>

            {{-- Punto activo --}}
            @if($conductor->esta_conectado && $conductor->puntoRecoleccion)
                <div class="punto-banner">
                    <div class="punto-banner-icon">
                        <svg width="18" height="18" fill="none" stroke="#4ade80" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="punto-banner-name">{{ $conductor->puntoRecoleccion->nombre }}</div>
                        <div class="punto-banner-dir">{{ $conductor->puntoRecoleccion->direccion }}</div>
                    </div>
                    <span class="punto-banner-badge">Activo</span>
                </div>
            @endif

            {{-- Servicio activo --}}
            @if($servicioActivo)
            <div class="active-srv-card" style="margin-bottom:2rem;">
                <div class="section-head">
                    <div class="section-title" style="display:flex;align-items:center;gap:.5rem;">
                        <span style="font-size:1.1rem;">🟢</span> Servicio en curso
                    </div>
                </div>
                @php
                    $icoActivo = ['viaje'=>'🚗','mandado_libre'=>'🛒','delivery_tienda'=>'🏪'];
                    $tipoActivo = ['viaje'=>'Viaje','mandado_libre'=>'Mandado','delivery_tienda'=>'Delivery'];
                    $estatusLabel = ['aceptado'=>'Aceptado','en_sitio'=>'En sitio','en_ruta'=>'En ruta'];
                @endphp
                <div style="background:linear-gradient(135deg,rgba(74,222,128,.12),rgba(74,222,128,.04));border:1.5px solid rgba(74,222,128,.3);border-radius:var(--r-md);padding:1.2rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.8rem;">
                        <div style="display:flex;align-items:center;gap:.5rem;font-weight:700;color:#4ade80;">
                            {{ $icoActivo[$servicioActivo->tipo] ?? '📦' }} {{ $tipoActivo[$servicioActivo->tipo] ?? $servicioActivo->tipo }}
                        </div>
                        <div style="font-family:var(--font-display);font-weight:700;font-size:1.15rem;color:#4ade80;">
                            ${{ number_format($servicioActivo->total_final, 2) }}
                        </div>
                    </div>
                    <div style="font-size:.82rem;color:rgba(255,255,255,.6);margin-bottom:.15rem;">📍 {{ $servicioActivo->direccion_origen }}</div>
                    <div style="font-size:.82rem;color:rgba(255,255,255,.6);margin-bottom:.6rem;">🏁 {{ $servicioActivo->direccion_destino }}</div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.35);margin-bottom:1rem;">
                        {{ $servicioActivo->cliente->nombre ?? 'Cliente' }} · Estatus: <strong style="color:#4ade80;">{{ $estatusLabel[$servicioActivo->estatus] ?? ucfirst(str_replace('_',' ',$servicioActivo->estatus)) }}</strong>
                    </div>

                    {{-- Botones de avance de estatus --}}
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        @if($servicioActivo->estatus === 'aceptado')
                            <form method="POST" action="{{ route('conductor.servicio.estatus', $servicioActivo->id) }}" style="flex:1;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estatus" value="en_sitio">
                                <button type="submit" class="btn-aceptar" style="width:100%;">📍 Llegué al sitio</button>
                            </form>
                        @elseif($servicioActivo->estatus === 'en_sitio')
                            <form method="POST" action="{{ route('conductor.servicio.estatus', $servicioActivo->id) }}" style="flex:1;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estatus" value="en_ruta">
                                <button type="submit" class="btn-aceptar" style="width:100%;">🚗 Iniciar ruta</button>
                            </form>
                        @elseif($servicioActivo->estatus === 'en_ruta')
                            <form method="POST" action="{{ route('conductor.servicio.estatus', $servicioActivo->id) }}" style="flex:1;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="estatus" value="completado">
                                <button type="submit" class="btn-aceptar" style="width:100%;background:#22c55e;">✓ Completar servicio</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Servicios pendientes (polling) --}}
            @if($conductor->esta_conectado)
            <div id="pendientes-section" style="margin-bottom:2rem;">
                <div class="section-head">
                    <div class="section-title" style="display:flex;align-items:center;gap:.5rem;">
                        <span id="bell-icon" style="font-size:1.2rem;">🔔</span>
                        Solicitudes entrantes
                        <span id="pendientes-count" style="background:#4ade80;color:#0c1509;font-size:.72rem;font-weight:700;padding:.15rem .55rem;border-radius:99px;display:none;">0</span>
                    </div>
                </div>
                <div id="pendientes-list">
                    <div class="empty-srv" id="no-pendientes">
                        Esperando nuevas solicitudes...<br>
                        <span style="font-size:.78rem;">Se revisa cada 5 segundos.</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Servicios --}}
            <div class="section-head">
                <div class="section-title">Servicios recientes</div>
                <span class="section-sub">Últimos 5</span>
            </div>

            @forelse($serviciosRecientes as $s)
                @php
                    $iconos = ['viaje'=>'🚗','mandado_libre'=>'🛒','delivery_tienda'=>'🏪'];
                @endphp
                <div class="srv-item">
                    <div class="srv-left">
                        <div class="srv-ico">{{ $iconos[$s->tipo] ?? '📦' }}</div>
                        <div style="min-width:0;">
                            <div class="srv-ruta">{{ $s->direccion_origen }} → {{ $s->direccion_destino }}</div>
                            <div class="srv-meta">{{ $s->cliente->nombre ?? '' }} · {{ \Carbon\Carbon::parse($s->creado_en)->format('d/m H:i') }}</div>
                            @if(!in_array($s->estatus, ['completado','cancelado']))
                                <form method="POST" action="{{ route('conductor.servicio.estatus', $s->id) }}" class="estatus-form">
                                    @csrf @method('PATCH')
                                    <select name="estatus" class="estatus-select">
                                        <option value="en_sitio"   {{ $s->estatus==='en_sitio'  ?'selected':'' }}>En sitio</option>
                                        <option value="en_ruta"    {{ $s->estatus==='en_ruta'   ?'selected':'' }}>En ruta</option>
                                        <option value="completado">Completado ✓</option>
                                    </select>
                                    <button type="submit" class="btn-update">Actualizar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="srv-right">
                        <div class="srv-monto">${{ number_format($s->total_final, 2) }}</div>
                        <div style="font-size:.72rem; color:rgba(255,255,255,.3); margin-top:.3rem;">{{ ucfirst(str_replace('_',' ',$s->estatus)) }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-srv">
                    Aún no tienes servicios.<br>
                    <span style="font-size:.78rem;">Conéctate para empezar.</span>
                </div>
            @endforelse

        </div>
    </div>
</div>

@if($conductor->esta_conectado)
@push('styles')
<style>
    .pend-card { background: linear-gradient(135deg, rgba(74,222,128,.08), rgba(74,222,128,.02)); border: 1.5px solid rgba(74,222,128,.25); border-radius: var(--r-md); padding: 1.1rem 1.2rem; margin-bottom: .7rem; animation: slideIn .3s ease; }
    @keyframes slideIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
    .pend-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .6rem; }
    .pend-tipo { display: flex; align-items: center; gap: .5rem; font-weight: 700; font-size: .9rem; color: #4ade80; }
    .pend-precio { font-family: var(--font-display); font-weight: 700; font-size: 1.15rem; color: #4ade80; }
    .pend-ruta { font-size: .82rem; color: rgba(255,255,255,.6); margin-bottom: .1rem; display: flex; align-items: center; gap: .4rem; }
    .pend-cliente { font-size: .78rem; color: rgba(255,255,255,.35); margin-bottom: .7rem; }
    .pend-actions { display: flex; gap: .5rem; }
    .btn-aceptar { flex: 1; padding: .6rem; border-radius: var(--r-sm); border: none; background: #4ade80; color: #0c1509; font-weight: 700; font-size: .85rem; cursor: pointer; font-family: var(--font-body); transition: all .15s; }
    .btn-aceptar:hover { background: #22c55e; transform: translateY(-1px); }
    .btn-rechazar { padding: .6rem .9rem; border-radius: var(--r-sm); border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.05); color: rgba(255,255,255,.5); font-weight: 600; font-size: .85rem; cursor: pointer; font-family: var(--font-body); transition: all .15s; }
    .btn-rechazar:hover { background: rgba(239,68,68,.15); color: #fca5a5; border-color: rgba(239,68,68,.25); }

    @keyframes bellRing { 0%,100%{transform:rotate(0)} 15%{transform:rotate(14deg)} 30%{transform:rotate(-14deg)} 45%{transform:rotate(10deg)} 60%{transform:rotate(-10deg)} 75%{transform:rotate(4deg)} }
    .bell-ringing { animation: bellRing .8s ease; }
</style>
@endpush

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const iconos = {viaje:'🚗', mandado_libre:'🛒', delivery_tienda:'🏪'};
const tipoLabel = {viaje:'Viaje', mandado_libre:'Mandado', delivery_tienda:'Delivery'};
let prevIds = [];
let alertSound = null;

// Crear sonido de alerta con Web Audio API
function playAlertSound() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        // Tono 1
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.connect(gain1); gain1.connect(ctx.destination);
        osc1.frequency.value = 880;
        osc1.type = 'sine';
        gain1.gain.setValueAtTime(0.3, ctx.currentTime);
        gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
        osc1.start(ctx.currentTime);
        osc1.stop(ctx.currentTime + 0.3);
        // Tono 2
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.connect(gain2); gain2.connect(ctx.destination);
        osc2.frequency.value = 1100;
        osc2.type = 'sine';
        gain2.gain.setValueAtTime(0.3, ctx.currentTime + 0.15);
        gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
        osc2.start(ctx.currentTime + 0.15);
        osc2.stop(ctx.currentTime + 0.5);
    } catch(e) {}
}

function fetchPendientes() {
    fetch('{{ route("conductor.api.pendientes") }}')
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('pendientes-list');
            const badge = document.getElementById('pendientes-count');
            const bell = document.getElementById('bell-icon');

            if (!data.servicios || data.servicios.length === 0) {
                list.innerHTML = `<div class="empty-srv" id="no-pendientes">Esperando nuevas solicitudes...<br><span style="font-size:.78rem;">Se revisa cada 5 segundos.</span></div>`;
                badge.style.display = 'none';
                return;
            }

            // Detectar nuevos servicios
            const newIds = data.servicios.map(s => s.id);
            const isNew = newIds.some(id => !prevIds.includes(id));
            if (isNew && prevIds.length > 0) {
                playAlertSound();
                bell.classList.remove('bell-ringing');
                void bell.offsetWidth;
                bell.classList.add('bell-ringing');
            }
            if (prevIds.length === 0 && newIds.length > 0) {
                playAlertSound();
            }
            prevIds = newIds;

            badge.textContent = data.servicios.length;
            badge.style.display = 'inline';

            list.innerHTML = data.servicios.map(s => `
                <div class="pend-card" id="pend-${s.id}">
                    <div class="pend-top">
                        <div class="pend-tipo">${iconos[s.tipo]||'📦'} ${tipoLabel[s.tipo]||s.tipo}</div>
                        <div class="pend-precio">$${parseFloat(s.total_final).toFixed(2)}</div>
                    </div>
                    <div class="pend-ruta">📍 ${s.direccion_origen} → ${s.direccion_destino}</div>
                    <div class="pend-cliente">${s.cliente_nombre} · ${s.distancia_km} km</div>
                    <div class="pend-actions">
                        <button class="btn-aceptar" onclick="aceptar(${s.id})">✓ Aceptar</button>
                        <button class="btn-rechazar" onclick="rechazar(${s.id})">✕</button>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => {});
}

function aceptar(id) {
    const btn = document.querySelector(`#pend-${id} .btn-aceptar`);
    if (btn) { btn.textContent = 'Aceptando...'; btn.disabled = true; }

    fetch(`/conductor/web-api/servicio/${id}/aceptar`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            const card = document.getElementById(`pend-${id}`);
            if (card) {
                card.style.borderColor = '#4ade80';
                card.innerHTML = `<div style="text-align:center;padding:.5rem;color:#4ade80;font-weight:700;">✓ Servicio aceptado — recarga para ver detalles</div>`;
            }
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(data.msg || 'No se pudo aceptar.');
            fetchPendientes();
        }
    })
    .catch(() => { alert('Error de conexión.'); if(btn){ btn.textContent='✓ Aceptar'; btn.disabled=false; } });
}

function rechazar(id) {
    fetch(`/conductor/web-api/servicio/${id}/rechazar`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
    }).then(() => {
        const card = document.getElementById(`pend-${id}`);
        if (card) card.remove();
    });
}

// Iniciar polling
fetchPendientes();
setInterval(fetchPendientes, 5000);
</script>
@endpush
@endif
@endsection