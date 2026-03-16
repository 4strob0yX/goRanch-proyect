@extends('layouts.app')
@section('title', 'Puntos de Recolección - Admin goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .layout { display: flex; min-height: 100vh; }

    .sidebar { background: var(--verde-oscuro); position: sticky; top: 0; height: 100vh; width: 240px; min-width: 240px; display: flex; flex-direction: column; z-index: 50; overflow-y: auto; }
    .sb-brand { padding: 1.3rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .6rem; font-family: var(--font-display); font-weight: 700; color: white; font-size: 1.05rem; }
    .sb-brand-dot { width: 28px; height: 28px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .sb-label { padding: .8rem 1rem .3rem; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); }
    .sb-item { display: flex; align-items: center; gap: .7rem; padding: .6rem 1rem; border-radius: var(--r-sm); text-decoration: none; color: rgba(255,255,255,.5); font-size: .85rem; font-weight: 500; margin: .1rem .5rem; transition: all .15s; }
    .sb-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.85); }
    .sb-item.active { background: rgba(255,255,255,.1); color: white; }

    .main { flex: 1; min-width: 0; }
    .main-header { background: var(--blanco); border-bottom: 1px solid var(--borde); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 40; }
    .main-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; }
    .main-sub { font-size: .82rem; color: var(--gris); margin-top: .1rem; }
    .main-body { padding: 1.8rem 2rem; max-width: 900px; }

    /* Modal overlay */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 200; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal { background: var(--blanco); border-radius: var(--r-xl); padding: 2rem; width: 100%; max-width: 480px; box-shadow: var(--sombra-lg); position: relative; }
    .modal-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; margin-bottom: .3rem; }
    .modal-sub { font-size: .875rem; color: var(--gris); margin-bottom: 1.5rem; }
    .modal-close { position: absolute; top: 1.2rem; right: 1.2rem; background: var(--fondo); border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gris); font-size: 1rem; }

    /* Tabla */
    .table-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; margin-top: 1.5rem; }
    .tc-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--borde); display: flex; justify-content: space-between; align-items: center; }
    .tc-title { font-family: var(--font-display); font-weight: 700; font-size: .95rem; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: .7rem 1.2rem; text-align: left; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); background: var(--fondo); border-bottom: 1px solid var(--borde); }
    tbody td { padding: .85rem 1.2rem; font-size: .875rem; border-bottom: 1px solid var(--gris-claro); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: var(--fondo); }

    .punto-ico { width: 36px; height: 36px; border-radius: var(--r-sm); background: var(--verde-bg); display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .empty-state { text-align: center; padding: 3rem; color: var(--gris); }

    @media (max-width: 900px) {
        .sidebar { display: none; }
        .main { width: 100%; }
        .main-body { padding: 1rem; }
    }

    /* ── Hamburger móvil ── */
    .mobile-header { display: none; background: var(--verde-oscuro); padding: .9rem 1.2rem; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
    .mobile-brand { font-family: var(--font-display); font-weight: 700; color: white; font-size: 1rem; }
    .hamburger { background: none; border: none; cursor: pointer; padding: .3rem; display: flex; flex-direction: column; gap: 5px; }
    .hamburger span { display: block; width: 22px; height: 2px; background: white; border-radius: 2px; transition: all .2s; }
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 90; }
    .sidebar-overlay.open { display: block; }

    @media (max-width: 768px) {
        .mobile-header { display: flex; }
        .sidebar { display: none; position: fixed; top: 0; left: 0; height: 100vh; z-index: 95; transform: translateX(-100%); transition: transform .25s; }
        .sidebar.open { display: flex; transform: translateX(0); }
        .main { width: 100%; }
        .main-header { top: 0; }
    }

</style>
@endpush

@section('content')
<div class="layout">
    <aside class="sidebar">
        <div class="sb-brand">
            <div class="sb-brand-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg></div>
            goRanch Admin
        </div>
        <div class="sb-label">Gestión</div>
        <a href="{{ route('admin.dashboard') }}"   class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
        <a href="{{ route('admin.conductores') }}"  class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>Conductores</a>
        <a href="{{ route('admin.usuarios') }}"    class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>Usuarios</a>
        <a href="{{ route('admin.servicios') }}"   class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Servicios</a>
        <div class="sb-label">Configuración</div>
        <a href="{{ route('admin.puntos') }}"      class="sb-item active"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Puntos</a>
        <a href="{{ route('admin.admins') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Admins</a>
        <div style="margin-top:auto; padding:1rem; border-top:1px solid rgba(255,255,255,.08);">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="sb-item" style="width:100%; background:none; border:none; cursor:pointer; color:rgba(255,255,255,.35);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="main-header">
            <div>
                <div class="main-title">Puntos de Recolección</div>
                <div class="main-sub">Lugares donde los conductores esperan servicios</div>
            </div>
            <button class="btn btn-primary" onclick="abrirModal()" style="border-radius:var(--r-sm);">
                + Nuevo Punto
            </button>
        </div>

        <div class="main-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="table-card">
                <div class="tc-header">
                    <span class="tc-title">{{ $puntos->count() }} puntos registrados</span>
                </div>

                @if($puntos->isEmpty())
                    <div class="empty-state">
                        <p style="font-size:2rem; margin-bottom:.5rem;">📍</p>
                        No hay puntos registrados aún.<br>
                        <button onclick="abrirModal()" style="margin-top:1rem;" class="btn btn-primary btn-sm" style="border-radius:var(--r-sm);">Crear el primero</button>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Punto</th>
                                <th>Dirección</th>
                                <th>Conductores ahora</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($puntos as $p)
                                <tr>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:.8rem;">
                                            <div class="punto-ico">📍</div>
                                            <div>
                                                <div style="font-weight:700; font-size:.9rem;">{{ $p->nombre }}</div>
                                                <div style="font-size:.72rem; color:var(--gris);">ID #{{ $p->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="color:var(--texto-2); font-size:.85rem;">{{ $p->direccion }}</td>
                                    <td>
                                        @php $cnt = \App\Models\Conductor::where('punto_recoleccion_id', $p->id)->where('esta_conectado', true)->count(); @endphp
                                        <span class="badge {{ $cnt > 0 ? 'badge-green' : 'badge-gray' }}">{{ $cnt }} conductor{{ $cnt !== 1 ? 'es':'' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $p->activo ? 'badge-green' : 'badge-red' }}">
                                            {{ $p->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:.4rem;">
                                            <button onclick="abrirEditar({{ $p->id }}, '{{ addslashes($p->nombre) }}', '{{ addslashes($p->direccion) }}', {{ $p->activo ? 'true' : 'false' }})"
                                                class="btn btn-outline btn-sm" style="border-radius:var(--r-sm);">Editar</button>
                                            <form method="POST" action="{{ route('admin.puntos.toggle', $p->id) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $p->activo ? 'btn-danger' : '' }}" style="border-radius:var(--r-sm); {{ !$p->activo ? 'background:var(--verde-bg);color:var(--verde-oscuro);' : '' }}">
                                                    {{ $p->activo ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal crear/editar --}}
<div class="modal-overlay" id="modal">
    <div class="modal">
        <button class="modal-close" onclick="cerrarModal()">✕</button>
        <div class="modal-title" id="modal-title">Nuevo Punto</div>
        <div class="modal-sub" id="modal-sub">Agrega un nuevo punto de recolección.</div>

        <form method="POST" id="modal-form" action="{{ route('admin.puntos.store') }}">
            @csrf
            <span id="method-field"></span>

            <div class="form-group">
                <label class="form-label">Nombre del punto</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                    <input type="text" name="nombre" id="input-nombre" class="form-input" placeholder="Ej: Plaza Central, Mercado Municipal" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Dirección / Referencia</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg></span>
                    <input type="text" name="direccion" id="input-direccion" class="form-input" placeholder="Calle, colonia, referencia visible" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Latitud</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/></svg></span>
                    <input type="number" name="lat" id="input-lat" class="form-input" placeholder="Ej: 20.5000" step="0.000001" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Longitud</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="2" x2="12" y2="22"/></svg></span>
                    <input type="number" name="lng" id="input-lng" class="form-input" placeholder="Ej: -100.3500" step="0.000001" required>
                </div>
            </div>

            <p style="font-size:.75rem; color:var(--gris); margin-bottom:1rem;">
                💡 Puedes obtener las coordenadas en <a href="https://maps.google.com" target="_blank" style="color:var(--verde);">Google Maps</a> → clic derecho sobre el punto → copiar coordenadas.
            </p>

            <button type="submit" class="btn btn-primary btn-full" style="border-radius:var(--r-sm);" id="modal-btn">Crear Punto</button>
        </form>
    </div>
</div>

@push('scripts')

<script>
function abrirMenu() {
    document.querySelector('.sidebar').classList.add('open');
    document.getElementById('overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function cerrarMenu() {
    document.querySelector('.sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
    document.body.style.overflow = '';
}
// Cerrar al hacer clic en cualquier link del sidebar en móvil
document.querySelectorAll('.sb-item').forEach(el => {
    el.addEventListener('click', () => {
        if (window.innerWidth <= 768) cerrarMenu();
    });
});
</script>

<script>
function abrirModal() {
    document.getElementById('modal-title').textContent = 'Nuevo Punto';
    document.getElementById('modal-sub').textContent = 'Agrega un nuevo punto de recolección.';
    document.getElementById('modal-form').action = '{{ route("admin.puntos.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('modal-btn').textContent = 'Crear Punto';
    document.getElementById('input-nombre').value = '';
    document.getElementById('input-direccion').value = '';
    document.getElementById('input-lat').value = '';
    document.getElementById('input-lng').value = '';
    document.getElementById('modal').classList.add('open');
}

function abrirEditar(id, nombre, direccion, activo) {
    document.getElementById('modal-title').textContent = 'Editar Punto';
    document.getElementById('modal-sub').textContent = 'Modifica los datos del punto.';
    document.getElementById('modal-form').action = '/admin/puntos/' + id;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
    document.getElementById('modal-btn').textContent = 'Guardar Cambios';
    document.getElementById('input-nombre').value = nombre;
    document.getElementById('input-direccion').value = direccion;
    document.getElementById('modal').classList.add('open');
}

function cerrarModal() {
    document.getElementById('modal').classList.remove('open');
}

document.getElementById('modal').addEventListener('click', function(e) {
    if(e.target === this) cerrarModal();
});
</script>
@endpush
@endsection