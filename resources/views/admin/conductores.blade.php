@extends('layouts.app')
@section('title', 'Conductores - Admin goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .layout { display: flex; min-height: 100vh; }

    /* Sidebar */
    .sidebar { background: var(--verde-oscuro); position: sticky; top: 0; height: 100vh; width: 240px; min-width: 240px; display: flex; flex-direction: column; z-index: 50; overflow-y: auto; }
    .sb-brand { padding: 1.3rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: .6rem; font-family: var(--font-display); font-weight: 700; color: white; font-size: 1.05rem; }
    .sb-brand-dot { width: 28px; height: 28px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .sb-label { padding: .8rem 1rem .3rem; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: rgba(255,255,255,.3); }
    .sb-item { display: flex; align-items: center; gap: .7rem; padding: .6rem 1rem; border-radius: var(--r-sm); text-decoration: none; color: rgba(255,255,255,.5); font-size: .85rem; font-weight: 500; margin: .1rem .5rem; transition: all .15s; }
    .sb-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.85); }
    .sb-item.active { background: rgba(255,255,255,.1); color: white; }

    /* Main */
    .main { flex: 1; min-width: 0; }
    .main-header { background: var(--blanco); border-bottom: 1px solid var(--borde); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 40; }
    .main-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; }
    .main-sub { font-size: .82rem; color: var(--gris); margin-top: .1rem; }
    .main-body { padding: 1.8rem 2rem; }

    /* Filtros */
    .filters { display: flex; gap: .4rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .chip { padding: .4rem 1rem; border-radius: var(--r-full); font-size: .82rem; font-weight: 600; border: 1.5px solid var(--borde); background: var(--blanco); color: var(--gris); text-decoration: none; transition: all .15s; white-space: nowrap; }
    .chip:hover { border-color: var(--verde-claro); color: var(--verde-oscuro); }
    .chip.active { background: var(--verde-oscuro); color: white; border-color: var(--verde-oscuro); }
    .chip-yellow.active { background: var(--amarillo); border-color: var(--amarillo); color: white; }
    .chip-red.active { background: var(--rojo); border-color: var(--rojo); color: white; }

    /* Stats strip */
    .stats-strip { display: grid; grid-template-columns: repeat(4, 1fr); gap: .8rem; margin-bottom: 1.5rem; }
    .stat-mini { background: var(--blanco); border-radius: var(--r-md); border: 1px solid var(--borde); padding: .9rem 1rem; }
    .stat-mini-val { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; line-height: 1; }
    .stat-mini-lbl { font-size: .72rem; color: var(--gris); margin-top: .3rem; }

    /* Grid conductores */
    .conductores-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }

    .c-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; transition: box-shadow .15s; }
    .c-card:hover { box-shadow: var(--sombra); }

    .c-card-head { padding: 1.1rem; display: flex; align-items: center; gap: .9rem; border-bottom: 1px solid var(--borde); }
    .c-avatar { width: 44px; height: 44px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: 1rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .c-name { font-weight: 700; font-size: .9rem; }
    .c-email { font-size: .75rem; color: var(--gris); margin-top: .1rem; }

    .c-card-body { padding: 1rem 1.1rem; }
    .c-row { display: flex; justify-content: space-between; font-size: .82rem; padding: .3rem 0; border-bottom: 1px solid var(--gris-claro); }
    .c-row:last-child { border-bottom: none; }
    .c-row-lbl { color: var(--gris); }
    .c-row-val { font-weight: 600; }

    .c-docs { padding: .8rem 1.1rem; border-top: 1px solid var(--borde); background: var(--fondo); }
    .c-docs-title { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--gris); margin-bottom: .5rem; }
    .doc-badge { display: inline-flex; align-items: center; gap: .3rem; font-size: .72rem; padding: .2rem .6rem; border-radius: var(--r-full); margin-right: .3rem; margin-bottom: .3rem; font-weight: 600; }

    .c-card-foot { padding: .85rem 1.1rem; display: flex; gap: .5rem; border-top: 1px solid var(--borde); }

    .doc-link { display: flex; align-items: center; gap: .5rem; font-size: .78rem; padding: .4rem .7rem; border-radius: var(--r-sm); margin-bottom: .4rem; text-decoration: none; border: 1px solid var(--borde); background: var(--blanco); color: var(--texto); transition: all .15s; cursor: pointer; }
    .doc-link:hover { border-color: var(--verde-claro); background: var(--verde-bg); }
    .doc-link-ico { font-size: .9rem; flex-shrink: 0; }
    .doc-link-name { font-weight: 600; flex: 1; }
    .doc-link-status { font-size: .68rem; font-weight: 700; padding: .1rem .45rem; border-radius: var(--r-full); }

    /* Modal preview */
    .doc-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.7); z-index: 200; align-items: center; justify-content: center; padding: 1rem; }
    .doc-modal-overlay.open { display: flex; }
    .doc-modal { background: white; border-radius: var(--r-lg); max-width: 700px; width: 100%; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; }
    .doc-modal-header { padding: 1rem 1.2rem; border-bottom: 1px solid var(--borde); display: flex; justify-content: space-between; align-items: center; }
    .doc-modal-title { font-weight: 700; font-size: .95rem; }
    .doc-modal-close { background: none; border: none; cursor: pointer; font-size: 1.3rem; color: var(--gris); padding: .2rem; }
    .doc-modal-close:hover { color: var(--texto); }
    .doc-modal-body { padding: 1rem; overflow: auto; flex: 1; text-align: center; }
    .doc-modal-body img { max-width: 100%; max-height: 70vh; border-radius: var(--r-sm); }
    .doc-modal-body iframe { width: 100%; height: 70vh; border: none; border-radius: var(--r-sm); }
    .doc-modal-foot { padding: .8rem 1.2rem; border-top: 1px solid var(--borde); text-align: right; }

    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--gris); }
    .empty-ico { font-size: 3rem; margin-bottom: 1rem; opacity: .4; }
    .empty-title { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--texto-2); margin-bottom: .4rem; }

    @media (max-width: 900px) {
        .sidebar { display: none; }
        .main { width: 100%; }
        .stats-strip { grid-template-columns: repeat(2, 1fr); }
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
        <a href="{{ route('admin.conductores') }}"  class="sb-item active"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>Conductores
            @if($stats['pendientes']) <span style="margin-left:auto;background:#ef4444;color:white;border-radius:999px;padding:.1rem .5rem;font-size:.7rem;font-weight:700;">{{ $stats['pendientes'] }}</span> @endif
        </a>
        <a href="{{ route('admin.usuarios') }}"    class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Usuarios</a>
        <a href="{{ route('admin.servicios') }}"   class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Servicios</a>
        <div class="sb-label">Configuración</div>
        <a href="{{ route('admin.puntos') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Puntos</a>
        <a href="{{ route('admin.admins') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Admins</a>
        <div style="margin-top:auto; padding:1rem; border-top:1px solid rgba(255,255,255,.08);">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="sb-item" style="width:100%; background:none; border:none; cursor:pointer; color:rgba(255,255,255,.35);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="overlay" onclick="cerrarMenu()"></div>

    <div class="main">
        <div class="main-header">
            <div>
                <div class="main-title">Conductores</div>
                <div class="main-sub">Gestiona solicitudes y conductores activos</div>
            </div>
        </div>

        <div class="main-body">

            {{-- Stats --}}
            <div class="stats-strip">
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ $stats['totales'] }}</div>
                    <div class="stat-mini-lbl">Total conductores</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--verde-oscuro);">{{ $stats['activos'] }}</div>
                    <div class="stat-mini-lbl">Activos</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--amarillo);">{{ $stats['pendientes'] }}</div>
                    <div class="stat-mini-lbl">Pendientes</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--azul);">{{ $stats['conectados'] }}</div>
                    <div class="stat-mini-lbl">Conectados ahora</div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="filters">
                <a href="?estatus=todos"      class="chip {{ $filtro==='todos'      ?'active':'' }}">Todos ({{ $stats['totales'] }})</a>
                <a href="?estatus=pendiente"  class="chip chip-yellow {{ $filtro==='pendiente' ?'active':'' }}">⏳ Pendientes ({{ $stats['pendientes'] }})</a>
                <a href="?estatus=activo"     class="chip {{ $filtro==='activo'     ?'active':'' }}">✓ Activos ({{ $stats['activos'] }})</a>
                <a href="?estatus=suspendido" class="chip chip-red {{ $filtro==='suspendido' ?'active':'' }}">Suspendidos</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
            @endif

            {{-- Grid --}}
            @if($conductores->isEmpty())
                <div class="empty-state">
                    <div class="empty-ico">🚗</div>
                    <div class="empty-title">Sin conductores en este filtro</div>
                    <p style="font-size:.875rem;">Cambia el filtro o espera nuevas solicitudes.</p>
                </div>
            @else
                <div class="conductores-grid">
                    @foreach($conductores as $c)
                        @php
                            $estClass = ['activo'=>'badge-green','pendiente'=>'badge-yellow','suspendido'=>'badge-red'];
                            $vehIco   = ['moto'=>'🏍️','bici'=>'🚲','auto'=>'🚗'];
                        @endphp
                        <div class="c-card">
                            <div class="c-card-head">
                                <div class="c-avatar">{{ strtoupper(substr($c->usuario->nombre ?? 'C', 0, 2)) }}</div>
                                <div style="flex:1; min-width:0;">
                                    <div class="c-name">{{ $c->usuario->nombre ?? '—' }}</div>
                                    <div class="c-email">{{ $c->usuario->email ?? '' }}</div>
                                </div>
                                <span class="badge {{ $estClass[$c->estatus] ?? 'badge-gray' }}">{{ ucfirst($c->estatus) }}</span>
                            </div>

                            <div class="c-card-body">
                                <div class="c-row">
                                    <span class="c-row-lbl">Vehículo</span>
                                    <span class="c-row-val">{{ $vehIco[$c->tipo_vehiculo] ?? '' }} {{ ucfirst($c->tipo_vehiculo) }}</span>
                                </div>
                                <div class="c-row">
                                    <span class="c-row-lbl">Marca / Modelo</span>
                                    <span class="c-row-val">{{ $c->marca }} {{ $c->modelo }}</span>
                                </div>
                                <div class="c-row">
                                    <span class="c-row-lbl">Placa</span>
                                    <span class="c-row-val" style="font-family:monospace; letter-spacing:.08em;">{{ strtoupper($c->placa) }}</span>
                                </div>
                                <div class="c-row">
                                    <span class="c-row-lbl">Calificación</span>
                                    <span class="c-row-val">⭐ {{ $c->calificacion_promedio }}</span>
                                </div>
                                <div class="c-row">
                                    <span class="c-row-lbl">Estado</span>
                                    <span class="c-row-val">
                                        @if($c->esta_conectado)
                                            <span style="color:var(--verde); font-size:.8rem;">● Conectado</span>
                                        @else
                                            <span style="color:var(--gris); font-size:.8rem;">○ Desconectado</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            @if($c->documentos->count())
                                <div class="c-docs">
                                    <div class="c-docs-title">Documentos</div>
                                    @foreach($c->documentos as $doc)
                                        @php
                                            $dc = ['aprobado'=>'badge-green','pendiente'=>'badge-yellow','rechazado'=>'badge-red'];
                                            $docIcos = ['ine'=>'🪪','licencia'=>'📋','tarjeta_circulacion'=>'🚘','seguro'=>'🛡️'];
                                            $docUrl = asset('storage/' . $doc->url_archivo);
                                            $ext = strtolower(pathinfo($doc->url_archivo, PATHINFO_EXTENSION));
                                            $isPdf = $ext === 'pdf';
                                        @endphp
                                        <div class="doc-link" onclick="abrirDocModal('{{ addslashes(ucfirst(str_replace('_',' ',$doc->tipo_documento))) }}', '{{ $docUrl }}', {{ $isPdf ? 'true' : 'false' }})">
                                            <span class="doc-link-ico">{{ $docIcos[$doc->tipo_documento] ?? '📄' }}</span>
                                            <span class="doc-link-name">{{ ucfirst(str_replace('_',' ',$doc->tipo_documento)) }}</span>
                                            <span class="doc-link-status {{ $dc[$doc->estatus] ?? 'badge-gray' }}">{{ ucfirst($doc->estatus) }}</span>
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="c-card-foot">
                                @if($c->estatus === 'pendiente')
                                    <form method="POST" action="{{ route('admin.conductores.aprobar', $c->id) }}" style="flex:1;" onsubmit="return confirm('¿Aprobar a {{ addslashes($c->usuario->nombre) }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-full btn-sm" style="background:var(--verde-bg); color:var(--verde-oscuro); border-radius:var(--r-sm);">✓ Aprobar</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.conductores.rechazar', $c->id) }}" style="flex:1;" onsubmit="return confirm('¿Rechazar a {{ addslashes($c->usuario->nombre) }}? Se suspenderá su cuenta.')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-full btn-sm" style="border-radius:var(--r-sm);">✕ Rechazar</button>
                                    </form>
                                @elseif($c->estatus === 'activo')
                                    <form method="POST" action="{{ route('admin.conductores.rechazar', $c->id) }}" style="flex:1;" onsubmit="return confirm('¿Suspender a {{ addslashes($c->usuario->nombre) }}? No podrá recibir servicios.')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-full btn-sm" style="border-radius:var(--r-sm);">Suspender</button>
                                    </form>
                                @elseif($c->estatus === 'suspendido')
                                    <form method="POST" action="{{ route('admin.conductores.aprobar', $c->id) }}" style="flex:1;" onsubmit="return confirm('¿Reactivar a {{ addslashes($c->usuario->nombre) }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-full btn-sm" style="background:var(--verde-bg); color:var(--verde-oscuro); border-radius:var(--r-sm);">Reactivar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal de preview de documentos --}}
