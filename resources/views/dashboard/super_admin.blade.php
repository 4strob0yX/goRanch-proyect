@extends('layouts.app')
@section('title', 'Admin Dashboard - goRanch')

@push('styles')
<style>
    body { background: #f3f1ec; }
    .admin-layout { display: grid; grid-template-columns: 240px 1fr; min-height: 100vh; }

    /* Sidebar */
    .sidebar { background: #1a2118; padding: 0; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; width: 240px; z-index: 50; }
    .sidebar-header { padding: 1.5rem 1.2rem; border-bottom: 1px solid rgba(255,255,255,.08); }
    .sidebar-brand { display: flex; align-items: center; gap: .6rem; font-family: 'Syne', sans-serif; font-weight: 700; color: white; font-size: 1rem; text-decoration: none; }
    .sidebar-role { font-size: .7rem; color: #6b9b5e; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; margin-top: .2rem; }
    .sidebar-nav { flex: 1; padding: 1rem .6rem; overflow-y: auto; }
    .sidebar-item { display: flex; align-items: center; gap: .75rem; padding: .7rem .9rem; border-radius: 10px; text-decoration: none; color: #8aa880; font-size: .875rem; font-weight: 500; margin-bottom: .2rem; transition: all .15s; }
    .sidebar-item:hover { background: rgba(255,255,255,.06); color: #d4e8c8; }
    .sidebar-item.active { background: var(--verde); color: white; }
    .sidebar-badge { margin-left: auto; background: #ef4444; color: white; border-radius: 999px; padding: .1rem .5rem; font-size: .7rem; font-weight: 700; }
    .sidebar-footer { padding: 1rem 1.2rem; border-top: 1px solid rgba(255,255,255,.08); }
    .sidebar-logout { display: flex; align-items: center; gap: .6rem; color: #8aa880; font-size: .875rem; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; width: 100%; }
    .sidebar-logout:hover { color: #ef4444; }

    /* Main */
    .main-content { margin-left: 240px; }
    .top-header { background: white; border-bottom: 1px solid var(--gris-claro); padding: .9rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 40; }
    .header-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; }
    .header-search { display: flex; align-items: center; gap: .5rem; background: var(--fondo); border-radius: 999px; padding: .5rem 1rem; }
    .header-search input { background: none; border: none; outline: none; font-family: 'DM Sans', sans-serif; font-size: .875rem; width: 200px; }
    .header-right { display: flex; align-items: center; gap: 1rem; }
    .notif-btn { position: relative; background: none; border: none; cursor: pointer; color: var(--texto); }
    .notif-dot { position: absolute; top: 0; right: 0; width: 8px; height: 8px; background: var(--rojo); border-radius: 50%; border: 2px solid white; }
    .admin-avatar { display: flex; align-items: center; gap: .5rem; font-size: .875rem; font-weight: 600; }
    .admin-avatar-circle { width: 34px; height: 34px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--verde-oscuro); font-size: .8rem; }

    /* Page content */
    .page-body { padding: 2rem; }
    .welcome-row { margin-bottom: 1.5rem; }
    .welcome-row p { color: var(--verde); font-weight: 600; font-size: .875rem; }
    .welcome-row h1 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.8rem; }
    .welcome-row span { color: var(--gris); font-size: .875rem; }

    /* Stats grid */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card { background: white; border-radius: 16px; padding: 1.2rem; box-shadow: var(--sombra); }
    .stat-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .8rem; }
    .stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .stat-badge { font-size: .75rem; font-weight: 700; padding: .2rem .6rem; border-radius: 999px; }
    .badge-green-light { background: #dcfce7; color: #166534; }
    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-orange { background: #ffedd5; color: #c2410c; }
    .badge-purple { background: #f3e8ff; color: #7e22ce; }
    .stat-value { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; }
    .stat-label { font-size: .8rem; color: var(--gris); margin-top: .2rem; }

    /* Charts row */
    .charts-row { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; margin-bottom: 1.5rem; }
    .chart-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: var(--sombra); }
    .chart-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem; }
    .chart-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; }
    .chart-sub { font-size: .8rem; color: var(--gris); margin-top: .2rem; }
    .chart-period { font-size: .8rem; color: var(--gris); }

    /* Bar chart */
    .bar-chart { display: flex; align-items: flex-end; gap: .5rem; height: 160px; }
    .bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: .3rem; height: 100%; justify-content: flex-end; }
    .bar { width: 100%; background: var(--verde); border-radius: 6px 6px 0 0; min-height: 4px; transition: height .3s; }
    .bar-label { font-size: .7rem; color: var(--gris); white-space: nowrap; }

    /* Map placeholder */
    .map-card { background: #2a3528; border-radius: 16px; overflow: hidden; position: relative; }
    .map-inner { height: 100%; min-height: 280px; background: linear-gradient(135deg, #2a3a28, #1e2c1c); display: flex; align-items: center; justify-content: center; }
    .map-status-row { position: absolute; bottom: 1rem; left: 1rem; right: 1rem; display: flex; gap: .5rem; }
    .map-status-chip { background: rgba(0,0,0,.6); border-radius: 999px; padding: .3rem .8rem; font-size: .75rem; color: white; display: flex; align-items: center; gap: .3rem; }
    .map-dot { width: 8px; height: 8px; border-radius: 50%; }

    /* Table */
    .table-card { background: white; border-radius: 16px; box-shadow: var(--sombra); overflow: hidden; }
    .table-header { padding: 1.2rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--gris-claro); }
    .table-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; }
    .table-sub { font-size: .8rem; color: var(--gris); margin-top: .1rem; }
    .table-actions { display: flex; gap: .5rem; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: .75rem 1.5rem; text-align: left; font-size: .75rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--gris); border-bottom: 1px solid var(--gris-claro); }
    tbody td { padding: .9rem 1.5rem; font-size: .875rem; border-bottom: 1px solid #f9fafb; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover { background: var(--fondo); }
    .user-cell { display: flex; align-items: center; gap: .7rem; }
    .user-initials { width: 32px; height: 32px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .75rem; color: var(--verde-oscuro); flex-shrink: 0; }
    .user-name { font-weight: 600; }
    .user-id { font-size: .75rem; color: var(--gris); }
    .action-link { color: var(--verde-oscuro); font-weight: 600; font-size: .8rem; text-decoration: none; padding: .3rem .7rem; border-radius: 6px; background: var(--verde-bg); }
    .action-link:hover { background: var(--verde-claro); }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .charts-row { grid-template-columns: 1fr; }
    }
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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item active">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.conductores') }}" class="sidebar-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
                Conductores
                @if($stats['solicitudes_pendientes'] > 0)
                    <span class="sidebar-badge">{{ $stats['solicitudes_pendientes'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.usuarios') }}" class="sidebar-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
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

    {{-- Main --}}
    <div class="main-content">
        {{-- Top header --}}
        <div class="top-header">
            <div class="header-title">Operations Overview</div>
            <div class="header-right">
                <div class="header-search">
                    <svg width="16" height="16" fill="none" stroke="var(--gris)" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Search drivers, loads...">
                </div>
                <button class="notif-btn">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                    <div class="notif-dot"></div>
                </button>
                <div class="admin-avatar">
                    <div class="admin-avatar-circle">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</div>
                    {{ explode(' ', auth()->user()->nombre)[0] }}
                </div>
            </div>
        </div>

        <div class="page-body">
            {{-- Alertas --}}
            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
            @endif

            {{-- Welcome --}}
            <div class="welcome-row">
                <p>Welcome back, {{ explode(' ', auth()->user()->nombre)[0] }}</p>
                <h1>Dashboard</h1>
                <span>📅 {{ now()->format('M d, Y') }}</span>
            </div>

            {{-- Stats --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#dcfce7;">
                            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        </div>
                        <span class="stat-badge badge-green-light">+15%</span>
                    </div>
                    <div class="stat-value">${{ number_format($stats['total_ingresos'], 0) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#dbeafe;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>
                        </div>
                        <span class="stat-badge badge-blue">{{ $stats['conductores_total'] > 0 ? round($stats['conductores_activos'] / $stats['conductores_total'] * 100) : 0 }}% Active</span>
                    </div>
                    <div class="stat-value">{{ $stats['conductores_activos'] }}<span style="font-size:1rem; color:var(--gris); font-weight:400;">/{{ $stats['conductores_total'] }}</span></div>
                    <div class="stat-label">Active Drivers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#ffedd5;">
                            <svg width="18" height="18" fill="none" stroke="#ea580c" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        @if($stats['solicitudes_pendientes'] > 0)
                            <span class="stat-badge badge-orange">Needs Action</span>
                        @endif
                    </div>
                    <div class="stat-value">{{ $stats['solicitudes_pendientes'] }}</div>
                    <div class="stat-label">Pending Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon" style="background:#f3e8ff;">
                            <svg width="18" height="18" fill="#a855f7" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <span class="stat-badge badge-purple">+2.4%</span>
                    </div>
                    <div class="stat-value">{{ $stats['calificacion_promedio'] ?? '5.0' }}</div>
                    <div class="stat-label">Avg. Rating</div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="charts-row">
                {{-- Gráfica de barras --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <div>
                            <div class="chart-title">Daily Earnings</div>
                            <div class="chart-sub">Revenue trends over last 7 days</div>
                        </div>
                        <span class="chart-period">Last 7 Days</span>
                    </div>
                    @php $maxVal = max(array_column($ganancias, 'total')) ?: 1; @endphp
                    <div class="bar-chart">
                        @foreach($ganancias as $g)
                            <div class="bar-wrap">
                                <div class="bar" style="height: {{ max(4, ($g['total'] / $maxVal) * 140) }}px;" title="${{ number_format($g['total'], 0) }}"></div>
                                <span class="bar-label">{{ $g['fecha'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mapa --}}
                <div class="map-card">
                    <div class="map-inner">
                        <span style="color:rgba(255,255,255,.2); font-size:.875rem;">Operations Center</span>
                    </div>
                    <div class="map-status-row">
                        <div class="map-status-chip"><div class="map-dot" style="background:#4ade80;"></div> Available ({{ $stats['conductores_activos'] }})</div>
                        <div class="map-status-chip"><div class="map-dot" style="background:#fb923c;"></div> En Route</div>
                        <div class="map-status-chip"><div class="map-dot" style="background:#6b7280;"></div> Offline</div>
                    </div>
                </div>
            </div>

            {{-- Tabla servicios recientes --}}
            <div class="table-card">
                <div class="table-header">
                    <div>
                        <div class="table-title">Solicitudes Recientes</div>
                        <div class="table-sub">Latest transport requests</div>
                    </div>
                    <div class="table-actions">
                        <a href="{{ route('admin.servicios') }}" class="btn btn-outline" style="font-size:.8rem; padding:.4rem .9rem; border-radius:8px;">
                            Filter
                        </a>
                        <a href="#" class="btn btn-primary" style="font-size:.8rem; padding:.4rem .9rem; border-radius:8px;">
                            Export
                        </a>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Origin → Dest</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($serviciosRecientes as $s)
                            <tr>
                                <td style="font-weight:600; font-size:.8rem;">#REQ-{{ str_pad($s->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-initials">{{ strtoupper(substr($s->cliente->nombre ?? 'U', 0, 2)) }}</div>
                                        <div>
                                            <div class="user-name">{{ $s->cliente->nombre ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php $tipos = ['viaje'=>'Viaje','mandado_libre'=>'Mandado','delivery_tienda'=>'Delivery']; @endphp
                                    <span class="badge badge-gray">{{ $tipos[$s->tipo] ?? $s->tipo }}</span>
                                </td>
                                <td style="font-size:.8rem; color:var(--gris);">{{ Str::limit($s->direccion_origen, 15) }} → {{ Str::limit($s->direccion_destino, 15) }}</td>
                                <td>
                                    @php
                                        $clases = ['buscando'=>'badge-yellow','aceptado'=>'badge-blue','completado'=>'badge-green','cancelado'=>'badge-red','en_ruta'=>'badge-blue'];
                                        $clase = $clases[$s->estatus] ?? 'badge-gray';
                                    @endphp
                                    <span class="badge {{ $clase }}">{{ ucfirst($s->estatus) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.servicios') }}" class="action-link">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align:center; color:var(--gris); padding:2rem;">No hay servicios aún.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
