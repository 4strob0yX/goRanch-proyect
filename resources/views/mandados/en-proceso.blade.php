@extends('layouts.app')
@section('title', 'Mandado en Proceso - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .page { max-width: 600px; padding: 2rem 1rem; }
    .mandado-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .mandado-title { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; }
    .folio-badge { background: var(--gris-claro); border-radius: 999px; padding: .3rem .9rem; font-size: .85rem; font-weight: 600; color: var(--gris); }

    /* Tienda card */
    .tienda-card { background: linear-gradient(135deg, #d4e8c8, #b8d8a8); border-radius: 16px; padding: 1.2rem 1.4rem; margin-bottom: 1rem; }
    .tienda-label { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; }

    /* Timeline */
    .timeline-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); padding: 1.2rem 1.4rem; margin-bottom: 1rem; }
    .timeline-item { display: flex; gap: 1rem; position: relative; }
    .timeline-item:not(:last-child) { margin-bottom: 1.5rem; }
    .timeline-left { display: flex; flex-direction: column; align-items: center; }
    .timeline-dot { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .timeline-dot.active { background: var(--verde); }
    .timeline-dot.pending { background: var(--gris-claro); }
    .timeline-line { width: 2px; flex: 1; background: var(--gris-claro); margin: 4px 0; min-height: 30px; }
    .timeline-content { flex: 1; padding-top: .3rem; }
    .timeline-content strong { font-weight: 600; display: block; }
    .timeline-content .status-text { font-size: .875rem; color: var(--verde); font-weight: 600; }
    .timeline-content .desc { font-size: .85rem; color: var(--gris); margin-top: .2rem; }
    .timeline-content .pending-text { font-size: .875rem; color: var(--gris); }
    .live-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--verde); animation: pulse 2s infinite; margin-left: auto; margin-top: .4rem; }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.3;} }

    /* Conductor card */
    .conductor-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); padding: 1.2rem 1.4rem; margin-bottom: 1rem; }
    .conductor-row-inner { display: flex; align-items: center; gap: 1rem; }
    .c-avatar { width: 52px; height: 52px; border-radius: 50%; background: #f0c060; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; position: relative; }
    .c-rating { position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%); background: var(--verde); color: white; border-radius: 999px; padding: .05rem .4rem; font-size: .65rem; font-weight: 700; white-space: nowrap; }
    .c-info strong { font-weight: 700; font-size: 1rem; display: block; }
    .c-info span { font-size: .8rem; color: var(--gris); }
    .c-placa { background: var(--gris-claro); border-radius: 999px; padding: .2rem .7rem; font-size: .8rem; font-weight: 600; margin-left: auto; }
    .action-btns-row { display: flex; gap: .8rem; margin-top: 1rem; }

    /* Costos */
    .costos-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); padding: 1.2rem 1.4rem; }
    .costo-row { display: flex; justify-content: space-between; font-size: .9rem; padding: .4rem 0; }
    .costo-row.total { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; border-top: 1px solid var(--gris-claro); margin-top: .4rem; padding-top: .8rem; }
    .costo-row.total span:last-child { color: var(--verde-oscuro); }
    .precio-warning { background: #fef3c7; border-radius: 10px; padding: .7rem .9rem; margin-top: .8rem; display: flex; gap: .5rem; font-size: .8rem; color: #92400e; }
</style>
@endpush

@section('content')
<div class="page" style="margin: 0 auto;">
    <div class="mandado-header">
        <h1 class="mandado-title">Mandado en Proceso</h1>
        <span class="folio-badge">#GR-{{ $servicio->id ?? '8492' }}</span>
    </div>

    {{-- Tienda --}}
    <div class="tienda-card">
        <div class="tienda-label">
            <svg width="20" height="20" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            {{ $servicio->tienda ?? 'Tienda El Cruce' }}
        </div>
    </div>

    {{-- Timeline --}}
    <div class="timeline-card">
        <div class="timeline-item">
            <div class="timeline-left">
                <div class="timeline-dot active">
                    <svg width="18" height="18" fill="white" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                </div>
                <div class="timeline-line"></div>
            </div>
            <div class="timeline-content">
                <div style="display:flex; justify-content:space-between;">
                    <strong>Comprando</strong>
                    <div class="live-dot"></div>
                </div>
                <div class="status-text">En progreso • Hace 5 min</div>
                <div class="desc">{{ $conductor->usuario->nombre ?? 'Ramón' }} está recolectando tus artículos en {{ $servicio->tienda ?? 'Tienda El Cruce' }}.</div>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-left">
                <div class="timeline-dot pending">
                    <svg width="18" height="18" fill="none" stroke="var(--gris)" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
                </div>
                <div class="timeline-line"></div>
            </div>
            <div class="timeline-content">
                <strong>En camino a tu casa</strong>
                <div class="pending-text">Pendiente</div>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-left">
                <div class="timeline-dot pending">
                    <svg width="18" height="18" fill="none" stroke="var(--gris)" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="timeline-content">
                <strong>Entrega</strong>
                <div class="pending-text">Pendiente</div>
            </div>
        </div>
    </div>

    {{-- Conductor --}}
    <div class="conductor-card">
        <div class="conductor-row-inner">
            <div class="c-avatar">
                🧑
                <div class="c-rating">4.9 ★</div>
            </div>
            <div class="c-info">
                <strong>{{ $conductor->usuario->nombre ?? 'Ramón G.' }}</strong>
                <span>Tu Runner asignado</span>
            </div>
            <span class="c-placa">{{ $conductor->modelo ?? 'Ford Ranger' }} • {{ $conductor->placa ?? 'GR-220' }}</span>
        </div>
        <div class="action-btns-row">
            <a href="tel:{{ $conductor->usuario->telefono ?? '' }}" class="btn btn-primary" style="flex:1; border-radius:10px;">
                <svg width="16" height="16" fill="white" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                Llamar
            </a>
            <a href="#" class="btn btn-outline" style="flex:1; border-radius:10px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                Mensaje
            </a>
        </div>
    </div>

    {{-- Costos --}}
    <div class="costos-card">
        <div class="costo-row">
            <span>Servicio de mandado</span>
            <span>${{ number_format($servicio->costo_productos ?? 110, 2) }} MXN</span>
        </div>
        <div class="costo-row">
            <span>Tarifa de envío</span>
            <span>${{ number_format($servicio->costo_envio ?? 40, 2) }} MXN</span>
        </div>
        <div class="costo-row total">
            <span>Total Estimado</span>
            <span>${{ number_format(($servicio->total_final ?? 150), 2) }} MXN</span>
        </div>
        <div class="precio-warning">
            <svg width="16" height="16" fill="none" stroke="#92400e" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            El precio final puede variar si se agregan productos adicionales durante el proceso de compra.
        </div>
    </div>
</div>
@endsection
