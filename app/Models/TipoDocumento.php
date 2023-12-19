<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    public static $snakeAttributes = false;
    protected $table = "tipoDocumento";
    protected $fillable = [
        "tituloDocumento",
        "descripcion",
        "idEstado",
        "idProceso"
    ];

    public $timestamps = false;
    public function proceso()
    {
        return $this->belongsTo(Proceso::class, 'idProceso');
    }

    public function estado()
    {
        return $this->belongsTo(Status::class, 'idEstado');
    }
}
