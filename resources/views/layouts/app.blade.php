<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'goRanch')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --verde:        #6aaa4b;
            --verde-oscuro: #3a6b28;
            --verde-claro:  #d4edca;
            --verde-bg:     #eef5ea;
            --fondo:        #f3f1ec;
            --blanco:       #ffffff;
            --texto:        #1a1a1a;
            --gris:         #6b7280;
            --gris-claro:   #e5e7eb;
            --rojo:         #ef4444;
            --amarillo:     #f59e0b;
            --sombra:       0 4px 24px rgba(0,0,0,0.08);
            --radio:        16px;
            --radio-sm:     10px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--fondo);
            color: var(--texto);
            min-height: 100vh;
        }

        h1,h2,h3,h4 { font-family: 'Syne', sans-serif; }

        /* --- Navbar --- */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 64px;
            background: var(--blanco);
            border-bottom: 1px solid var(--gris-claro);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { display: flex; align-items: center; gap: .5rem; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.2rem; text-decoration: none; color: var(--texto); }
        .navbar-brand svg { color: var(--verde); }
        .navbar-nav { display: flex; align-items: center; gap: 1.5rem; }
        .nav-link { text-decoration: none; color: var(--gris); font-weight: 500; font-size: .95rem; transition: color .2s; }
        .nav-link:hover, .nav-link.active { color: var(--texto); }
        .nav-link.active { background: var(--verde-bg); color: var(--verde-oscuro); padding: .4rem .9rem; border-radius: 999px; font-weight: 600; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--verde-claro); }
        .avatar-placeholder { width: 36px; height: 36px; border-radius: 50%; background: var(--verde-claro); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--verde-oscuro); font-size: .85rem; }

        /* --- Botones --- */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; padding: .75rem 1.5rem; border-radius: 999px; font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 1rem; cursor: pointer; border: none; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--verde); color: var(--blanco); }
        .btn-primary:hover { background: var(--verde-oscuro); transform: translateY(-1px); }
        .btn-dark { background: var(--texto); color: var(--blanco); }
        .btn-dark:hover { background: #333; }
        .btn-outline { background: transparent; border: 2px solid var(--gris-claro); color: var(--texto); }
        .btn-outline:hover { border-color: var(--verde); color: var(--verde); }
        .btn-full { width: 100%; }
        .btn-danger { background: #fee2e2; color: var(--rojo); }

        /* --- Inputs --- */
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; font-size: .875rem; font-weight: 500; margin-bottom: .4rem; color: var(--texto); }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gris); }
        .form-input { width: 100%; padding: .8rem 1rem .8rem 2.8rem; border: 1.5px solid var(--gris-claro); border-radius: var(--radio-sm); font-family: 'DM Sans', sans-serif; font-size: .95rem; background: var(--blanco); color: var(--texto); transition: border-color .2s; outline: none; }
        .form-input:focus { border-color: var(--verde); }
        .form-input.no-icon { padding-left: 1rem; }
        .form-input.error { border-color: var(--rojo); }
        .form-error { font-size: .8rem; color: var(--rojo); margin-top: .3rem; }

        /* --- Cards --- */
        .card { background: var(--blanco); border-radius: var(--radio); box-shadow: var(--sombra); padding: 1.5rem; }

        /* --- Alerts --- */
        .alert { padding: .9rem 1.2rem; border-radius: var(--radio-sm); font-size: .9rem; margin-bottom: 1rem; }
        .alert-success { background: var(--verde-claro); color: var(--verde-oscuro); }
        .alert-error { background: #fee2e2; color: #991b1b; }

        /* --- Badge / chip --- */
        .badge { display: inline-flex; align-items: center; gap: .3rem; padding: .25rem .75rem; border-radius: 999px; font-size: .8rem; font-weight: 600; }
        .badge-green { background: var(--verde-claro); color: var(--verde-oscuro); }
        .badge-gray { background: var(--gris-claro); color: var(--gris); }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        /* --- Utilidades --- */
        .text-verde { color: var(--verde); }
        .text-gris { color: var(--gris); }
        .text-sm { font-size: .875rem; }
        .text-center { text-align: center; }
        .mt-1 { margin-top: .5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: .5rem; }
        .gap-2 { gap: 1rem; }
        .w-full { width: 100%; }

        /* --- Page wrapper --- */
        .page { padding: 2rem; max-width: 1200px; margin: 0 auto; }

        @media (max-width: 768px) {
            .page { padding: 1rem; }
            .navbar { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
