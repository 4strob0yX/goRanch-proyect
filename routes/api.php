<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ServicioApiController;
use Illuminate\Support\Facades\Route;

// ── Auth (public) ──
Route::post('/auth/register', [AuthApiController::class, 'register']);
Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::post('/auth/login-conductor', [AuthApiController::class, 'loginConductor']);

// ── Protected routes ──
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/me', [AuthApiController::class, 'me']);

    // Puntos de recoleccion (public data)
    Route::get('/puntos', [ServicioApiController::class, 'puntos']);
    Route::get('/conductores-disponibles', [ServicioApiController::class, 'conductoresDisponibles']);

    // ── Usuario ──
    Route::post('/viaje', [ServicioApiController::class, 'storeViaje']);
    Route::post('/mandado', [ServicioApiController::class, 'storeMandado']);
    Route::get('/servicio/{id}/status', [ServicioApiController::class, 'status']);
    Route::post('/servicio/{id}/cancelar', [ServicioApiController::class, 'cancelar']);
    Route::get('/mis-servicios', [ServicioApiController::class, 'misServicios']);
    Route::get('/servicio-activo', [ServicioApiController::class, 'servicioActivo']);

    // ── Admin ──
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [ServicioApiController::class, 'adminDashboard']);
        Route::patch('/conductor/{id}/aprobar', [ServicioApiController::class, 'adminAprobarConductor']);
        Route::patch('/conductor/{id}/rechazar', [ServicioApiController::class, 'adminRechazarConductor']);
        Route::patch('/usuario/{id}/toggle', [ServicioApiController::class, 'adminToggleUsuario']);
    });

    // ── Conductor ──
    Route::prefix('conductor')->group(function () {
        Route::get('/dashboard', [ServicioApiController::class, 'conductorDashboard']);
        Route::post('/conectar', [ServicioApiController::class, 'conductorConectar']);
        Route::post('/desconectar', [ServicioApiController::class, 'conductorDesconectar']);
        Route::get('/pendientes', [ServicioApiController::class, 'conductorPendientes']);
        Route::post('/servicio/{id}/aceptar', [ServicioApiController::class, 'conductorAceptar']);
        Route::post('/servicio/{id}/rechazar', [ServicioApiController::class, 'conductorRechazar']);
        Route::patch('/servicio/{id}/estatus', [ServicioApiController::class, 'conductorActualizarEstatus']);
    });
});
