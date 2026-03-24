@extends('layouts.app')
@section('title', 'Mandado en Proceso - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .top-bar { background: var(--blanco); border-bottom: 1px solid var(--borde); height: 56px; padding: 0 1.2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .back-btn { color: var(--gris); text-decoration: none; display: flex; align-items: center; gap: .4rem; font-size: .875rem; }
    .top-title { font-family: var(--font-display); font-weight: 700; }

    .page-body { max-width: 480px; margin: 0 auto; padding: 1.5rem; }

    /* Status timeline */
    .timeline { margin-bottom: 2rem; }
    .tl-item { display: flex; gap: 1rem; margin-bottom: 0; }
    .tl-left { display: flex; flex-direction: column; align-items: center; }
    .tl-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .75rem; font-weight: 700; }
    .tl-dot.done { background: var(--verde); color: white; }
    .tl-dot.current { background: var(--amarillo); color: white; animation: pulse 1.5s infinite; }
    .tl-dot.pending { background: var(--borde); color: var(--gris); }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
    .tl-line { width: 2px; flex: 1; background: var(--borde); margin: 3px 0; min-height: 24px; }
    .tl-line.done { background: var(--verde); }
    .tl-right { padding-bottom: 1.4rem; padding-top: .1rem; }
    .tl-title { font-weight: 600; font-size: .9rem; }
    .tl-sub { font-size: .75rem; color: var(--gris); margin-top: .15rem; }

    /* Lista items */
    .items-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; margin-bottom: 1rem; }
    .ic-header { padding: .8rem 1.2rem; background: var(--fondo); border-bottom: 1px solid var(--borde); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); }
    .ic-item { display: flex; align-items: center; gap: .9rem; padding: .8rem 1.2rem; border-bottom: 1px solid var(--borde); }
    .ic-item:last-child { border-bottom: none; }
    .ic-check { width: 22px; height: 22px; border-radius: 50%; background: var(--verde-bg); border: 1.5px solid var(--verde-claro); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ic-nombre { flex: 1; font-weight: 500; font-size: .875rem; }
    .ic-qty { font-size: .78rem; color: var(--gris); background: var(--fondo); padding: .2rem .6rem; border-radius: var(--r-full); }

    /* Conductor card */
    .conductor-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); padding: 1rem 1.2rem; display: flex; align-items: center; gap: .9rem; margin-bottom: 1rem; }
    .c-avatar { width: 44px; height: 44px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: 1rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .c-name { font-weight: 700; font-size: .9rem; }
    .c-sub { font-size: .75rem; color: var(--gris); margin-top: .1rem; }
    .c-rating { font-size: .75rem; color: var(--amarillo); font-weight: 600; margin-top: .1rem; }

    /* Resumen */
    .resumen-card { background: var(--verde-oscuro); border-radius: var(--r-lg); padding: 1.1rem 1.3rem; color: white; }
    .resumen-row { display: flex; justify-content: space-between; font-size: .85rem; color: rgba(255,255,255,.6); margin-bottom: .4rem; }
    .resumen-total { display: flex; justify-content: space-between; font-family: var(--font-display); font-weight: 700; font-size: 1.2rem; color: white; padding-top: .7rem; border-top: 1px solid rgba(255,255,255,.12); margin-top: .3rem; }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('dashboard') }}" class="back-btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <span class="top-title">Mandado en proceso</span>
    <span style="font-size:.75rem; color:var(--gris);">#{{ $servicio->id }}</span>
</div>

