@extends('layouts.app')
@section('title', 'En Camino - goRanch')

@push('styles')
<style>
    body { overflow: hidden; }
    .map-container { position: fixed; inset: 0; background: #e8e0d0; }
    .map-bg { width:100%; height:100%; background:linear-gradient(135deg, #e8e4d8 0%, #d4cdb8 100%); }
    .map-river { position:absolute; background:#89bdd3; border-radius:999px; }
    .status-chip {
        position:absolute; top:80px; left:50%; transform:translateX(-50%);
        background:var(--blanco); border-radius:999px; padding:.5rem 1.2rem;
        display:flex; align-items:center; gap:.6rem; font-weight:600; font-size:.9rem;
        box-shadow: var(--sombra); z-index:10; white-space:nowrap;
    }
    .status-dot { width:10px; height:10px; border-radius:50%; background:var(--verde); animation:pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

    .map-navbar { position:absolute; top:0; left:0; right:0; display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; background:var(--blanco); border-bottom:1px solid var(--gris-claro); z-index:10; }
    .map-navbar-brand { font-family:'Syne',sans-serif; font-weight:700; font-size:1.1rem; display:flex; align-items:center; gap:.5rem; text-decoration:none; color:var(--texto); }

    .map-controls { position:absolute; right:1rem; top:50%; transform:translateY(-50%); display:flex; flex-direction:column; gap:.5rem; z-index:5; }
    .map-ctrl-btn { width:40px; height:40px; background:var(--blanco); border-radius:50%; box-shadow:var(--sombra); display:flex; align-items:center; justify-content:center; cursor:pointer; border:none; color:var(--texto); font-size:1.2rem; font-weight:700; }

    /* Panel conductor */
    .conductor-panel {
        position:absolute; bottom:0; left:50%; transform:translateX(-50%);
        width:100%; max-width:520px;
        background:var(--blanco); border-radius:24px 24px 0 0;
        padding:1.5rem 1.8rem 2rem;
        box-shadow:0 -8px 32px rgba(0,0,0,.12); z-index:10;
    }
    .panel-handle { width:40px; height:4px; background:var(--gris-claro); border-radius:999px; margin:0 auto 1.5rem; }
    .conductor-avatar { width:70px; height:70px; border-radius:50%; background:var(--verde-claro); margin:0 auto .5rem; display:flex; align-items:center; justify-content:center; font-size:1.8rem; position:relative; }
    .conductor-rating { position:absolute; bottom:-4px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,.75); color:white; border-radius:999px; padding:.1rem .5rem; font-size:.75rem; font-weight:600; white-space:nowrap; }
    .conductor-name { font-family:'Syne',sans-serif; font-weight:700; font-size:1.2rem; text-align:center; margin-bottom:.3rem; }
    .conductor-verified { display:flex; justify-content:center; margin-bottom:1rem; }
    .verified-badge { background:var(--verde-claro); color:var(--verde-oscuro); font-size:.8rem; font-weight:600; padding:.2rem .8rem; border-radius:999px; }
    .vehicle-row { background:var(--fondo); border-radius:12px; padding:.9rem 1rem; display:flex; align-items:center; justify-content:space-between; margin-bottom:1.2rem; }
    .vehicle-info { display:flex; align-items:center; gap:.7rem; }
    .vehicle-icon { width:38px; height:38px; background:var(--gris-claro); border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .vehicle-details strong { display:block; font-weight:600; font-size:.9rem; }
    .vehicle-details span { font-size:.8rem; color:var(--gris); }
    .llegada { text-align:right; }
    .llegada .llegada-label { font-size:.75rem; color:var(--gris); text-transform:uppercase; letter-spacing:.05em; }
    .llegada .llegada-hora { font-family:'Syne',sans-serif; font-weight:700; font-size:1rem; }
    .action-btns { display:grid; grid-template-columns:1fr 1fr; gap:.8rem; margin-bottom:.8rem; }
    .action-btn { display:flex; flex-direction:column; align-items:center; gap:.4rem; background:none; border:none; cursor:pointer; }
    .action-btn-circle { width:52px; height:52px; border-radius:50%; background:var(--verde); display:flex; align-items:center; justify-content:center; }
    .action-btn span { font-size:.8rem; color:var(--gris); }
    .bottom-links-row { display:flex; justify-content:space-between; font-size:.85rem; }
    .bottom-links-row a { color:var(--gris); text-decoration:none; display:flex; align-items:center; gap:.3rem; }
    .bottom-links-row a:hover { color:var(--texto); }
</style>
@endpush

@section('content')
<div class="map-container">
    <div class="map-bg" style="position:relative;">
        <div class="map-river" style="width:18px; height:55%; top:0; left:60%; transform:rotate(8deg);"></div>
    </div>

    {{-- Status chip --}}
    <div class="status-chip">
        <div class="status-dot"></div>
        En camino...
    </div>

    {{-- Navbar --}}
    <div class="map-navbar">
        <a href="{{ route('dashboard') }}" class="map-navbar-brand">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
            goRanch
        </a>
        <div style="display:flex; align-items:center; gap:1rem;">
            <a href="#" style="color:var(--gris); font-size:.9rem; text-decoration:none; display:flex; align-items:center; gap:.3rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Ayuda
            </a>
            <div style="width:34px; height:34px; border-radius:50%; background:var(--verde-claro);"></div>
        </div>
    </div>

    {{-- Map controls --}}
    <div class="map-controls">
        <button class="map-ctrl-btn">+</button>
        <button class="map-ctrl-btn">−</button>
        <button class="map-ctrl-btn" style="font-size:.8rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
    </div>

    {{-- Panel conductor --}}
    <div class="conductor-panel">
        <div class="panel-handle"></div>

        <div class="conductor-avatar">
            🧑‍🦱
            <div class="conductor-rating">4.8 ⭐</div>
        </div>
        <div class="conductor-name">{{ $conductor->usuario->nombre ?? 'Luis G.' }}</div>
        <div class="conductor-verified">
            <span class="verified-badge">Conductor Verificado</span>
        </div>

        <div class="vehicle-row">
            <div class="vehicle-info">
                <div class="vehicle-icon">
                    <svg width="20" height="20" fill="none" stroke="var(--gris)" stroke-width="1.8" viewBox="0 0 24 24"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m-4 12h8a2 2 0 002-2v-6a2 2 0 00-2-2h-8"/></svg>
                </div>
                <div class="vehicle-details">
                    <strong>{{ $conductor->modelo ?? 'Moto Italika 150' }}</strong>
                    <span>Placa {{ $conductor->placa ?? 'XJ-99' }}</span>
                </div>
            </div>
            <div class="llegada">
                <div class="llegada-label">Llegada est.</div>
                <div class="llegada-hora">12:45 PM</div>
            </div>
        </div>

        <div class="action-btns">
            <div class="action-btn">
                <div class="action-btn-circle">
                    <svg width="22" height="22" fill="white" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                </div>
                <span>Llamar</span>
            </div>
            <div class="action-btn">
                <div class="action-btn-circle">
                    <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                </div>
                <span>Mensaje</span>
            </div>
        </div>

        <div class="bottom-links-row">
            <a href="#">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Compartir mi viaje
            </a>
            <a href="#">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Reportar problema
            </a>
        </div>
    </div>
</div>
@endsection
