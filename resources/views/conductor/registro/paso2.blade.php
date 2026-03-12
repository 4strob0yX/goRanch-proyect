@extends('layouts.app')
@section('title', 'Registro Conductor - Paso 2')

@push('styles')
<style>
    body { background: var(--fondo); min-height: 100vh; display: flex; flex-direction: column; }
    .top-bar { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 2rem; background: var(--blanco); border-bottom: 1px solid var(--gris-claro); }
    .top-bar-brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; text-decoration: none; color: var(--texto); }

    .progress-bar-top { height: 4px; background: var(--gris-claro); }
    .progress-bar-fill { height: 100%; background: var(--verde-oscuro); width: 66%; transition: width .3s; }

    .progress-label-top { padding: .8rem 2rem; font-size: .85rem; color: var(--gris); display: flex; justify-content: space-between; }
    .progress-label-top span { color: var(--verde-oscuro); font-weight: 600; }

    .body-wrap { flex: 1; padding: 1.5rem 2rem 3rem; max-width: 700px; margin: 0 auto; width: 100%; }
    .page-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.8rem; margin-bottom: .3rem; }
    .page-sub { color: var(--gris); margin-bottom: 1.8rem; }

    /* Tipo vehículo */
    .vehiculo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
    .vehiculo-option { position: relative; }
    .vehiculo-option input[type="radio"] { position: absolute; opacity: 0; }
    .vehiculo-card {
        border: 2px solid var(--gris-claro); border-radius: 16px; padding: 1.3rem;
        cursor: pointer; transition: all .2s; display: block;
    }
    .vehiculo-option input:checked + .vehiculo-card {
        border-color: var(--verde-oscuro); background: var(--verde-bg);
    }
    .vehiculo-card:hover { border-color: var(--verde); }
    .vehiculo-icon { width: 44px; height: 44px; border-radius: 50%; background: var(--verde-bg); display: flex; align-items: center; justify-content: center; margin-bottom: .8rem; }
    .vehiculo-option input:checked + .vehiculo-card .vehiculo-icon { background: rgba(255,255,255,.7); }
    .vehiculo-card h3 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; margin-bottom: .3rem; }
    .vehiculo-card p { font-size: .8rem; color: var(--gris); line-height: 1.4; }

    /* Detalles */
    .detalles-card { background: var(--blanco); border-radius: 16px; box-shadow: var(--sombra); padding: 1.5rem; margin-bottom: 1.2rem; }
    .detalles-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; margin-bottom: 1.2rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    /* Warning */
    .warning-card { background: #fef3c7; border-radius: 12px; padding: 1rem 1.2rem; display: flex; gap: .8rem; margin-bottom: 1.5rem; }
    .warning-icon { width: 22px; height: 22px; border-radius: 50%; background: #f59e0b; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: .1rem; }
    .warning-text strong { font-size: .875rem; font-weight: 700; color: #92400e; display: block; }
    .warning-text p { font-size: .8rem; color: #92400e; margin-top: .2rem; line-height: 1.4; }

    /* Footer */
    .form-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; }
    .btn-atras { background: none; border: none; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: .95rem; color: var(--gris); cursor: pointer; }
    .btn-atras:hover { color: var(--texto); }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('conductor.registro.paso1') }}" class="top-bar-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <a href="#" style="color:var(--gris); font-size:.9rem; text-decoration:none;">Ayuda</a>
</div>

<div class="progress-bar-top">
    <div class="progress-bar-fill"></div>
</div>
<div class="progress-label-top">
    <span>Paso 2 de 3</span>
    <span>Información del vehículo</span>
</div>

<div class="body-wrap">
    <h1 class="page-title">Registra tu vehículo</h1>
    <p class="page-sub">Elige el tipo de vehículo que usarás para realizar entregas en goRanch.</p>

    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem;">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('conductor.registro.paso2.store') }}">
        @csrf

        {{-- Tipo de vehículo --}}
        <div class="vehiculo-grid">
            <label class="vehiculo-option">
                <input type="radio" name="tipo_vehiculo" value="moto"
                    {{ old('tipo_vehiculo', session('registro_conductor.paso2.tipo_vehiculo')) === 'moto' ? 'checked' : '' }}>
                <span class="vehiculo-card">
                    <div class="vehiculo-icon">
                        <svg width="22" height="22" fill="none" stroke="var(--verde-oscuro)" stroke-width="1.8" viewBox="0 0 24 24"><path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3"/><circle cx="13" cy="17" r="3"/><circle cx="3" cy="17" r="2"/></svg>
                    </div>
                    <h3>Motocicleta</h3>
                    <p>Ideal para distancias medias y rápidas entregas rurales.</p>
                </span>
            </label>

            <label class="vehiculo-option">
                <input type="radio" name="tipo_vehiculo" value="bici"
                    {{ old('tipo_vehiculo', session('registro_conductor.paso2.tipo_vehiculo')) === 'bici' ? 'checked' : '' }}>
                <span class="vehiculo-card">
                    <div class="vehiculo-icon" style="background:var(--gris-claro);">
                        <svg width="22" height="22" fill="none" stroke="var(--gris)" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/><path d="M12 17V7l-7 5h7"/></svg>
                    </div>
                    <h3>Bicicleta / Triciclo</h3>
                    <p>Perfecto para distancias cortas dentro del pueblo.</p>
                </span>
            </label>
        </div>
        @error('tipo_vehiculo') <p class="form-error" style="margin-top:-.8rem; margin-bottom:1rem;">{{ $message }}</p> @enderror

        {{-- Detalles del vehículo --}}
        <div class="detalles-card">
            <h3 class="detalles-title">Detalles del vehículo</h3>

            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="modelo" class="form-input no-icon @error('modelo') error @enderror"
                        placeholder="Modelo del vehículo"
                        value="{{ old('modelo', session('registro_conductor.paso2.modelo')) }}">
                    @error('modelo') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <input type="text" name="placa" class="form-input no-icon @error('placa') error @enderror"
                        placeholder="Placa / Matrícula"
                        value="{{ old('placa', session('registro_conductor.paso2.placa')) }}">
                    @error('placa') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-group">
                <input type="text" name="marca" class="form-input no-icon @error('marca') error @enderror"
                    placeholder="Marca del vehículo"
                    value="{{ old('marca', session('registro_conductor.paso2.marca')) }}">
                @error('marca') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Warning --}}
        <div class="warning-card">
            <div class="warning-icon">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="warning-text">
                <strong>Requisito importante</strong>
                <p>Te pediremos fotos de tu vehículo y tarjeta de circulación en el siguiente paso. Asegúrate de tenerlos a la mano.</p>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('conductor.registro.paso1') }}" class="btn-atras">← Atrás</a>
            <button type="submit" class="btn btn-dark" style="border-radius:12px; padding:.9rem 2rem;">
                Siguiente →
            </button>
        </div>
    </form>
</div>
@endsection
