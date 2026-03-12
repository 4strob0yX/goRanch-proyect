@extends('layouts.app')
@section('title', 'Mis Viajes - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .top-bar { background: white; border-bottom: 1px solid var(--gris-claro); padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; position: sticky; top: 0; z-index: 40; }
    .btn-back { background: none; border: none; color: var(--texto); cursor: pointer; display: flex; align-items: center; text-decoration: none; }
    .top-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; }

    .page-body { padding: 1.5rem; max-width: 600px; margin: 0 auto; }

    .stats-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: .8rem; margin-bottom: 1.5rem; }
    .strip-card { background: white; border-radius: 14px; padding: 1rem; text-align: center; box-shadow: var(--sombra); }
    .strip-value { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.4rem; }
    .strip-label { font-size: .75rem; color: var(--gris); margin-top: .2rem; }

    .filter-row { display: flex; gap: .5rem; margin-bottom: 1.2rem; overflow-x: auto; padding-bottom: .3rem; }
    .filter-chip { padding: .4rem 1rem; border-radius: 999px; font-size: .82rem; font-weight: 600; border: 1.5px solid var(--gris-claro); color: var(--gris); background: white; white-space: nowrap; text-decoration: none; transition: all .15s; }
    .filter-chip:hover { border-color: var(--verde); color: var(--verde); }
    .filter-chip.active { background: var(--verde-oscuro); border-color: var(--verde-oscuro); color: white; }

    .viaje-card { background: white; border-radius: 16px; box-shadow: var(--sombra); margin-bottom: .9rem; overflow: hidden; }
    .viaje-header { padding: 1rem 1.2rem; display: flex; justify-content: space-between; align-items: flex-start; }
    .viaje-tipo-row { display: flex; align-items: center; gap: .6rem; }
    .viaje-tipo-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .viaje-tipo-label { font-weight: 700; font-size: .9rem; }
    .viaje-fecha { font-size: .78rem; color: var(--gris); margin-top: .1rem; }
    .viaje-monto { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.1rem; color: var(--verde-oscuro); }

    .viaje-ruta { padding: 0 1.2rem .9rem; }
    .ruta-line { display: flex; align-items: flex-start; gap: .7rem; }
    .ruta-dots { display: flex; flex-direction: column; align-items: center; padding-top: .3rem; }
    .ruta-dot-o { width: 10px; height: 10px; border-radius: 50%; background: var(--verde); flex-shrink: 0; }
    .ruta-line-v { width: 2px; height: 22px; background: var(--gris-claro); margin: 2px 0; }
    .ruta-dot-d { width: 10px; height: 10px; border-radius: 50%; background: #ef4444; flex-shrink: 0; }
    .ruta-texts { flex: 1; }
    .ruta-text { font-size: .85rem; line-height: 1.4; }
    .ruta-text:first-child { margin-bottom: .5rem; }
    .ruta-text span { color: var(--gris); font-size: .72rem; display: block; }

    .viaje-footer { padding: .7rem 1.2rem; border-top: 1px solid var(--gris-claro); display: flex; justify-content: space-between; align-items: center; background: var(--fondo); }
    .conductor-mini { display: flex; align-items: center; gap: .5rem; font-size: .8rem; color: var(--gris); }
    .conductor-mini-avatar { width: 24px; height: 24px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-size: .65rem; font-weight: 700; color: var(--verde-oscuro); }
    .btn-calificar { font-size: .78rem; font-weight: 700; color: var(--verde-oscuro); background: var(--verde-bg); border: none; border-radius: 8px; padding: .3rem .8rem; cursor: pointer; font-family: 'DM Sans', sans-serif; text-decoration: none; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--gris); }
    .empty-icon { width: 70px; height: 70px; border-radius: 50%; background: var(--verde-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('dashboard') }}" class="btn-back">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <div class="top-title">Mis Viajes</div>
</div>

