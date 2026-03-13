@extends('layouts.app')
@section('title', 'Registro Conductor - Paso 1')

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

    .back-link { display: flex; align-items: center; gap: .4rem; color: var(--gris); text-decoration: none; font-size: .875rem; margin-bottom: 1.5rem; }
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
            <div class="pstep active">1</div>
            <div class="pline"></div>
            <div class="pstep">2</div>
            <div class="pline"></div>
            <div class="pstep">3</div>
        </div>
        <div class="prog-label">Paso <strong>1 de 3</strong> — Datos personales</div>
    </div>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem;">{{ $errors->first() }}</div>
    @endif

    <div class="step-card">
        <h2 class="step-title">¿Quién eres?</h2>
        <p class="step-sub">Cuéntanos sobre ti. Tus datos serán verificados.</p>

        <form method="POST" action="{{ route('conductor.registro.paso1.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre completo</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                    <input type="text" name="nombre" class="form-input @error('nombre') error @enderror" placeholder="Nombre y apellido" value="{{ old('nombre', session('conductor_reg.nombre')) }}" autofocus>
                </div>
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                    <input type="email" name="email" class="form-input @error('email') error @enderror" placeholder="tu@correo.com" value="{{ old('email', session('conductor_reg.email')) }}">
                </div>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2A19.79 19.79 0 013.07 9.81a2 2 0 012-2.18H8a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 15a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></span>
                    <input type="tel" name="telefono" class="form-input @error('telefono') error @enderror" placeholder="10 dígitos" value="{{ old('telefono', session('conductor_reg.telefono')) }}">
                </div>
                @error('telefono') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipo de vehículo</label>
                <div class="radio-group">
                    <label class="radio-card" id="rv-moto" onclick="selectVehiculo('moto')">
                        <input type="radio" name="tipo_vehiculo" value="moto" {{ old('tipo_vehiculo', session('conductor_reg.tipo_vehiculo')) === 'moto' ? 'checked' : '' }}>
                        <div class="radio-ico">🏍️</div>
                        <div class="radio-lbl">Moto</div>
                    </label>
                    <label class="radio-card" id="rv-bici" onclick="selectVehiculo('bici')">
                        <input type="radio" name="tipo_vehiculo" value="bici" {{ old('tipo_vehiculo', session('conductor_reg.tipo_vehiculo')) === 'bici' ? 'checked' : '' }}>
                        <div class="radio-ico">🚲</div>
                        <div class="radio-lbl">Bicicleta</div>
                    </label>
                    <label class="radio-card" id="rv-auto" onclick="selectVehiculo('auto')">
                        <input type="radio" name="tipo_vehiculo" value="auto" {{ old('tipo_vehiculo', session('conductor_reg.tipo_vehiculo')) === 'auto' ? 'checked' : '' }}>
                        <div class="radio-ico">🚗</div>
                        <div class="radio-lbl">Auto</div>
                    </label>
                </div>
                @error('tipo_vehiculo') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                        <input type="password" name="password" class="form-input @error('password') error @enderror" placeholder="Mínimo 8 caracteres">
                    </div>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Repite">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="border-radius:var(--r-sm);">Continuar →</button>
        </form>
    </div>

    <p style="text-align:center; font-size:.82rem; color:var(--gris); margin-top:1.2rem;">
        ¿Ya tienes cuenta? <a href="{{ route('login.conductor') }}" style="color:var(--verde); font-weight:600; text-decoration:none;">Inicia sesión</a>
    </p>
</div>

@push('scripts')
<script>
function selectVehiculo(tipo) {
    ['moto','bici','auto'].forEach(t => {
        document.getElementById('rv-'+t).classList.toggle('selected', t === tipo);
    });
}
// Restaurar selección previa
const prev = '{{ old("tipo_vehiculo", session("conductor_reg.tipo_vehiculo")) }}';
if(prev) selectVehiculo(prev);
</script>
@endpush
@endsection
