<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Tabla personalizada
    protected $table = 'usuarios';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'password',
        'foto_perfil',
        'token_fcm',
        'rol',
        'estatus',
    ];

    // Campos ocultos al serializar
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Timestamps personalizados
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    // Cast de tipos
    protected $casts = [
        'password' => 'hashed',
    ];

    // -----------------------------------------------
    // Helpers de roles
    // -----------------------------------------------

    public function esUsuario(): bool
    {
        return $this->rol === 'usuario';
    }

    public function esConductor(): bool
    {
        return $this->rol === 'conductor';
    }

    public function esAdminTienda(): bool
    {
        return $this->rol === 'admin_tienda';
    }

    public function esSuperAdmin(): bool
    {
        return $this->rol === 'super_admin';
    }

    public function estaBloqueado(): bool
    {
        return $this->estatus === 'bloqueado';
    }

    // -----------------------------------------------
    // Relaciones
    // -----------------------------------------------

    public function direcciones()
    {
        return $this->hasMany(DireccionUsuario::class, 'usuario_id');
    }

    public function conductor()
    {
        return $this->hasOne(Conductor::class, 'usuario_id');
    }
}
