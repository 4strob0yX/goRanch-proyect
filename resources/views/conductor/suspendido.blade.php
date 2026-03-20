@extends('layouts.app')
@section('title', 'Cuenta Suspendida - goRanch')

@push('styles')
<style>
    body { background: var(--verde-oscuro); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    body::before { display: none; }

    .susp-card { background: white; border-radius: var(--r-xl); padding: 2.5rem 2rem; max-width: 420px; width: 90%; text-align: center; }

    .susp-ico { width: 72px; height: 72px; border-radius: 50%; background: #fef2f2; border: 2px solid #fecaca; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.3rem; }

    .susp-title { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; margin-bottom: .5rem; color: #991b1b; }
    .susp-sub { color: var(--gris); font-size: .9rem; line-height: 1.6; margin-bottom: 1.8rem; }

    .reason-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--r-sm); padding: 1rem 1.2rem; text-align: left; margin-bottom: 1.5rem; }
    .reason-title { font-weight: 600; font-size: .82rem; color: #991b1b; margin-bottom: .3rem; display: flex; align-items: center; gap: .4rem; }
    .reason-text { font-size: .85rem; color: #7f1d1d; line-height: 1.5; }

    .info-list { display: flex; flex-direction: column; gap: .7rem; margin-bottom: 2rem; text-align: left; }
    .info-item { display: flex; align-items: flex-start; gap: .8rem; }
    .info-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; background: var(--fondo); color: var(--gris); }
    .info-lbl { font-size: .875rem; font-weight: 500; }
    .info-sub { font-size: .75rem; color: var(--gris); margin-top: .1rem; }

    .divider { height: 1px; background: var(--borde); margin: 1.5rem 0; }
</style>
@endpush

@section('content')
<div class="susp-card">
    <div class="susp-ico">🚫</div>
    <h1 class="susp-title">Cuenta suspendida</h1>
    <p class="susp-sub">
        Tu cuenta de conductor ha sido suspendida temporalmente.
        No puedes acceder al panel ni recibir servicios hasta que se resuelva.
    </p>

    <div class="reason-box">
        <div class="reason-title">⚠️ Motivo de la suspensión</div>
        <div class="reason-text">
            Tu solicitud fue rechazada o tu cuenta fue suspendida por el equipo de revisión.
            Si crees que es un error, contacta a soporte.
        </div>
    </div>

    <div class="info-list">
        <div class="info-item">
            <div class="info-dot">1</div>
            <div>
                <div class="info-lbl">Revisa tu correo</div>
                <div class="info-sub">Te enviamos los detalles de la suspensión</div>
            </div>
        </div>
        <div class="info-item">
            <div class="info-dot">2</div>
            <div>
                <div class="info-lbl">Contacta a soporte</div>
                <div class="info-sub">Podemos ayudarte a resolver la situación</div>
            </div>
        </div>
        <div class="info-item">
            <div class="info-dot">3</div>
            <div>
                <div class="info-lbl">Reactivación</div>
                <div class="info-sub">Una vez resuelto, tu cuenta será reactivada</div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <p style="font-size:.82rem; color:var(--gris); margin-bottom:1.2rem;">
        ¿Necesitas ayuda? Escríbenos a <strong style="color:var(--verde-oscuro);">soporte@goranch.mx</strong>
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline btn-full" style="border-radius:var(--r-sm);">Cerrar sesión</button>
    </form>
</div>
@endsection
