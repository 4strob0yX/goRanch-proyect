<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $fillable = [
        'cliente_id', 'conductor_id', 'tienda_id', 'punto_recoleccion_id',
        'tipo', 'estatus',
        'direccion_origen', 'direccion_destino',
        'ubicacion_origen', 'ubicacion_destino',
        'distancia_km', 'costo_envio', 'costo_productos',
        'tarifa_plataforma', 'total_final',
        'metodo_pago', 'notas',
        'iniciado_en', 'finalizado_en',
    ];

    protected $casts = [
        'iniciado_en'  => 'datetime',
        'finalizado_en' => 'datetime',
        'creado_en'    => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'conductor_id');
    }

    public function puntoRecoleccion()
    {
        return $this->belongsTo(PuntoRecoleccion::class, 'punto_recoleccion_id');
    }

    public function estaCompletado(): bool { return $this->estatus === 'completado'; }
    public function estaCancelado(): bool  { return $this->estatus === 'cancelado'; }
    public function estaBuscando(): bool   { return $this->estatus === 'buscando'; }
}
