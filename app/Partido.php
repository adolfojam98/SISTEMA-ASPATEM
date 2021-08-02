<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    public function jugadores(){
        return $this->belongsToMany(Usuario::class)->withPivot('sets');
    }
}
