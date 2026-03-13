@extends('layouts.app')
@section('title', 'Solicitud Pendiente - goRanch')

@push('styles')
<style>
    body { background: var(--verde-oscuro); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    body::before { display: none; }

    .pend-card { background: white; border-radius: var(--r-xl); padding: 2.5rem 2rem; max-width: 420px; width: 90%; text-align: center; }

    .pend-ico { width: 72px; height: 72px; border-radius: 50%; background: var(--amarillo-bg); border: 2px solid #fde68a; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.3rem; }

    .pend-title { font-family: var(--font-display); font-size: 1.6rem; font-weight: 700; margin-bottom: .5rem; }
    .pend-sub { color: var(--gris); font-size: .9rem; line-height: 1.6; margin-bottom: 1.8rem; }

    .steps-mini { display: flex; flex-direction: column; gap: .7rem; margin-bottom: 2rem; text-align: left; }
    .smi { display: flex; align-items: center; gap: .8rem; }
    .smi-dot { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .82rem; font-weight: 700; flex-shrink: 0; }
    .smi-dot.done { background: var(--verde-bg); color: var(--verde-oscuro); }
    .smi-dot.wait { background: var(--amarillo-bg); color: #92400e; }
    .smi-dot.pend { background: var(--fondo); color: var(--gris); }
    .smi-lbl { font-size: .875rem; font-weight: 500; }
    .smi-sub { font-size: .75rem; color: var(--gris); margin-top: .1rem; }

    .divider { height: 1px; background: var(--borde); margin: 1.5rem 0; }
</style>
@endpush

@section('content')
<div class="pend-card">
    <div class="pend-ico">⏳</div>
    <h1 class="pend-title">Solicitud enviada</h1>
    <p class="pend-sub">
        Recibimos tu solicitud. Nuestro equipo revisará tus documentos
        y te avisará en menos de <strong>24 horas</strong>.
    </p>

    <div class="steps-mini">
        <div class="smi">
            <div class="smi-dot done">✓</div>
            <div>
                <div class="smi-lbl">Datos enviados</div>
                <div class="smi-sub">Tu información fue recibida</div>
            </div>
        </div>
        <div class="smi">
            <div class="smi-dot wait">👁</div>
            <div>
                <div class="smi-lbl">En revisión</div>
                <div class="smi-sub">Verificando tus documentos</div>
            </div>
        </div>
        <div class="smi">
            <div class="smi-dot pend">3</div>
            <div>
                <div class="smi-lbl">Activación</div>
                <div class="smi-sub">Recibirás un correo de confirmación</div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <p style="font-size:.82rem; color:var(--gris); margin-bottom:1.2rem;">
        ¿Tienes dudas? Escríbenos a <strong style="color:var(--verde-oscuro);">soporte@goranch.mx</strong>
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline btn-full" style="border-radius:var(--r-sm);">Cerrar sesión</button>
    </form>
</div>
@endsection
