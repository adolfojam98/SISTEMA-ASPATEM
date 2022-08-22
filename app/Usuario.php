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
        /* casos: 
            1) no socio: socio == false y no tiene cuotas generadas; 
            2) inactivo: (socio == false y tiene cuotas generadas) || (socio == true y ultimas 4 cuotas no pagas);
            3) socio: socio == true y al menos una de las ultimas 4 cuotas pagada
        */
        $state = (object) [
            'socio' => null,
            'activo' => null
        ];

        //cantidad de cuotas generadas
        $hasLeastOneCuota = Cuota::Where('usuario_id', $this->id)->get()->count();

        //obtenemos las ultimas 4 cuotas
        $lastFourCuotas = Cuota::Where('usuario_id', $this->id)->latest()->take(4)->get();

        //si tiene alguna de las ultimas 4 cuotas pagas no es moroso
        $moroso = true;
        foreach ($lastFourCuotas as $key => $cuota) {
            if($cuota->pago) {
                $moroso = false;
            }
        }
        
        //caso 1
        if(!$this->socio && !$hasLeastOneCuota) {
            $state->socio = false;
            $state->activo = false;
        }
        //caso 2
        else if((!$this->socio && $hasLeastOneCuota) || ($this->socio && $moroso )) {
            $state->socio = true;
            $state->activo = false;
        }
        //caso 3
        else if($this->socio && !$moroso) {
            $state->socio = true;
            $state->activo = true;
        }

        return $state;
    }
}