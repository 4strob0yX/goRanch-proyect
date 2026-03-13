@extends('layouts.app')
@section('title', 'Inicio - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }

    /* Navbar personalizada */
    .app-nav { background: var(--blanco); border-bottom: 1px solid var(--borde); padding: 0 1.5rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .app-nav-brand { display: flex; align-items: center; gap: .5rem; font-family: var(--font-display); font-weight: 700; color: var(--verde-oscuro); text-decoration: none; font-size: 1.1rem; }
    .app-nav-right { display: flex; align-items: center; gap: .8rem; }
    .notif-btn { width: 36px; height: 36px; border-radius: 50%; background: var(--fondo); border: 1px solid var(--borde); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--gris); position: relative; }
    .notif-dot { position: absolute; top: 6px; right: 6px; width: 7px; height: 7px; border-radius: 50%; background: var(--rojo); border: 1.5px solid white; }

    /* Hero saludo */
    .hero-section {
        background: var(--verde-oscuro);
        padding: 2rem 1.5rem 4rem;
        position: relative; overflow: hidden;
    }
    .hero-section::after {
        content: '';
        position: absolute; bottom: -1px; left: 0; right: 0; height: 32px;
        background: var(--fondo);
        border-radius: 24px 24px 0 0;
    }
    .hero-section::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(93,163,66,.2) 0%, transparent 60%);
    }
    .hero-content { position: relative; z-index: 1; max-width: 600px; }
    .hero-greeting { font-size: .85rem; color: rgba(255,255,255,.55); margin-bottom: .3rem; }
    .hero-name { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; color: white; margin-bottom: .3rem; }
    .hero-sub { font-size: .875rem; color: rgba(255,255,255,.5); }

    /* Contenido principal */
    .main-content { max-width: 640px; margin: 0 auto; padding: 0 1.5rem 2rem; margin-top: -1rem; }

    /* Servicios */
    .services-title { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; color: var(--texto); }
    .services-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .9rem; margin-bottom: 2rem; }

    .service-card {
        background: var(--blanco); border-radius: var(--r-lg);
        box-shadow: var(--sombra-sm); border: 1.5px solid var(--borde);
        padding: 1.3rem; text-decoration: none; color: var(--texto);
        transition: all .2s; display: block;
    }
    .service-card:hover { transform: translateY(-3px); box-shadow: var(--sombra); border-color: var(--verde-claro); }
    .service-icon { width: 46px; height: 46px; border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; margin-bottom: .9rem; font-size: 1.4rem; }
    .service-name { font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: .3rem; }
    .service-desc { font-size: .78rem; color: var(--gris); line-height: 1.4; }
    .service-arrow { margin-top: .9rem; font-size: .78rem; color: var(--verde); font-weight: 600; display: flex; align-items: center; gap: .3rem; }

    /* Historial */
    .historial-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .9rem; }
    .historial-title { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; }
    .ver-todo { font-size: .82rem; color: var(--verde); font-weight: 600; text-decoration: none; }

    .viaje-item { background: var(--blanco); border-radius: var(--r-md); padding: 1rem; margin-bottom: .7rem; border: 1px solid var(--borde); display: flex; align-items: center; gap: .9rem; }
    .viaje-ico { width: 38px; height: 38px; border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .viaje-info { flex: 1; min-width: 0; }
    .viaje-ruta { font-weight: 600; font-size: .875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .viaje-meta { font-size: .75rem; color: var(--gris); margin-top: .2rem; }
    .viaje-monto { font-family: var(--font-display); font-weight: 700; font-size: .95rem; color: var(--verde-oscuro); white-space: nowrap; }

    .empty-hist { text-align: center; padding: 2rem; color: var(--gris); font-size: .875rem; background: var(--blanco); border-radius: var(--r-md); border: 1.5px dashed var(--borde); }

    /* Bottom nav mobile */
    .bottom-nav { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: var(--blanco); border-top: 1px solid var(--borde); padding: .5rem; display: grid; grid-template-columns: repeat(4, 1fr); z-index: 100; }
    .bottom-item { display: flex; flex-direction: column; align-items: center; gap: .25rem; padding: .5rem; text-decoration: none; color: var(--gris); font-size: .65rem; font-weight: 500; border-radius: var(--r-sm); transition: all .15s; }
    .bottom-item.active { color: var(--verde-oscuro); }
    .bottom-item svg { width: 20px; height: 20px; }
</style>
@endpush

@section('content')
<nav class="app-nav">
    <a href="{{ route('dashboard') }}" class="app-nav-brand">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <div class="app-nav-right">
        <button class="notif-btn">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            <span class="notif-dot"></span>
        </button>
        <a href="{{ route('perfil') }}">
            <div class="avatar-placeholder">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
        </a>
    </div>
</nav>

<div class="hero-section">
    <div class="hero-content">
        <p class="hero-greeting">Buenos días 👋</p>
        <h1 class="hero-name">{{ explode(' ', auth()->user()->nombre)[0] }}</h1>
        <p class="hero-sub">¿Qué necesitas hoy?</p>
    </div>
</div>

<div class="main-content">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    {{-- Servicios --}}
    <h2 class="services-title">Servicios</h2>
    <div class="services-grid">
        <a href="{{ route('viaje.nuevo') }}" class="service-card">
            <div class="service-icon" style="background:var(--verde-bg);">🚗</div>
            <div class="service-name">Pedir Viaje</div>
            <div class="service-desc">Te llevamos a donde necesites en tu comunidad.</div>
            <div class="service-arrow">Pedir ahora <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></div>
        </a>
        <a href="{{ route('mandado.nuevo') }}" class="service-card">
            <div class="service-icon" style="background:#fef3c7;">🛒</div>
            <div class="service-name">Mandado</div>
            <div class="service-desc">Dinos qué comprar y te lo traemos.</div>
            <div class="service-arrow">Pedir ahora <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></div>
        </a>
    </div>

    {{-- Historial reciente --}}
    <div class="historial-header">
        <h2 class="historial-title">Viajes recientes</h2>
        <a href="{{ route('mis-viajes') }}" class="ver-todo">Ver todo →</a>
    </div>

    @php
        $recientes = \App\Models\Servicio::with(['conductor.usuario'])
            ->where('cliente_id', auth()->id())
            ->orderByDesc('creado_en')
            ->limit(4)
            ->get();
        $iconos = ['viaje'=>'🚗','mandado_libre'=>'🛒','delivery_tienda'=>'🏪'];
        $bgIcono = ['viaje'=>'var(--verde-bg)','mandado_libre'=>'#fef3c7','delivery_tienda'=>'#f3e8ff'];
        $estatusClass = ['completado'=>'badge-green','cancelado'=>'badge-red','buscando'=>'badge-yellow','aceptado'=>'badge-blue','en_ruta'=>'badge-blue','en_sitio'=>'badge-blue'];
    @endphp

    @forelse($recientes as $s)
        <div class="viaje-item">
            <div class="viaje-ico" style="background:{{ $bgIcono[$s->tipo] ?? 'var(--verde-bg)' }};">
                {{ $iconos[$s->tipo] ?? '📦' }}
            </div>
            <div class="viaje-info">
                <div class="viaje-ruta">{{ $s->direccion_origen }} → {{ $s->direccion_destino }}</div>
                <div class="viaje-meta">
                    {{ \Carbon\Carbon::parse($s->creado_en)->format('d M • H:i') }} ·
                    <span class="badge {{ $estatusClass[$s->estatus] ?? 'badge-gray' }}" style="font-size:.7rem; padding:.1rem .5rem;">{{ ucfirst($s->estatus) }}</span>
                </div>
            </div>
            <div class="viaje-monto">${{ number_format($s->total_final, 0) }}</div>
        </div>
    @empty
        <div class="empty-hist">
            Aún no tienes viajes.<br>¡Pide tu primero arriba! 👆
        </div>
    @endforelse
</div>

{{-- Bottom nav --}}
<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="bottom-item active">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Inicio
    </a>
    <a href="{{ route('viaje.nuevo') }}" class="bottom-item">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
        Viaje
    </a>
    <a href="{{ route('mis-viajes') }}" class="bottom-item">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Historial
    </a>
    <a href="{{ route('perfil') }}" class="bottom-item">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Perfil
    </a>
</nav>
@endsection
