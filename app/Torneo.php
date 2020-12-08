<?php

namespace App;
use App\Categoria;

use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    protected $fillable = ['nombre'];
    
    public function categorias(){
        return $this->hasMany(Categoria::class);
    }

    public function jugadores(){
        return $this->belongsToMany(Usuario::class)->withPivot('puntos');
    }
    
}
