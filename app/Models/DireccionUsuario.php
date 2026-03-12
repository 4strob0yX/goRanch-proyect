<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionUsuario extends Model
{
    protected $table = 'direcciones_usuario';

    public $timestamps = false; // Solo tiene 'actualizado_en', no 'creado_en'

    protected $fillable = [
        'usuario_id',
        'alias',
        'direccion',
        'ubicacion',
    ];

    // -----------------------------------------------
    // Relaciones
    // -----------------------------------------------

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
