@extends('layouts.app')
@section('title', 'Conductores - Admin goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
    .sidebar { background: #1a2118; padding: 0; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; width: 240px; z-index: 50; }
    .sidebar-header { padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); }
    .sidebar-brand { display: flex; align-items: center; gap: .6rem; font-family: 'Syne', sans-serif; font-weight: 700; color: white; font-size: 1rem; text-decoration: none; }
    .sidebar-role { font-size: .7rem; color: #6b9b5e; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; margin-top: .2rem; }
    .sidebar-nav { flex: 1; padding: 1rem .6rem; }
    .sidebar-item { display: flex; align-items: center; gap: .75rem; padding: .7rem .9rem; border-radius: 10px; text-decoration: none; color: #8aa880; font-size: .875rem; font-weight: 500; margin-bottom: .2rem; transition: all .15s; }
    .sidebar-item:hover { background: rgba(255,255,255,.06); color: #d4e8c8; }
    .sidebar-item.active { background: var(--verde); color: white; }
    .sidebar-badge { margin-left: auto; background: #ef4444; color: white; border-radius: 999px; padding: .1rem .5rem; font-size: .7rem; font-weight: 700; }
    .sidebar-footer { padding: 1rem 1.2rem; border-top: 1px solid rgba(255,255,255,.08); }
    .sidebar-logout { display: flex; align-items: center; gap: .6rem; color: #8aa880; font-size: .875rem; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; width: 100%; }
    .main-content { margin-left: 240px; }
    .top-header { background: white; border-bottom: 1px solid var(--gris-claro); padding: .9rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 40; }
    .header-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; }
    .page-body { padding: 2rem; }

    /* Filtros */
    .filter-tabs { display: flex; gap: .5rem; margin-bottom: 1.5rem; }
    .filter-tab { padding: .5rem 1.2rem; border-radius: 999px; font-size: .875rem; font-weight: 600; text-decoration: none; border: 2px solid var(--gris-claro); color: var(--gris); transition: all .15s; }
    .filter-tab:hover { border-color: var(--verde); color: var(--verde); }
    .filter-tab.active { background: var(--verde); border-color: var(--verde); color: white; }

    /* Cards conductor */
    .conductores-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; }
    .conductor-card { background: white; border-radius: 16px; box-shadow: var(--sombra); overflow: hidden; }
    .conductor-card-header { padding: 1.2rem; display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid var(--gris-claro); }
    .c-avatar { width: 48px; height: 48px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--verde-oscuro); font-size: 1.1rem; flex-shrink: 0; }
    .c-info-main strong { font-weight: 700; display: block; }
    .c-info-main span { font-size: .8rem; color: var(--gris); }
    .conductor-card-body { padding: 1rem 1.2rem; }
    .detail-row { display: flex; justify-content: space-between; font-size: .875rem; padding: .3rem 0; }
    .detail-row span:first-child { color: var(--gris); }
    .detail-row span:last-child { font-weight: 600; }
    .docs-row { margin-top: .8rem; padding-top: .8rem; border-top: 1px solid var(--gris-claro); }
    .docs-title { font-size: .75rem; font-weight: 700; color: var(--gris); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .5rem; }
    .doc-item { display: flex; align-items: center; justify-content: space-between; padding: .4rem 0; font-size: .8rem; }
    .doc-item a { color: var(--verde); text-decoration: none; font-weight: 600; }
    .conductor-card-footer { padding: 1rem 1.2rem; display: flex; gap: .6rem; border-top: 1px solid var(--gris-claro); }

    .empty-state { text-align: center; padding: 3rem; color: var(--gris); }
    .empty-state svg { margin: 0 auto 1rem; display: block; opacity: .3; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
                goRanch
            </a>
            <div class="sidebar-role">Admin Panel</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.conductores') }}" class="sidebar-item active">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
                Conductores
            </a>
            <a href="{{ route('admin.usuarios') }}" class="sidebar-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Usuarios
            </a>
            <a href="{{ route('admin.servicios') }}" class="sidebar-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Servicios
            </a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <div class="top-header">
            <div class="header-title">Conductores</div>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
            @endif

            {{-- Filtros --}}
            <div class="filter-tabs">
                <a href="{{ route('admin.conductores', ['estatus' => 'pendiente']) }}"
                    class="filter-tab {{ $estatus === 'pendiente' ? 'active' : '' }}">Pendientes</a>
                <a href="{{ route('admin.conductores', ['estatus' => 'activo']) }}"
                    class="filter-tab {{ $estatus === 'activo' ? 'active' : '' }}">Activos</a>
                <a href="{{ route('admin.conductores', ['estatus' => 'suspendido']) }}"
                    class="filter-tab {{ $estatus === 'suspendido' ? 'active' : '' }}">Suspendidos</a>
                <a href="{{ route('admin.conductores', ['estatus' => 'todos']) }}"
                    class="filter-tab {{ $estatus === 'todos' ? 'active' : '' }}">Todos</a>
            </div>

            {{-- Grid conductores --}}
            @if($conductores->count() > 0)
                <div class="conductores-grid">
                    @foreach($conductores as $conductor)
                        <div class="conductor-card">
                            <div class="conductor-card-header">
                                <div class="c-avatar">{{ strtoupper(substr($conductor->usuario->nombre ?? 'C', 0, 2)) }}</div>
                                <div class="c-info-main">
                                    <strong>{{ $conductor->usuario->nombre ?? 'N/A' }}</strong>
                                    <span>{{ $conductor->usuario->email ?? '' }}</span>
                                    <span> • {{ $conductor->usuario->telefono ?? '' }}</span>
                                </div>
                                @php $clases = ['pendiente'=>'badge-yellow','activo'=>'badge-green','suspendido'=>'badge-red']; @endphp
                                <span class="badge {{ $clases[$conductor->estatus] ?? 'badge-gray' }}" style="margin-left:auto;">
                                    {{ ucfirst($conductor->estatus) }}
                                </span>
                            </div>

                            <div class="conductor-card-body">
                                <div class="detail-row">
                                    <span>Vehículo</span>
                                    <span>{{ ucfirst($conductor->tipo_vehiculo) }} • {{ $conductor->marca }} {{ $conductor->modelo }}</span>
                                </div>
                                <div class="detail-row">
                                    <span>Placa</span>
                                    <span>{{ $conductor->placa }}</span>
                                </div>
                                <div class="detail-row">
                                    <span>Calificación</span>
                                    <span>⭐ {{ $conductor->calificacion_promedio }}</span>
                                </div>
                                <div class="detail-row">
                                    <span>Registro</span>
                                    <span>{{ \Carbon\Carbon::parse($conductor->creado_en)->format('d/m/Y') }}</span>
                                </div>

                                @if($conductor->documentos->count() > 0)
                                    <div class="docs-row">
                                        <div class="docs-title">Documentos</div>
                                        @foreach($conductor->documentos as $doc)
                                            <div class="doc-item">
                                                <span>{{ ucfirst($doc->tipo_documento) }}</span>
                                                <div style="display:flex; align-items:center; gap:.5rem;">
                                                    <a href="{{ Storage::url($doc->url_archivo) }}" target="_blank">Ver</a>
                                                    <span class="badge {{ $doc->estaAprobado() ? 'badge-green' : ($doc->estaRechazado() ? 'badge-red' : 'badge-yellow') }}">
                                                        {{ ucfirst($doc->estatus) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if($conductor->estatus === 'pendiente')
                                <div class="conductor-card-footer">
                                    <form method="POST" action="{{ route('admin.conductores.aprobar', $conductor->id) }}" style="flex:1;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary btn-full" style="border-radius:10px; padding:.6rem;">
                                            ✓ Aprobar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.conductores.rechazar', $conductor->id) }}" style="flex:1;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-full" style="border-radius:10px; padding:.6rem;">
                                            ✕ Rechazar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:1.5rem;">{{ $conductores->links() }}</div>
            @else
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
                    <p>No hay conductores {{ $estatus !== 'todos' ? $estatus.'s' : '' }} por el momento.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
