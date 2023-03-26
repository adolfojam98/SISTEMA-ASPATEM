<?php

namespace App\Http\Services;

use App\Error;
use App\Fecha;
use App\FechaUsuario;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FechaService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("FEC0001", "Unespected error", "Unespected", 500);
    }

    function fechaUsuarioDelete($fecha_id, $usuario_id)
    {
        $this->clearErrors();
        
        $fecha_usuario = DB::table('fecha_usuario')
        ->where('usuario_id',$usuario_id)
        ->where('fecha_id', $fecha_id)->delete();
    }

    function updateFechaUsuario($fecha_id, $usuario_id, $categoria_mayor_id = null, $categoria_menor_id = null, $monto_pagado = null, $puntos = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $fecha = Fecha::whereId($fecha_id)->first();
        $fecha_usuario = $fecha->fecha_usuario($usuario_id)->first();

        if($fecha_id && $usuario_id)
        {

            if($fecha_usuario) {
                $this->fechaUsuarioDelete($fecha_id, $usuario_id);
            }

            $fecha_usuario = new FechaUsuario();
            $fecha_usuario->fecha_id = $fecha_id;
            $fecha_usuario->usuario_id = $usuario_id;

            $fecha_usuario->categoria_menor_id = $categoria_menor_id;
            $fecha_usuario->categoria_mayor_id = $categoria_mayor_id;
            $fecha_usuario->monto_pagado = $monto_pagado ?? 0;
            $fecha_usuario->puntos = $puntos ?? 0;
        
            $fecha_usuario->save();

            return $fecha_usuario;

        } else {
            $this->setError("FEC0001");
        }

        return false;
    }

    function updatePuntos($fecha_id)
    {
        //todo los jugadores que jugaron del torneo con su categoria y puntos
        $jugadores = Fecha::find($fecha_id)->first()->resumen_jugadores();

        //filtramos por los jugadores que juegan esta fecha
        

        dd($jugadores);
    }

}