<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre','apellido','mail','puntos','telefono','socio'];

    public function relaciones()
    {
        return $this->hasMany(Relaciones::Class);
    }
}

