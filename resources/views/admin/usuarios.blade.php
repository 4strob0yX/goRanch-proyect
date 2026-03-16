@extends('layouts.app')
@section('title', 'Usuarios - Admin goRanch')

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
    .main-body { padding: 1.8rem 2rem; }

    /* Buscador */
    .search-bar { display: flex; gap: .8rem; align-items: center; margin-bottom: 1.5rem; }
    .search-wrap { position: relative; flex: 1; max-width: 380px; }
    .search-ico { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--gris); pointer-events: none; }
    .search-input { width: 100%; padding: .7rem 1rem .7rem 2.6rem; border: 1.5px solid var(--borde); border-radius: var(--r-full); font-family: var(--font-body); font-size: .875rem; background: var(--blanco); outline: none; transition: border-color .18s; }
    .search-input:focus { border-color: var(--verde); }

    /* Stats strip */
    .stats-strip { display: grid; grid-template-columns: repeat(3, 1fr); gap: .8rem; margin-bottom: 1.5rem; }
    .stat-mini { background: var(--blanco); border-radius: var(--r-md); border: 1px solid var(--borde); padding: .9rem 1rem; }
    .stat-mini-val { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; line-height: 1; }
    .stat-mini-lbl { font-size: .72rem; color: var(--gris); margin-top: .3rem; }

    /* Filtros */
    .filters { display: flex; gap: .4rem; margin-bottom: 1.2rem; }
    .chip { padding: .35rem .9rem; border-radius: var(--r-full); font-size: .8rem; font-weight: 600; border: 1.5px solid var(--borde); background: var(--blanco); color: var(--gris); text-decoration: none; transition: all .15s; }
    .chip:hover { border-color: var(--verde-claro); color: var(--verde-oscuro); }
    .chip.active { background: var(--verde-oscuro); color: white; border-color: var(--verde-oscuro); }
    .chip-red.active { background: var(--rojo); border-color: var(--rojo); color: white; }

    /* Tabla */
    .table-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: .75rem 1.2rem; text-align: left; font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); background: var(--fondo); border-bottom: 1px solid var(--borde); white-space: nowrap; }
    tbody td { padding: .85rem 1.2rem; font-size: .875rem; border-bottom: 1px solid var(--gris-claro); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: var(--fondo); }

    .user-cell { display: flex; align-items: center; gap: .75rem; }
    .u-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: .78rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .u-name { font-weight: 600; font-size: .875rem; }
    .u-email { font-size: .75rem; color: var(--gris); }

    .empty-state { text-align: center; padding: 4rem; color: var(--gris); }

    @media (max-width: 900px) {
        .sidebar { display: none; }
        .main { width: 100%; }
        .main-body { padding: 1rem; }
        .stats-strip { grid-template-columns: repeat(3,1fr); }
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
        <a href="{{ route('admin.usuarios') }}"    class="sb-item active"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Usuarios</a>
        <a href="{{ route('admin.servicios') }}"   class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Servicios</a>
        <div class="sb-label">Configuración</div>
        <a href="{{ route('admin.puntos') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Puntos</a>
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
                <div class="main-title">Usuarios</div>
                <div class="main-sub">Clientes registrados en la plataforma</div>
            </div>
        </div>

        <div class="main-body">
            @php
                $busqueda = request('q', '');
                $filtro   = request('estatus', 'todos');
                $query    = \App\Models\Usuario::where('rol', 'usuario');
                if($busqueda) $query->where(fn($q) => $q->where('nombre','like',"%$busqueda%")->orWhere('email','like',"%$busqueda%"));
                if($filtro !== 'todos') $query->where('estatus', $filtro);
                $usuarios  = $query->orderByDesc('id')->paginate(20);
                $total     = \App\Models\Usuario::where('rol','usuario')->count();
                $activos   = \App\Models\Usuario::where('rol','usuario')->where('estatus','activo')->count();
                $bloqueados = \App\Models\Usuario::where('rol','usuario')->where('estatus','bloqueado')->count();
            @endphp

            {{-- Stats --}}
            <div class="stats-strip">
                <div class="stat-mini">
                    <div class="stat-mini-val">{{ $total }}</div>
                    <div class="stat-mini-lbl">Total usuarios</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--verde-oscuro);">{{ $activos }}</div>
                    <div class="stat-mini-lbl">Activos</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-val" style="color:var(--rojo);">{{ $bloqueados }}</div>
                    <div class="stat-mini-lbl">Bloqueados</div>
                </div>
            </div>

            {{-- Buscador + filtros --}}
            <div class="search-bar">
                <form method="GET" action="{{ route('admin.usuarios') }}" style="display:flex; gap:.7rem; width:100%;">
                    <div class="search-wrap">
                        <span class="search-ico"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
                        <input type="text" name="q" class="search-input" placeholder="Buscar por nombre o correo..." value="{{ $busqueda }}">
                    </div>
                    <input type="hidden" name="estatus" value="{{ $filtro }}">
                    <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--r-full);">Buscar</button>
                    @if($busqueda)
                        <a href="{{ route('admin.usuarios') }}" class="btn btn-outline btn-sm" style="border-radius:var(--r-full);">✕ Limpiar</a>
                    @endif
                </form>
            </div>

            <div class="filters">
                <a href="?estatus=todos"    class="chip {{ $filtro==='todos'    ?'active':'' }}">Todos ({{ $total }})</a>
                <a href="?estatus=activo"   class="chip {{ $filtro==='activo'   ?'active':'' }}">✓ Activos</a>
                <a href="?estatus=bloqueado" class="chip chip-red {{ $filtro==='bloqueado' ?'active':'' }}">Bloqueados</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
            @endif

            {{-- Tabla --}}
            <div class="table-card">
                @if($usuarios->isEmpty())
                    <div class="empty-state">
                        <p style="font-size:2rem; margin-bottom:.7rem;">👤</p>
                        Sin usuarios en este filtro.
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Teléfono</th>
                                <th>Viajes</th>
                                <th>Registro</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $u)
                                @php
                                    $viajes = \App\Models\Servicio::where('cliente_id', $u->id)->count();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <div class="u-avatar">{{ strtoupper(substr($u->nombre, 0, 2)) }}</div>
                                            <div>
                                                <div class="u-name">{{ $u->nombre }}</div>
                                                <div class="u-email">{{ $u->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="color:var(--gris); font-size:.82rem;">{{ $u->telefono ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $viajes > 0 ? 'green' : 'gray' }}">{{ $viajes }} viaje{{ $viajes !== 1 ? 's':'' }}</span>
                                    </td>
                                    <td style="font-size:.78rem; color:var(--gris);">{{ \Carbon\Carbon::parse($u->creado_en)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $u->estatus === 'activo' ? 'badge-green' : 'badge-red' }}">
                                            {{ ucfirst($u->estatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.usuarios.bloquear', $u->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $u->estatus === 'activo' ? 'btn-danger' : '' }}" style="border-radius:var(--r-sm); {{ $u->estatus !== 'activo' ? 'background:var(--verde-bg);color:var(--verde-oscuro);' : '' }}">
                                                {{ $u->estatus === 'activo' ? 'Bloquear' : 'Desbloquear' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($usuarios->hasPages())
                        <div style="padding:1rem 1.2rem; display:flex; justify-content:flex-end;">
                            {{ $usuarios->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>

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
</script>

@endpush
@endsection