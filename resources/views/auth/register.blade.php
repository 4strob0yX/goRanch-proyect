@extends('layouts.app')
@section('title', 'Registro - goRanch')

@push('styles')
<style>
    body { background: var(--fondo); min-height: 100vh; display: flex; flex-direction: column; }

    .reg-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.5rem;
        background: var(--blanco); border-bottom: 1px solid var(--borde);
    }
    .reg-brand { display: flex; align-items: center; gap: .5rem; font-family: var(--font-display); font-weight: 700; color: var(--verde-oscuro); text-decoration: none; font-size: 1.1rem; }
    .reg-login-link { font-size: .875rem; color: var(--gris); }
    .reg-login-link a { color: var(--verde); font-weight: 600; text-decoration: none; }

    .reg-body { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
    .reg-card { background: var(--blanco); border-radius: var(--r-xl); box-shadow: var(--sombra-lg); padding: 2.5rem 2rem; width: 100%; max-width: 460px; }

    .reg-title { font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; margin-bottom: .3rem; }
    .reg-sub { color: var(--gris); font-size: .875rem; margin-bottom: 2rem; }

    .eye-btn { position: absolute; right: .9rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gris); padding: 0; }

    .conductor-cta {
        margin-top: 1.5rem; padding: 1rem;
        background: var(--verde-bg); border-radius: var(--r-md);
        border: 1px solid var(--verde-claro);
        text-align: center; font-size: .875rem; color: var(--texto-2);
    }
    .conductor-cta a { color: var(--verde-oscuro); font-weight: 700; text-decoration: none; }
</style>
@endpush

@section('content')
<header class="reg-header">
    <a href="{{ route('login') }}" class="reg-brand">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="var(--verde)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        goRanch
    </a>
    <p class="reg-login-link">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</header>

<div class="reg-body">
    <div class="reg-card">
        <h1 class="reg-title">Crea tu cuenta</h1>
        <p class="reg-sub">Empieza a pedir viajes y mandados en tu comunidad.</p>

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nombre completo</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                    <input type="text" name="nombre" class="form-input @error('nombre') error @enderror" placeholder="Tu nombre y apellido" value="{{ old('nombre') }}" autofocus>
                </div>
                @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                    <input type="email" name="email" class="form-input @error('email') error @enderror" placeholder="tu@correo.com" value="{{ old('email') }}">
                </div>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <div class="input-wrap">
                    <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg></span>
                    <input type="tel" name="telefono" class="form-input @error('telefono') error @enderror" placeholder="10 dígitos" value="{{ old('telefono') }}">
                </div>
                @error('telefono') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                        <input type="password" name="password" id="p1" class="form-input @error('password') error @enderror" placeholder="Mínimo 8 caracteres">
                        <button type="button" class="eye-btn" onclick="toggle('p1')"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                    </div>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar</label>
                    <div class="input-wrap">
                        <span class="input-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                        <input type="password" name="password_confirmation" id="p2" class="form-input" placeholder="Repite la contraseña">
                        <button type="button" class="eye-btn" onclick="toggle('p2')"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="border-radius:var(--r-sm); margin-top:.3rem;">
                Crear mi cuenta
            </button>
        </form>

        <div class="conductor-cta">
            🚗 ¿Quieres trabajar como conductor? <a href="{{ route('conductor.registro.paso1') }}">Regístrate aquí</a>
        </div>
    </div>
</div>

@push('scripts')
<script>function toggle(id){const el=document.getElementById(id);el.type=el.type==='password'?'text':'password';}</script>
@endpush
@endsection
