@extends('layouts.app')
@section('title', 'Nuevo Mandado - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .navbar { background: var(--blanco); border-bottom: 1px solid var(--gris-claro); padding: .9rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .navbar-brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; text-decoration: none; color: var(--texto); }
    .page { max-width: 700px; margin: 0 auto; padding: 1.5rem; }
    .page-title { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; margin-bottom: .3rem; }
    .page-sub { color: var(--gris); margin-bottom: 1.5rem; }

    /* Artículos */
    .add-row { display: flex; gap: .6rem; margin-bottom: 1.2rem; }
    .add-row input { flex: 1; padding: .7rem 1rem; border: 1.5px solid var(--gris-claro); border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: .9rem; outline: none; background: white; }
    .add-row input:focus { border-color: var(--verde); }
    .add-row input.qty-input { max-width: 70px; text-align: center; }
    .btn-add { background: var(--verde); color: white; border: none; border-radius: 10px; padding: .7rem 1.1rem; font-weight: 700; cursor: pointer; white-space: nowrap; font-family: 'DM Sans', sans-serif; }

    .items-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .7rem; font-size: .85rem; }
    .items-header strong { font-weight: 700; }
    .btn-clear { background: none; border: none; color: #ef4444; font-size: .82rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; }

    .item-row { display: flex; align-items: center; gap: .8rem; background: white; border-radius: 10px; padding: .7rem 1rem; margin-bottom: .5rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
    .item-nombre { flex: 1; font-weight: 600; font-size: .9rem; }
    .item-qty { display: flex; align-items: center; gap: .4rem; }
    .qty-btn { width: 26px; height: 26px; border-radius: 6px; border: 1.5px solid var(--gris-claro); background: white; cursor: pointer; font-weight: 700; font-size: .85rem; display: flex; align-items: center; justify-content: center; }
    .qty-num { min-width: 22px; text-align: center; font-weight: 600; }
    .btn-del { background: none; border: none; cursor: pointer; color: var(--gris-claro); padding: 0; }
    .btn-del:hover { color: #ef4444; }

    .empty-list { text-align: center; padding: 2rem; color: var(--gris); font-size: .875rem; background: white; border-radius: 10px; border: 2px dashed var(--gris-claro); }

    /* Sección envío */
    .section-card { background: white; border-radius: 16px; box-shadow: var(--sombra); padding: 1.3rem; margin-bottom: 1rem; }
    .section-label { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .5rem; }
    .campo-label { font-size: .82rem; font-weight: 600; color: var(--gris); margin-bottom: .3rem; }

    .error-box { background: #fee2e2; border-radius: 10px; padding: .8rem 1rem; font-size: .85rem; color: #991b1b; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <span style="font-family:'Syne',sans-serif; font-weight:700;">Nuevo Mandado</span>
    <div style="width:20px;"></div>
</nav>

<div class="page">
    <h1 class="page-title">¿Qué necesitas?</h1>
    <p class="page-sub">Agrega los artículos y te lo traemos.</p>

    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    {{-- 1. Lista de artículos (UI dinámica, se serializa antes de enviar) --}}
    <div class="section-card">
        <div class="section-label">
            🛒 Tu lista
        </div>

        <div class="add-row">
            <input type="text" id="inp-nombre" placeholder="Artículo (ej: Tortillas 1kg)" maxlength="100">
            <input type="number" id="inp-qty" class="qty-input" value="1" min="1" max="99">
            <button type="button" class="btn-add" onclick="agregarItem()">+ Agregar</button>
        </div>

        <div class="items-header">
            <strong>Lista (<span id="items-count">0</span>)</strong>
            <button type="button" class="btn-clear" onclick="limpiarLista()">Borrar todo</button>
        </div>

        <div id="items-list">
            <div class="empty-list" id="empty-msg">Aún no has agregado artículos.</div>
        </div>
    </div>

    {{-- 2. Formulario real que se envía --}}
    <form id="mandado-form" method="POST" action="{{ route('mandado.store') }}">
        @csrf

        {{-- Campos ocultos de ítems — se llenan con JS antes del submit --}}
        <div id="hidden-items"></div>

        {{-- Dirección --}}
        <div class="section-card">
            <div class="section-label">📍 ¿A dónde lo llevamos?</div>

            <div class="form-group">
                <div class="campo-label">Punto de origen (donde compramos)</div>
                <select name="direccion_origen" class="form-input no-icon @error('direccion_origen') error @enderror" required>
                    <option value="">Selecciona el punto más cercano...</option>
                    @foreach($puntos as $punto)
                        <option value="{{ $punto->nombre }}"
                            data-lat="{{ $punto->lat }}"
                            data-lng="{{ $punto->lng }}">
                            📍 {{ $punto->nombre }} — {{ $punto->direccion }}
                        </option>
                    @endforeach
                </select>
                @error('direccion_origen') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="campo-label">Dirección de entrega</div>
                <input type="text" name="direccion_destino" class="form-input no-icon @error('direccion_destino') error @enderror"
                    placeholder="Tu calle, colonia, referencia..." value="{{ old('direccion_destino') }}" required>
                @error('direccion_destino') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Coordenadas ocultas --}}
            <input type="hidden" name="lat_origen" id="lat_origen" value="20.5000">
            <input type="hidden" name="lng_origen" id="lng_origen" value="-100.3500">
            <input type="hidden" name="lat_destino" id="lat_destino" value="20.4950">
            <input type="hidden" name="lng_destino" id="lng_destino" value="-100.3550">
        </div>

        {{-- Pago --}}
        <div class="section-card">
            <div class="section-label">💳 Método de pago</div>
            <div style="display:flex; gap:.8rem;">
                <label style="flex:1; cursor:pointer;">
                    <input type="radio" name="metodo_pago" value="efectivo" checked style="display:none;" id="pago-efectivo">
                    <div class="pago-opt" id="opt-efectivo" style="border:2px solid var(--verde); border-radius:12px; padding:.9rem; text-align:center; background:var(--verde-bg);">
                        <div style="font-size:1.3rem;">💵</div>
                        <div style="font-weight:700; font-size:.9rem; margin-top:.3rem;">Efectivo</div>
                    </div>
                </label>
                <label style="flex:1; cursor:pointer;">
                    <input type="radio" name="metodo_pago" value="billetera" style="display:none;" id="pago-billetera">
                    <div class="pago-opt" id="opt-billetera" style="border:2px solid var(--gris-claro); border-radius:12px; padding:.9rem; text-align:center;">
                        <div style="font-size:1.3rem;">👛</div>
                        <div style="font-weight:700; font-size:.9rem; margin-top:.3rem;">Billetera</div>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="border-radius:14px; padding:1.1rem; font-size:1rem; margin-bottom:2rem;"
            onclick="return prepararEnvio()">
            Pedir Mandado →
        </button>
    </form>
</div>

@push('scripts')
<script>
let items = [];

function renderItems() {
    const list   = document.getElementById('items-list');
    const empty  = document.getElementById('empty-msg');
    const counter = document.getElementById('items-count');
    counter.textContent = items.length;

    if (items.length === 0) {
        list.innerHTML = '<div class="empty-list" id="empty-msg">Aún no has agregado artículos.</div>';
        return;
    }

    list.innerHTML = items.map((item, i) => `
        <div class="item-row">
            <div class="item-nombre">${item.nombre}</div>
            <div class="item-qty">
                <button class="qty-btn" type="button" onclick="cambiarQty(${i}, -1)">−</button>
                <span class="qty-num">${item.cantidad}</span>
                <button class="qty-btn" type="button" onclick="cambiarQty(${i}, 1)">+</button>
            </div>
            <button class="btn-del" type="button" onclick="eliminarItem(${i})">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
            </button>
        </div>
    `).join('');
}

function agregarItem() {
    const nombre = document.getElementById('inp-nombre').value.trim();
    const qty    = parseInt(document.getElementById('inp-qty').value) || 1;
    if (!nombre) { document.getElementById('inp-nombre').focus(); return; }
    items.push({ nombre, cantidad: qty, precio_est: 0 });
    document.getElementById('inp-nombre').value = '';
    document.getElementById('inp-qty').value = 1;
    document.getElementById('inp-nombre').focus();
    renderItems();
}

function cambiarQty(i, delta) {
    items[i].cantidad = Math.max(1, items[i].cantidad + delta);
    renderItems();
}

function eliminarItem(i) {
    items.splice(i, 1);
    renderItems();
}

function limpiarLista() {
    items = [];
    renderItems();
}

// Actualizar coordenadas al elegir punto de origen
document.querySelector('select[name="direccion_origen"]').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt.dataset.lat) {
        document.getElementById('lat_origen').value = opt.dataset.lat;
        document.getElementById('lng_origen').value = opt.dataset.lng;
        // Aproximar destino ligeramente diferente
        document.getElementById('lat_destino').value = (parseFloat(opt.dataset.lat) - 0.005).toFixed(6);
        document.getElementById('lng_destino').value = (parseFloat(opt.dataset.lng) - 0.005).toFixed(6);
    }
});

