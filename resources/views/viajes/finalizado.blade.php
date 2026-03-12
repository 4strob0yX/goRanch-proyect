@extends('layouts.app')
@section('title', 'Viaje Finalizado - goRanch')

@push('styles')
<style>
    body { background:var(--fondo); display:flex; flex-direction:column; align-items:center; justify-content:flex-start; min-height:100vh; }
    .top-nav { display:flex; align-items:center; justify-content:space-between; width:100%; padding:1rem 1.5rem; background:var(--blanco); border-bottom:1px solid var(--gris-claro); }
    .top-nav-brand { font-family:'Syne',sans-serif; font-weight:700; font-size:1.1rem; display:flex; align-items:center; gap:.5rem; text-decoration:none; color:var(--texto); }

    .finish-card {
        background:var(--blanco); border-radius:24px;
        padding:2rem 1.8rem 2.5rem; width:100%; max-width:440px; margin:1.5rem 1rem;
        box-shadow:0 8px 40px rgba(0,0,0,.1);
        /* Fondo mapa arriba */
        overflow:hidden; position:relative;
    }
    .map-header { height:100px; background:linear-gradient(135deg, #d4e8d0, #a8c898); margin:-2rem -1.8rem 1.5rem; display:flex; align-items:center; justify-content:center; }
    .river-line { width:30%; height:18px; background:#89bdd3; border-radius:999px; transform:rotate(-10deg); }

    .check-circle { width:56px; height:56px; border-radius:50%; background:var(--verde); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem; }
    .finish-title { font-family:'Syne',sans-serif; font-weight:700; font-size:1.4rem; text-align:center; }
    .finish-sub { color:var(--gris); text-align:center; font-size:.9rem; margin-bottom:.5rem; }
    .finish-price { font-family:'Syne',sans-serif; font-size:2rem; font-weight:800; text-align:center; margin-bottom:1.5rem; }
    .finish-price span { font-size:1rem; color:var(--gris); font-weight:400; font-family:'DM Sans',sans-serif; }

    .conductor-row { display:flex; align-items:center; gap:.8rem; background:var(--fondo); border-radius:12px; padding:.9rem; margin-bottom:1.5rem; }
    .conductor-mini-avatar { width:42px; height:42px; border-radius:50%; background:var(--verde-claro); display:flex; align-items:center; justify-content:center; font-size:1.1rem; position:relative; }
    .mini-rating { position:absolute; bottom:-4px; left:50%; transform:translateX(-50%); background:var(--verde); color:white; border-radius:999px; padding:.05rem .35rem; font-size:.65rem; font-weight:700; white-space:nowrap; }
    .conductor-mini-info strong { font-weight:600; font-size:.95rem; display:block; }
    .conductor-mini-info span { font-size:.8rem; color:var(--gris); }
    .info-btn { margin-left:auto; width:28px; height:28px; border-radius:50%; border:1.5px solid var(--gris-claro); display:flex; align-items:center; justify-content:center; color:var(--gris); font-size:.8rem; cursor:pointer; }

    .rating-label { text-align:center; font-weight:600; margin-bottom:.8rem; }
    .stars { display:flex; justify-content:center; gap:.5rem; margin-bottom:1.2rem; }
    .star { font-size:2rem; cursor:pointer; color:var(--gris-claro); transition:color .15s; }
    .star:hover, .star.active { color:#f59e0b; }

    .comment-input { width:100%; border:1.5px solid var(--gris-claro); border-radius:12px; padding:.9rem 1rem; font-family:'DM Sans',sans-serif; font-size:.9rem; resize:none; outline:none; margin-bottom:1.2rem; }
    .comment-input:focus { border-color:var(--verde); }
    .skip-link { display:block; text-align:center; color:var(--gris); font-size:.875rem; text-decoration:none; margin-top:.7rem; }
    .skip-link:hover { color:var(--texto); }
</style>
@endpush

@section('content')
<div class="top-nav">
    <a href="{{ route('dashboard') }}" class="top-nav-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <button style="background:none;border:none;cursor:pointer;color:var(--gris);">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
    </button>
</div>

<div class="finish-card">
    <div class="map-header">
        <div class="river-line"></div>
    </div>

    <div class="check-circle">
        <svg width="28" height="28" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h1 class="finish-title">¡Viaje Finalizado!</h1>
    <p class="finish-sub">Has llegado a tu destino</p>
    <div class="finish-price">
        ${{ number_format($servicio->total_final ?? 50, 2) }} <span>MXN</span>
    </div>

    <div class="conductor-row">
        <div class="conductor-mini-avatar">
            🧑‍🦱
            <div class="mini-rating">4.9 ★</div>
        </div>
        <div class="conductor-mini-info">
            <strong>{{ $conductor->usuario->nombre ?? 'Juan Pérez' }}</strong>
            <span>{{ $conductor->modelo ?? 'Toyota Hilux' }} • {{ $conductor->placa ?? 'PLT-839' }}</span>
        </div>
        <div class="info-btn">ℹ</div>
    </div>

    <div class="rating-label">¿Cómo estuvo tu conductor?</div>
    <div class="stars" id="stars">
        @for($i = 1; $i <= 5; $i++)
            <span class="star" data-val="{{ $i }}" onclick="setRating({{ $i }})">★</span>
        @endfor
    </div>

    <form method="POST" action="{{ route('viaje.calificar', $servicio->id ?? 1) }}">
        @csrf
        <input type="hidden" name="calificacion" id="calificacion-input" value="0">
        <textarea class="comment-input" name="comentario" rows="3"
            placeholder="Comentario (Opcional) - ¿Qué te gustó más?"></textarea>
        <button type="submit" class="btn btn-primary btn-full" style="border-radius:12px; padding:1rem; font-size:1rem;">
            Enviar Calificación →
        </button>
    </form>

    <a href="{{ route('dashboard') }}" class="skip-link">Saltar por ahora</a>
</div>

@push('scripts')
<script>
function setRating(val) {
    document.getElementById('calificacion-input').value = val;
    document.querySelectorAll('.star').forEach(s => {
        s.classList.toggle('active', parseInt(s.dataset.val) <= val);
    });
}
</script>
@endpush
@endsection
