@extends('layouts.app')
@section('title', 'Nuevo Mandado - goRanch')

@push('styles')
<style>
    .page { max-width: 1100px; padding: 2rem; }
    .mandado-grid { display: grid; grid-template-columns: 1fr 380px; gap: 2rem; margin-top: 1.5rem; }
    .page-title { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; }
    .page-sub { color: var(--gris); margin-top: .3rem; }

    /* Lista izquierda */
    .add-item-row { display: flex; gap: .8rem; margin-bottom: 1.5rem; }
    .add-item-row .form-input { flex: 1; border-radius: 12px; padding: .8rem 1rem; border: 1.5px solid var(--gris-claro); font-family: 'DM Sans',sans-serif; font-size: .95rem; outline: none; }
    .add-item-row .form-input:focus { border-color: var(--verde); }

    .list-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .8rem; }
    .list-count { font-weight: 700; font-size: .875rem; text-transform: uppercase; letter-spacing: .05em; }
    .borrar-todo { color: var(--rojo); font-size: .875rem; font-weight: 600; background: none; border: none; cursor: pointer; }

    .item-card { display: flex; align-items: center; gap: 1rem; background: var(--blanco); border-radius: 12px; padding: .9rem 1rem; margin-bottom: .6rem; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
    .item-check { width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--gris-claro); display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
    .item-check.checked { background: var(--verde); border-color: var(--verde); }
    .item-info { flex: 1; }
    .item-info strong { font-weight: 600; display: block; font-size: .95rem; }
    .item-info span { font-size: .8rem; color: var(--gris); }
    .qty-controls { display: flex; align-items: center; gap: .5rem; }
    .qty-btn { width: 28px; height: 28px; border-radius: 8px; border: 1.5px solid var(--gris-claro); background: var(--blanco); cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem; }
    .qty-num { font-weight: 600; min-width: 20px; text-align: center; }
    .delete-btn { background: none; border: none; cursor: pointer; color: var(--gris-claro); }
    .delete-btn:hover { color: var(--rojo); }

    /* Panel derecho */
    .detalles-panel { background: var(--blanco); border-radius: 20px; box-shadow: var(--sombra); padding: 1.5rem; height: fit-content; }
    .detalles-title { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; margin-bottom: 1.2rem; }
    .total-row { display: flex; justify-content: space-between; font-size: .9rem; color: var(--gris); margin-bottom: 1.2rem; padding-top: .8rem; border-top: 1px solid var(--gris-claro); }
    .total-num { font-family: 'Syne', sans-serif; font-weight: 700; color: var(--texto); font-size: 1rem; }

    .tip-card { background: var(--verde-bg); border-radius: 12px; padding: .9rem 1rem; margin-top: 1rem; display: flex; gap: .7rem; }
    .tip-icon { width: 24px; height: 24px; border-radius: 50%; background: var(--verde); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: .1rem; }
    .tip-text strong { font-size: .875rem; font-weight: 600; color: var(--verde-oscuro); }
    .tip-text p { font-size: .8rem; color: var(--gris); margin-top: .2rem; line-height: 1.4; }

    @media (max-width: 768px) {
        .mandado-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <div class="navbar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link">Inicio</a>
        <a href="#" class="nav-link">Mis Viajes</a>
        <a href="{{ route('perfil') }}" class="nav-link">Perfil</a>
        <a href="#" class="nav-link">Ayuda</a>
        <div class="avatar-placeholder">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
    </div>
</nav>

<div class="page" style="margin: 0 auto;">
    <h1 class="page-title">Crea tu lista de mandados</h1>
    <p class="page-sub">Agrega los artículos que necesitas que compremos o recojamos por ti.</p>

    <div class="mandado-grid">
        {{-- Lista de artículos --}}
        <div>
            <form id="mandado-form" method="POST" action="{{ route('mandado.store') }}">
                @csrf

                <div class="add-item-row">
                    <input type="text" id="nuevo-articulo" class="form-input" placeholder="Ej: 2 costales de maíz, medicina...">
                    <input type="text" id="nuevo-detalle" class="form-input" placeholder="Detalle (opcional)" style="max-width:160px;">
                    <button type="button" class="btn btn-primary" style="border-radius:12px; white-space:nowrap;" onclick="agregarArticulo()">
                        + Agregar
                    </button>
                </div>

                <div class="list-header">
                    <span class="list-count">Tu Lista (<span id="count">0</span>)</span>
                    <button type="button" class="borrar-todo" onclick="borrarTodo()">Borrar todo</button>
                </div>

                <div id="items-list">
                    {{-- Items dinámicos --}}
                </div>

                <input type="hidden" name="items_json" id="items-json">
            </form>
        </div>

        {{-- Panel detalles --}}
        <div>
            <div class="detalles-panel">
                <div class="detalles-title">
                    <div style="width:22px; height:22px; border-radius:50%; background:var(--verde); display:flex; align-items:center; justify-content:center;">
                        <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                    </div>
                    Detalles del Mandado
                </div>

                <div class="form-group">
                    <label class="form-label">¿Dónde lo compramos? <span style="color:var(--rojo);">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </span>
                        <input type="text" name="tienda" form="mandado-form" class="form-input" placeholder="Ej: Ferretería El Rancho, Tienda de Don Pedro">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Instrucciones especiales</label>
                    <textarea name="instrucciones" form="mandado-form" rows="4"
                        style="width:100%; border:1.5px solid var(--gris-claro); border-radius:10px; padding:.8rem; font-family:'DM Sans',sans-serif; font-size:.875rem; outline:none; resize:none;"
                        placeholder="Ej: Si no hay de la marca X, compra de la marca Y. Preguntar por el descuento de mayoreo."></textarea>
                    <p style="font-size:.75rem; color:var(--gris); margin-top:.3rem;">El conductor verá estas notas al aceptar el viaje.</p>
                </div>

                <div class="total-row">
                    <span>Total de artículos</span>
                    <span class="total-num" id="total-count">0</span>
                </div>

                <button type="submit" form="mandado-form" class="btn btn-dark btn-full" style="border-radius:12px; padding:1rem; font-size:1rem;" onclick="prepararEnvio()">
                    Pedir Mandado →
                </button>

                <div class="tip-card">
                    <div class="tip-icon">
                        <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <div class="tip-text">
                        <strong>Tip Rápido</strong>
                        <p>Sé específico con las marcas y tamaños para asegurar que traigamos exactamente lo que necesitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let items = [];

function renderItems() {
    const list = document.getElementById('items-list');
    const count = document.getElementById('count');
    const total = document.getElementById('total-count');

    count.textContent = items.length;
    total.textContent = items.reduce((s, i) => s + i.qty, 0);

    list.innerHTML = items.map((item, idx) => `
        <div class="item-card">
            <div class="item-check checked" onclick="toggleCheck(${idx})">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="item-info">
                <strong>${item.nombre}</strong>
                ${item.detalle ? `<span>${item.detalle}</span>` : ''}
            </div>
            <div class="qty-controls">
                <button class="qty-btn" type="button" onclick="changeQty(${idx}, -1)">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn" type="button" onclick="changeQty(${idx}, 1)">+</button>
            </div>
            <button class="delete-btn" type="button" onclick="deleteItem(${idx})">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </button>
        </div>
    `).join('');
}

function agregarArticulo() {
    const nombre = document.getElementById('nuevo-articulo').value.trim();
    const detalle = document.getElementById('nuevo-detalle').value.trim();
    if (!nombre) return;
    items.push({ nombre, detalle, qty: 1 });
    document.getElementById('nuevo-articulo').value = '';
    document.getElementById('nuevo-detalle').value = '';
    renderItems();
}

function changeQty(idx, delta) {
    items[idx].qty = Math.max(1, items[idx].qty + delta);
    renderItems();
}

function deleteItem(idx) {
    items.splice(idx, 1);
    renderItems();
}

function borrarTodo() {
    items = [];
    renderItems();
}

function prepararEnvio() {
    document.getElementById('items-json').value = JSON.stringify(items);
}

// Enter para agregar
document.getElementById('nuevo-articulo').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); agregarArticulo(); }
});
</script>
@endpush
@endsection
