@extends('layouts.app')
@section('title', 'Mis Viajes - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .top-nav { background: var(--blanco); border-bottom: 1px solid var(--borde); height: 56px; padding: 0 1.2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .back-btn { color: var(--gris); text-decoration: none; display: flex; align-items: center; gap: .4rem; font-size: .875rem; }
    .top-title { font-family: var(--font-display); font-weight: 700; }

    .page-body { max-width: 560px; margin: 0 auto; padding: 1.5rem; }

    /* Stats strip */
    .stats-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: .7rem; margin-bottom: 1.5rem; }
    .sc { background: var(--blanco); border-radius: var(--r-md); border: 1px solid var(--borde); padding: .8rem; text-align: center; }
    .sc-val { font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--verde-oscuro); }
    .sc-lbl { font-size: .7rem; color: var(--gris); margin-top: .15rem; }

    /* Filtros */
    .filters { display: flex; gap: .4rem; margin-bottom: 1.2rem; overflow-x: auto; padding-bottom: .2rem; }
    .filters::-webkit-scrollbar { display: none; }
    .chip { padding: .4rem .9rem; border-radius: var(--r-full); font-size: .8rem; font-weight: 600; border: 1.5px solid var(--borde); background: var(--blanco); color: var(--gris); cursor: pointer; white-space: nowrap; transition: all .15s; }
    .chip:hover { border-color: var(--verde-claro); color: var(--verde-oscuro); }
    .chip.active { background: var(--verde-oscuro); color: white; border-color: var(--verde-oscuro); }

    /* Viaje card */
    .viaje-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); padding: 1.1rem; margin-bottom: .8rem; transition: box-shadow .15s; }
    .viaje-card:hover { box-shadow: var(--sombra-sm); }

    .vc-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .9rem; }
    .vc-tipo { display: flex; align-items: center; gap: .5rem; }
    .vc-ico { width: 36px; height: 36px; border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
    .vc-tipo-name { font-weight: 700; font-size: .875rem; }
    .vc-fecha { font-size: .72rem; color: var(--gris); }

    .vc-ruta { display: flex; gap: .7rem; margin-bottom: .9rem; }
    .vc-ruta-dots { display: flex; flex-direction: column; align-items: center; padding-top: .25rem; }
    .vc-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .vc-dot-a { background: var(--verde); }
    .vc-dot-b { background: var(--rojo); }
    .vc-dot-line { width: 1.5px; flex: 1; background: var(--borde); margin: 2px 0; min-height: 14px; }
    .vc-ruta-text { flex: 1; }
    .vc-from { font-size: .84rem; font-weight: 500; color: var(--texto); margin-bottom: .25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .vc-to { font-size: .84rem; font-weight: 500; color: var(--texto); margin-top: .25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .vc-space { height: 10px; }

    .vc-bottom { display: flex; align-items: center; justify-content: space-between; padding-top: .8rem; border-top: 1px solid var(--borde); }
    .vc-monto { font-family: var(--font-display); font-weight: 700; font-size: 1rem; color: var(--verde-oscuro); }
    .vc-conductor { font-size: .75rem; color: var(--gris); }

    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-ico { font-size: 3rem; margin-bottom: 1rem; }
    .empty-title { font-family: var(--font-display); font-weight: 700; font-size: 1.2rem; margin-bottom: .5rem; }
    .empty-sub { font-size: .875rem; color: var(--gris); margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')
<nav class="top-nav">
    <a href="{{ route('dashboard') }}" class="back-btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <span class="top-title">Mis Viajes</span>
    <div style="width:40px;"></div>
</nav>

<div class="page-body">

    @php
        $servicios  = \App\Models\Servicio::with(['conductor.usuario'])->where('cliente_id', auth()->id())->orderByDesc('creado_en')->get();
        $completados = $servicios->where('estatus','completado')->count();
        $gastado     = $servicios->where('estatus','completado')->sum('total_final');
        $filtro      = request('filtro','todos');
        $tipoLabel   = ['viaje'=>'Viaje','mandado_libre'=>'Mandado','delivery_tienda'=>'Delivery'];
        $tipoIco     = ['viaje'=>'🚗','mandado_libre'=>'🛒','delivery_tienda'=>'🏪'];
        $tipoBg      = ['viaje'=>'var(--verde-bg)','mandado_libre'=>'#fef3c7','delivery_tienda'=>'#f3e8ff'];
        $estClass    = ['completado'=>'badge-green','cancelado'=>'badge-red','buscando'=>'badge-yellow','aceptado'=>'badge-blue','en_ruta'=>'badge-blue','en_sitio'=>'badge-blue'];

        $filtered = match($filtro) {
            'viajes'   => $servicios->where('tipo','viaje'),
            'mandados' => $servicios->where('tipo','mandado_libre'),
            'activos'  => $servicios->whereNotIn('estatus',['completado','cancelado']),
            default    => $servicios,
        };
    @endphp

    {{-- Stats --}}
    <div class="stats-strip">
        <div class="sc">
            <div class="sc-val">{{ $servicios->count() }}</div>
            <div class="sc-lbl">Totales</div>
        </div>
        <div class="sc">
            <div class="sc-val">{{ $completados }}</div>
            <div class="sc-lbl">Completados</div>
        </div>
        <div class="sc">
            <div class="sc-val">${{ number_format($gastado, 0) }}</div>
            <div class="sc-lbl">Gastado</div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="filters">
        <a href="?filtro=todos"    class="chip {{ $filtro==='todos'   ?'active':'' }}">Todos</a>
        <a href="?filtro=activos"  class="chip {{ $filtro==='activos' ?'active':'' }}">🔴 Activos</a>
        <a href="?filtro=viajes"   class="chip {{ $filtro==='viajes'  ?'active':'' }}">🚗 Viajes</a>
        <a href="?filtro=mandados" class="chip {{ $filtro==='mandados'?'active':'' }}">🛒 Mandados</a>
    </div>

    {{-- Lista --}}
    @forelse($filtered as $s)
        <div class="viaje-card">
            <div class="vc-top">
                <div class="vc-tipo">
                    <div class="vc-ico" style="background:{{ $tipoBg[$s->tipo] ?? 'var(--verde-bg)' }};">{{ $tipoIco[$s->tipo] ?? '📦' }}</div>
                    <div>
                        <div class="vc-tipo-name">{{ $tipoLabel[$s->tipo] ?? ucfirst($s->tipo) }}</div>
                        <div class="vc-fecha">{{ \Carbon\Carbon::parse($s->creado_en)->format('d M Y · H:i') }}</div>
                    </div>
                </div>
                <span class="badge {{ $estClass[$s->estatus] ?? 'badge-gray' }}">{{ ucfirst(str_replace('_',' ',$s->estatus)) }}</span>
            </div>

            <div class="vc-ruta">
                <div class="vc-ruta-dots">
                    <div class="vc-dot vc-dot-a"></div>
                    <div class="vc-dot-line"></div>
                    <div class="vc-dot vc-dot-b"></div>
                </div>
                <div class="vc-ruta-text">
                    <div class="vc-from">{{ $s->direccion_origen }}</div>
                    <div class="vc-space"></div>
                    <div class="vc-to">{{ $s->direccion_destino }}</div>
                </div>
            </div>

            <div class="vc-bottom">
                <div class="vc-conductor">
                    {{ $s->conductor?->usuario?->nombre ?? 'Sin conductor' }}
                </div>
                <div class="vc-monto">${{ number_format($s->total_final, 2) }}</div>
            </div>

            @if(in_array($s->estatus, ['buscando', 'aceptado', 'en_sitio', 'en_ruta']))
                <a href="{{ $s->tipo === 'mandado_libre'
                    ? route('mandado.en-proceso', $s->id)
                    : route('viaje.en-camino', $s->id) }}"
                    class="btn btn-primary btn-full btn-sm" style="margin-top:.8rem; border-radius:var(--r-sm);">
                    {{ $s->estatus === 'buscando' ? 'Esperando conductor →' : 'Ver en tiempo real →' }}
                </a>
            @endif
            @if($s->estatus === 'completado' && $s->conductor && !$s->calificacion_cliente)
                <a href="{{ route('viaje.finalizado', $s->id) }}"
                    class="btn btn-outline btn-full btn-sm" style="margin-top:.8rem; border-radius:var(--r-sm);">
                    ⭐ Calificar viaje
                </a>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-ico">🗺️</div>
            <div class="empty-title">Sin viajes aquí</div>
            <div class="empty-sub">¿Listo para tu primer viaje?</div>
            <a href="{{ route('viaje.nuevo') }}" class="btn btn-primary">Pedir un viaje</a>
        </div>
    @endforelse

</div>
@endsection
