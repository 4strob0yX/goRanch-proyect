<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\MandadoController;
use App\Http\Controllers\RegistroConductorController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// Rutas públicas (solo para no autenticados)
// -----------------------------------------------

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/login/conductor', [AuthController::class, 'showLoginConductor'])->name('login.conductor');
    Route::post('/login/conductor', [AuthController::class, 'loginConductor']);

    // Registro conductor (3 pasos)
    Route::get('/conductor/registro', [RegistroConductorController::class, 'paso1'])->name('conductor.registro.paso1');
    Route::post('/conductor/registro/paso1', [RegistroConductorController::class, 'storePaso1'])->name('conductor.registro.paso1.store');
    Route::get('/conductor/registro/paso2', [RegistroConductorController::class, 'paso2'])->name('conductor.registro.paso2');
    Route::post('/conductor/registro/paso2', [RegistroConductorController::class, 'storePaso2'])->name('conductor.registro.paso2.store');
    Route::get('/conductor/registro/paso3', [RegistroConductorController::class, 'paso3'])->name('conductor.registro.paso3');
    Route::post('/conductor/registro/paso3', [RegistroConductorController::class, 'storePaso3'])->name('conductor.registro.paso3.store');
});

// Pendiente de aprobación (requiere auth)
Route::get('/conductor/pendiente', [RegistroConductorController::class, 'pendiente'])
    ->middleware('auth')
    ->name('conductor.registro.pendiente');

// Conductor suspendido (requiere auth)
Route::get('/conductor/suspendido', [ConductorController::class, 'suspendido'])
    ->middleware('auth')
    ->name('conductor.suspendido');

// -----------------------------------------------
// Logout
// -----------------------------------------------

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// -----------------------------------------------
// Dashboard Usuario (cliente)
// -----------------------------------------------

Route::middleware(['auth', 'rol:usuario'])->group(function () {
    Route::get('/inicio', [DashboardController::class, 'usuario'])->name('dashboard');
    Route::get('/perfil', [DashboardController::class, 'perfil'])->name('perfil');
    Route::get('/mis-viajes', [DashboardController::class, 'misViajes'])->name('mis-viajes');

    // Viajes
    Route::get('/viaje/nuevo', [ViajeController::class, 'nuevo'])->name('viaje.nuevo');
    Route::post('/viaje', [ViajeController::class, 'store'])->name('viaje.store');
    Route::get('/viaje/{id}/en-camino', [ViajeController::class, 'enCamino'])->name('viaje.en-camino');
    Route::get('/viaje/{id}/finalizado', [ViajeController::class, 'finalizado'])->name('viaje.finalizado');
    Route::post('/viaje/{id}/calificar', [ViajeController::class, 'calificar'])->name('viaje.calificar');

    // Mandados
    Route::get('/mandado/nuevo', [MandadoController::class, 'nuevo'])->name('mandado.nuevo');
    Route::post('/mandado', [MandadoController::class, 'store'])->name('mandado.store');
    Route::get('/mandado/{id}/en-proceso', [MandadoController::class, 'enProceso'])->name('mandado.en-proceso');
});

// -----------------------------------------------
// Dashboard Conductor
// -----------------------------------------------

Route::middleware(['auth', 'rol:conductor', 'conductor.activo'])->prefix('conductor')->group(function () {
    Route::get('/inicio', [ConductorController::class, 'dashboard'])->name('conductor.dashboard');
    Route::post('/conectar', [ConductorController::class, 'conectar'])->name('conductor.conectar');
    Route::post('/desconectar', [ConductorController::class, 'desconectar'])->name('conductor.desconectar');
    Route::patch('/servicio/{id}/estatus', [ConductorController::class, 'actualizarServicio'])->name('conductor.servicio.estatus');
});

// -----------------------------------------------
// Dashboard Admin Tienda
// -----------------------------------------------

Route::middleware(['auth', 'rol:admin_tienda'])->prefix('tienda')->group(function () {
    Route::get('/inicio', [DashboardController::class, 'adminTienda'])->name('tienda.dashboard');
});

// -----------------------------------------------
// Dashboard Super Admin
// -----------------------------------------------

Route::middleware(['auth', 'rol:super_admin'])->prefix('admin')->group(function () {
    Route::get('/inicio', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/conductores', [AdminController::class, 'conductores'])->name('admin.conductores');
    Route::patch('/conductores/{conductor}/aprobar', [AdminController::class, 'aprobarConductor'])->name('admin.conductores.aprobar');
    Route::patch('/conductores/{conductor}/rechazar', [AdminController::class, 'rechazarConductor'])->name('admin.conductores.rechazar');
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::patch('/usuarios/{usuario}/bloquear', [AdminController::class, 'bloquearUsuario'])->name('admin.usuarios.bloquear');
    Route::get('/servicios', [AdminController::class, 'servicios'])->name('admin.servicios');

    // Puntos de recolección
    Route::get('/puntos',               [AdminController::class, 'puntos'])->name('admin.puntos');
    Route::post('/puntos',              [AdminController::class, 'storePunto'])->name('admin.puntos.store');
    Route::patch('/puntos/{id}',        [AdminController::class, 'updatePunto'])->name('admin.puntos.update');
    Route::patch('/puntos/{id}/toggle', [AdminController::class, 'togglePunto'])->name('admin.puntos.toggle');

    // Administradores
    Route::get('/admins',                    [AdminController::class, 'admins'])->name('admin.admins');
    Route::post('/admins',                   [AdminController::class, 'storeAdmin'])->name('admin.admins.store');
    Route::patch('/admins/{usuario}/toggle', [AdminController::class, 'toggleAdmin'])->name('admin.admins.toggle');
});

// -----------------------------------------------
// Redirect raíz
// -----------------------------------------------

Route::get('/', function () {
    return redirect()->route('login');
});