// Enter para agregar
document.getElementById('inp-nombre').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); agregarItem(); }
});

// Pago visual toggle
document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('opt-efectivo').style.border  = '2px solid var(--gris-claro)';
        document.getElementById('opt-efectivo').style.background = 'white';
        document.getElementById('opt-billetera').style.border = '2px solid var(--gris-claro)';
        document.getElementById('opt-billetera').style.background = 'white';
        const sel = document.querySelector('input[name="metodo_pago"]:checked').value;
        document.getElementById('opt-' + sel).style.border = '2px solid var(--verde)';
        document.getElementById('opt-' + sel).style.background = 'var(--verde-bg)';
    });
});

// Antes de enviar: inyectar ítems como campos array
function prepararEnvio() {
    if (items.length === 0) {
        alert('Agrega al menos un artículo a tu lista.');
        return false;
    }

    const container = document.getElementById('hidden-items');
    container.innerHTML = '';
    items.forEach((item, i) => {
        container.innerHTML += `
            <input type="hidden" name="items[${i}][nombre]"    value="${item.nombre}">
            <input type="hidden" name="items[${i}][cantidad]"  value="${item.cantidad}">
            <input type="hidden" name="items[${i}][precio_est]" value="0">
        `;
    });
    return true;
}
</script>
@endpush
@endsection
