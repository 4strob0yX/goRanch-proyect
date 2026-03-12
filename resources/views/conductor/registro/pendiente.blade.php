@extends('layouts.app')
@section('title', 'Registro Enviado - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .pendiente-card {
        background: var(--blanco); border-radius: 24px; padding: 3rem 2rem;
        width: 100%; max-width: 460px; text-align: center;
        box-shadow: 0 8px 40px rgba(0,0,0,.1);
    }
    .pending-icon {
        width: 80px; height: 80px; border-radius: 50%;
        background: #fef3c7; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .pendiente-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.6rem; margin-bottom: .5rem; }
    .pendiente-sub { color: var(--gris); font-size: .95rem; line-height: 1.6; margin-bottom: 2rem; }

    .steps-list { text-align: left; margin-bottom: 2rem; }
    .step-item { display: flex; align-items: flex-start; gap: 1rem; padding: .8rem 0; border-bottom: 1px solid var(--gris-claro); }
    .step-item:last-child { border-bottom: none; }
    .step-num { width: 28px; height: 28px; border-radius: 50%; background: var(--verde-bg); color: var(--verde-oscuro); font-weight: 700; font-size: .8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .step-text strong { font-weight: 600; display: block; font-size: .9rem; }
    .step-text span { font-size: .8rem; color: var(--gris); }

    .email-badge { background: var(--verde-bg); border-radius: 12px; padding: .8rem 1.2rem; font-size: .875rem; color: var(--verde-oscuro); font-weight: 600; margin-bottom: 1.5rem; display: inline-block; }
</style>
@endpush

@section('content')
<div class="pendiente-card">
    <div class="pending-icon">
        <svg width="36" height="36" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
        </svg>
    </div>

    <h1 class="pendiente-title">¡Solicitud Enviada!</h1>
    <p class="pendiente-sub">
        Recibimos tu registro correctamente. Nuestro equipo revisará tus documentos en las próximas <strong>24 a 48 horas</strong>.
    </p>

    <div class="email-badge">
        📧 Notificación a: {{ auth()->user()->email }}
    </div>

    <div class="steps-list">
        <div class="step-item">
            <div class="step-num">1</div>
            <div class="step-text">
                <strong>Revisión de documentos</strong>
                <span>Verificamos tu licencia y seguro del vehículo.</span>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <div class="step-text">
                <strong>Aprobación de cuenta</strong>
                <span>Te enviamos un correo cuando tu cuenta esté activa.</span>
            </div>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <div class="step-text">
                <strong>Empieza a trabajar</strong>
                <span>Inicia sesión y comienza a recibir solicitudes.</span>
            </div>
        </div>
    </div>

    <a href="{{ route('login.conductor') }}" class="btn btn-primary btn-full" style="border-radius:12px; padding:1rem;">
        Ir al Login de Conductores
    </a>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:.8rem;">
        @csrf
        <button type="submit" style="background:none; border:none; color:var(--gris); font-size:.875rem; cursor:pointer; font-family:'DM Sans',sans-serif;">
            Cerrar sesión
        </button>
    </form>
</div>
@endsection
