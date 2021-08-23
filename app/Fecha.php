<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{
    public function jugadores(){
        return $this->belongsToMany(Usuario::class)->withPivot('puntos');
    }
}
