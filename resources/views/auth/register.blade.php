@extends('layouts.app')
@section('title', 'Crear Cuenta - goRanch')

@push('styles')
<style>
    body { background: linear-gradient(180deg, #eef5ea 0%, #dce8f0 100%); min-height: 100vh; display: flex; flex-direction: column; }
    .register-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.2rem 2rem; background: transparent;
    }
    .register-header .brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; text-decoration: none; color: var(--texto); }
    .register-body { flex: 1; display: flex; align-items: center; justify-content: center; padding: 1rem 2rem 3rem; }
    .register-card {
        background: var(--blanco); border-radius: 24px; padding: 2.5rem 2rem;
        width: 100%; max-width: 500px; box-shadow: 0 8px 40px rgba(0,0,0,0.1);
    }
    /* Progress bar */
    .progress-bar {
        display: flex; gap: .4rem; margin-bottom: 2rem;
    }
    .progress-step {
        flex: 1; height: 4px; border-radius: 999px; background: var(--gris-claro);
    }
    .progress-step.active { background: var(--verde); }

    .register-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.6rem; }
    .register-step-label { color: var(--verde); font-weight: 600; font-size: .875rem; }
    .register-sub { color: var(--gris); font-size: .9rem; margin-bottom: 1.8rem; }

    .select-input { width: 100%; padding: .8rem 1rem .8rem 2.8rem; border: 1.5px solid var(--gris-claro); border-radius: var(--radio-sm); font-family: 'DM Sans', sans-serif; font-size: .95rem; background: var(--blanco); color: var(--texto); outline: none; appearance: none; cursor: pointer; }
    .select-input:focus { border-color: var(--verde); }

    .terms-check { display: flex; align-items: flex-start; gap: .75rem; font-size: .9rem; color: var(--gris); margin-bottom: 1.5rem; }
    .terms-check input[type="checkbox"] { width: 18px; height: 18px; min-width: 18px; border-radius: 4px; cursor: pointer; accent-color: var(--verde); margin-top: 2px; }
    .terms-check a { color: var(--verde); font-weight: 600; text-decoration: underline; }

    .login-link { text-align: center; font-size: .9rem; color: var(--gris); margin-top: 1rem; }
    .login-link a { color: var(--verde); font-weight: 600; text-decoration: none; }
</style>
@endpush

@section('content')
<header class="register-header">
    <a href="{{ route('login') }}" style="color:var(--gris);">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <a href="{{ route('login') }}" class="brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <a href="#" style="color:var(--gris); font-size:.9rem; text-decoration:none;">Ayuda</a>
</header>

<div class="register-body">
    <div class="register-card">
        <div class="progress-bar">
            <div class="progress-step active"></div>
            <div class="progress-step"></div>
            <div class="progress-step"></div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:.3rem;">
            <h1 class="register-title">Crear Cuenta</h1>
            <span class="register-step-label">Paso 1 de 3</span>
        </div>
        <p class="register-sub">Únete a goRanch para viajar seguro y fácil.</p>

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input type="text" name="nombre" class="form-input @error('nombre') error @enderror"
                        placeholder="Nombre Completo" value="{{ old('nombre') }}">
                </div>
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                    </span>
                    <input type="tel" name="telefono" class="form-input @error('telefono') error @enderror"
                        placeholder="Número de Celular" value="{{ old('telefono') }}">
                </div>
                @error('telefono') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                    </span>
                    <select name="comunidad" class="select-input">
                        <option value="" disabled selected>Tu Comunidad / Localidad</option>
                        <option>Rancho El Girasol</option>
                        <option>San Francisco</option>
                        <option>Guadalupe de las Corrientes</option>
                        <option>La Cajetilla</option>
                        <option>Otra</option>
                    </select>
                    <span style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--gris);">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    </span>
                </div>
            </div>

            {{-- Campo email oculto (requerido por el sistema) --}}
           <div class="form-group">
    <div class="input-wrap">
        <span class="input-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </span>
        <input type="email" name="email" class="form-input @error('email') error @enderror"
            placeholder="Correo Electrónico" value="{{ old('email') }}">
    </div>
    @error('email') <p class="form-error">{{ $message }}</p> @enderror
</div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </span>
                    <input type="password" name="password" class="form-input @error('password') error @enderror" placeholder="Contraseña">
                </div>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </span>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Confirmar Contraseña">
                </div>
            </div>

            <div class="terms-check">
                <input type="checkbox" name="terminos" id="terminos" required>
                <label for="terminos">
                    Acepto los <a href="#">términos y condiciones</a> y la política de privacidad.
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="border-radius:12px; padding:1rem; font-size:1rem;">
                Registrarme →
            </button>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </div>
    </div>
</div>
@endsection
