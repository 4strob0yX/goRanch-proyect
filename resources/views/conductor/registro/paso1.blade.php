@extends('layouts.app')
@section('title', 'Registro Conductor - Paso 1')

@push('styles')
<style>
    body { background: linear-gradient(180deg, #eef5ea 0%, #dce8f0 100%); min-height: 100vh; display: flex; flex-direction: column; }
    .top-bar { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 2rem; }
    .top-bar-brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; text-decoration: none; color: var(--texto); }
    .body-wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding: 1rem 2rem 3rem; }
    .card { background: var(--blanco); border-radius: 24px; padding: 2.5rem 2rem; width: 100%; max-width: 500px; box-shadow: 0 8px 40px rgba(0,0,0,.1); }

    .progress-wrap { margin-bottom: 2rem; }
    .progress-steps { display: flex; gap: .4rem; margin-bottom: .5rem; }
    .progress-step { flex: 1; height: 4px; border-radius: 999px; background: var(--gris-claro); }
    .progress-step.active { background: var(--verde-oscuro); }
    .progress-label { font-size: .8rem; color: var(--gris); }
    .progress-label span { color: var(--verde-oscuro); font-weight: 600; }

    .card-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.6rem; }
    .card-sub { color: var(--gris); font-size: .9rem; margin-bottom: 1.8rem; }

    .eye-btn { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gris); }
    .login-link { text-align: center; font-size: .9rem; color: var(--gris); margin-top: 1rem; }
    .login-link a { color: var(--verde); font-weight: 600; text-decoration: none; }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('login.conductor') }}" style="color:var(--gris);">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <a href="{{ route('login') }}" class="top-bar-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <a href="#" style="color:var(--gris); font-size:.9rem; text-decoration:none;">Ayuda</a>
</div>

<div class="body-wrap">
    <div class="card">
        <div class="progress-wrap">
            <div class="progress-steps">
                <div class="progress-step active"></div>
                <div class="progress-step"></div>
                <div class="progress-step"></div>
            </div>
            <p class="progress-label"><span>Paso 1 de 3</span> — Datos personales</p>
        </div>

        <h1 class="card-title">Únete como Conductor</h1>
        <p class="card-sub">Genera ingresos manejando en tu comunidad.</p>

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('conductor.registro.paso1.store') }}">
            @csrf

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="text" name="nombre" class="form-input @error('nombre') error @enderror"
                        placeholder="Nombre Completo" value="{{ old('nombre', session('registro_conductor.paso1.nombre')) }}">
                </div>
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input type="email" name="email" class="form-input @error('email') error @enderror"
                        placeholder="Correo Electrónico" value="{{ old('email', session('registro_conductor.paso1.email')) }}">
                </div>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    </span>
                    <input type="tel" name="telefono" class="form-input @error('telefono') error @enderror"
                        placeholder="Número de Teléfono" value="{{ old('telefono', session('registro_conductor.paso1.telefono')) }}">
                </div>
                @error('telefono') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </span>
                    <input type="password" name="password" id="pass1"
                        class="form-input @error('password') error @enderror" placeholder="Contraseña">
                    <button type="button" class="eye-btn" onclick="document.getElementById('pass1').type = document.getElementById('pass1').type === 'password' ? 'text' : 'password'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </span>
                    <input type="password" name="password_confirmation" id="pass2"
                        class="form-input" placeholder="Confirmar Contraseña">
                    <button type="button" class="eye-btn" onclick="document.getElementById('pass2').type = document.getElementById('pass2').type === 'password' ? 'text' : 'password'">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="border-radius:12px; padding:1rem; font-size:1rem;">
                Continuar →
            </button>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="{{ route('login.conductor') }}">Inicia sesión</a>
        </div>
    </div>
</div>
@endsection
