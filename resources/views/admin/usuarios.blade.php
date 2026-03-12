@extends('layouts.app')
@section('title', 'Usuarios - Admin goRanch')

@push('styles')
<style>
    body { background: var(--fondo); }
    .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }
    .sidebar { background: #1a2118; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; width: 240px; z-index: 50; }
    .sidebar-header { padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); }
    .sidebar-brand { display: flex; align-items: center; gap: .6rem; font-family: 'Syne', sans-serif; font-weight: 700; color: white; font-size: 1rem; text-decoration: none; }
    .sidebar-role { font-size: .7rem; color: #6b9b5e; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; margin-top: .2rem; }
    .sidebar-nav { flex: 1; padding: 1rem .6rem; }
    .sidebar-item { display: flex; align-items: center; gap: .75rem; padding: .7rem .9rem; border-radius: 10px; text-decoration: none; color: #8aa880; font-size: .875rem; font-weight: 500; margin-bottom: .2rem; transition: all .15s; }
    .sidebar-item:hover { background: rgba(255,255,255,.06); color: #d4e8c8; }
    .sidebar-item.active { background: var(--verde); color: white; }
    .sidebar-footer { padding: 1rem 1.2rem; border-top: 1px solid rgba(255,255,255,.08); }
    .sidebar-logout { display: flex; align-items: center; gap: .6rem; color: #8aa880; font-size: .875rem; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; width: 100%; }
    .main-content { margin-left: 240px; }
    .top-header { background: white; border-bottom: 1px solid var(--gris-claro); padding: .9rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 40; }
    .header-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; }
    .page-body { padding: 2rem; }

    /* Búsqueda */
    .search-bar { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
    .search-input-wrap { flex: 1; position: relative; max-width: 400px; }
    .search-input-wrap svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gris); }
    .search-input { width: 100%; padding: .7rem 1rem .7rem 2.8rem; border: 1.5px solid var(--gris-claro); border-radius: 999px; font-family: 'DM Sans', sans-serif; font-size: .875rem; outline: none; background: white; }
    .search-input:focus { border-color: var(--verde); }

    /* Stats rápidas */
    .mini-stats { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
    .mini-stat { background: white; border-radius: 12px; padding: .9rem 1.2rem; box-shadow: var(--sombra); display: flex; align-items: center; gap: .8rem; }
    .mini-stat-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .mini-stat-info strong { font-family: 'Syne', sans-serif; font-size: 1.2rem; font-weight: 800; display: block; }
    .mini-stat-info span { font-size: .75rem; color: var(--gris); }

    /* Tabla */
    .table-card { background: white; border-radius: 16px; box-shadow: var(--sombra); overflow: hidden; }
    .table-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--gris-claro); display: flex; justify-content: space-between; align-items: center; }
    .table-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: .75rem 1.5rem; text-align: left; font-size: .75rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--gris); border-bottom: 1px solid var(--gris-claro); }
    tbody td { padding: .9rem 1.5rem; font-size: .875rem; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--fondo); }
    .user-cell { display: flex; align-items: center; gap: .8rem; }
    .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .user-name { font-weight: 600; }
    .user-email { font-size: .78rem; color: var(--gris); }
    .action-btn { padding: .35rem .8rem; border-radius: 8px; font-size: .78rem; font-weight: 600; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .btn-block { background: #fee2e2; color: #991b1b; }
    .btn-block:hover { background: #fecaca; }
    .btn-unblock { background: var(--verde-bg); color: var(--verde-oscuro); }
    .btn-unblock:hover { background: var(--verde-claro); }
    .empty-state { text-align: center; padding: 3rem; color: var(--gris); }
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
            <a href="{{ route('admin.conductores') }}" class="sidebar-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
                Conductores
            </a>
            <a href="{{ route('admin.usuarios') }}" class="sidebar-item active">
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
            <div class="header-title">Usuarios</div>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
            @endif

            {{-- Mini stats --}}
            <div class="mini-stats">
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:var(--verde-bg);">
                        <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div class="mini-stat-info">
                        <strong>{{ $usuarios->total() }}</strong>
                        <span>Total usuarios</span>
                    </div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#dcfce7;">
                        <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="mini-stat-info">
                        <strong>{{ $usuarios->where('estatus', 'activo')->count() }}</strong>
                        <span>Activos</span>
                    </div>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-icon" style="background:#fee2e2;">
                        <svg width="18" height="18" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                    </div>
                    <div class="mini-stat-info">
                        <strong>{{ $usuarios->where('estatus', 'bloqueado')->count() }}</strong>
                        <span>Bloqueados</span>
                    </div>
                </div>
            </div>

            {{-- Búsqueda --}}
            <form method="GET" action="{{ route('admin.usuarios') }}" class="search-bar">
                <div class="search-input-wrap">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" class="search-input"
                        placeholder="Buscar por nombre, email o teléfono..."
                        value="{{ $busqueda }}">
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius:999px; padding:.6rem 1.2rem; font-size:.875rem;">
                    Buscar
                </button>
                @if($busqueda)
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-outline" style="border-radius:999px; padding:.6rem 1.2rem; font-size:.875rem;">
                        Limpiar
                    </a>
                @endif
            </form>

            {{-- Tabla --}}
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">
                        {{ $busqueda ? "Resultados para \"$busqueda\"" : 'Todos los usuarios' }}
                        <span style="font-size:.8rem; color:var(--gris); font-weight:400; margin-left:.5rem;">({{ $usuarios->total() }})</span>
                    </div>
                </div>

                @if($usuarios->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Teléfono</th>
                                <th>Registro</th>
                                <th>Estatus</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar">{{ strtoupper(substr($usuario->nombre, 0, 2)) }}</div>
                                            <div>
                                                <div class="user-name">{{ $usuario->nombre }}</div>
                                                <div class="user-email">{{ $usuario->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="color:var(--gris);">{{ $usuario->telefono }}</td>
                                    <td style="color:var(--gris); font-size:.8rem;">
                                        {{ \Carbon\Carbon::parse($usuario->creado_en)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $usuario->estatus === 'activo' ? 'badge-green' : 'badge-red' }}">
                                            {{ ucfirst($usuario->estatus) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.usuarios.bloquear', $usuario->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="action-btn {{ $usuario->estatus === 'activo' ? 'btn-block' : 'btn-unblock' }}"
                                                onclick="return confirm('¿Confirmas esta acción?')">
                                                {{ $usuario->estatus === 'activo' ? 'Bloquear' : 'Desbloquear' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="padding:1rem 1.5rem;">{{ $usuarios->links() }}</div>
                @else
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <p style="margin-top:.5rem;">No se encontraron usuarios.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
