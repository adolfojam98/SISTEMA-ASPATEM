<?php

namespace App\Http\Services;

use App\Error;
use App\Pago;
use App\Torneo;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TorneoService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("TOR0001", "Unespected error", "Unespected", 500);
    }

    public function calculateCategoria($torneo_id, $puntos_jugador)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $torneo = Torneo::whereId($torneo_id)->first();
        $categorias = $torneo->categorias;
        
        if($torneo && $categorias && $puntos_jugador >= 0) {
            $categoria_jugador = null;

            foreach ($categorias as $key => $categoria) {
                if($categoria->puntos_minimos <= $puntos_jugador && $categoria->puntos_maximos >= $puntos_jugador) {
                    $categoria_jugador = $categoria;
                }
            }

            return $categoria_jugador;

        } else {
            $this->setError("TORO0001");
        }

        return false;
    }

}