@extends('layouts.app')
@section('title', 'Nuevo Viaje - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .top-nav { background: var(--blanco); border-bottom: 1px solid var(--borde); height: 56px; padding: 0 1.2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .back-btn { display: flex; align-items: center; gap: .4rem; color: var(--gris); text-decoration: none; font-size: .875rem; }
    .top-title { font-family: var(--font-display); font-weight: 700; font-size: 1rem; }

    .page-body { max-width: 520px; margin: 0 auto; padding: 1.5rem; }

    .section-title { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; margin-bottom: .3rem; }
    .section-sub { color: var(--gris); font-size: .875rem; margin-bottom: 1rem; }

    /* Mapa */
    #map-viaje { width: 100%; height: 320px; border-radius: 14px; border: 1.5px solid var(--borde); margin-bottom: 1rem; z-index: 1; }
    .map-legend { display: flex; gap: 1.2rem; margin-bottom: 1rem; font-size: .8rem; color: var(--gris); }
    .map-legend span { display: flex; align-items: center; gap: .3rem; }
    .leg-dot { width: 10px; height: 10px; border-radius: 50%; }

    .addr-card { background: var(--blanco); border: 1.5px solid var(--borde); border-radius: var(--r-md); padding: .9rem 1.1rem; margin-bottom: .7rem; display: flex; align-items: center; gap: .8rem; }
    .addr-card.set { border-color: var(--verde-claro); background: var(--verde-bg); }
    .addr-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; }
    .addr-label { font-size: .75rem; color: var(--gris); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
    .addr-text { font-size: .875rem; font-weight: 500; margin-top: .1rem; }

    /* Pago */
    .pago-row { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; margin-bottom: 1.5rem; }
    .pago-card { background: var(--blanco); border: 1.5px solid var(--borde); border-radius: var(--r-md); padding: 1rem; text-align: center; cursor: pointer; transition: all .15s; }
    .pago-card:hover { border-color: var(--verde-claro); }
    .pago-card.selected { border-color: var(--verde); background: var(--verde-bg); }
    .pago-card input { display: none; }
    .pago-icon { font-size: 1.8rem; margin-bottom: .4rem; }
    .pago-label { font-weight: 700; font-size: .875rem; }

    /* Tarifa */
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
    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <h2 class="section-title">¿A dónde vamos?</h2>
    <p class="section-sub">Toca el mapa: primero tu <b>origen</b> (verde), luego tu <b>destino</b> (rojo).</p>

    <div id="map-viaje"></div>
    <div class="map-legend">
        <span><div class="leg-dot" style="background:#2ecc71;"></div> Origen</span>
        <span><div class="leg-dot" style="background:#e74c3c;"></div> Destino</span>
        <span><div class="leg-dot" style="background:#3498db;"></div> Puntos de recolección</span>
    </div>

    {{-- Address cards --}}
    <div class="addr-card" id="card-origen">
        <div class="addr-dot" style="background:#2ecc71;"></div>
        <div>
            <div class="addr-label">Origen</div>
            <div class="addr-text" id="text-origen">Toca el mapa para elegir...</div>
        </div>
    </div>
    <div class="addr-card" id="card-destino">
        <div class="addr-dot" style="background:#e74c3c;"></div>
        <div>
            <div class="addr-label">Destino</div>
            <div class="addr-text" id="text-destino">Toca el mapa para elegir...</div>
        </div>
    </div>

    <form method="POST" action="{{ route('viaje.store') }}" id="viaje-form" style="margin-top:1rem;">
        @csrf
        <input type="hidden" name="direccion_origen" id="direccion_origen" value="">
        <input type="hidden" name="direccion_destino" id="direccion_destino" value="">
        <input type="hidden" name="lat_origen" id="lat_origen" value="">
        <input type="hidden" name="lng_origen" id="lng_origen" value="">
        <input type="hidden" name="lat_destino" id="lat_destino" value="">
        <input type="hidden" name="lng_destino" id="lng_destino" value="">

        {{-- Pago --}}
        <div class="form-group" style="margin-top:1rem;">
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
        <div class="tarifa-card" id="tarifa-card">
            <div class="tarifa-row"><span>Tarifa base</span><span>$15.00</span></div>
            <div class="tarifa-row" id="tarifa-km"><span>Por km</span><span>—</span></div>
            <div class="tarifa-total"><span>Estimado</span><span id="tarifa-total">Selecciona ruta</span></div>
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-lg" style="border-radius:var(--r-md);" onclick="return validarViaje()">
            Confirmar Viaje →
        </button>
    </form>
</div>

@push('scripts')
<script>
@php
    $puntosJson = $puntos->map(function($p) {
        return ['id'=>$p->id,'nombre'=>$p->nombre,'direccion'=>$p->direccion,'lat'=>$p->lat,'lng'=>$p->lng];
    });
@endphp
const puntosData = @json($puntosJson);
const defaultCenter = puntosData.length > 0 ? [puntosData[0].lat, puntosData[0].lng] : [20.5, -100.35];

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

// ── Mapa único ──
const map = L.map('map-viaje').setView(defaultCenter, 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

// Puntos de recolección
puntosData.forEach(p => {
    if (p.lat && p.lng) {
        L.marker([p.lat, p.lng], {icon: blueIcon}).addTo(map).bindPopup(`<b>${p.nombre}</b><br>${p.direccion}`);
    }
});

let markerOrigen = null, markerDestino = null, routeLine = null;
let step = 'origen'; // 'origen' → 'destino'

map.on('click', function(e) {
    const {lat, lng} = e.latlng;

    if (step === 'origen') {
        if (markerOrigen) map.removeLayer(markerOrigen);
        markerOrigen = L.marker([lat, lng], {icon: greenIcon}).addTo(map);
        document.getElementById('lat_origen').value = lat.toFixed(6);
        document.getElementById('lng_origen').value = lng.toFixed(6);
        reverseGeocode(lat, lng, 'text-origen', 'direccion_origen', 'card-origen');
        step = 'destino';
    } else {
        if (markerDestino) map.removeLayer(markerDestino);
        markerDestino = L.marker([lat, lng], {icon: redIcon}).addTo(map);
        document.getElementById('lat_destino').value = lat.toFixed(6);
        document.getElementById('lng_destino').value = lng.toFixed(6);
        reverseGeocode(lat, lng, 'text-destino', 'direccion_destino', 'card-destino');
        step = 'origen';
        drawRoute();
    }
});

function drawRoute() {
    if (routeLine) map.removeLayer(routeLine);
    if (!markerOrigen || !markerDestino) return;
    const a = markerOrigen.getLatLng(), b = markerDestino.getLatLng();
    routeLine = L.polyline([a, b], {color: '#3d7a2a', weight: 3, dashArray: '8,6'}).addTo(map);
    map.fitBounds(routeLine.getBounds(), {padding: [40, 40]});
    calcularTarifa(a.lat, a.lng, b.lat, b.lng);
}

function calcularTarifa(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)**2;
    const km = R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const costoKm = (km * 8).toFixed(2);
    const total = (15 + km * 8).toFixed(2);
    document.getElementById('tarifa-km').innerHTML = `<span>Por km (~${km.toFixed(1)} km)</span><span>$${costoKm}</span>`;
    document.getElementById('tarifa-total').textContent = `~$${total}`;
}

function reverseGeocode(lat, lng, textId, hiddenId, cardId) {
    document.getElementById(textId).textContent = 'Buscando dirección...';
    document.getElementById(cardId).classList.add('set');

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
        headers: { 'Accept-Language': 'es' }
    })
    .then(r => r.json())
    .then(data => {
        const addr = data.display_name || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        const short = addr.split(',').slice(0, 3).join(',');
        document.getElementById(textId).textContent = short;
        document.getElementById(hiddenId).value = short;
    })
    .catch(() => {
        const fallback = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        document.getElementById(textId).textContent = fallback;
        document.getElementById(hiddenId).value = fallback;
    });
}

