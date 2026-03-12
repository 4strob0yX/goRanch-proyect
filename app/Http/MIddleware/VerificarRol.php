<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarRol
{
    /**
     * Uso en rutas:
     *   ->middleware('rol:usuario')
     *   ->middleware('rol:conductor,super_admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();

        if ($usuario->estaBloqueado()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido bloqueada.',
            ]);
        }

        if (!in_array($usuario->rol, $roles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
