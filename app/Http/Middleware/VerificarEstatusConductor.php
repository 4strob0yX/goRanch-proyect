<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarEstatusConductor
{
    public function handle(Request $request, Closure $next): mixed
    {
        $conductor = Auth::user()->conductor ?? null;

        if (!$conductor) {
            return redirect()->route('conductor.registro.paso1');
        }

        if ($conductor->estaSuspendido()) {
            return redirect()->route('conductor.suspendido');
        }

        if ($conductor->estaPendiente()) {
            return redirect()->route('conductor.registro.pendiente');
        }

        return $next($request);
    }
}