function togglePago(tipo) {
    document.getElementById('pago-efectivo').classList.toggle('selected', tipo === 'efectivo');
    document.getElementById('pago-billetera').classList.toggle('selected', tipo === 'billetera');
}

function validarViaje() {
    if (!document.getElementById('lat_origen').value) { alert('Selecciona tu punto de origen en el mapa.'); return false; }
    if (!document.getElementById('lat_destino').value) { alert('Selecciona tu destino en el mapa.'); return false; }

    // Check conductor availability (non-blocking warning)
    const lat = document.getElementById('lat_origen').value;
    const lng = document.getElementById('lng_origen').value;
    const btn = document.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Verificando disponibilidad...';

    fetch(`/web-api/conductores-disponibles?lat=${lat}&lng=${lng}`)
        .then(r => r.json())
        .then(data => {
            if (data.disponibles === 0) {
                if (!confirm('No hay conductores conectados en tu zona en este momento. ¿Deseas continuar de todos modos? Tu solicitud quedará en espera.')) {
                    btn.disabled = false;
                    btn.textContent = 'Confirmar Viaje →';
                    return;
                }
            }
            btn.closest('form').submit();
        })
        .catch(() => {
            btn.closest('form').submit();
        });

    return false; // Prevent default, we submit manually after check
}
</script>
@endpush
@endsection
