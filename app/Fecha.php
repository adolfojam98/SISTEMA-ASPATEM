<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Torneo;

class Fecha extends Model
{
    public function jugadores(){
        return $this->belongsToMany(Usuario::class)->withPivot('puntos','monto_pagado');
    }

    public function calcularIngresos(){
        return DB::table('fecha_usuario')
            ->where('fecha_id',$this->id)
            ->sum('monto_pagado');
    }

    public function getTorneoNombre(){
        return Torneo::whereId($this->torneo_id)->first()->nombre;
    }
}
