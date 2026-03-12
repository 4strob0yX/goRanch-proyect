<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // -----------------------------------------------
    // Registro
    // -----------------------------------------------

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string|max:20|unique:usuarios,telefono',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nombre.required'      => 'El nombre es obligatorio.',
            'email.required'       => 'El correo es obligatorio.',
            'email.unique'         => 'Este correo ya está registrado.',
            'telefono.required'    => 'El teléfono es obligatorio.',
            'telefono.unique'      => 'Este teléfono ya está registrado.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol'      => 'usuario',
            'estatus'  => 'activo',
        ]);

        Auth::login($usuario);

        return redirect()->route('dashboard')->with('success', '¡Bienvenido a goRanch, ' . $usuario->nombre . '!');
    }

    // -----------------------------------------------
    // Login
    // -----------------------------------------------

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors([
                'email' => 'Credenciales incorrectas.',
            ])->withInput($request->only('email'));
        }

        // Verificar si está bloqueado
        if ($usuario->estaBloqueado()) {
            return back()->withErrors([
                'email' => 'Tu cuenta ha sido bloqueada. Contacta al soporte.',
            ]);
        }

        Auth::login($usuario, $request->boolean('remember'));

        $request->session()->regenerate();

        // Redirigir según rol
        return $this->redirigirPorRol($usuario);
    }

    // -----------------------------------------------
    // Logout
    // -----------------------------------------------

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    // -----------------------------------------------
    // Helper: redirigir según rol
    // -----------------------------------------------

    private function redirigirPorRol(Usuario $usuario)
    {
        return match ($usuario->rol) {
            'conductor'    => redirect()->route('conductor.dashboard'),
            'admin_tienda' => redirect()->route('tienda.dashboard'),
            'super_admin'  => redirect()->route('admin.dashboard'),
            default        => redirect()->route('dashboard'), // usuario
        };
    }

    public function showLoginConductor()
{
    return view('auth.login-conductor');
}

public function loginConductor(Request $request)
{
    // Por ahora redirige al mismo login normal
    // Después lo personalizamos para conductor
    return $this->login($request);
}

}
