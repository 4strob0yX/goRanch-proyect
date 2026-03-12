<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoConductor extends Model
{
    protected $table = 'documentos_conductor';

    public $timestamps = false;

    protected $fillable = [
        'conductor_id',
        'tipo_documento',
        'url_archivo',
        'estatus',
        'comentarios_rechazo',
    ];

    // -----------------------------------------------
    // Relaciones
    // -----------------------------------------------

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'conductor_id');
    }

    // -----------------------------------------------
    // Helpers
    // -----------------------------------------------

    public function estaPendiente(): bool
    {
        return $this->estatus === 'pendiente';
    }

    public function estaAprobado(): bool
    {
        return $this->estatus === 'aprobado';
    }

    public function estaRechazado(): bool
    {
        return $this->estatus === 'rechazado';
    }
}
