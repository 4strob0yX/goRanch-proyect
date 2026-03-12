@extends('layouts.app')
@section('title', 'Registro Conductor - Paso 3')

@push('styles')
<style>
    body { background: var(--fondo); min-height: 100vh; display: flex; flex-direction: column; }
    .top-bar { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 2rem; background: var(--blanco); border-bottom: 1px solid var(--gris-claro); }
    .top-bar-brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; text-decoration: none; color: var(--texto); }

    .progress-bar-top { height: 4px; background: var(--gris-claro); }
    .progress-bar-fill { height: 100%; background: var(--verde-oscuro); width: 100%; }
    .progress-label-top { padding: .8rem 2rem; font-size: .85rem; color: var(--gris); display: flex; justify-content: space-between; }
    .progress-label-top span { color: var(--verde-oscuro); font-weight: 600; }

    .body-wrap { flex: 1; padding: 1.5rem 2rem 3rem; max-width: 600px; margin: 0 auto; width: 100%; }
    .page-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.8rem; margin-bottom: .3rem; }
    .page-sub { color: var(--gris); margin-bottom: 1.8rem; }

    /* Upload area */
    .upload-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); padding: 1.5rem; margin-bottom: 1rem; }
    .upload-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; margin-bottom: .3rem; display: flex; align-items: center; gap: .5rem; }
    .upload-sub { font-size: .8rem; color: var(--gris); margin-bottom: 1rem; }
    .upload-area {
        border: 2px dashed var(--gris-claro); border-radius: 12px; padding: 2rem 1rem;
        text-align: center; cursor: pointer; transition: all .2s; position: relative;
    }
    .upload-area:hover { border-color: var(--verde); background: var(--verde-bg); }
    .upload-area.has-file { border-color: var(--verde); background: var(--verde-bg); border-style: solid; }
    .upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-icon { width: 48px; height: 48px; border-radius: 50%; background: var(--verde-bg); display: flex; align-items: center; justify-content: center; margin: 0 auto .8rem; }
    .upload-area.has-file .upload-icon { background: var(--verde); }
    .upload-text strong { display: block; font-weight: 600; font-size: .9rem; }
    .upload-text span { font-size: .8rem; color: var(--gris); }
    .file-name { font-size: .85rem; color: var(--verde-oscuro); font-weight: 600; margin-top: .5rem; display: none; }

    .info-card { background: var(--verde-bg); border-radius: 12px; padding: 1rem 1.2rem; display: flex; gap: .7rem; margin-bottom: 1.5rem; }
    .info-icon { width: 22px; height: 22px; border-radius: 50%; background: var(--verde); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .info-text strong { font-size: .875rem; color: var(--verde-oscuro); display: block; font-weight: 700; }
    .info-text p { font-size: .8rem; color: var(--gris); margin-top: .2rem; line-height: 1.4; }

    .form-footer { display: flex; justify-content: space-between; align-items: center; }
    .btn-atras { background: none; border: none; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: .95rem; color: var(--gris); cursor: pointer; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('conductor.registro.paso2') }}" style="color:var(--gris);">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <a href="{{ route('login') }}" class="top-bar-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <a href="#" style="color:var(--gris); font-size:.9rem; text-decoration:none;">Ayuda</a>
</div>

<div class="progress-bar-top">
    <div class="progress-bar-fill"></div>
</div>
<div class="progress-label-top">
    <span>Paso 3 de 3</span>
    <span>Documentos</span>
</div>

<div class="body-wrap">
    <h1 class="page-title">Sube tus documentos</h1>
    <p class="page-sub">Necesitamos verificar tu identidad antes de activar tu cuenta.</p>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('conductor.registro.paso3.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Licencia --}}
        <div class="upload-card">
            <div class="upload-title">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                Licencia de Conducir
            </div>
            <p class="upload-sub">Foto clara del frente y reverso de tu licencia vigente.</p>

            <div class="upload-area" id="licencia-area">
                <input type="file" name="licencia" id="licencia-input" accept=".jpg,.jpeg,.png,.pdf"
                    onchange="handleFile('licencia')">
                <div class="upload-icon" id="licencia-icon">
                    <svg width="22" height="22" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div class="upload-text">
                    <strong>Subir licencia</strong>
                    <span>JPG, PNG o PDF • Máx. 5MB</span>
                </div>
                <div class="file-name" id="licencia-name"></div>
            </div>
            @error('licencia') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Seguro --}}
        <div class="upload-card">
            <div class="upload-title">
                <svg width="18" height="18" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Seguro del Vehículo
            </div>
            <p class="upload-sub">Póliza de seguro vigente de tu vehículo.</p>

            <div class="upload-area" id="seguro-area">
                <input type="file" name="seguro" id="seguro-input" accept=".jpg,.jpeg,.png,.pdf"
                    onchange="handleFile('seguro')">
                <div class="upload-icon" id="seguro-icon">
                    <svg width="22" height="22" fill="none" stroke="var(--verde-oscuro)" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div class="upload-text">
                    <strong>Subir seguro</strong>
                    <span>JPG, PNG o PDF • Máx. 5MB</span>
                </div>
                <div class="file-name" id="seguro-name"></div>
            </div>
            @error('seguro') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Info --}}
        <div class="info-card">
            <div class="info-icon">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="info-text">
                <strong>Revisión en 24-48 horas</strong>
                <p>Nuestro equipo revisará tus documentos. Te notificaremos por correo cuando tu cuenta esté activa.</p>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('conductor.registro.paso2') }}" class="btn-atras">← Atrás</a>
            <button type="submit" class="btn btn-primary" style="border-radius:12px; padding:.9rem 2rem;">
                Enviar y Finalizar ✓
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function handleFile(tipo) {
    const input = document.getElementById(tipo + '-input');
    const area = document.getElementById(tipo + '-area');
    const name = document.getElementById(tipo + '-name');
    const icon = document.getElementById(tipo + '-icon');

    if (input.files.length > 0) {
        area.classList.add('has-file');
        name.style.display = 'block';
        name.textContent = '✓ ' + input.files[0].name;
        icon.innerHTML = '<svg width="22" height="22" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
    }
}
</script>
@endpush
@endsection
