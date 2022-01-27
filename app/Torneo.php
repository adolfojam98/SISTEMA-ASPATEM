<?php

namespace App;
use App\Categoria;
use App\Usuario;
use App\Fecha;

use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    protected $fillable = ['nombre'];
    
    public function categorias(){
        return $this->hasMany(Categoria::class);
    }

    public function jugadores(){
        return $this->belongsToMany(Usuario::class)->withPivot('puntos')->withTimestamps();
    }
    
    public function calcularIngresos(){
        $monto = Fecha::where('torneo_id',$this->id)
            ->join('fecha_usuario','fecha_usuario.fecha_id','=','fechas.id')
            ->sum('monto_pagado');
        return $monto;
    }
}
