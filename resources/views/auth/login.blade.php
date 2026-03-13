@extends('layouts.app')
@section('title', 'Iniciar Sesión - goRanch')

@push('styles')
<style>
    body { display: flex; min-height: 100vh; }

    .login-left {
        width: 420px; flex-shrink: 0;
        background: var(--verde-oscuro);
        display: flex; flex-direction: column; justify-content: space-between;
        padding: 2.5rem;
        position: relative; overflow: hidden;
    }
    .login-left::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 20% 80%, rgba(93,163,66,.25) 0%, transparent 60%),
                    radial-gradient(ellipse at 80% 20%, rgba(30,61,18,.6) 0%, transparent 50%);
    }
    .login-left > * { position: relative; z-index: 1; }

    .left-brand { display: flex; align-items: center; gap: .6rem; }
    .left-brand-dot { width: 32px; height: 32px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; }
    .left-brand-name { font-family: var(--font-display); font-weight: 700; font-size: 1.3rem; color: white; }

    .left-hero { }
    .left-tagline { font-family: var(--font-display); font-size: 2.4rem; font-weight: 900; color: white; line-height: 1.1; margin-bottom: 1rem; }
    .left-tagline em { color: var(--verde-claro); font-style: normal; }
    .left-sub { font-size: .9rem; color: rgba(255,255,255,.55); line-height: 1.6; }

    .left-stats { display: flex; gap: 1.5rem; }
    .left-stat strong { display: block; font-family: var(--font-display); font-size: 1.6rem; color: white; }
    .left-stat span { font-size: .78rem; color: rgba(255,255,255,.5); }

    .login-right {
        flex: 1; display: flex; align-items: center; justify-content: center;
        padding: 2rem;
    }
    .login-box { width: 100%; max-width: 400px; }

    .login-title { font-family: var(--font-display); font-size: 2rem; font-weight: 700; margin-bottom: .3rem; }
    .login-sub { color: var(--gris); font-size: .9rem; margin-bottom: 2rem; }

    .link-conductor {
        display: flex; align-items: center; gap: .6rem;
        background: var(--fondo-2); border-radius: var(--r-md);
        padding: .8rem 1rem; margin-bottom: 1.5rem;
        text-decoration: none; color: var(--texto-2);
        font-size: .875rem; font-weight: 500;
        border: 1px solid var(--borde); transition: all .18s;
    }
    .link-conductor:hover { border-color: var(--verde); color: var(--verde-oscuro); background: var(--verde-bg); }
    .link-conductor-icon { width: 32px; height: 32px; border-radius: var(--r-sm); background: var(--verde-oscuro); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .or-divider { display: flex; align-items: center; gap: 1rem; margin: 1.2rem 0; color: var(--gris); font-size: .8rem; }
    .or-divider::before, .or-divider::after { content: ''; flex: 1; height: 1px; background: var(--borde); }

    .register-link { text-align: center; font-size: .875rem; color: var(--gris); margin-top: 1.2rem; }
    .register-link a { color: var(--verde); font-weight: 600; text-decoration: none; }
    .register-link a:hover { color: var(--verde-hover); }

    @media (max-width: 768px) {
        .login-left { display: none; }
        .login-right { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="login-left">
    <div class="left-brand">
        <div class="left-brand-dot">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        </div>
        <span class="left-brand-name">goRanch</span>
    </div>

    <div class="left-hero">
        <h1 class="left-tagline">Tu pueblo,<br><em>más cerca</em><br>que nunca.</h1>
        <p class="left-sub">Viajes, mandados y entregas en zonas rurales. Sin complicaciones, sin apps extra.</p>
    </div>

    <div class="left-stats">
        <div class="left-stat">
            <strong>5+</strong>
            <span>Puntos de servicio</span>
        </div>
        <div class="left-stat">
            <strong>24/7</strong>
            <span>Disponibilidad</span>
        </div>
        <div class="left-stat">
            <strong>⭐ 4.9</strong>
            <span>Calificación</span>
        </div>
    </div>
</div>

<div class="login-right">
    <div class="login-box">
        <h1 class="login-title">Bienvenido</h1>
        <p class="login-sub">Inicia sesión para continuar</p>

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Acceso rápido conductor --}}
        <a href="{{ route('login.conductor') }}" class="link-conductor">
            <div class="link-conductor-icon">
                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
            </div>
            <div>
                <div style="font-weight:600; font-size:.875rem;">¿Eres conductor?</div>
                <div style="font-size:.78rem; color:var(--gris);">Accede a tu panel de trabajo</div>
            </div>
            <svg style="margin-left:auto;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </a>

        <div class="or-divider">o inicia como cliente</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Correo electrónico</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input type="email" name="email" class="form-input @error('email') error @enderror"
                        placeholder="tu@correo.com" value="{{ old('email') }}" autofocus>
                </div>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" style="display:flex; justify-content:space-between;">
                    Contraseña
                    <a href="#" style="font-weight:400; color:var(--verde); font-size:.78rem; text-decoration:none;">¿Olvidaste tu contraseña?</a>
                </label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </span>
                    <input type="password" name="password" id="pass" class="form-input @error('password') error @enderror" placeholder="••••••••">
                    <button type="button" onclick="togglePass()" style="position:absolute;right:.9rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gris);">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:.5rem; border-radius:var(--r-sm);">
                Iniciar Sesión
            </button>
        </form>

        <p class="register-link">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function togglePass() {
    const p = document.getElementById('pass');
    p.type = p.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
@endsection
