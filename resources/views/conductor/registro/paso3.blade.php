@extends('layouts.app')
@section('title', 'Registro Conductor - Paso 3')

@push('styles')
<style>
    body { background: var(--fondo); }
    .reg-wrap { max-width: 480px; margin: 0 auto; padding: 1.5rem; }

    .prog-header { text-align: center; margin-bottom: 2rem; padding-top: 1rem; }
    .prog-brand { font-family: var(--font-display); font-weight: 700; color: var(--verde-oscuro); font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; gap: .5rem; }
    .prog-steps { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 1rem; }
    .pstep { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .82rem; border: 2px solid var(--borde); background: var(--blanco); color: var(--gris); }
    .pstep.done { background: var(--verde); border-color: var(--verde); color: white; }
    .pstep.active { background: var(--verde-oscuro); border-color: var(--verde-oscuro); color: white; }
    .pline { width: 40px; height: 2px; }
    .pline.done { background: var(--verde); }
    .pline { background: var(--borde); }
    .prog-label { font-size: .82rem; color: var(--gris); }
    .prog-label strong { color: var(--texto); }

    .step-card { background: var(--blanco); border-radius: var(--r-xl); border: 1px solid var(--borde); padding: 2rem; box-shadow: var(--sombra-sm); }
    .step-title { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; margin-bottom: .3rem; }
    .step-sub { color: var(--gris); font-size: .875rem; margin-bottom: 1.5rem; }

    .doc-item { border: 1.5px dashed var(--borde); border-radius: var(--r-md); padding: 1rem; margin-bottom: .8rem; display: flex; align-items: center; gap: .9rem; cursor: pointer; transition: all .15s; position: relative; }
    .doc-item:hover { border-color: var(--verde-claro); background: var(--verde-bg); }
    .doc-item.uploaded { border-color: var(--verde); border-style: solid; background: var(--verde-bg); }
    .doc-ico { width: 40px; height: 40px; border-radius: var(--r-sm); background: var(--fondo); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
    .doc-name { font-weight: 600; font-size: .875rem; }
    .doc-hint { font-size: .75rem; color: var(--gris); margin-top: .15rem; }
    .doc-status { margin-left: auto; font-size: .8rem; color: var(--gris); }
    .doc-status.ok { color: var(--verde); font-weight: 700; }
    .doc-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

    .info-box { background: var(--amarillo-bg); border: 1px solid #fde68a; border-radius: var(--r-sm); padding: .85rem 1rem; font-size: .82rem; color: #92400e; margin-bottom: 1.2rem; display: flex; gap: .6rem; align-items: flex-start; }
</style>
@endpush

@section('content')
<div class="reg-wrap">
    <div class="prog-header">
        <div class="prog-brand">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
            goRanch
        </div>
        <div class="prog-steps">
            <div class="pstep done">✓</div>
            <div class="pline done"></div>
            <div class="pstep done">✓</div>
            <div class="pline done"></div>
            <div class="pstep active">3</div>
        </div>
        <div class="prog-label">Paso <strong>3 de 3</strong> — Documentos</div>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem;">{{ $errors->first() }}</div>
    @endif

    <div class="step-card">
        <h2 class="step-title">Tus documentos</h2>
        <p class="step-sub">Sube fotos claras y legibles. Los revisaremos en menos de 24 horas.</p>

        <div class="info-box">
            <span>⚠️</span>
            <span>Asegúrate de que los documentos sean vigentes y la foto sea clara. Documentos borrosos serán rechazados.</span>
        </div>

        <form method="POST" action="{{ route('conductor.registro.paso3.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="doc-item" id="di-ine" onclick="document.getElementById('f-ine').click()">
                <div class="doc-ico">🪪</div>
                <div>
                    <div class="doc-name">INE / Identificación oficial</div>
                    <div class="doc-hint">Frente y vuelta visible</div>
                </div>
                <div class="doc-status" id="st-ine">Subir</div>
                <input type="file" name="ine" id="f-ine" class="doc-input" accept="image/*,.pdf" onchange="markDoc('ine')">
            </div>

            <div class="doc-item" id="di-licencia" onclick="document.getElementById('f-licencia').click()">
                <div class="doc-ico">📋</div>
                <div>
                    <div class="doc-name">Licencia de conducir</div>
                    <div class="doc-hint">Vigente · Categoría correspondiente</div>
                </div>
                <div class="doc-status" id="st-licencia">Subir</div>
                <input type="file" name="licencia" id="f-licencia" class="doc-input" accept="image/*,.pdf" onchange="markDoc('licencia')">
            </div>

            <div class="doc-item" id="di-circulacion" onclick="document.getElementById('f-circulacion').click()">
                <div class="doc-ico">🚘</div>
                <div>
                    <div class="doc-name">Tarjeta de circulación</div>
                    <div class="doc-hint">Del vehículo registrado</div>
                </div>
                <div class="doc-status" id="st-circulacion">Subir</div>
                <input type="file" name="tarjeta_circulacion" id="f-circulacion" class="doc-input" accept="image/*,.pdf" onchange="markDoc('circulacion')">
            </div>

            @error('ine') <p class="form-error">{{ $message }}</p> @enderror
            @error('licencia') <p class="form-error">{{ $message }}</p> @enderror
            @error('tarjeta_circulacion') <p class="form-error">{{ $message }}</p> @enderror

            <div style="display:flex; gap:.7rem; margin-top:1rem;">
                <a href="{{ route('conductor.registro.paso2') }}" class="btn btn-outline btn-lg" style="border-radius:var(--r-sm); flex:1;">← Atrás</a>
                <button type="submit" class="btn btn-primary btn-lg" style="border-radius:var(--r-sm); flex:2;">Enviar solicitud ✓</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function markDoc(key) {
    const el = document.getElementById('di-' + key);
    const st = document.getElementById('st-' + key);
    const input = document.getElementById('f-' + key);
    if(input.files.length > 0) {
        el.classList.add('uploaded');
        st.textContent = '✓ Listo';
        st.classList.add('ok');
    }
}
</script>
@endpush
@endsection
