<?php

namespace App;
use App\Relacion;
use App\Cuota;
use App\Torneo;
use App\Configuracion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Constants;

class Usuario extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre','apellido','mail','puntos','telefono','socio','dni'];

    public function relaciones()
    {
        return $this->belongsToMany(Relacion::class)->withPivot('usuario_id');
    }

    public function hasRelacionesWithSocios(){
        $usuario = Usuario::with('relaciones.usuarios')->find($this->id);
        $relaciones = $usuario->relaciones()->get();
        
        $hasRelacionesWithSocios = false;
        foreach ($relaciones as $relacion) {
            $relacion->usuario = $relacion->usuarios->firstWhere('id', '!=', $this->id);

            if($relacion->usuario->socio()->activo){
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

    public function getPartidosByFechaId($fecha_id)
    {
        return $this->partidos()->where('fecha_id', $fecha_id);
    }


    public function fechas()
    {
        return $this->belongsToMany(Fecha::class)->withPivot('puntos','monto_pagado');
    }

    public function cuotas(){
        return $this->hasMany(Cuota::class)->orderByDesc('id');
    }

    public function socio(){
        /*
        Socio activo
                1) Si socio true
                2) Si tiene cuotas
        Socio inactivo
                1) Si socio false
                2 Si tiene cuota
        Externo
                1) Si socio false
                2) Si no tiene cuota     
        */

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

        //obtenemos los datos de configuracion: para corregir la performance, esto no deberia estar aca
        $defaultConfiguraciones = Constants::CONFIGURACIONES_DEFAULT;
        $keys = array_keys($defaultConfiguraciones);

        $bajaPorCuotasNoPagas = Configuracion::Where('nombre', $keys[0])->first();
        $cantidadDeCuotasAdeudadasParaDarDeBaja = Configuracion::Where('nombre', $keys[1])->first();

        if($bajaPorCuotasNoPagas->valor) {
            //obtenemos las cuotas pasadas (la cantidad depende de la configuracion)
            $lastXCuotas = Cuota::Where('usuario_id', $this->id)
            ->where('periodo', '<=', new Carbon())
            ->latest()->take($cantidadDeCuotasAdeudadasParaDarDeBaja->valor)->get();

            //obtenemos las cuotas futuras, por si pago por adelantado
            $futureCuotas = Cuota::Where('usuario_id', $this->id)
            ->where('periodo', '>', new Carbon())->get();

            //si tiene alguna de las ultimas cuotas pagas no es moroso
            $moroso = true;
            foreach ($lastXCuotas as $key => $cuota) {
                if($cuota->pago) {
                    $moroso = false;
                }
            }

            //si pago alguna cuota futura, tampoco es moroso
            foreach ($futureCuotas as $key => $cuota) {
                if($cuota->pago) {
                    $moroso = false;
                }
            }
        } else {
            //más que moroso yo diria que no esta activo, pero ya no se entiende nada esto...
            $moroso = false;
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