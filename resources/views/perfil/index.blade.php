@extends('layouts.app')
@section('title', 'Mi Perfil - goRanch')

@push('styles')
<style>
    .page { max-width: 680px; padding: 2rem; }
    .profile-card {
        background: linear-gradient(135deg, #e6f2de, #d4e8c8);
        border-radius: 20px; padding: 2rem; text-align: center; margin-bottom: 1.2rem;
    }
    .profile-avatar-wrap { position: relative; display: inline-block; margin-bottom: 1rem; }
    .profile-avatar { width: 80px; height: 80px; border-radius: 50%; background: #f0c060; display: flex; align-items: center; justify-content: center; font-size: 2rem; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .profile-check { position: absolute; bottom: 0; right: 0; width: 24px; height: 24px; background: var(--verde); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; }
    .profile-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; }
    .member-badge { display: inline-flex; align-items: center; gap: .3rem; background: var(--verde); color: white; border-radius: 999px; padding: .3rem .8rem; font-size: .8rem; font-weight: 600; margin-top: .4rem; }

    .menu-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); overflow: hidden; margin-bottom: 1.2rem; }
    .menu-item { display: flex; align-items: center; gap: 1rem; padding: 1.1rem 1.3rem; border-bottom: 1px solid var(--gris-claro); text-decoration: none; color: var(--texto); transition: background .15s; }
    .menu-item:last-child { border-bottom: none; }
    .menu-item:hover { background: var(--fondo); }
    .menu-item-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--verde-bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .menu-item-text { flex: 1; font-weight: 500; }
    .menu-item-arrow { color: var(--gris-claro); }
    .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--rojo); }

    .logout-btn { display: flex; align-items: center; justify-content: center; gap: .5rem; color: var(--rojo); font-weight: 600; font-size: .95rem; background: none; border: none; cursor: pointer; padding: 1rem; width: 100%; }
    .logout-btn:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <div class="navbar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link">Inicio</a>
        <a href="#" class="nav-link">Servicios</a>
        <a href="#" class="nav-link">Ayuda</a>
        <div class="avatar-placeholder">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
    </div>
</nav>

<div class="page" style="margin: 0 auto;">
    {{-- Profile Card --}}
    <div class="profile-card">
        <div class="profile-avatar-wrap">
            <div class="profile-avatar">🧑</div>
            <div class="profile-check">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
        </div>
        <div class="profile-name">{{ auth()->user()->nombre }}</div>
        <div>
            <span class="member-badge">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                Ranchero Gold Member
            </span>
        </div>
    </div>

    {{-- Menu --}}
    <div class="menu-card">
        <a href="#" class="menu-item">
            <div class="menu-item-icon">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
            </div>
            <span class="menu-item-text">Mis Viajes</span>
            <svg class="menu-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="{{ route('mandado.nuevo') }}" class="menu-item">
            <div class="menu-item-icon">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
            </div>
            <span class="menu-item-text">Mis Mandados</span>
            <svg class="menu-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="#" class="menu-item">
            <div class="menu-item-icon">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </div>
            <span class="menu-item-text">Métodos de Pago</span>
            <svg class="menu-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="#" class="menu-item">
            <div class="menu-item-icon">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <span class="menu-item-text">Datos Personales</span>
            <svg class="menu-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>
        <a href="#" class="menu-item">
            <div class="menu-item-icon">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            </div>
            <span class="menu-item-text">Notificaciones</span>
            <div style="display:flex; align-items:center; gap:.5rem; margin-left:auto;">
                <div class="notif-dot"></div>
                <svg class="menu-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
            </div>
        </a>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Cerrar Sesión
        </button>
    </form>
</div>
@endsection
