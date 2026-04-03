<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Models\DocumentoConductor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegistroConductorController extends Controller
{
    // -----------------------------------------------
    // PASO 1 — Datos personales
    // -----------------------------------------------

    public function paso1()
    {
        return view('conductor.registro.paso1');
    }

    public function storePaso1(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string|max:20|unique:usuarios,telefono',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'email.required'     => 'El correo es obligatorio.',
            'email.unique'       => 'Este correo ya está registrado.',
            'telefono.required'  => 'El teléfono es obligatorio.',
            'telefono.unique'    => 'Este teléfono ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Guardar datos en sesión para usarlos en pasos siguientes
        $request->session()->put('registro_conductor.paso1', [
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => $request->password,
        ]);

        return redirect()->route('conductor.registro.paso2');
    }

    // -----------------------------------------------
    // PASO 2 — Datos del vehículo
    // -----------------------------------------------

    public function paso2()
    {
        // Si no completó el paso 1, regresar
        if (!session('registro_conductor.paso1')) {
            return redirect()->route('conductor.registro.paso1');
        }

        return view('conductor.registro.paso2');
    }

    public function storePaso2(Request $request)
    {
        $request->validate([
            'tipo_vehiculo' => 'required|in:moto,bici,auto',
            'marca'         => 'required|string|max:50',
            'modelo'        => 'required|string|max:50',
            'placa'         => 'required|string|max:20|unique:conductores,placa',
        ], [
            'tipo_vehiculo.required' => 'Selecciona el tipo de vehículo.',
            'tipo_vehiculo.in'       => 'Tipo de vehículo no válido.',
            'marca.required'         => 'La marca es obligatoria.',
            'modelo.required'        => 'El modelo es obligatorio.',
            'placa.required'         => 'La placa es obligatoria.',
            'placa.unique'           => 'Esta placa ya está registrada.',
        ]);

        $request->session()->put('registro_conductor.paso2', [
            'tipo_vehiculo' => $request->tipo_vehiculo,
            'marca'         => $request->marca,
            'modelo'        => $request->modelo,
            'placa'         => $request->placa,
        ]);

        return redirect()->route('conductor.registro.paso3');
    }

    // -----------------------------------------------
    // PASO 3 — Documentos
    // -----------------------------------------------

    public function paso3()
    {
        if (!session('registro_conductor.paso2')) {
            return redirect()->route('conductor.registro.paso2');
        }

        return view('conductor.registro.paso3');
    }

    public function storePaso3(Request $request)
    {
        $request->validate([
            'ine'                  => 'required|file|mimes:jpg,jpeg,png,pdf|max:900',
            'licencia'             => 'required|file|mimes:jpg,jpeg,png,pdf|max:900',
            'tarjeta_circulacion'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:900',
        ], [
            'ine.required'                  => 'La identificación oficial (INE) es obligatoria.',
            'ine.max'                       => 'La INE no debe superar 900KB.',
            'ine.mimes'                     => 'La INE debe ser imagen o PDF.',
            'licencia.required'             => 'La licencia de conducir es obligatoria.',
            'licencia.max'                  => 'La licencia no debe superar 900KB.',
            'licencia.mimes'                => 'La licencia debe ser imagen o PDF.',
            'tarjeta_circulacion.required'  => 'La tarjeta de circulación es obligatoria.',
            'tarjeta_circulacion.max'       => 'La tarjeta de circulación no debe superar 900KB.',
            'tarjeta_circulacion.mimes'     => 'La tarjeta de circulación debe ser imagen o PDF.',
        ]);

        $paso1 = session('registro_conductor.paso1');
        $paso2 = session('registro_conductor.paso2');

        // Re-validar unicidad antes de crear (previene race conditions)
        if (Usuario::where('email', $paso1['email'])->exists()) {
            return redirect()->route('conductor.registro.paso1')
                ->withErrors(['email' => 'Este correo ya fue registrado. Intenta de nuevo.']);
        }
        if (Usuario::where('telefono', $paso1['telefono'])->exists()) {
            return redirect()->route('conductor.registro.paso1')
                ->withErrors(['telefono' => 'Este teléfono ya fue registrado. Intenta de nuevo.']);
        }
        if (Conductor::where('placa', $paso2['placa'])->exists()) {
            return redirect()->route('conductor.registro.paso2')
                ->withErrors(['placa' => 'Esta placa ya fue registrada. Intenta de nuevo.']);
        }

        // Subir archivos antes de la transacción (no se puede hacer rollback de archivos)
        $inePath         = $request->file('ine')->store('documentos/conductores', 'public');
        $licenciaPath    = $request->file('licencia')->store('documentos/conductores', 'public');
        $circulacionPath = $request->file('tarjeta_circulacion')->store('documentos/conductores', 'public');

        try {
            $usuario = DB::transaction(function () use ($paso1, $paso2, $inePath, $licenciaPath, $circulacionPath) {
                $usuario = Usuario::create([
                    'nombre'   => $paso1['nombre'],
                    'email'    => $paso1['email'],
                    'telefono' => $paso1['telefono'],
                    'password' => Hash::make($paso1['password']),
                    'rol'      => 'conductor',
                    'estatus'  => 'activo',
                ]);

                $conductor = Conductor::create([
                    'usuario_id'    => $usuario->id,
                    'tipo_vehiculo' => $paso2['tipo_vehiculo'],
                    'marca'         => $paso2['marca'],
                    'modelo'        => $paso2['modelo'],
                    'placa'         => $paso2['placa'],
                    'estatus'       => 'pendiente',
                ]);

                foreach ([
                    'ine'                  => $inePath,
                    'licencia'             => $licenciaPath,
                    'tarjeta_circulacion'  => $circulacionPath,
                ] as $tipo => $path) {
                    DocumentoConductor::create([
                        'conductor_id'   => $conductor->id,
                        'tipo_documento' => $tipo,
                        'url_archivo'    => $path,
                        'estatus'        => 'pendiente',
                    ]);
                }

                return $usuario;
            });
        } catch (\Exception $e) {
            // Limpiar archivos subidos si la transacción falló
            Storage::disk('public')->delete([$inePath, $licenciaPath, $circulacionPath]);

            return back()->withErrors(['error' => 'Ocurrió un error al procesar tu solicitud. Intenta de nuevo.']);
        }

        // Limpiar sesión
        $request->session()->forget('registro_conductor');

        // Login automático
        Auth::login($usuario);

        return redirect()->route('conductor.registro.pendiente');
    }

    // -----------------------------------------------
    // Pantalla de espera — pendiente de aprobación
    // -----------------------------------------------

    public function pendiente()
    {
        return view('conductor.registro.pendiente');
    }
}
