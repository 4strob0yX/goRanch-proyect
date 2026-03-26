<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string|max:20|unique:usuarios,telefono',
            'password' => 'required|string|min:8',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol'      => 'usuario',
            'estatus'  => 'activo',
        ]);

        $token = $usuario->createToken('mobile')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        if ($usuario->estaBloqueado()) {
            return response()->json(['message' => 'Tu cuenta ha sido bloqueada.'], 403);
        }

        $token = $usuario->createToken('mobile')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token'   => $token,
        ]);
    }

    public function loginConductor(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)
            ->where('rol', 'conductor')
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        if ($usuario->estaBloqueado()) {
            return response()->json(['message' => 'Tu cuenta ha sido bloqueada.'], 403);
        }

        $conductor = $usuario->conductor;
        if (!$conductor || $conductor->estatus !== 'activo') {
            return response()->json([
                'message' => 'Tu cuenta de conductor no esta activa.',
                'estatus_conductor' => $conductor ? $conductor->estatus : 'sin_registro',
            ], 403);
        }

        $token = $usuario->createToken('mobile-conductor')->plainTextToken;

        return response()->json([
            'usuario'   => $usuario,
            'conductor' => $conductor,
            'token'     => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesion cerrada.']);
    }

    public function me(Request $request)
    {
        $usuario = $request->user();
        $data = ['usuario' => $usuario];

        if ($usuario->esConductor() && $usuario->conductor) {
            $data['conductor'] = $usuario->conductor->load('puntoRecoleccion');
        }

        return response()->json($data);
    }
}
