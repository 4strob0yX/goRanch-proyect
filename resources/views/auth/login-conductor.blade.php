@extends('layouts.app')
@section('title', 'Acceso Conductores - goRanch')

@push('styles')
<style>
    body {
        background: var(--verde-oscuro);
        display: flex; align-items: center; justify-content: center;
        min-height: 100vh; padding: 1.5rem;
    }
    body::before { display: none; }

    .login-wrap { width: 100%; max-width: 400px; }

    .top-back {
        display: inline-flex; align-items: center; gap: .5rem;
        color: rgba(255,255,255,.5); font-size: .875rem; text-decoration: none;
        margin-bottom: 2rem; transition: color .18s;
    }
    .top-back:hover { color: rgba(255,255,255,.8); }

    .brand-row { display: flex; align-items: center; gap: .7rem; margin-bottom: .5rem; }
    .brand-dot { width: 40px; height: 40px; border-radius: 50%; background: var(--verde-mid); display: flex; align-items: center; justify-content: center; }
    .brand-name { font-family: var(--font-display); font-weight: 700; font-size: 1.4rem; color: white; }
    .brand-sub { font-size: .8rem; color: rgba(255,255,255,.4); margin-bottom: 2.5rem; }

    .login-title { font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: white; margin-bottom: .3rem; }
    .login-sub { color: rgba(255,255,255,.5); font-size: .875rem; margin-bottom: 1.8rem; }

    .field-label { font-size: .8rem; font-weight: 600; color: rgba(255,255,255,.5); letter-spacing: .05em; text-transform: uppercase; margin-bottom: .4rem; display: block; }
    .field-wrap { position: relative; margin-bottom: 1rem; }
    .field-input {
        width: 100%; padding: .85rem 1rem .85rem 2.8rem;
        background: rgba(255,255,255,.07);
        border: 1.5px solid rgba(255,255,255,.12);
        border-radius: var(--r-sm);
        color: white; font-family: var(--font-body); font-size: .9rem;
        outline: none; transition: border-color .18s, background .18s;
    }
    .field-input:focus { border-color: var(--verde-mid); background: rgba(255,255,255,.1); }
    .field-input::placeholder { color: rgba(255,255,255,.3); }
    .field-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.35); pointer-events: none; }
    .eye-btn { position: absolute; right: .9rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,.35); }

    .btn-conductor {
        width: 100%; padding: .9rem; border-radius: var(--r-sm);
        background: var(--verde-mid); color: white;
        font-family: var(--font-body); font-weight: 700; font-size: .95rem;
        border: none; cursor: pointer; margin-top: .5rem;
        transition: all .18s;
    }
    .btn-conductor:hover { background: var(--verde); transform: translateY(-1px); }

    .register-row { text-align: center; margin-top: 1.5rem; font-size: .875rem; color: rgba(255,255,255,.4); }
    .register-row a { color: var(--verde-claro); font-weight: 600; text-decoration: none; }

    .error-box { background: rgba(192,57,43,.2); border: 1px solid rgba(192,57,43,.4); border-radius: var(--r-sm); padding: .8rem 1rem; font-size: .875rem; color: #fca5a5; margin-bottom: 1.2rem; }
</style>
@endpush

@section('content')
<div class="login-wrap">
    <a href="{{ route('login') }}" class="top-back">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Volver al inicio
    </a>

    <div class="brand-row">
        <div class="brand-dot">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
        </div>
        <span class="brand-name">goRanch</span>
    </div>
    <p class="brand-sub">Panel exclusivo para conductores</p>

    <h1 class="login-title">Acceso Conductores</h1>
    <p class="login-sub">Inicia sesión para ver tus servicios y conectarte.</p>

    @if($errors->any())
        <div class="error-box">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.conductor') }}">
        @csrf

        <div>
            <label class="field-label">Correo electrónico</label>
            <div class="field-wrap">
                <span class="field-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>
                <input type="email" name="email" class="field-input" placeholder="tu@correo.com" value="{{ old('email') }}" autofocus>
            </div>
        </div>

        <div>
            <label class="field-label">Contraseña</label>
            <div class="field-wrap">
                <span class="field-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                <input type="password" name="password" id="pass" class="field-input" placeholder="••••••••">
                <button type="button" class="eye-btn" onclick="const p=document.getElementById('pass');p.type=p.type==='password'?'text':'password'">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-conductor">Entrar al Panel →</button>
    </form>

    <div class="register-row">
        ¿Aún no eres conductor? <a href="{{ route('conductor.registro.paso1') }}">Regístrate aquí</a>
    </div>
</div>
@endsection
