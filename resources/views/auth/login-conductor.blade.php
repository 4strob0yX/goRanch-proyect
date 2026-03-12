@extends('layouts.app')
@section('title', 'Acceso Conductor - goRanch')

@push('styles')
<style>
    body { background: #f3f1ec; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; }
    .top-bar { display: flex; align-items: center; justify-content: space-between; width: 100%; max-width: 480px; margin-bottom: 1.5rem; }
    .top-bar-brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; }
    .top-bar-brand svg { color: var(--verde); }
    .conductor-card {
        background: var(--blanco); border-radius: 24px; padding: 2.5rem 2rem;
        width: 100%; max-width: 480px; box-shadow: 0 8px 40px rgba(0,0,0,0.1);
    }
    .conductor-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: var(--verde-bg); display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.2rem;
    }
    .conductor-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.5rem; text-align: center; }
    .conductor-sub { color: var(--gris); text-align: center; margin-bottom: 1.8rem; font-size: .9rem; }
    .eye-btn { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gris); }
    .bottom-links { display: flex; justify-content: space-between; margin-top: 1.2rem; font-size: .875rem; }
    .bottom-links a { color: var(--verde); font-weight: 600; text-decoration: none; }
    .terms { text-align: center; margin-top: 1.5rem; font-size: .8rem; color: var(--gris); line-height: 1.6; }
    .terms a { color: var(--verde); text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="top-bar">
    <a href="{{ route('login') }}" style="color:var(--gris); text-decoration:none;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    </a>
    <div class="top-bar-brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
        goRanch
    </div>
    <a href="#" style="color:var(--gris); font-size:.9rem; text-decoration:none;">Ayuda</a>
</div>

<div class="conductor-card">
    <div class="conductor-icon">
        <svg width="30" height="30" fill="none" stroke="var(--verde-oscuro)" stroke-width="1.8" viewBox="0 0 24 24">
            <rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
        </svg>
    </div>
    <h1 class="conductor-title">Acceso de Conductor</h1>
    <p class="conductor-sub">Inicia sesión para ver tu ruta de hoy.</p>

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">ID de Conductor o Teléfono</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </span>
                <input type="text" name="email" class="form-input @error('email') error @enderror"
                    placeholder="Ej. 883-291 o 55-1234-5678" value="{{ old('email') }}">
            </div>
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </span>
                <input type="password" name="password" id="pass-conductor"
                    class="form-input @error('password') error @enderror" placeholder="Ingresa tu clave">
                <button type="button" class="eye-btn" onclick="document.getElementById('pass-conductor').type = document.getElementById('pass-conductor').type === 'password' ? 'text' : 'password'">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-dark btn-full" style="border-radius:12px; padding:1rem; font-size:1rem;">
            Entrar a Trabajar →
        </button>
    </form>

    <div class="bottom-links">
        <a href="#">¿Olvidaste tu contraseña?</a>
        <span style="color:var(--gris);">•</span>
        <span style="text-align:right; font-size:.875rem;">¿Nuevo conductor?<br><a href="{{ route('register') }}">Regístrate</a></span>
    </div>
</div>

<p class="terms">
    Al ingresar, aceptas los <a href="#">Términos de Servicio</a> de goRanch.<br>
    Conduce con cuidado.
</p>
@endsection
