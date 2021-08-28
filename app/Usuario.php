<?php

namespace App;
use App\Relacion;
use App\Cuota;
use App\Torneo;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre','apellido','mail','puntos','telefono','socio','dni'];

    public function relaciones()
    {
        return $this->belongsToMany(Relacion::class)->withPivot('usuario_id');
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class)->withPivot('puntos');
    }
    public function partidos()
    {
        return $this->belongsToMany(Partido::class)->withPivot('sets');
    }
    public function fechas()
    {
        return $this->belongsToMany(Fecha::class)->withPivot('puntos','monto_pagado');
    }

   public function cuotas(){
       return $this->hasMany(Cuota::class)->orderByDesc('id');
   }
}