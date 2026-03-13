@extends('layouts.app')
@section('title', 'Viaje Finalizado - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); display: flex; flex-direction: column; min-height: 100vh; }

    .page-body { max-width: 420px; margin: 0 auto; padding: 2rem 1.5rem; flex: 1; display: flex; flex-direction: column; }

    .success-icon { width: 80px; height: 80px; border-radius: 50%; background: var(--verde-bg); border: 2px solid var(--verde-claro); display: flex; align-items: center; justify-content: center; margin: 1.5rem auto; font-size: 2.5rem; }
    .done-title { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; text-align: center; margin-bottom: .3rem; }
    .done-sub { text-align: center; color: var(--gris); font-size: .9rem; margin-bottom: 2rem; }

    .receipt { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; margin-bottom: 1.5rem; }
    .receipt-header { background: var(--verde-oscuro); padding: 1rem 1.3rem; }
    .receipt-header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .3rem; }
    .r-id { font-size: .75rem; color: rgba(255,255,255,.4); }
    .r-tipo { font-size: .75rem; color: rgba(255,255,255,.5); background: rgba(255,255,255,.1); padding: .2rem .6rem; border-radius: var(--r-full); }
    .r-ruta { font-weight: 600; font-size: .9rem; color: white; }
    .r-sub { font-size: .75rem; color: rgba(255,255,255,.4); margin-top: .2rem; }

    .receipt-body { padding: 1.1rem 1.3rem; }
    .r-row { display: flex; justify-content: space-between; font-size: .875rem; color: var(--gris); margin-bottom: .5rem; }
    .r-total { display: flex; justify-content: space-between; font-family: var(--font-display); font-weight: 700; font-size: 1.2rem; color: var(--texto); padding-top: .7rem; border-top: 1px solid var(--borde); margin-top: .3rem; }

    /* Rating */
    .rating-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); padding: 1.3rem; margin-bottom: 1.5rem; }
    .rating-title { font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: .3rem; }
    .rating-sub { font-size: .82rem; color: var(--gris); margin-bottom: 1rem; }
    .stars { display: flex; gap: .4rem; justify-content: center; margin-bottom: 1rem; }
    .star { font-size: 2rem; cursor: pointer; opacity: .3; transition: all .15s; }
    .star:hover, .star.active { opacity: 1; transform: scale(1.15); }

    .actions { margin-top: auto; display: flex; flex-direction: column; gap: .7rem; }
</style>
@endpush

@section('content')
<div class="page-body">

    <div class="success-icon">✅</div>
    <h1 class="done-title">¡Llegaste!</h1>
    <p class="done-sub">Tu viaje ha sido completado. Gracias por usar goRanch.</p>

    {{-- Recibo --}}
    <div class="receipt">
        <div class="receipt-header">
            <div class="receipt-header-top">
                <span class="r-id">#{{ $servicio->id }}</span>
                <span class="r-tipo">🚗 Viaje</span>
            </div>
            <div class="r-ruta">{{ $servicio->direccion_origen }} → {{ $servicio->direccion_destino }}</div>
            <div class="r-sub">{{ \Carbon\Carbon::parse($servicio->creado_en)->format('d M Y · H:i') }}</div>
        </div>
        <div class="receipt-body">
            <div class="r-row"><span>Tarifa base</span><span>$15.00</span></div>
            <div class="r-row"><span>Distancia ({{ $servicio->distancia_km }} km)</span><span>${{ number_format($servicio->distancia_km * 8, 2) }}</span></div>
            <div class="r-row"><span>Método de pago</span><span>{{ ucfirst($servicio->metodo_pago) }}</span></div>
            @if($servicio->conductor)
                <div class="r-row"><span>Conductor</span><span>{{ $servicio->conductor->usuario->nombre ?? '—' }}</span></div>
            @endif
            <div class="r-total"><span>Total</span><span>${{ number_format($servicio->total_final, 2) }}</span></div>
        </div>
    </div>

    {{-- Calificar --}}
    @if($servicio->conductor && !$servicio->calificacion_cliente)
        <div class="rating-card">
            <div class="rating-title">¿Cómo fue tu viaje?</div>
            <div class="rating-sub">Tu calificación ayuda a mejorar el servicio.</div>
            <form method="POST" action="{{ route('viaje.calificar', $servicio->id) }}">
                @csrf
                <div class="stars">
                    @for($i=1; $i<=5; $i++)
                        <span class="star" data-val="{{ $i }}" onclick="setRating({{ $i }})">⭐</span>
                    @endfor
                </div>
                <input type="hidden" name="calificacion" id="rating-val" value="5">
                <button type="submit" class="btn btn-primary btn-full" style="border-radius:var(--r-sm);">Enviar calificación</button>
            </form>
        </div>
    @endif

    <div class="actions">
        <a href="{{ route('viaje.nuevo') }}" class="btn btn-primary btn-lg" style="border-radius:var(--r-md);">Pedir otro viaje</a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-lg" style="border-radius:var(--r-md);">Ir al inicio</a>
    </div>

</div>

@push('scripts')
<script>
function setRating(val) {
    document.getElementById('rating-val').value = val;
    document.querySelectorAll('.star').forEach((s, i) => s.classList.toggle('active', i < val));
}
setRating(5);
</script>
@endpush
@endsection
