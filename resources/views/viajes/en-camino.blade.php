@extends('layouts.app')
@section('title', 'Viaje en Camino - goRanch')

@push('styles')
<style>
    body { background: var(--verde-oscuro); min-height: 100vh; }
    body::before { display: none; }

    .top-bar { padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .back-link { color: rgba(255,255,255,.5); text-decoration: none; font-size: .875rem; display: flex; align-items: center; gap: .4rem; }
    .back-link:hover { color: white; }
    .viaje-id { font-size: .75rem; color: rgba(255,255,255,.3); }

    /* Mapa simulado */
    .map-area {
        margin: 0 1.5rem;
        border-radius: var(--r-xl);
        overflow: hidden;
        height: 200px;
        background: linear-gradient(135deg, #1e3d12 0%, #2d5a1b 50%, #1e3d12 100%);
        position: relative;
        border: 1px solid rgba(255,255,255,.08);
    }
    .map-grid {
        position: absolute; inset: 0;
        background-image: linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .map-route { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; }
    .map-pin-a { position: absolute; left: 25%; top: 60%; font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(0,0,0,.4)); }
    .map-pin-b { position: absolute; right: 20%; top: 30%; font-size: 1.5rem; filter: drop-shadow(0 2px 4px rgba(0,0,0,.4)); }
    .map-car { position: absolute; left: 45%; top: 48%; font-size: 1.4rem; animation: drive 3s ease-in-out infinite alternate; filter: drop-shadow(0 2px 6px rgba(74,222,128,.4)); }
    @keyframes drive { from { left: 38%; top: 52%; } to { left: 52%; top: 44%; } }
    .map-line { position: absolute; left: 25%; top: 30%; right: 18%; bottom: 25%; border: 2px dashed rgba(74,222,128,.35); border-radius: 50% 20% 50% 20%; }

    /* Info panel */
    .info-panel { background: var(--blanco); border-radius: var(--r-xl) var(--r-xl) 0 0; margin-top: 1.5rem; padding: 1.5rem; min-height: calc(100vh - 360px); }

    .status-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.2rem; }
    .status-badge-live { display: flex; align-items: center; gap: .5rem; background: var(--verde-bg); border: 1px solid var(--verde-claro); border-radius: var(--r-full); padding: .35rem .9rem; font-size: .82rem; font-weight: 700; color: var(--verde-oscuro); }
    .pulse { width: 8px; height: 8px; border-radius: 50%; background: var(--verde); animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .3; } }
    .eta-text { font-size: .82rem; color: var(--gris); }
    .eta-num { font-family: var(--font-display); font-weight: 700; color: var(--verde-oscuro); font-size: 1rem; }

    /* Ruta visual */
    .ruta-visual { display: flex; gap: .9rem; margin-bottom: 1.5rem; }
    .ruta-dots { display: flex; flex-direction: column; align-items: center; gap: 0; padding-top: .1rem; }
    .dot-origen { width: 10px; height: 10px; border-radius: 50%; background: var(--verde); border: 2px solid white; box-shadow: 0 0 0 2px var(--verde-claro); }
    .dot-line { width: 2px; flex: 1; background: var(--borde); margin: 3px 0; min-height: 20px; }
    .dot-destino { width: 10px; height: 10px; border-radius: 50%; background: var(--rojo); border: 2px solid white; box-shadow: 0 0 0 2px #fecaca; }
    .ruta-text { flex: 1; }
    .ruta-from { font-weight: 600; font-size: .9rem; color: var(--texto); margin-bottom: .3rem; }
    .ruta-mid-space { height: 16px; }
    .ruta-to { font-weight: 600; font-size: .9rem; color: var(--texto); }
    .ruta-sub { font-size: .75rem; color: var(--gris); }

    /* Conductor card */
    .conductor-card { display: flex; align-items: center; gap: 1rem; background: var(--fondo); border-radius: var(--r-md); padding: 1rem; margin-bottom: 1.2rem; border: 1px solid var(--borde); }
    .c-avatar { width: 46px; height: 46px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: 1.1rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .c-name { font-weight: 700; font-size: .95rem; }
    .c-vehicle { font-size: .78rem; color: var(--gris); margin-top: .15rem; }
    .c-rating { font-size: .78rem; color: var(--amarillo); font-weight: 700; margin-top: .1rem; }
    .c-actions { margin-left: auto; display: flex; gap: .5rem; }
    .icon-btn { width: 38px; height: 38px; border-radius: 50%; border: 1.5px solid var(--borde); background: var(--blanco); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--gris); text-decoration: none; }
    .icon-btn:hover { border-color: var(--verde); color: var(--verde); }

    /* Monto */
    .monto-row { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-top: 1px solid var(--borde); }
    .monto-lbl { font-size: .85rem; color: var(--gris); }
    .monto-val { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--verde-oscuro); }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('dashboard') }}" class="back-link">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Inicio
    </a>
    <span class="viaje-id">Viaje #{{ $servicio->id }}</span>
</div>

<div class="map-area">
    <div class="map-grid"></div>
    <div class="map-line"></div>
    <div class="map-pin-a">🟢</div>
    <div class="map-pin-b">🔴</div>
    <div class="map-car">🚗</div>
</div>

<div class="info-panel">
    <div class="status-row">
        <div class="status-badge-live">
            <div class="pulse"></div>
            {{ $servicio->estatus === 'en_ruta' ? 'En camino' : 'Conductor llegando' }}
        </div>
        <div>
            <div class="eta-text">ETA estimado</div>
            <div class="eta-num">~8 min</div>
        </div>
    </div>

    {{-- Ruta --}}
    <div class="ruta-visual">
        <div class="ruta-dots">
            <div class="dot-origen"></div>
            <div class="dot-line"></div>
            <div class="dot-destino"></div>
        </div>
        <div class="ruta-text">
            <div class="ruta-from">{{ $servicio->direccion_origen }}</div>
            <div class="ruta-sub">Punto de partida</div>
            <div class="ruta-mid-space"></div>
            <div class="ruta-to">{{ $servicio->direccion_destino }}</div>
            <div class="ruta-sub">Tu destino</div>
        </div>
    </div>

    {{-- Conductor --}}
    @if($servicio->conductor)
        <div class="conductor-card">
            <div class="c-avatar">{{ strtoupper(substr($servicio->conductor->usuario->nombre ?? 'C', 0, 2)) }}</div>
            <div>
                <div class="c-name">{{ $servicio->conductor->usuario->nombre ?? 'Conductor' }}</div>
                <div class="c-vehicle">{{ ucfirst($servicio->conductor->tipo_vehiculo) }} · {{ $servicio->conductor->placa }}</div>
                <div class="c-rating">⭐ {{ $servicio->conductor->calificacion_promedio }}</div>
            </div>
            <div class="c-actions">
                <a href="tel:" class="icon-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2A19.79 19.79 0 013.07 9.81a2 2 0 012-2.18H8a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                </a>
            </div>
        </div>
    @endif

    <div class="monto-row">
        <div class="monto-lbl">Total estimado · {{ ucfirst($servicio->metodo_pago) }}</div>
        <div class="monto-val">${{ number_format($servicio->total_final, 2) }}</div>
    </div>
</div>
@endsection
