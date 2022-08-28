<?php

namespace App;
use App\Relacion;
use App\Cuota;
use App\Torneo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Usuario extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre','apellido','mail','puntos','telefono','socio','dni'];

    public function relaciones()
    {
        return $this->belongsToMany(Relacion::class)->withPivot('usuario_id');
    }

    public function hasRelacionesWithSocios(){
        $usuario = Usuario::with('relaciones.usuarios')->find(1);
        $relaciones = $usuario->relaciones;

        $hasRelacionesWithSocios = false;
        foreach ($relaciones as $relacion) {
            $relacion->usuario = $relacion->usuarios->firstWhere('id', '!=', $this->id);
            if($relacion->usuario->socio == 1){
                $hasRelacionesWithSocios = true;
            }
        }
        return $hasRelacionesWithSocios;
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class)->withPivot('puntos')->withTimestamps();
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

    public function socio(){
        $socio = false;
        $activo = false;

        //obtenemos las cuotas no pagas
        $cuotasNoPagas = Cuota::Where('usuario_id', $this->id)->get()->filter(function ($cuota) {
            return !$cuota->pago;
        })->values();

        //si tiene alguna cuota generada entonces alguna vez fue socio
        $hasLeastOneCuota = Cuota::Where('usuario_id', $this->id)->get()->count();

        //si alguna vez fue socio y tiene las ultimas 3 cuotas no pagas es inactivo
        if($cuotasNoPagas->count() >= 3 ){
            $socio = true;
        } else {
            $socio = true;
        }

        if(!$hasLeastOneCuota) {
            $socio = false;
        }

        return $socio;
    }
}