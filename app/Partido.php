<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{

    protected $fillable = ['fecha_id','categoria_id','partido_fase_id','grupo_id','sig_partido_id'];

    public function jugadores(){
        return $this->belongsToMany(Usuario::class)->withPivot('sets');
    }

    public function fase()
    {
        return $this->hasOne(PartidoFase::class, 'id', 'partido_fase_id');
    }

    public function grupo()
    {
        return $this->hasOne(Grupo::class, 'id', 'grupo_id');
    }
}
