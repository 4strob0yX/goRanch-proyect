<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'conductores';

    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $fillable = [
        'usuario_id',
        'tipo_vehiculo',
        'marca',
        'modelo',
        'placa',
        'esta_conectado',
        'ubicacion_actual',
        'punto_recoleccion_id',
        'estatus',
        'calificacion_promedio',
    ];

    protected $casts = [
        'esta_conectado' => 'boolean',
        'calificacion_promedio' => 'decimal:2',
    ];

    // -----------------------------------------------
    // Relaciones
    // -----------------------------------------------

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoConductor::class, 'conductor_id');
    }

    public function puntoRecoleccion()
    {
        return $this->belongsTo(PuntoRecoleccion::class, 'punto_recoleccion_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'conductor_id');
    }

    // -----------------------------------------------
    // Helpers
    // -----------------------------------------------

    public function estaDisponible(): bool
    {
        return $this->esta_conectado
            && $this->estatus === 'activo'
            && $this->punto_recoleccion_id !== null;
    }

    public function estaPendiente(): bool  { return $this->estatus === 'pendiente'; }
    public function estaActivo(): bool     { return $this->estatus === 'activo'; }
    public function estaSuspendido(): bool { return $this->estatus === 'suspendido'; }
}
