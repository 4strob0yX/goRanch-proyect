@extends('layouts.app')
@section('title', 'Nuevo Viaje - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .top-nav { background: var(--blanco); border-bottom: 1px solid var(--borde); height: 56px; padding: 0 1.2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .back-btn { display: flex; align-items: center; gap: .4rem; color: var(--gris); text-decoration: none; font-size: .875rem; }
    .top-title { font-family: var(--font-display); font-weight: 700; font-size: 1rem; }

    .page-body { max-width: 520px; margin: 0 auto; padding: 1.5rem; }

    .step-bar { display: flex; align-items: center; gap: .3rem; margin-bottom: 2rem; }
    .step { flex: 1; height: 3px; border-radius: 99px; background: var(--borde); }
    .step.done { background: var(--verde); }

    .section-title { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; margin-bottom: .3rem; }
    .section-sub { color: var(--gris); font-size: .875rem; margin-bottom: 1.5rem; }

    /* Puntos */
    .punto-grid { display: grid; gap: .7rem; margin-bottom: 1.5rem; }
    .punto-card { background: var(--blanco); border: 1.5px solid var(--borde); border-radius: var(--r-md); padding: .9rem 1.1rem; cursor: pointer; transition: all .15s; display: flex; align-items: center; gap: .9rem; }
    .punto-card:hover { border-color: var(--verde-claro); background: var(--verde-bg); }
    .punto-card.selected { border-color: var(--verde); background: var(--verde-bg); }
    .punto-card input[type=radio] { display: none; }
    .punto-check { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--borde); flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .15s; }
    .punto-card.selected .punto-check { background: var(--verde); border-color: var(--verde); }
    .punto-ico { font-size: 1.2rem; }
    .punto-name { font-weight: 600; font-size: .9rem; }
    .punto-dir { font-size: .78rem; color: var(--gris); margin-top: .1rem; }

    /* Pago */
    .pago-row { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; margin-bottom: 1.5rem; }
    .pago-card { background: var(--blanco); border: 1.5px solid var(--borde); border-radius: var(--r-md); padding: 1rem; text-align: center; cursor: pointer; transition: all .15s; }
    .pago-card:hover { border-color: var(--verde-claro); }
    .pago-card.selected { border-color: var(--verde); background: var(--verde-bg); }
    .pago-card input { display: none; }
    .pago-icon { font-size: 1.8rem; margin-bottom: .4rem; }
    .pago-label { font-weight: 700; font-size: .875rem; }

    /* Resumen tarifa */
    .tarifa-card { background: var(--verde-oscuro); border-radius: var(--r-lg); padding: 1.2rem 1.3rem; color: white; margin-bottom: 1.5rem; }
    .tarifa-row { display: flex; justify-content: space-between; font-size: .85rem; margin-bottom: .4rem; color: rgba(255,255,255,.6); }
    .tarifa-total { display: flex; justify-content: space-between; font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; color: white; padding-top: .7rem; border-top: 1px solid rgba(255,255,255,.12); margin-top: .4rem; }

    .error-box { background: var(--rojo-bg); border-radius: var(--r-sm); padding: .8rem 1rem; font-size: .875rem; color: var(--rojo); margin-bottom: 1rem; border: 1px solid #fecaca; }
</style>
@endpush

@section('content')
<nav class="top-nav">
    <a href="{{ route('dashboard') }}" class="back-btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <span class="top-title">Pedir Viaje</span>
    <div style="width:40px;"></div>
</nav>

<div class="page-body">
    <div class="step-bar">
        <div class="step done"></div>
        <div class="step done"></div>
        <div class="step"></div>
    </div>

    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('viaje.store') }}" id="viaje-form">
        @csrf

        {{-- Punto de salida --}}
        <h2 class="section-title">¿De dónde sales?</h2>
        <p class="section-sub">Elige el punto de recolección más cercano a ti.</p>

        <div class="punto-grid" id="puntos-grid">
            @foreach($puntos as $p)
                <label class="punto-card" id="pc-{{ $p->id }}">
                    <input type="radio" name="punto_id" value="{{ $p->id }}"
                        data-lat="{{ $p->lat }}" data-lng="{{ $p->lng }}"
                        onchange="seleccionarPunto({{ $p->id }}, {{ $p->lat }}, {{ $p->lng }})">
                    <div class="punto-check" id="chk-{{ $p->id }}">
                        <svg width="10" height="10" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="punto-ico">📍</div>
                    <div>
                        <div class="punto-name">{{ $p->nombre }}</div>
                        <div class="punto-dir">{{ $p->direccion }}</div>
                    </div>
                </label>
            @endforeach
        </div>

        <input type="hidden" name="lat_origen" id="lat_origen" value="">
        <input type="hidden" name="lng_origen" id="lng_origen" value="">

        {{-- Destino --}}
        <div class="form-group">
            <label class="form-label">¿A dónde vas?</label>
            <div class="input-wrap">
                <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                <input type="text" name="direccion_destino" class="form-input @error('direccion_destino') error @enderror"
                    placeholder="Calle, colonia o referencia..." value="{{ old('direccion_destino') }}" required>
            </div>
            @error('direccion_destino') <p class="form-error">{{ $message }}</p> @enderror
            <input type="hidden" name="direccion_origen" id="direccion_origen" value="">
            <input type="hidden" name="lat_destino" id="lat_destino" value="20.4950">
            <input type="hidden" name="lng_destino" id="lng_destino" value="-100.3550">
        </div>

        {{-- Pago --}}
        <div class="form-group">
            <label class="form-label">Método de pago</label>
        </div>
        <div class="pago-row">
            <label class="pago-card selected" id="pago-efectivo">
                <input type="radio" name="metodo_pago" value="efectivo" checked onchange="togglePago('efectivo')">
                <div class="pago-icon">💵</div>
                <div class="pago-label">Efectivo</div>
            </label>
            <label class="pago-card" id="pago-billetera">
                <input type="radio" name="metodo_pago" value="billetera" onchange="togglePago('billetera')">
                <div class="pago-icon">👛</div>
                <div class="pago-label">Billetera</div>
            </label>
        </div>

        {{-- Tarifa estimada --}}
        <div class="tarifa-card">
            <div class="tarifa-row"><span>Tarifa base</span><span>$15.00</span></div>
            <div class="tarifa-row"><span>Por km (~3 km)</span><span>$24.00</span></div>
            <div class="tarifa-total"><span>Estimado</span><span>~$39.00</span></div>
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-lg" style="border-radius:var(--r-md);">
            Confirmar Viaje →
        </button>
    </form>
</div>

@push('scripts')
<script>
function seleccionarPunto(id, lat, lng) {
    document.querySelectorAll('.punto-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('pc-' + id).classList.add('selected');
    document.getElementById('lat_origen').value = lat;
    document.getElementById('lng_origen').value = lng;
    document.getElementById('direccion_origen').value =
        document.querySelector('#pc-' + id + ' .punto-name').textContent.trim();
}
function togglePago(tipo) {
    document.getElementById('pago-efectivo').classList.toggle('selected', tipo === 'efectivo');
    document.getElementById('pago-billetera').classList.toggle('selected', tipo === 'billetera');
}
</script>
@endpush
@endsection