<div class="page-body">

    {{-- Stats --}}
    <div class="stats-strip">
        <div class="strip-card">
            <div class="strip-value">{{ $stats['total'] }}</div>
            <div class="strip-label">Total</div>
        </div>
        <div class="strip-card">
            <div class="strip-value" style="color:var(--verde-oscuro);">{{ $stats['completados'] }}</div>
            <div class="strip-label">Completados</div>
        </div>
        <div class="strip-card">
            <div class="strip-value">${{ number_format($stats['gastado'], 0) }}</div>
            <div class="strip-label">Gastado</div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="filter-row">
        <a href="{{ route('mis-viajes') }}" class="filter-chip {{ $filtro === 'todos' ? 'active' : '' }}">Todos</a>
        <a href="{{ route('mis-viajes', ['filtro' => 'viaje']) }}" class="filter-chip {{ $filtro === 'viaje' ? 'active' : '' }}">🚗 Viajes</a>
        <a href="{{ route('mis-viajes', ['filtro' => 'mandado_libre']) }}" class="filter-chip {{ $filtro === 'mandado_libre' ? 'active' : '' }}">🛒 Mandados</a>
        <a href="{{ route('mis-viajes', ['filtro' => 'completado']) }}" class="filter-chip {{ $filtro === 'completado' ? 'active' : '' }}">✅ Completados</a>
        <a href="{{ route('mis-viajes', ['filtro' => 'cancelado']) }}" class="filter-chip {{ $filtro === 'cancelado' ? 'active' : '' }}">❌ Cancelados</a>
    </div>

    {{-- Lista --}}
    @if($servicios->count() > 0)
        @foreach($servicios as $s)
            @php
                $iconos  = ['viaje'=>'🚗','mandado_libre'=>'🛒','delivery_tienda'=>'🏪'];
                $bgIcono = ['viaje'=>'var(--verde-bg)','mandado_libre'=>'#fef3c7','delivery_tienda'=>'#f3e8ff'];
                $labels  = ['viaje'=>'Viaje','mandado_libre'=>'Mandado','delivery_tienda'=>'Delivery'];
                $estatusClases = ['completado'=>'badge-green','cancelado'=>'badge-red','buscando'=>'badge-yellow','aceptado'=>'badge-blue','en_ruta'=>'badge-blue','en_sitio'=>'badge-blue'];
            @endphp

            <div class="viaje-card">
                <div class="viaje-header">
                    <div class="viaje-tipo-row">
                        <div class="viaje-tipo-icon" style="background:{{ $bgIcono[$s->tipo] ?? 'var(--verde-bg)' }};">
                            {{ $iconos[$s->tipo] ?? '📦' }}
                        </div>
                        <div>
                            <div class="viaje-tipo-label">{{ $labels[$s->tipo] ?? $s->tipo }}</div>
                            <div class="viaje-fecha">{{ \Carbon\Carbon::parse($s->creado_en)->format('d M Y • H:i') }}</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div class="viaje-monto">${{ number_format($s->total_final, 2) }}</div>
                        <span class="badge {{ $estatusClases[$s->estatus] ?? 'badge-gray' }}" style="font-size:.72rem;">
                            {{ ucfirst(str_replace('_',' ',$s->estatus)) }}
                        </span>
                    </div>
                </div>

                <div class="viaje-ruta">
                    <div class="ruta-line">
                        <div class="ruta-dots">
                            <div class="ruta-dot-o"></div>
                            <div class="ruta-line-v"></div>
                            <div class="ruta-dot-d"></div>
                        </div>
                        <div class="ruta-texts">
                            <div class="ruta-text">{{ $s->direccion_origen }}<span>Origen</span></div>
                            <div class="ruta-text">{{ $s->direccion_destino }}<span>Destino</span></div>
                        </div>
                    </div>
                </div>

                <div class="viaje-footer">
                    <div class="conductor-mini">
                        @if($s->conductor)
                            <div class="conductor-mini-avatar">{{ strtoupper(substr($s->conductor->usuario->nombre ?? 'C', 0, 2)) }}</div>
                            {{ Str::limit($s->conductor->usuario->nombre ?? 'Conductor', 18) }}
                            @if($s->conductor->calificacion_promedio) · ⭐ {{ $s->conductor->calificacion_promedio }} @endif
                        @else
                            Sin conductor
                        @endif
                    </div>
                    <span style="font-size:.75rem; color:var(--gris);">
                        {{ $s->metodo_pago === 'efectivo' ? '💵' : '👛' }} {{ ucfirst($s->metodo_pago) }}
                    </span>
                </div>

                @if($s->distancia_km || $s->estatus === 'completado')
                    <div style="padding:.6rem 1.2rem; border-top:1px solid var(--gris-claro); display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:.78rem; color:var(--gris);">
                            @if($s->distancia_km) 📍 {{ $s->distancia_km }} km @endif
                            @if($s->finalizado_en && $s->iniciado_en)
                                · ⏱ {{ \Carbon\Carbon::parse($s->iniciado_en)->diffForHumans($s->finalizado_en, true) }}
                            @endif
                        </span>
                        @if($s->estatus === 'completado' && $s->conductor_id)
                            <a href="{{ route('viaje.finalizado', $s->id) }}" class="btn-calificar">⭐ Calificar</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach

        <div style="margin-top:1rem;">{{ $servicios->links() }}</div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" fill="none" stroke="var(--verde-oscuro)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
            </div>
            <p style="font-weight:600; margin-bottom:.3rem;">No tienes viajes aún</p>
            <p style="font-size:.85rem;">¡Pide tu primer viaje o mandado!</p>
            <a href="{{ route('viaje.nuevo') }}" class="btn btn-primary" style="display:inline-block; margin-top:1rem; border-radius:12px; padding:.8rem 1.5rem;">
                Pedir Viaje
            </a>
        </div>
    @endif
</div>
@endsection
