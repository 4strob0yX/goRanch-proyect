<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoRecoleccion extends Model
{
    protected $table = 'puntos_recoleccion';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'ubicacion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // -----------------------------------------------
    // Relaciones
    // -----------------------------------------------

    public function conductores()
    {
        return $this->hasMany(Conductor::class, 'punto_recoleccion_id');
    }

    public function conductoresDisponibles()
    {
        return $this->hasMany(Conductor::class, 'punto_recoleccion_id')
                    ->where('esta_conectado', true)
                    ->where('estatus', 'activo');
    }

    // -----------------------------------------------
    // Helper: encontrar el punto más cercano a unas coords
    // Usa fórmula de Haversine en MySQL/MariaDB
    // -----------------------------------------------

    public static function masCercanoA(float $lat, float $lng): ?self
    {
        // ST_Distance_Sphere devuelve metros
        return self::where('activo', true)
            ->selectRaw("
                *,
                ST_Distance_Sphere(
                    ubicacion,
                    ST_GeomFromText('POINT({$lng} {$lat})', 4326)
                ) AS distancia_metros
            ")
            ->orderBy('distancia_metros')
            ->first();
    }
}
