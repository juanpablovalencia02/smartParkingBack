<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    public static $snakeAttributes = false;
    protected $table = "vehiculo";
    protected $fillable = [
        "placa", "modelo", "marca", "color"
    ];

    const RUTA_FOTO = "vehiculos";

    const RUTA_FOTO_DEFAULT = "/default/auto.png";

    public $timestamps = false;


    public function getFotoUrlAttribute()
    {
        if (
            isset($this->attributes['foto']) &&
            isset($this->attributes['foto'][0])
        ) {
            return url($this->attributes['foto']);
        }
        return url(self::RUTA_FOTO_DEFAULT);
    }
}
