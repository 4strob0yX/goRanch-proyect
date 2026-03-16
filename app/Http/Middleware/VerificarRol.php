<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarRol
{
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
            // Redirigir al dashboard correcto según el rol
            return redirect()->to($this->dashboardPorRol($usuario->rol));
        }

        return $next($request);
    }

    private function dashboardPorRol(string $rol): string
    {
        return match($rol) {
            'super_admin'  => route('admin.dashboard'),
            'conductor'    => route('conductor.dashboard'),
            'admin_tienda' => route('tienda.dashboard'),
            default        => route('dashboard'),
        };
    }
}