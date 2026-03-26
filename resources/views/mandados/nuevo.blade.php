@extends('layouts.app')
@section('title', 'Nuevo Mandado - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .navbar { background: var(--blanco); border-bottom: 1px solid var(--gris-claro); padding: .9rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .navbar-brand { display: flex; align-items: center; gap: .5rem; font-family: var(--font-display); font-weight: 700; text-decoration: none; color: var(--texto); }
    .page { max-width: 700px; margin: 0 auto; padding: 1.5rem; }
    .page-title { font-family: var(--font-display); font-size: 1.8rem; font-weight: 800; margin-bottom: .3rem; }
    .page-sub { color: var(--gris); margin-bottom: 1.5rem; }

    /* Artículos */
    .add-row { display: flex; gap: .6rem; margin-bottom: 1.2rem; }
    .add-row input { flex: 1; padding: .7rem 1rem; border: 1.5px solid var(--gris-claro); border-radius: 10px; font-family: var(--font-body); font-size: .9rem; outline: none; background: white; }
    .add-row input:focus { border-color: var(--verde); }
    .add-row input.qty-input { max-width: 70px; text-align: center; }
    .btn-add { background: var(--verde); color: white; border: none; border-radius: 10px; padding: .7rem 1.1rem; font-weight: 700; cursor: pointer; white-space: nowrap; font-family: var(--font-body); }

    .items-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .7rem; font-size: .85rem; }
    .items-header strong { font-weight: 700; }
    .btn-clear { background: none; border: none; color: #ef4444; font-size: .82rem; font-weight: 600; cursor: pointer; font-family: var(--font-body); }

    .item-row { display: flex; align-items: center; gap: .8rem; background: white; border-radius: 10px; padding: .7rem 1rem; margin-bottom: .5rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
    .item-nombre { flex: 1; font-weight: 600; font-size: .9rem; }
    .item-qty { display: flex; align-items: center; gap: .4rem; }
    .qty-btn { width: 26px; height: 26px; border-radius: 6px; border: 1.5px solid var(--gris-claro); background: white; cursor: pointer; font-weight: 700; font-size: .85rem; display: flex; align-items: center; justify-content: center; }
    .qty-num { min-width: 22px; text-align: center; font-weight: 600; }
    .btn-del { background: none; border: none; cursor: pointer; color: var(--gris-claro); padding: 0; }
    .btn-del:hover { color: #ef4444; }

    .empty-list { text-align: center; padding: 2rem; color: var(--gris); font-size: .875rem; background: white; border-radius: 10px; border: 2px dashed var(--gris-claro); }

    /* Secciones */
    .section-card { background: white; border-radius: 16px; box-shadow: var(--sombra); padding: 1.3rem; margin-bottom: 1rem; }
    .section-label { font-family: var(--font-display); font-weight: 700; font-size: 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .5rem; }
    .campo-label { font-size: .82rem; font-weight: 600; color: var(--gris); margin-bottom: .3rem; }

    .error-box { background: #fee2e2; border-radius: 10px; padding: .8rem 1rem; font-size: .85rem; color: #991b1b; margin-bottom: 1rem; }

    /* Mapa */
    #map-origen, #map-destino { width: 100%; height: 250px; border-radius: 12px; border: 1.5px solid var(--gris-claro); margin-bottom: .8rem; z-index: 1; }
    .map-hint { font-size: .78rem; color: var(--gris); margin-bottom: .8rem; display: flex; align-items: center; gap: .3rem; }
    .addr-display { background: var(--verde-bg); border: 1px solid var(--verde-claro); border-radius: 8px; padding: .6rem .9rem; font-size: .85rem; color: var(--verde-oscuro); font-weight: 500; margin-bottom: .5rem; min-height: 36px; display: flex; align-items: center; gap: .4rem; }
    .addr-display.empty { background: var(--fondo); border-color: var(--gris-claro); color: var(--gris); }
</style>
@endpush

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <span style="font-family:var(--font-display); font-weight:700;">Nuevo Mandado</span>
    <div style="width:20px;"></div>
</nav>

<div class="page">
    <h1 class="page-title">¿Qué necesitas?</h1>
    <p class="page-sub">Agrega los artículos y te lo traemos.</p>

    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    {{-- 1. Lista de artículos --}}
    <div class="section-card">
        <div class="section-label">🛒 Tu lista</div>

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

    {{-- 2. Formulario --}}
    <form id="mandado-form" method="POST" action="{{ route('mandado.store') }}">
        @csrf
        <div id="hidden-items"></div>

        {{-- Mapa origen --}}
        <div class="section-card">
            <div class="section-label">📍 ¿Dónde compramos?</div>
            <div class="map-hint">Toca el mapa para elegir el punto de compra</div>
            <div id="map-origen"></div>
            <div class="addr-display empty" id="addr-origen">Selecciona un punto en el mapa...</div>
            <input type="hidden" name="direccion_origen" id="direccion_origen" value="{{ old('direccion_origen') }}">
            <input type="hidden" name="lat_origen" id="lat_origen" value="{{ old('lat_origen') }}">
            <input type="hidden" name="lng_origen" id="lng_origen" value="{{ old('lng_origen') }}">
        </div>

        {{-- Mapa destino --}}
        <div class="section-card">
            <div class="section-label">🏠 ¿A dónde lo llevamos?</div>
            <div class="map-hint">Toca el mapa para elegir tu ubicación de entrega</div>
            <div id="map-destino"></div>
            <div class="addr-display empty" id="addr-destino">Selecciona un punto en el mapa...</div>
            <input type="hidden" name="direccion_destino" id="direccion_destino" value="{{ old('direccion_destino') }}">
            <input type="hidden" name="lat_destino" id="lat_destino" value="{{ old('lat_destino') }}">
            <input type="hidden" name="lng_destino" id="lng_destino" value="{{ old('lng_destino') }}">
        </div>

        {{-- Notas --}}
        <div class="section-card">
            <div class="section-label">📝 Notas (opcional)</div>
            <textarea name="notas" class="form-input no-icon" rows="2" placeholder="Instrucciones especiales..." style="resize:vertical; border-radius:10px;">{{ old('notas') }}</textarea>
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
// ── Datos de puntos de recolección ──
@php
    $puntosJson = $puntos->map(function($p) {
        return ['id'=>$p->id,'nombre'=>$p->nombre,'direccion'=>$p->direccion,'lat'=>$p->lat,'lng'=>$p->lng];
    });
@endphp
const puntosData = @json($puntosJson);

// ── Centro por defecto (primer punto o fallback) ──
const defaultCenter = puntosData.length > 0
    ? [puntosData[0].lat, puntosData[0].lng]
    : [20.5, -100.35];

// ── Iconos custom ──
const greenIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
});
const redIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
});
const blueIcon = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
});

