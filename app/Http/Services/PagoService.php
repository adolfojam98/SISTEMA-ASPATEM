<?php

namespace App\Http\Services;

use App\Error;
use App\Pago;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PagoService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("PAGO0001", "Unespected error", "Unespected", 500);
        $this->errorDefinitions[] = new Error("PAGO0002", "Duplicated pay", "Duplicated", 500);
    }

    public function createPago($cuota_id, $monto_total, $fecha_pago)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $pago = Pago::where('cuota_id', $cuota_id)->first();

        if($pago) {
            $this->setError("PAGO0002");
            return false;
        }
        
        if($cuota_id && $monto_total && $fecha_pago) {
            $newPago = new Pago();
            $newPago->cuota_id = $cuota_id;
            $newPago->monto_total = $monto_total;
            $newPago->fecha_pago = $fecha_pago;
            $newPago->save();

            return $newPago;

        } else {
            $this->setError("PAGO0001");
        }

        return false;
    }

}