@extends('layouts.app')
@section('title', 'Mi Perfil - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .app-nav { background: var(--blanco); border-bottom: 1px solid var(--borde); padding: 0 1.5rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .app-nav-brand { font-family: var(--font-display); font-weight: 700; color: var(--verde-oscuro); font-size: 1.1rem; }
    .nav-back { display: flex; align-items: center; gap: .4rem; color: var(--gris); text-decoration: none; font-size: .875rem; }

    .page-body { max-width: 500px; margin: 0 auto; padding: 1.5rem; }

    /* Hero perfil */
    .perfil-hero { text-align: center; padding: 2rem 1rem; }
    .perfil-avatar { width: 80px; height: 80px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: 2rem; color: var(--verde-oscuro); margin: 0 auto 1rem; border: 3px solid var(--verde); }
    .perfil-nombre { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; margin-bottom: .2rem; }
    .perfil-email { font-size: .875rem; color: var(--gris); margin-bottom: .6rem; }
    .perfil-rol { display: inline-block; background: var(--verde-bg); color: var(--verde-oscuro); font-size: .75rem; font-weight: 700; padding: .25rem .8rem; border-radius: var(--r-full); }

    /* Stats */
    .stats-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: .8rem; margin-bottom: 1.5rem; }
    .stat-cell { background: var(--blanco); border-radius: var(--r-md); padding: .9rem; text-align: center; border: 1px solid var(--borde); }
    .stat-val { font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--verde-oscuro); }
    .stat-lbl { font-size: .72rem; color: var(--gris); margin-top: .2rem; }

    /* Menú */
    .menu-section { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; margin-bottom: 1rem; }
    .menu-section-title { padding: .7rem 1.2rem; font-size: .72rem; font-weight: 700; color: var(--gris); text-transform: uppercase; letter-spacing: .06em; background: var(--fondo); border-bottom: 1px solid var(--borde); }
    .menu-item { display: flex; align-items: center; gap: .9rem; padding: .95rem 1.2rem; border-bottom: 1px solid var(--borde); text-decoration: none; color: var(--texto); transition: background .15s; }
    .menu-item:last-child { border-bottom: none; }
    .menu-item:hover { background: var(--fondo); }
    .menu-icon { width: 34px; height: 34px; border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .menu-label { flex: 1; font-weight: 500; font-size: .9rem; }
    .menu-arrow { color: var(--gris-claro); }
    .menu-item.danger { color: var(--rojo); }
    .menu-item.danger .menu-icon { background: var(--rojo-bg); }
    .menu-item.danger:hover { background: var(--rojo-bg); }
</style>
@endpush

@section('content')
<nav class="app-nav">
    <a href="{{ route('dashboard') }}" class="nav-back">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <span class="app-nav-brand">Mi Perfil</span>
    <div style="width:40px;"></div>
</nav>

<div class="page-body">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Hero --}}
    <div class="perfil-hero">
        <div class="perfil-avatar">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
        <div class="perfil-nombre">{{ auth()->user()->nombre }}</div>
        <div class="perfil-email">{{ auth()->user()->email }}</div>
        <span class="perfil-rol">Cliente</span>
    </div>

    {{-- Stats --}}
    @php
        $totalViajes    = \App\Models\Servicio::where('cliente_id', auth()->id())->count();
        $completados    = \App\Models\Servicio::where('cliente_id', auth()->id())->where('estatus','completado')->count();
        $gastado        = \App\Models\Servicio::where('cliente_id', auth()->id())->where('estatus','completado')->sum('total_final');
    @endphp
    <div class="stats-strip">
        <div class="stat-cell">
            <div class="stat-val">{{ $totalViajes }}</div>
            <div class="stat-lbl">Viajes totales</div>
        </div>
        <div class="stat-cell">
            <div class="stat-val">{{ $completados }}</div>
            <div class="stat-lbl">Completados</div>
        </div>
        <div class="stat-cell">
            <div class="stat-val">${{ number_format($gastado, 0) }}</div>
            <div class="stat-lbl">Gastado</div>
        </div>
    </div>

    {{-- Menú cuenta --}}
    <div class="menu-section">
        <div class="menu-section-title">Mi cuenta</div>
        <a href="{{ route('mis-viajes') }}" class="menu-item">
            <div class="menu-icon" style="background:var(--verde-bg);">
                <svg width="16" height="16" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <span class="menu-label">Mis Viajes</span>
            <svg class="menu-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="#" class="menu-item">
            <div class="menu-icon" style="background:#fef3c7;">
                <svg width="16" height="16" fill="none" stroke="#92400e" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <span class="menu-label">Editar perfil</span>
            <svg class="menu-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="#" class="menu-item">
            <div class="menu-icon" style="background:var(--azul-bg);">
                <svg width="16" height="16" fill="none" stroke="var(--azul)" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <span class="menu-label">Cambiar contraseña</span>
            <svg class="menu-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    </div>

    {{-- Menú ayuda --}}
    <div class="menu-section">
        <div class="menu-section-title">Soporte</div>
        <a href="#" class="menu-item">
            <div class="menu-icon" style="background:var(--verde-bg);">
                <svg width="16" height="16" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <span class="menu-label">Ayuda y soporte</span>
            <svg class="menu-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
    </div>

    {{-- Cerrar sesión --}}
    <div class="menu-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="menu-item danger" style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                <div class="menu-icon">
                    <svg width="16" height="16" fill="none" stroke="var(--rojo)" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </div>
                <span class="menu-label">Cerrar sesión</span>
            </button>
        </form>
    </div>
</div>
@endsection