// ── Mapa Origen ──
const mapOrigen = L.map('map-origen').setView(defaultCenter, 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(mapOrigen);

// Mostrar puntos de recolección como marcadores azules
puntosData.forEach(p => {
    if (p.lat && p.lng) {
        L.marker([p.lat, p.lng], {icon: blueIcon})
         .addTo(mapOrigen)
         .bindPopup(`<b>${p.nombre}</b><br>${p.direccion}`);
    }
});

let markerOrigen = null;
mapOrigen.on('click', function(e) {
    const {lat, lng} = e.latlng;
    if (markerOrigen) mapOrigen.removeLayer(markerOrigen);
    markerOrigen = L.marker([lat, lng], {icon: greenIcon}).addTo(mapOrigen);
    document.getElementById('lat_origen').value = lat.toFixed(6);
    document.getElementById('lng_origen').value = lng.toFixed(6);
    reverseGeocode(lat, lng, 'addr-origen', 'direccion_origen');
});

// ── Mapa Destino ──
const mapDestino = L.map('map-destino').setView(defaultCenter, 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(mapDestino);

let markerDestino = null;
mapDestino.on('click', function(e) {
    const {lat, lng} = e.latlng;
    if (markerDestino) mapDestino.removeLayer(markerDestino);
    markerDestino = L.marker([lat, lng], {icon: redIcon}).addTo(mapDestino);
    document.getElementById('lat_destino').value = lat.toFixed(6);
    document.getElementById('lng_destino').value = lng.toFixed(6);
    reverseGeocode(lat, lng, 'addr-destino', 'direccion_destino');
});

// ── Reverse geocoding con Nominatim ──
function reverseGeocode(lat, lng, displayId, hiddenId) {
    const el = document.getElementById(displayId);
    el.textContent = 'Buscando dirección...';
    el.classList.remove('empty');

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
        headers: { 'Accept-Language': 'es' }
    })
    .then(r => r.json())
    .then(data => {
        const addr = data.display_name || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        const short = addr.split(',').slice(0, 3).join(',');
        el.textContent = '📍 ' + short;
        document.getElementById(hiddenId).value = short;
    })
    .catch(() => {
        const fallback = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        el.textContent = '📍 ' + fallback;
        document.getElementById(hiddenId).value = fallback;
    });
}

