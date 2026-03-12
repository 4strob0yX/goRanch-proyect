@extends('layouts.app')
@section('title', 'Iniciar Sesión - goRanch')

@push('styles')
<style>
    body { background: #f3f1ec; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .login-card {
        background: var(--blanco);
        border-radius: 24px;
        padding: 2rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.1);
    }
    .login-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.6rem; text-align: center; margin-bottom: 1.2rem; }
    .login-hero {
        width: 100%; height: 160px; border-radius: 14px; object-fit: cover;
        background: linear-gradient(135deg, #c8dfc0, #a8c98a);
        margin-bottom: 1.5rem;
        display: block;
        overflow: hidden;
        position: relative;
    }
    .login-hero-inner {
        width: 100%; height: 100%;
        background: linear-gradient(160deg, #b5d4a0 0%, #7db55e 50%, #4a8a30 100%);
        display: flex; align-items: center; justify-content: center;
    }
    .login-hero-inner svg { opacity: .25; }
    .login-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.4rem; text-align: center; }
    .login-sub { color: var(--gris); text-align: center; font-size: .9rem; margin-bottom: 1.5rem; }
    .eye-btn { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gris); }
    .links-row { text-align: center; margin-top: 1rem; font-size: .9rem; color: var(--gris); }
    .links-row a { color: var(--verde); font-weight: 600; text-decoration: none; }
    .links-row a:hover { text-decoration: underline; }
    .divider { text-align: center; color: var(--gris); font-size: .85rem; margin: .8rem 0; }
</style>
@endpush

@section('content')
<div class="login-card">
    <div class="login-logo">goRanch</div>

    <div class="login-hero">
        <div class="login-hero-inner">
            <svg width="120" height="60" viewBox="0 0 120 60" fill="white">
                <path d="M0 40 Q30 10 60 30 Q90 50 120 20" stroke="white" stroke-width="3" fill="none"/>
                <circle cx="60" cy="30" r="8" fill="white"/>
            </svg>
        </div>
    </div>

    <h1 class="login-title">Bienvenido de nuevo</h1>
    <p class="login-sub">Ingresa tus datos para continuar tu viaje</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Correo</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                </span>
                <input type="email" name="email" class="form-input @error('email') error @enderror"
                    placeholder="Ingresa tu correo" value="{{ old('email') }}" autocomplete="username">
            </div>
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Contraseña</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </span>
                <input type="password" name="password" id="password-field"
                    class="form-input @error('password') error @enderror"
                    placeholder="••••••••" autocomplete="current-password">
                <button type="button" class="eye-btn" onclick="togglePassword()">
                    <svg id="eye-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="border-radius:12px; padding:1rem;">
            Iniciar Sesión
        </button>
    </form>

    <div class="links-row mt-2">
        <a href="#">Olvidé mi contraseña</a>
    </div>
    <div class="links-row mt-1">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
    </div>
    <div class="divider">— o —</div>
    <div class="links-row">
        ¿Eres conductor? <a href="{{ route('login.conductor') }}">Acceso conductor</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const f = document.getElementById('password-field');
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
