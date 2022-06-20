<?php

namespace App\Http\Services;

use App\Error;
use App\Cuota;
use App\Usuario;
use App\Configuracion;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UsuarioService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("USUA0001", "Unespected error", "Unespected", 500);
    }

    public function recalculateValueSocio($id)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();
        
        $usuario = Usuario::whereId($id)->first();

        if(!$usuario) {
            $this->setError("USUA0001");
            return false;
        }

        //obtenemos las cuotas no pagas
        $cuotasNoPagas = Cuota::Where('usuario_id', $id)->get()->filter(function ($cuota) {
            return !$cuota->pago;
        })->values();

        //si tiene alguna cuota generada entonces alguna vez fue socio
        $hasLeastOneCuota = Cuota::Where('usuario_id', $id)->get()->count();

        //si alguna vez fue socio y tiene un detalle cuota de moroso alto no pago, le damos de baja
        if($hasLeastOneCuota) {
            $socio = true;
            $cuotasNoPagas->each(function ($cuota) {
                foreach ($cuota->detalles() as $key => $detalle) {
                    if($detalle->codigo === 'moroso_alto') {
                        $socio = false;
                    }
                }
            });

            $usuario->socio = $socio;
            $usuario->save();

            return $socio;

        }

        $usuario->socio = false;
        $usuario->save();

        return false;
    }

}