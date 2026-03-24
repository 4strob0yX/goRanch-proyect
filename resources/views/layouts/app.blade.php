<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'goRanch')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;0,700;0,900;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ═══════════════════════════════════════
           TOKENS — sistema de diseño goRanch
           Estilo: campo/rural orgánico moderno
        ═══════════════════════════════════════ */
        :root {
            /* Verdes */
            --verde:         #3d7a2a;
            --verde-hover:   #2d5a1b;
            --verde-oscuro:  #1e3d12;
            --verde-claro:   #c8e6b8;
            --verde-bg:      #eef5e8;
            --verde-mid:     #5a9e42;

            /* Neutros */
            --fondo:         #f5f2eb;   /* beige cálido — tierra */
            --fondo-2:       #ede9df;
            --blanco:        #ffffff;
            --texto:         #1c1c18;   /* casi negro cálido */
            --texto-2:       #4a4a40;
            --gris:          #7a7a6e;
            --gris-claro:    #deded6;
            --borde:         #d4d0c4;

            /* Semáforos */
            --rojo:          #c0392b;
            --rojo-bg:       #fde8e6;
            --amarillo:      #d97706;
            --amarillo-bg:   #fef3c7;
            --azul:          #2563eb;
            --azul-bg:       #dbeafe;

            /* Sombras */
            --sombra-sm:     0 1px 4px rgba(30,61,18,.08);
            --sombra:        0 4px 20px rgba(30,61,18,.10);
            --sombra-lg:     0 12px 40px rgba(30,61,18,.14);

            /* Radios */
            --r-sm:   8px;
            --r-md:   14px;
            --r-lg:   20px;
            --r-xl:   28px;
            --r-full: 999px;

            /* Tipografía */
            --font-display: 'Fraunces', Georgia, serif;
            --font-body:    'DM Sans', system-ui, sans-serif;
        }

        /* ── Reset & Base ── */
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: var(--fondo);
            color: var(--texto);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5 {
            font-family: var(--font-display);
            line-height: 1.15;
            letter-spacing: -.02em;
        }
        a { color: inherit; }
        button, input, select, textarea {
            font-family: var(--font-body);
        }
        img { max-width: 100%; }

        /* ── Navbar ── */
        .navbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem; height: 60px;
            background: var(--blanco);
            border-bottom: 1px solid var(--borde);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand {
            display: flex; align-items: center; gap: .5rem;
            font-family: var(--font-display); font-weight: 700; font-size: 1.15rem;
            text-decoration: none; color: var(--verde-oscuro);
        }
        .navbar-nav { display: flex; align-items: center; gap: .3rem; }
        .nav-link {
            text-decoration: none; color: var(--gris); font-weight: 500;
            font-size: .9rem; padding: .4rem .8rem; border-radius: var(--r-full);
            transition: all .18s;
        }
        .nav-link:hover { color: var(--texto); background: var(--fondo); }
        .nav-link.active { color: var(--verde-oscuro); background: var(--verde-bg); font-weight: 600; }
        .avatar-placeholder {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--verde-claro); display: flex; align-items: center;
            justify-content: center; font-weight: 700; color: var(--verde-oscuro);
            font-size: .8rem; flex-shrink: 0;
        }

        /* ── Botones ── */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: .45rem; padding: .7rem 1.4rem; border-radius: var(--r-full);
            font-family: var(--font-body); font-weight: 600; font-size: .9rem;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .18s; white-space: nowrap; line-height: 1;
        }
        .btn-primary { background: var(--verde); color: white; }
        .btn-primary:hover { background: var(--verde-hover); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(61,122,42,.3); }
        .btn-dark { background: var(--texto); color: white; }
        .btn-dark:hover { background: var(--texto-2); }
        .btn-outline { background: transparent; border: 1.5px solid var(--borde); color: var(--texto); }
        .btn-outline:hover { border-color: var(--verde); color: var(--verde); background: var(--verde-bg); }
        .btn-danger { background: var(--rojo-bg); color: var(--rojo); }
        .btn-danger:hover { background: #fbd0cd; }
        .btn-full { width: 100%; }
        .btn-sm { padding: .45rem 1rem; font-size: .82rem; }
        .btn-lg { padding: .9rem 1.8rem; font-size: 1rem; }
        .btn:disabled { opacity: .5; cursor: not-allowed; }

        /* ── Inputs ── */
        .form-group { margin-bottom: 1.1rem; }
        .form-label { display: block; font-size: .82rem; font-weight: 600; margin-bottom: .35rem; color: var(--texto-2); letter-spacing: .01em; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--gris); pointer-events: none; }
        .form-input {
            width: 100%; padding: .75rem 1rem .75rem 2.6rem;
            border: 1.5px solid var(--borde); border-radius: var(--r-sm);
            font-family: var(--font-body); font-size: .9rem;
            background: var(--blanco); color: var(--texto);
            transition: border-color .18s, box-shadow .18s; outline: none;
        }
        .form-input.no-icon { padding-left: 1rem; }
        .form-input:focus { border-color: var(--verde); box-shadow: 0 0 0 3px rgba(61,122,42,.12); }
        .form-input.error { border-color: var(--rojo); }
        .form-input::placeholder { color: var(--gris); }
        select.form-input { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a7a6e' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; padding-right: 2.5rem; }
        .form-error { font-size: .78rem; color: var(--rojo); margin-top: .3rem; display: flex; align-items: center; gap: .3rem; }

        /* ── Cards ── */
        .card { background: var(--blanco); border-radius: var(--r-lg); box-shadow: var(--sombra); padding: 1.5rem; }
        .card-sm { padding: 1rem; border-radius: var(--r-md); }

        /* ── Alerts ── */
        .alert { padding: .85rem 1.1rem; border-radius: var(--r-sm); font-size: .875rem; margin-bottom: 1rem; display: flex; align-items: center; gap: .6rem; }
        .alert-success { background: var(--verde-bg); color: var(--verde-oscuro); border: 1px solid var(--verde-claro); }
        .alert-error { background: var(--rojo-bg); color: var(--rojo); border: 1px solid #fecaca; }
        .alert-warning { background: var(--amarillo-bg); color: #92400e; border: 1px solid #fde68a; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .22rem .7rem; border-radius: var(--r-full); font-size: .75rem; font-weight: 700; letter-spacing: .02em; }
        .badge-green  { background: var(--verde-bg);    color: var(--verde-oscuro); }
        .badge-gray   { background: var(--gris-claro);  color: var(--gris); }
        .badge-yellow { background: var(--amarillo-bg); color: #92400e; }
        .badge-red    { background: var(--rojo-bg);     color: var(--rojo); }
        .badge-blue   { background: var(--azul-bg);     color: var(--azul); }

        /* ── Divisor ── */
        .divider { height: 1px; background: var(--borde); margin: 1.2rem 0; }

        /* ── Utilidades ── */
        .page { padding: 1.5rem; max-width: 1200px; margin: 0 auto; }
        .page-sm { max-width: 520px; }
        .page-md { max-width: 720px; }
        .text-verde   { color: var(--verde); }
        .text-gris    { color: var(--gris); }
        .text-sm      { font-size: .875rem; }
        .text-xs      { font-size: .78rem; }
        .text-center  { text-align: center; }
        .font-bold    { font-weight: 700; }
        .mt-1 { margin-top: .5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mb-1 { margin-bottom: .5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .flex   { display: flex; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .items-center   { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: .5rem; }
        .gap-2 { gap: 1rem; }
        .w-full { width: 100%; }
        .rounded { border-radius: var(--r-md); }
        .shadow  { box-shadow: var(--sombra); }

        /* ── Textura de fondo ── */
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='.8' fill='%233d7a2a' opacity='.04'/%3E%3C/svg%3E");
        }
        body > * { position: relative; z-index: 1; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .page { padding: 1rem; }
            .navbar { padding: 0 1rem; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
