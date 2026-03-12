@extends('layouts.app')
@section('title', 'Inicio - goRanch')

@push('styles')
<style>
    .navbar-user { display: flex; align-items: center; gap: 1rem; }
    .page { max-width: 1100px; margin: 0 auto; padding: 2.5rem 2rem; }

    .greeting h1 { font-family: 'Syne', sans-serif; font-size: 2.2rem; font-weight: 800; line-height: 1.1; }
    .greeting p { color: var(--gris); margin-top: .3rem; font-size: 1rem; }

    /* Cards de servicio */
    .service-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin: 1.8rem 0; }
    .service-card {
        border-radius: 20px; padding: 1.4rem; position: relative; overflow: hidden;
        text-decoration: none; color: var(--texto); display: block;
        transition: transform .2s, box-shadow .2s;
        min-height: 200px;
    }
    .service-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.12); }
    .service-card.transporte { background: #c8e6b8; }
    .service-card.mandados { background: #d6cfc8; }
    .service-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .5rem; }
    .service-card h2 { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 700; }
    .service-card p { font-size: .85rem; color: rgba(0,0,0,.6); }
    .service-icon {
        width: 50px; height: 50px; border-radius: 50%; background: rgba(255,255,255,0.7);
        display: flex; align-items: center; justify-content: center;
    }
    .service-img {
        width: 100%; height: 110px; border-radius: 12px; object-fit: cover; margin-top: 1rem;
        background: rgba(0,0,0,.15); position: relative; overflow: hidden;
    }
    .service-img-inner {
        width: 100%; height: 100%; display: flex; align-items: flex-end; justify-content: flex-end;
        padding: .6rem;
    }
    .service-btn {
        background: var(--blanco); border-radius: 999px; padding: .4rem 1rem;
        font-size: .85rem; font-weight: 600; display: inline-flex; align-items: center; gap: .3rem;
        color: var(--texto); box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }

    /* Últimos viajes */
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .section-header h3 { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 700; }
    .section-header a { color: var(--verde-oscuro); font-weight: 600; font-size: .9rem; text-decoration: none; }
    .section-header a:hover { text-decoration: underline; }

    .viajes-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); overflow: hidden; }
    .viaje-item { display: flex; align-items: center; gap: 1rem; padding: 1.1rem 1.3rem; border-bottom: 1px solid var(--gris-claro); }
    .viaje-item:last-child { border-bottom: none; }
    .viaje-icon { width: 44px; height: 44px; border-radius: 50%; background: var(--verde-bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .viaje-info { flex: 1; }
    .viaje-info strong { font-weight: 600; display: block; }
    .viaje-info span { font-size: .85rem; color: var(--gris); }
    .viaje-info .monto { color: var(--verde-oscuro); font-weight: 600; }
    .viaje-actions { display: flex; align-items: center; gap: .6rem; }
    .circle-btn { width: 32px; height: 32px; border-radius: 50%; border: 1.5px solid var(--gris-claro); background: var(--blanco); display: flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; color: var(--gris); transition: all .2s; }
    .circle-btn:hover { border-color: var(--verde); color: var(--verde); }

    @media (max-width: 600px) {
        .service-grid { grid-template-columns: 1fr; }
        .greeting h1 { font-size: 1.7rem; }
    }
</style>
@endpush

@section('content')
{{-- Navbar --}}
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <div class="navbar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link active">Inicio</a>
        <a href="#" class="nav-link">Mis Viajes</a>
        <a href="#" class="nav-link">Ayuda</a>
        <a href="{{ route('perfil') }}" class="navbar-user">
            @if(auth()->user()->foto_perfil)
                <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" class="avatar" alt="Perfil">
            @else
                <div class="avatar-placeholder">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
            @endif
            <span style="font-weight:600; font-size:.95rem;">{{ explode(' ', auth()->user()->nombre)[0] }}</span>
        </a>
    </div>
</nav>

<div class="page">
    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Greeting --}}
    <div class="greeting">
        <h1>Hola, {{ explode(' ', auth()->user()->nombre)[0] }}</h1>
        <p>¿Qué necesitas hacer en el rancho hoy?</p>
    </div>

    {{-- Cards de servicio --}}
    <div class="service-grid">
        {{-- Transporte --}}
        <a href="{{ route('viaje.nuevo') }}" class="service-card transporte">
            <div class="service-card-header">
                <div>
                    <h2>Transporte</h2>
                    <p>Solicitar viaje</p>
                </div>
                <div class="service-icon">
                    <svg width="24" height="24" fill="none" stroke="#3a6b28" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m-4 12h8a2 2 0 002-2v-6a2 2 0 00-2-2h-8m0 0V9m0 8v-5"/>
                    </svg>
                </div>
            </div>
            <div class="service-img">
                <div class="service-img-inner" style="background:linear-gradient(160deg, #9bbf88, #5a8c40);">
                    <span class="service-btn">Ir ahora →</span>
                </div>
            </div>
        </a>

        {{-- Mandados --}}
        <a href="{{ route('mandado.nuevo') }}" class="service-card mandados">
            <div class="service-card-header">
                <div>
                    <h2>Mandados</h2>
                    <p>Enviar paquete</p>
                </div>
                <div class="service-icon" style="background:rgba(255,255,255,0.5);">
                    <svg width="24" height="24" fill="none" stroke="#4a3f35" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                </div>
            </div>
            <div class="service-img">
                <div class="service-img-inner" style="background:linear-gradient(160deg, #c0b4a8, #8a7a6e);">
                    <span class="service-btn">Pedir ahora →</span>
                </div>
            </div>
        </a>
    </div>

    {{-- Últimos viajes --}}
    <div class="section-header">
        <h3>Últimos Viajes</h3>
        <a href="#">Ver todos</a>
    </div>

    <div class="viajes-card">
        @forelse($ultimosViajes ?? [] as $viaje)
            <div class="viaje-item">
                <div class="viaje-icon">
                    <svg width="20" height="20" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24">
                        @if($viaje->tipo === 'viaje')
                            <rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/>
                        @else
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                        @endif
                    </svg>
                </div>
                <div class="viaje-info">
                    <strong>{{ $viaje->direccion_origen }} a {{ $viaje->direccion_destino }}</strong>
                    <span>{{ $viaje->creado_en->diffForHumans() }} • <span class="monto">${{ number_format($viaje->total_final, 0) }} MXN</span></span>
                </div>
                <div class="viaje-actions">
                    <span class="badge badge-green">Completado</span>
                    <a href="#" class="circle-btn">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                </div>
            </div>
        @empty
            {{-- Viajes de muestra si no hay datos --}}
            <div class="viaje-item">
                <div class="viaje-icon">
                    <svg width="20" height="20" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="viaje-info">
                    <strong>Casa a Centro</strong>
                    <span>Ayer • <span class="monto">$50 MXN</span></span>
                </div>
                <div class="viaje-actions">
                    <span class="badge badge-green">Completado</span>
                    <a href="#" class="circle-btn"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
                </div>
            </div>
            <div class="viaje-item">
                <div class="viaje-icon" style="background:#f3f4f6;">
                    <svg width="20" height="20" fill="none" stroke="var(--gris)" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="viaje-info">
                    <strong>Entrega de Medicinas</strong>
                    <span>Hace 3 días • <span class="monto">$120 MXN</span></span>
                </div>
                <div class="viaje-actions">
                    <span class="badge badge-green">Completado</span>
                    <a href="#" class="circle-btn"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
