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
            <span>Asegúrate de que los documentos sean vigentes y la foto sea clara. Máximo 900KB por archivo. Las imágenes se comprimen automáticamente.</span>
        </div>

        <form id="doc-form" method="POST" action="{{ route('conductor.registro.paso3.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="doc-item" id="di-ine">
                <div class="doc-ico">🪪</div>
                <div>
                    <div class="doc-name">INE / Identificación oficial</div>
                    <div class="doc-hint">Frente y vuelta visible · Máx 900KB</div>
                </div>
                <div class="doc-status" id="st-ine">Subir</div>
                <input type="file" id="f-ine" class="doc-input" accept="image/*,.pdf" onchange="handleFile('ine', this)">
            </div>

            <div class="doc-item" id="di-licencia">
                <div class="doc-ico">📋</div>
                <div>
                    <div class="doc-name">Licencia de conducir</div>
                    <div class="doc-hint">Vigente · Máx 900KB</div>
                </div>
                <div class="doc-status" id="st-licencia">Subir</div>
                <input type="file" id="f-licencia" class="doc-input" accept="image/*,.pdf" onchange="handleFile('licencia', this)">
            </div>

            <div class="doc-item" id="di-circulacion">
                <div class="doc-ico">🚘</div>
                <div>
                    <div class="doc-name">Tarjeta de circulación</div>
                    <div class="doc-hint">Del vehículo registrado · Máx 900KB</div>
                </div>
                <div class="doc-status" id="st-circulacion">Subir</div>
                <input type="file" id="f-circulacion" class="doc-input" accept="image/*,.pdf" onchange="handleFile('circulacion', this)">
            </div>

            @error('ine') <p class="form-error">{{ $message }}</p> @enderror
            @error('licencia') <p class="form-error">{{ $message }}</p> @enderror
            @error('tarjeta_circulacion') <p class="form-error">{{ $message }}</p> @enderror

            <div style="display:flex; gap:.7rem; margin-top:1rem;">
                <a href="{{ route('conductor.registro.paso2') }}" class="btn btn-outline btn-lg" style="border-radius:var(--r-sm); flex:1;">← Atrás</a>
                <button type="submit" id="btn-submit" class="btn btn-primary btn-lg" style="border-radius:var(--r-sm); flex:2;">Enviar solicitud ✓</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const MAX_KB = 900;
const compressedFiles = {}; // { ine: File, licencia: File, circulacion: File }
const fieldMap = { ine: 'ine', licencia: 'licencia', circulacion: 'tarjeta_circulacion' };

function markStatus(key, text, ok) {
    const el = document.getElementById('di-' + key);
    const st = document.getElementById('st-' + key);
    if (ok) { el.classList.add('uploaded'); st.classList.add('ok'); }
    else { el.classList.remove('uploaded'); st.classList.remove('ok'); }
    st.textContent = text;
}

function handleFile(key, input) {
    if (!input.files.length) return;
    const file = input.files[0];

    // PDF: solo validar tamaño
    if (file.type === 'application/pdf') {
        if (file.size > MAX_KB * 1024) {
            alert('El PDF supera 900KB. Intenta con una imagen o un PDF más ligero.');
            input.value = '';
            markStatus(key, 'Subir', false);
            delete compressedFiles[key];
            return;
        }
        compressedFiles[key] = file;
        markStatus(key, '✓ ' + (file.size / 1024).toFixed(0) + 'KB', true);
        return;
    }

    // Imagen: comprimir con canvas
    markStatus(key, 'Comprimiendo...', false);
    compressImage(file, MAX_KB).then(blob => {
        const compressed = new File([blob], file.name.replace(/\.\w+$/, '.jpg'), { type: 'image/jpeg' });
        compressedFiles[key] = compressed;
        markStatus(key, '✓ ' + (compressed.size / 1024).toFixed(0) + 'KB', true);
    }).catch(() => {
        markStatus(key, 'Error', false);
        alert('No se pudo comprimir la imagen. Intenta con otra.');
    });
}

function compressImage(file, maxKB) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = () => {
            URL.revokeObjectURL(url);
            const canvas = document.createElement('canvas');
            let w = img.width, h = img.height;
            const MAX_DIM = 1200;
            if (w > MAX_DIM || h > MAX_DIM) {
                const ratio = Math.min(MAX_DIM / w, MAX_DIM / h);
                w = Math.round(w * ratio);
                h = Math.round(h * ratio);
            }
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(img, 0, 0, w, h);

            let quality = 0.7;
            (function tryCompress() {
                canvas.toBlob(blob => {
                    if (!blob) return reject();
                    if (blob.size <= maxKB * 1024 || quality <= 0.2) return resolve(blob);
                    quality -= 0.1;
                    tryCompress();
                }, 'image/jpeg', quality);
            })();
        };
        img.onerror = reject;
        img.src = url;
    });
}

// Interceptar submit: enviar con FormData usando archivos comprimidos
document.getElementById('doc-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const keys = ['ine', 'licencia', 'circulacion'];
    const missing = keys.filter(k => !compressedFiles[k]);
    if (missing.length) {
        alert('Faltan documentos por subir: ' + missing.join(', '));
        return;
    }

    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.textContent = 'Enviando...';

    const fd = new FormData();
    fd.append('_token', document.querySelector('input[name="_token"]').value);
    for (const key of keys) {
        fd.append(fieldMap[key], compressedFiles[key]);
    }

    fetch(this.action, {
        method: 'POST',
        body: fd,
        headers: { 'Accept': 'text/html' },
        redirect: 'follow'
    }).then(resp => {
        if (resp.redirected) {
            window.location.href = resp.url;
        } else {
            return resp.text().then(html => { document.open(); document.write(html); document.close(); });
        }
    }).catch(() => {
        alert('Error de conexión. Intenta de nuevo.');
        btn.disabled = false;
        btn.textContent = 'Enviar solicitud ✓';
    });
});
</script>
@endpush
@endsection
