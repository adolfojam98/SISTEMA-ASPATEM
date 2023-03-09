<?php

namespace App\Http\Services;

use App\Error;
use App\Fecha;
use App\FechaUsuario;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FechaService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("FEC0001", "Unespected error", "Unespected", 500);
    }


    function updateFechaUsuario($fecha_id, $usuario_id, $categoria_mayor_id = null, $categoria_menor_id = null, $monto_pagado = null, $puntos = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $fecha = Fecha::whereId($fecha_id)->first();
        $fecha_usuario = $fecha->fecha_usuario($usuario_id)->first();

        if(!$fecha_usuario)
        {
            $fecha_usuario = new FechaUsuario();
            $fecha_usuario->fecha_id = $fecha_id;
            $fecha_usuario->usuario_id = $usuario_id;
        }

        if($fecha_id && $usuario_id)
        {
            $fecha_usuario->pivot->categoria_menor_id = $categoria_menor_id;
            $fecha_usuario->pivot->categoria_mayor_id = $categoria_mayor_id;

            if($monto_pagado){
                $fecha_usuario->pivot->monto_pagado = $monto_pagado;
            }

            if($puntos){
                $fecha_usuario->pivot->puntos = $puntos;
            }
            
            $fecha_usuario->save();

            return $fecha_usuario;

        } else {
            $this->setError("FEC0001");
        }

        return false;
    }

}