<div class="page-body">

    {{-- Timeline --}}
    @php
        $pasos = [
            ['key'=>'buscando',  'label'=>'Buscando conductor', 'sub'=>'Asignando el mejor conductor disponible'],
            ['key'=>'aceptado',  'label'=>'Conductor en camino', 'sub'=>'Va a comprar tus artículos'],
            ['key'=>'en_ruta',   'label'=>'Regresando contigo', 'sub'=>'Ya tiene todo y viene en camino'],
            ['key'=>'completado','label'=>'¡Entregado!', 'sub'=>'Tu mandado llegó'],
        ];
        $orden = ['buscando'=>0,'aceptado'=>1,'en_sitio'=>1,'en_ruta'=>2,'completado'=>3];
        $actual = $orden[$servicio->estatus] ?? 0;
    @endphp

    <div class="timeline">
        @foreach($pasos as $idx => $paso)
            <div class="tl-item">
                <div class="tl-left">
                    <div class="tl-dot {{ $idx < $actual ? 'done' : ($idx === $actual ? 'current' : 'pending') }}">
                        @if($idx < $actual) ✓ @else {{ $idx + 1 }} @endif
                    </div>
                    @if(!$loop->last)
                        <div class="tl-line {{ $idx < $actual ? 'done' : '' }}"></div>
                    @endif
                </div>
                <div class="tl-right">
                    <div class="tl-title" style="{{ $idx === $actual ? 'color:var(--verde-oscuro);font-weight:700;' : ($idx < $actual ? '' : 'color:var(--gris);') }}">{{ $paso['label'] }}</div>
                    <div class="tl-sub">{{ $paso['sub'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Conductor o buscando --}}
    <div id="conductor-area">
        @if($servicio->conductor)
            <div class="conductor-card">
                <div class="c-avatar">{{ strtoupper(substr($servicio->conductor->usuario->nombre ?? 'C', 0, 2)) }}</div>
                <div>
                    <div class="c-name">{{ $servicio->conductor->usuario->nombre ?? 'Conductor' }}</div>
                    <div class="c-sub">{{ ucfirst($servicio->conductor->tipo_vehiculo) }} · {{ $servicio->conductor->placa }}</div>
                    <div class="c-rating">⭐ {{ $servicio->conductor->calificacion_promedio }}</div>
                </div>
                <a href="tel:" style="margin-left:auto; width:36px; height:36px; border-radius:50%; background:var(--verde-bg); border:1px solid var(--verde-claro); display:flex; align-items:center; justify-content:center; color:var(--verde-oscuro); text-decoration:none;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2A19.79 19.79 0 013.07 9.81a2 2 0 012-2.18H8a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                </a>
            </div>
        @else
            <div class="conductor-card" id="buscando-card" style="justify-content:center; flex-direction:column; align-items:center; gap:.6rem; padding:1.5rem;">
                <div class="searching-spinner"></div>
                <div style="font-weight:700; font-size:.95rem; color:var(--texto);">Buscando conductor...</div>
                <div style="font-size:.78rem; color:var(--gris); text-align:center;">Estamos notificando a los conductores cercanos.<br>Esto puede tomar unos momentos.</div>
            </div>
        @endif
    </div>

    {{-- Items from detalle_servicios --}}
    @if($items->count())
        <div class="items-card">
            <div class="ic-header">Tu lista ({{ $items->count() }} artículos)</div>
            @foreach($items as $item)
                <div class="ic-item">
                    <div class="ic-check"><svg width="11" height="11" fill="none" stroke="var(--verde)" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
                    <div class="ic-nombre">{{ $item->nombre }}</div>
                    <div class="ic-qty">×{{ $item->cantidad }}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Resumen --}}
    <div class="resumen-card">
        <div class="resumen-row"><span>Envío</span><span>${{ number_format($servicio->costo_envio, 2) }}</span></div>
        <div class="resumen-row"><span>Pago</span><span>{{ ucfirst($servicio->metodo_pago) }}</span></div>
        <div class="resumen-total"><span>Total estimado</span><span>${{ number_format($servicio->total_final, 2) }}</span></div>
    </div>

</div>

@if(!$servicio->conductor)
@push('styles')
<style>
    .searching-spinner { width: 36px; height: 36px; border: 3px solid var(--borde); border-top-color: var(--verde); border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush
@push('scripts')
<script>
function pollStatus() {
    fetch('{{ route("api.servicio.status", $servicio->id) }}')
        .then(r => r.json())
        .then(data => {
            if (data.estatus !== 'buscando' && data.conductor) {
                location.reload();
            }
        })
        .catch(() => {});
}
setInterval(pollStatus, 4000);
</script>
@endpush
@endif
@endsection
