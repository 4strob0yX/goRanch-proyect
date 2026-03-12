@extends('layouts.app')
@section('title', 'Nuevo Viaje - goRanch')

@push('styles')
<style>
    body { overflow: hidden; }
    .map-container { position: fixed; inset: 0; background: #e8e0d0; }
    .map-placeholder {
        width: 100%; height: 100%;
        background: linear-gradient(135deg, #e8e4d8 0%, #d4cdb8 50%, #c8c0a8 100%);
        position: relative; overflow: hidden;
    }
    /* Simular mapa con líneas */
    .map-road { position: absolute; background: #c4bba8; }
    .map-river { position: absolute; background: #89bdd3; border-radius: 999px; }

    .map-navbar {
        position: absolute; top: 0; left: 0; right: 0;
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.5rem; background: var(--blanco); border-bottom: 1px solid var(--gris-claro);
        z-index: 10;
    }
    .map-navbar-brand { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; gap: .5rem; text-decoration: none; color: var(--texto); }
    .notif-btn { background: none; border: none; cursor: pointer; color: var(--texto); position: relative; }
    .avatar-sm { width: 34px; height: 34px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--verde-oscuro); font-size: .8rem; }

    /* Marker */
    .map-marker {
        position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%);
        display: flex; flex-direction: column; align-items: center; z-index: 5;
    }
    .map-marker-dot { width: 36px; height: 36px; background: var(--verde); border-radius: 50% 50% 50% 0; transform: rotate(-45deg); box-shadow: 0 4px 12px rgba(106,170,75,.4); }
    .map-marker-dot::after { content: ''; position: absolute; inset: 6px; background: white; border-radius: 50%; }
    .map-marker-label { background: var(--blanco); border-radius: 8px; padding: .3rem .7rem; font-size: .8rem; font-weight: 600; margin-top: .5rem; box-shadow: var(--sombra); white-space: nowrap; }

    /* Map controls */
    .map-controls { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); display: flex; flex-direction: column; gap: .5rem; z-index: 5; }
    .map-ctrl-btn { width: 40px; height: 40px; background: var(--blanco); border-radius: 50%; box-shadow: var(--sombra); display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; color: var(--texto); }

    /* Panel inferior */
    .viaje-panel {
        position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 100%; max-width: 520px;
        background: var(--blanco); border-radius: 24px 24px 0 0;
        padding: 1.8rem 1.8rem 2rem;
        box-shadow: 0 -8px 32px rgba(0,0,0,0.12);
        z-index: 10;
    }
    .panel-handle { width: 40px; height: 4px; background: var(--gris-claro); border-radius: 999px; margin: 0 auto 1.5rem; }
    .panel-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; }
    .panel-sub { color: var(--gris); font-size: .875rem; margin-bottom: 1.3rem; }

    .origen-field {
        background: var(--verde-bg); border: none; border-radius: 10px; padding: .9rem 1rem;
        display: flex; align-items: center; gap: .6rem; margin-bottom: 1rem; cursor: pointer;
    }
    .origen-field span { flex: 1; font-weight: 500; color: var(--verde-oscuro); }

    .cost-row { display: flex; border: 1.5px solid var(--gris-claro); border-radius: 12px; overflow: hidden; margin-bottom: 1.2rem; }
    .cost-col { flex: 1; padding: .9rem 1rem; }
    .cost-col:first-child { border-right: 1.5px solid var(--gris-claro); }
    .cost-label { font-size: .7rem; font-weight: 700; letter-spacing: .05em; color: var(--gris); text-transform: uppercase; }
    .cost-value { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 700; margin-top: .2rem; }
    .cost-value.time { display: flex; align-items: center; gap: .3rem; }
    .time-dot { width: 16px; height: 16px; background: var(--verde); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
</style>
@endpush

@section('content')
<div class="map-container">
    {{-- Mapa de fondo --}}
    <div class="map-placeholder">
        <div class="map-river" style="width:20px; height:60%; top:0; left:55%; transform:rotate(5deg);"></div>
        <div class="map-road" style="width:80%; height:3px; top:30%; left:10%;"></div>
        <div class="map-road" style="width:3px; height:80%; top:10%; left:40%;"></div>

        {{-- Marker --}}
        <div class="map-marker">
            <div style="position:relative; width:36px; height:36px;">
                <div class="map-marker-dot"></div>
            </div>
            <div class="map-marker-label">Tu ubicación</div>
        </div>

        {{-- Controls --}}
        <div class="map-controls">
            <button class="map-ctrl-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="22"/><line x1="2" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="22" y2="12"/></svg>
            </button>
            <button class="map-ctrl-btn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
            </button>
        </div>
    </div>

    {{-- Navbar --}}
    <div class="map-navbar">
        <a href="{{ route('dashboard') }}" class="map-navbar-brand">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
            goRanch
        </a>
        <div style="display:flex; align-items:center; gap:1rem;">
            <button class="notif-btn">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            </button>
            <div class="avatar-sm">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
        </div>
    </div>

    {{-- Panel Nuevo Viaje --}}
    <div class="viaje-panel">
        <div class="panel-handle"></div>
        <h2 class="panel-title">Nuevo Viaje</h2>
        <p class="panel-sub">Planifica tu ruta rural segura.</p>

        <form method="POST" action="{{ route('viaje.store') }}">
            @csrf
            <input type="hidden" name="tipo" value="viaje">

            <div class="form-group">
                <label class="form-label">Origen</label>
                <div class="origen-field">
                    <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>Mi Ubicación Actual</span>
                    <svg width="16" height="16" fill="none" stroke="var(--gris)" stroke-width="2" viewBox="0 0 24 24"><path d="M17 3a2.828 2.828 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                </div>
                <input type="hidden" name="direccion_origen" value="Mi Ubicación Actual">
            </div>

            <div class="form-group">
                <label class="form-label">Destino</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    </span>
                    <input type="text" name="direccion_destino" class="form-input" placeholder="Escribe el destino...">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Detalles <span style="color:var(--gris); font-weight:400;">(Opcional)</span></label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    </span>
                    <input type="text" name="notas" class="form-input" placeholder="Instrucciones para el conductor">
                </div>
            </div>

            <div class="cost-row">
                <div class="cost-col">
                    <div class="cost-label">Costo Estimado</div>
                    <div class="cost-value">$50 MXN</div>
                </div>
                <div class="cost-col">
                    <div class="cost-label">Tiempo</div>
                    <div class="cost-value time">
                        <div class="time-dot"></div>
                        10 min
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="border-radius:12px; padding:1rem; font-size:1rem; gap:.5rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Buscar Conductor
            </button>
        </form>
    </div>
</div>
@endsection
