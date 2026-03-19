@extends('layouts.app')
@section('title', 'Registro Conductor - Paso 2')

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
    .pline { width: 40px; height: 2px; background: var(--borde); }
    .pline.done { background: var(--verde); }
    .prog-label { font-size: .82rem; color: var(--gris); }
    .prog-label strong { color: var(--texto); }

    .step-card { background: var(--blanco); border-radius: var(--r-xl); border: 1px solid var(--borde); padding: 2rem; box-shadow: var(--sombra-sm); }
    .step-title { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; margin-bottom: .3rem; }
    .step-sub { color: var(--gris); font-size: .875rem; margin-bottom: 1.5rem; }

    .radio-group { display: grid; grid-template-columns: repeat(3, 1fr); gap: .7rem; margin-bottom: 1.1rem; }
    .radio-card { border: 2px solid var(--borde); border-radius: var(--r-md); padding: .9rem .5rem; text-align: center; cursor: pointer; transition: all .15s; }
    .radio-card:hover { border-color: var(--verde-claro); background: var(--verde-bg); }
    .radio-card.selected { border-color: var(--verde); background: var(--verde-bg); }
    .radio-card input { display: none; }
    .radio-ico { font-size: 1.6rem; margin-bottom: .3rem; }
    .radio-lbl { font-weight: 600; font-size: .82rem; }
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
            <div class="pstep active">2</div>
            <div class="pline"></div>
            <div class="pstep">3</div>
        </div>
        <div class="prog-label">Paso <strong>2 de 3</strong> — Datos del vehículo</div>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem;">{{ $errors->first() }}</div>
    @endif

    <div class="step-card">
        <h2 class="step-title">Tu vehículo</h2>
        <p class="step-sub">Información del vehículo con el que darás el servicio.</p>

        <form method="POST" action="{{ route('conductor.registro.paso2.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Tipo de vehículo</label>
                <div class="radio-group">
                    <label class="radio-card" id="rv-moto" onclick="selectVehiculo('moto')">
                        <input type="radio" name="tipo_vehiculo" value="moto" {{ old('tipo_vehiculo', session('registro_conductor.paso2.tipo_vehiculo')) === 'moto' ? 'checked' : '' }}>
                        <div class="radio-ico">🏍️</div>
                        <div class="radio-lbl">Moto</div>
                    </label>
                    <label class="radio-card" id="rv-bici" onclick="selectVehiculo('bici')">
                        <input type="radio" name="tipo_vehiculo" value="bici" {{ old('tipo_vehiculo', session('registro_conductor.paso2.tipo_vehiculo')) === 'bici' ? 'checked' : '' }}>
                        <div class="radio-ico">🚲</div>
                        <div class="radio-lbl">Bicicleta</div>
                    </label>
                    <label class="radio-card" id="rv-auto" onclick="selectVehiculo('auto')">
                        <input type="radio" name="tipo_vehiculo" value="auto" {{ old('tipo_vehiculo', session('registro_conductor.paso2.tipo_vehiculo')) === 'auto' ? 'checked' : '' }}>
                        <div class="radio-ico">🚗</div>
                        <div class="radio-lbl">Auto</div>
                    </label>
                </div>
                @error('tipo_vehiculo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Marca</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg></span>
                        <input type="text" name="marca" class="form-input @error('marca') error @enderror" placeholder="Ej: Honda, Yamaha" value="{{ old('marca', session('conductor_reg.marca')) }}">
                    </div>
                    @error('marca') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Modelo</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg></span>
                        <input type="text" name="modelo" class="form-input @error('modelo') error @enderror" placeholder="Ej: CB300, Avenger" value="{{ old('modelo', session('conductor_reg.modelo')) }}">
                    </div>
                    @error('modelo') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Número de placa</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M6 12h.01M10 12h4M18 12h.01"/></svg></span>
                    <input type="text" name="placa" class="form-input @error('placa') error @enderror" placeholder="ABC-123-D" value="{{ old('placa', session('conductor_reg.placa')) }}" style="text-transform:uppercase;">
                </div>
                @error('placa') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:.7rem; margin-top:.5rem;">
                <a href="{{ route('conductor.registro.paso1') }}" class="btn btn-outline btn-lg" style="border-radius:var(--r-sm); flex:1;">← Atrás</a>
                <button type="submit" class="btn btn-primary btn-lg" style="border-radius:var(--r-sm); flex:2;">Continuar →</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function selectVehiculo(tipo) {
    ['moto','bici','auto'].forEach(t => {
        document.getElementById('rv-'+t).classList.toggle('selected', t === tipo);
    });
}
const prev = '{{ old("tipo_vehiculo", session("registro_conductor.paso2.tipo_vehiculo")) }}';
if(prev) selectVehiculo(prev);
</script>
@endpush
@endsection
