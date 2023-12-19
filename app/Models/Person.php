<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = "persona";
    protected $fillable = [
        "identificacion",
        "nombre1",
        "nombre2",
        "apellido1",
        "apellido2",
        "fechaNac",
        "idCiudadNac",
        "idCiudad",
        "direccion",
        "email",
        "idTipoIdentificacion",
        "telefonoFijo",
        "celular",
        "idCiudadUbicacion",
        "perfil",
        "sexo",
        "rh",
    ];
    const RUTA_FOTO = "images";
    const RUTA_FOTO_DEFAULT = "/default/user.svg";

    protected $appends = ['rutaFotoUrl'];

    public function getRutaFotoUrlAttribute()
    {
        if (
            isset($this->attributes['rutaFoto']) &&
            isset($this->attributes['rutaFoto'][0])
        ) {
            return url($this->attributes['rutaFoto']);
        }
        return url(self::RUTA_FOTO_DEFAULT);
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'idPersona');
    }
}