// ── Items ──
let items = [];

function renderItems() {
    const list = document.getElementById('items-list');
    const counter = document.getElementById('items-count');
    counter.textContent = items.length;

    if (items.length === 0) {
        list.innerHTML = '<div class="empty-list">Aún no has agregado artículos.</div>';
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
    const qty = parseInt(document.getElementById('inp-qty').value) || 1;
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
function eliminarItem(i) { items.splice(i, 1); renderItems(); }
function limpiarLista() { items = []; renderItems(); }

document.getElementById('inp-nombre').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); agregarItem(); }
});

// ── Pago toggle ──
document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('opt-efectivo').style.border = '2px solid var(--gris-claro)';
        document.getElementById('opt-efectivo').style.background = 'white';
        document.getElementById('opt-billetera').style.border = '2px solid var(--gris-claro)';
        document.getElementById('opt-billetera').style.background = 'white';
        const sel = document.querySelector('input[name="metodo_pago"]:checked').value;
        document.getElementById('opt-' + sel).style.border = '2px solid var(--verde)';
        document.getElementById('opt-' + sel).style.background = 'var(--verde-bg)';
    });
});

// ── Submit ──
function prepararEnvio() {
    if (items.length === 0) { alert('Agrega al menos un artículo a tu lista.'); return false; }
    if (!document.getElementById('lat_origen').value) { alert('Selecciona el punto de compra en el mapa.'); return false; }
    if (!document.getElementById('lat_destino').value) { alert('Selecciona el punto de entrega en el mapa.'); return false; }

    const container = document.getElementById('hidden-items');
    container.innerHTML = '';
    items.forEach((item, i) => {
        container.innerHTML += `
            <input type="hidden" name="items[${i}][nombre]"    value="${item.nombre}">
            <input type="hidden" name="items[${i}][cantidad]"  value="${item.cantidad}">
            <input type="hidden" name="items[${i}][precio_est]" value="0">
        `;
    });

    // Check conductor availability
    const lat = document.getElementById('lat_origen').value;
    const lng = document.getElementById('lng_origen').value;
    const btn = document.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Verificando disponibilidad...';

    fetch(`/web-api/conductores-disponibles?lat=${lat}&lng=${lng}`)
        .then(r => r.json())
        .then(data => {
            if (data.disponibles === 0) {
                if (!confirm('No hay conductores conectados en tu zona en este momento. ¿Deseas continuar? Tu solicitud quedará en espera.')) {
                    btn.disabled = false;
                    btn.textContent = 'Pedir Mandado →';
                    return;
                }
            }
            btn.closest('form').submit();
        })
        .catch(() => {
            btn.closest('form').submit();
        });

    return false;
}
</script>
@endpush
@endsection
