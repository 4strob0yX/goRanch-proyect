@extends('layouts.app')
@section('title', 'Servicios - Admin goRanch')

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

    /* Mini stats */
    .mini-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .mini-stat { background: white; border-radius: 12px; padding: 1rem 1.2rem; box-shadow: var(--sombra); }
    .mini-stat-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
    .mini-stat-icon { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
    .mini-stat strong { font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; display: block; }
    .mini-stat span { font-size: .78rem; color: var(--gris); }

    /* Filtros */
    .filtros-row { display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .filtro-group { display: flex; flex-direction: column; gap: .3rem; }
    .filtro-label { font-size: .75rem; font-weight: 700; color: var(--gris); text-transform: uppercase; letter-spacing: .05em; }
    .filtro-select { padding: .55rem 1rem; border: 1.5px solid var(--gris-claro); border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: .875rem; outline: none; background: white; cursor: pointer; }
    .filtro-select:focus { border-color: var(--verde); }

    /* Tabla */
    .table-card { background: white; border-radius: 16px; box-shadow: var(--sombra); overflow: hidden; }
    .table-header { padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--gris-claro); display: flex; justify-content: space-between; align-items: center; }
    .table-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; }
    .table-sub { font-size: .8rem; color: var(--gris); margin-top: .1rem; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: .75rem 1.2rem; text-align: left; font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--gris); border-bottom: 1px solid var(--gris-claro); white-space: nowrap; }
    tbody td { padding: .85rem 1.2rem; font-size: .855rem; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--fondo); }

    .user-cell { display: flex; align-items: center; gap: .6rem; }
    .user-avatar { width: 30px; height: 30px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .72rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .user-name { font-weight: 600; font-size: .855rem; }

    .ruta-cell { font-size: .78rem; color: var(--gris); line-height: 1.5; }
    .ruta-arrow { color: var(--verde); font-weight: 700; }

    .monto { font-weight: 700; color: var(--verde-oscuro); }

    .empty-state { text-align: center; padding: 3rem; color: var(--gris); }

    /* Tipo chip */
    .tipo-viaje    { background: #dbeafe; color: #1d4ed8; }
    .tipo-mandado  { background: #fef3c7; color: #92400e; }
    .tipo-delivery { background: #f3e8ff; color: #7e22ce; }
</style>
@endpush

@section('content')
<div class="admin-layout">
    {{-- Sidebar --}}
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
            <a href="{{ route('admin.usuarios') }}" class="sidebar-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Usuarios
            </a>
            <a href="{{ route('admin.servicios') }}" class="sidebar-item active">
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

    {{-- Main --}}
    <div class="main-content">
        <div class="top-header">
            <div class="header-title">Servicios</div>
        </div>

        <div class="page-body">
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
            @endif

            {{-- Mini stats --}}
            @php
                use App\Models\Servicio;
                $totales = [
                    'todos'      => Servicio::count(),
                    'completado' => Servicio::where('estatus','completado')->count(),
                    'buscando'   => Servicio::where('estatus','buscando')->count(),
                    'cancelado'  => Servicio::where('estatus','cancelado')->count(),
                    'ingresos'   => Servicio::where('estatus','completado')->sum('total_final'),
                ];
            @endphp
            <div class="mini-stats">
                <div class="mini-stat">
                    <div class="mini-stat-top">
                        <div class="mini-stat-icon" style="background:var(--verde-bg);">
                            <svg width="16" height="16" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                    </div>
                    <strong>{{ $totales['todos'] }}</strong>
                    <span>Total servicios</span>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-top">
                        <div class="mini-stat-icon" style="background:#dcfce7;">
                            <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                    </div>
                    <strong>{{ $totales['completado'] }}</strong>
                    <span>Completados</span>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-top">
                        <div class="mini-stat-icon" style="background:#fef3c7;">
                            <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <strong>{{ $totales['buscando'] }}</strong>
                    <span>En espera</span>
                </div>
                <div class="mini-stat">
                    <div class="mini-stat-top">
                        <div class="mini-stat-icon" style="background:#dcfce7;">
                            <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </div>
                    </div>
                    <strong>${{ number_format($totales['ingresos'], 0) }}</strong>
                    <span>Ingresos totales</span>
                </div>
            </div>

            {{-- Filtros --}}
            <form method="GET" action="{{ route('admin.servicios') }}">
                <div class="filtros-row">
                    <div class="filtro-group">
                        <label class="filtro-label">Tipo</label>
                        <select name="tipo" class="filtro-select" onchange="this.form.submit()">
                            <option value="todos" {{ $tipo === 'todos' ? 'selected' : '' }}>Todos los tipos</option>
                            <option value="viaje"          {{ $tipo === 'viaje'          ? 'selected' : '' }}>Viaje</option>
                            <option value="mandado_libre"  {{ $tipo === 'mandado_libre'  ? 'selected' : '' }}>Mandado libre</option>
                            <option value="delivery_tienda"{{ $tipo === 'delivery_tienda'? 'selected' : '' }}>Delivery tienda</option>
                        </select>
                    </div>
                    <div class="filtro-group">
                        <label class="filtro-label">Estatus</label>
                        <select name="estatus" class="filtro-select" onchange="this.form.submit()">
                            <option value="todos"      {{ $estatus === 'todos'      ? 'selected' : '' }}>Todos</option>
                            <option value="buscando"   {{ $estatus === 'buscando'   ? 'selected' : '' }}>Buscando</option>
                            <option value="aceptado"   {{ $estatus === 'aceptado'   ? 'selected' : '' }}>Aceptado</option>
                            <option value="en_sitio"   {{ $estatus === 'en_sitio'   ? 'selected' : '' }}>En sitio</option>
                            <option value="en_ruta"    {{ $estatus === 'en_ruta'    ? 'selected' : '' }}>En ruta</option>
                            <option value="completado" {{ $estatus === 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelado"  {{ $estatus === 'cancelado'  ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    @if($tipo !== 'todos' || $estatus !== 'todos')
                        <div class="filtro-group" style="justify-content:flex-end; flex: 1;">
                            <span style="visibility:hidden; font-size:.75rem;">x</span>
                            <a href="{{ route('admin.servicios') }}" class="btn btn-outline" style="border-radius:10px; padding:.5rem 1rem; font-size:.85rem; align-self:flex-end;">
                                Limpiar filtros
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            {{-- Tabla --}}
            <div class="table-card">
                <div class="table-header">
                    <div>
                        <div class="table-title">Lista de Servicios</div>
                        <div class="table-sub">{{ $servicios->total() }} registros encontrados</div>
                    </div>
                </div>

                @if($servicios->count() > 0)
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Conductor</th>
                                    <th>Tipo</th>
                                    <th>Ruta</th>
                                    <th>Total</th>
                                    <th>Pago</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $s)
                                    @php
                                        $tipoLabels  = ['viaje'=>'Viaje','mandado_libre'=>'Mandado','delivery_tienda'=>'Delivery'];
                                        $tipoClasses = ['viaje'=>'tipo-viaje','mandado_libre'=>'tipo-mandado','delivery_tienda'=>'tipo-delivery'];
                                        $estatusClases = [
                                            'buscando'   => 'badge-yellow',
                                            'aceptado'   => 'badge-blue',
                                            'en_sitio'   => 'badge-blue',
                                            'en_ruta'    => 'badge-blue',
                                            'completado' => 'badge-green',
                                            'cancelado'  => 'badge-red',
                                        ];
                                    @endphp
                                    <tr>
                                        <td style="font-weight:700; font-size:.78rem; color:var(--gris);">
                                            #{{ str_pad($s->id, 4, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td>
                                            <div class="user-cell">
                                                <div class="user-avatar">{{ strtoupper(substr($s->cliente->nombre ?? 'U', 0, 2)) }}</div>
                                                <div class="user-name">{{ Str::limit($s->cliente->nombre ?? 'N/A', 14) }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($s->conductor)
                                                <div class="user-cell">
                                                    <div class="user-avatar" style="background:#e0f2fe; color:#0369a1;">
                                                        {{ strtoupper(substr($s->conductor->usuario->nombre ?? 'C', 0, 2)) }}
                                                    </div>
                                                    <div class="user-name">{{ Str::limit($s->conductor->usuario->nombre ?? 'N/A', 14) }}</div>
                                                </div>
                                            @else
                                                <span style="color:var(--gris); font-size:.8rem;">Sin asignar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $tipoClasses[$s->tipo] ?? '' }}">
                                                {{ $tipoLabels[$s->tipo] ?? $s->tipo }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="ruta-cell">
                                                {{ Str::limit($s->direccion_origen, 18) }}<br>
                                                <span class="ruta-arrow">↓</span>
                                                {{ Str::limit($s->direccion_destino, 18) }}
                                            </div>
                                        </td>
                                        <td class="monto">${{ number_format($s->total_final, 2) }}</td>
                                        <td>
                                            <span style="font-size:.78rem; color:var(--gris);">
                                                {{ $s->metodo_pago === 'efectivo' ? '💵 Efectivo' : '👛 Billetera' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $estatusClases[$s->estatus] ?? 'badge-gray' }}">
                                                {{ ucfirst(str_replace('_', ' ', $s->estatus)) }}
                                            </span>
                                        </td>
                                        <td style="font-size:.78rem; color:var(--gris); white-space:nowrap;">
                                            {{ \Carbon\Carbon::parse($s->creado_en)->format('d/m/Y') }}<br>
                                            {{ \Carbon\Carbon::parse($s->creado_en)->format('H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="padding:1rem 1.5rem;">{{ $servicios->links() }}</div>
                @else
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        <p style="margin-top:.5rem;">No hay servicios con estos filtros.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
