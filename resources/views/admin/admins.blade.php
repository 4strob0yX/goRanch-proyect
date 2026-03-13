@extends('layouts.app')
@section('title', 'Administradores - Admin goRanch')

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

    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 200; align-items: center; justify-content: center; }
    .modal-overlay.open { display: flex; }
    .modal { background: var(--blanco); border-radius: var(--r-xl); padding: 2rem; width: 100%; max-width: 460px; box-shadow: var(--sombra-lg); position: relative; }
    .modal-title { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; margin-bottom: .3rem; }
    .modal-sub { font-size: .875rem; color: var(--gris); margin-bottom: 1.5rem; }
    .modal-close { position: absolute; top: 1.2rem; right: 1.2rem; background: var(--fondo); border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--gris); }

    /* Tabla */
    .table-card { background: var(--blanco); border-radius: var(--r-lg); border: 1px solid var(--borde); overflow: hidden; margin-top: 1.5rem; }
    .tc-header { padding: 1rem 1.3rem; border-bottom: 1px solid var(--borde); display: flex; justify-content: space-between; align-items: center; }
    .tc-title { font-family: var(--font-display); font-weight: 700; font-size: .95rem; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: .7rem 1.2rem; text-align: left; font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--gris); background: var(--fondo); border-bottom: 1px solid var(--borde); }
    tbody td { padding: .85rem 1.2rem; font-size: .875rem; border-bottom: 1px solid var(--gris-claro); vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: var(--fondo); }

    .admin-cell { display: flex; align-items: center; gap: .8rem; }
    .a-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--verde-oscuro); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 700; font-size: .82rem; color: white; flex-shrink: 0; }
    .a-name { font-weight: 700; font-size: .875rem; }
    .a-email { font-size: .75rem; color: var(--gris); }

    .warning-box { background: var(--amarillo-bg); border: 1px solid #fde68a; border-radius: var(--r-sm); padding: .85rem 1rem; font-size: .82rem; color: #92400e; margin-bottom: 1.2rem; display: flex; gap: .6rem; }

    @media (max-width: 900px) {
        .sidebar { display: none; }
        .main { width: 100%; }
        .main-body { padding: 1rem; }
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
        <a href="{{ route('admin.puntos') }}"      class="sb-item"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Puntos</a>
        <a href="{{ route('admin.admins') }}"      class="sb-item active"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Admins</a>
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
                <div class="main-title">Administradores</div>
                <div class="main-sub">Gestiona quién tiene acceso al panel</div>
            </div>
            <button class="btn btn-primary" onclick="document.getElementById('modal').classList.add('open')" style="border-radius:var(--r-sm);">
                + Nuevo Admin
            </button>
        </div>

        <div class="main-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="warning-box">
                <span>⚠️</span>
                <span>Los administradores tienen acceso completo al panel. Crea cuentas solo para personas de confianza.</span>
            </div>

            <div class="table-card">
                <div class="tc-header">
                    <span class="tc-title">{{ $admins->count() }} administrador{{ $admins->count() !== 1 ? 'es':'' }}</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Administrador</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Registro</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $a)
                            <tr>
                                <td>
                                    <div class="admin-cell">
                                        <div class="a-avatar">{{ strtoupper(substr($a->nombre, 0, 2)) }}</div>
                                        <div>
                                            <div class="a-name">
                                                {{ $a->nombre }}
                                                @if($a->id === auth()->id())
                                                    <span class="badge badge-green" style="font-size:.65rem; margin-left:.3rem;">Tú</span>
                                                @endif
                                            </div>
                                            <div class="a-email">{{ $a->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:.82rem; color:var(--gris);">{{ $a->telefono ?? '—' }}</td>
                                <td><span class="badge badge-blue">{{ ucfirst(str_replace('_',' ',$a->rol)) }}</span></td>
                                <td style="font-size:.78rem; color:var(--gris);">{{ \Carbon\Carbon::parse($a->creado_en)->format('d M Y') }}</td>
                                <td><span class="badge {{ $a->estatus === 'activo' ? 'badge-green' : 'badge-red' }}">{{ ucfirst($a->estatus) }}</span></td>
                                <td>
                                    @if($a->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.admins.toggle', $a->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $a->estatus === 'activo' ? 'btn-danger' : '' }}" style="border-radius:var(--r-sm); {{ $a->estatus !== 'activo' ? 'background:var(--verde-bg);color:var(--verde-oscuro);' : '' }}">
                                                {{ $a->estatus === 'activo' ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    @else
                                        <span style="font-size:.75rem; color:var(--gris);">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal nuevo admin --}}
<div class="modal-overlay" id="modal">
    <div class="modal">
        <button class="modal-close" onclick="document.getElementById('modal').classList.remove('open')">✕</button>
        <div class="modal-title">Nuevo Administrador</div>
        <div class="modal-sub">Tendrá acceso completo al panel de goRanch.</div>

        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nombre completo</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                    <input type="text" name="nombre" class="form-input" placeholder="Nombre y apellido" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                    <input type="email" name="email" class="form-input" placeholder="correo@ejemplo.com" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2A19.79 19.79 0 013.07 9.81a2 2 0 012-2.18H8a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6z"/></svg></span>
                    <input type="tel" name="telefono" class="form-input" placeholder="10 dígitos" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Contraseña temporal</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                    <input type="password" name="password" class="form-input" placeholder="Mínimo 8 caracteres" required>
                </div>
            </div>

            <div class="alert alert-warning" style="margin-bottom:1rem;">
                ⚠️ Comparte la contraseña de forma segura y pídele que la cambie al entrar.
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="border-radius:var(--r-sm);">Crear Administrador</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('modal').addEventListener('click', function(e) {
    if(e.target === this) this.classList.remove('open');
});
</script>
@endpush
@endsection