<div class="doc-modal-overlay" id="docModalOverlay" onclick="cerrarDocModal()">
    <div class="doc-modal" onclick="event.stopPropagation()">
        <div class="doc-modal-header">
            <span class="doc-modal-title" id="docModalTitle">Documento</span>
            <button class="doc-modal-close" onclick="cerrarDocModal()">&times;</button>
        </div>
        <div class="doc-modal-body" id="docModalBody"></div>
        <div class="doc-modal-foot">
            <a id="docModalDownload" href="#" target="_blank" class="btn btn-sm" style="background:var(--verde-bg); color:var(--verde-oscuro); border-radius:var(--r-sm); text-decoration:none; display:inline-flex; align-items:center; gap:.4rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Abrir en nueva pestaña
            </a>
        </div>
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

// Document preview modal
function abrirDocModal(title, url, isPdf) {
    document.getElementById('docModalTitle').textContent = title;
    document.getElementById('docModalDownload').href = url;

    const body = document.getElementById('docModalBody');
    if (isPdf) {
        body.innerHTML = '<iframe src="' + url + '"></iframe>';
    } else {
        body.innerHTML = '<img src="' + url + '" alt="' + title + '">';
    }

    document.getElementById('docModalOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function cerrarDocModal() {
    document.getElementById('docModalOverlay').classList.remove('open');
    document.getElementById('docModalBody').innerHTML = '';
    document.body.style.overflow = '';
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarDocModal();
});
</script>

@endpush
@endsection