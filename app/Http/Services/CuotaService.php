<?php

namespace App\Http\Services;

use App\Error;
use App\CuotaDetalleTipo;
use App\CuotaDetalle;
use App\Cuota;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CuotaService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("CUOTA0001", "Unespected error", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0002", "Specific mount need an specific description", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0003", "To store a new CuotaDetalleTipe you need a porcentaje or value", "Unespected", 500);
    }

    public function createCuota($usuario_id, $periodo)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();
        
        if($periodo && $usuario_id) {
            $newCuota = new Cuota();
            $newCuota->periodo = $periodo;
            $newCuota->usuario_id = $usuario_id;
            $newCuota->save();

            return $newCuota;

        } else {
            $this->setError("CUOTA0001");
        }

        return false;
    }

    public function createCuotaDetalle($cuota_id, $cuota_detalle_tipo_id, $monto, $descripcion = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        if(!$cuota_id || !$cuota_detalle_tipo_id || $monto == null) {
            $this->setError("CUOTA0001");
            return false;
        } else {
            $cuotaDetalle = new CuotaDetalle();
            $cuotaDetalle->cuota_id = $cuota_id;
            $cuotaDetalle->cuota_detalle_tipo_id = $cuota_detalle_tipo_id;
            $cuotaDetalle->monto = $monto;
            $cuotaDetalle->descripcion = $descripcion;
            $cuotaDetalle->save();
            
            return $cuotaDetalle;

        }      

        return false;

    }

    public function createCuotaDetalleTipo($nombre, $porcentaje = null, $valor = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        if(trim($nombre)) {
            $cuotaDetalleTipo = new CuotaDetalleTipo();
            $cuotaDetalleTipo->nombre = $nombre;
            $cuotaDetalleTipo->codigo = Str::slug($nombre, '_');

            if($porcentaje)
                $cuotaDetalleTipo->porcentaje = $porcentaje;

            if($valor)
                $cuotaDetalleTipo->valor = $valor;

            // if(!$porcentaje && !$valor) {
            //     $this->setError("CUOTA0003");
            // }

            $cuotaDetalleTipo->save();

            return $cuotaDetalleTipo;

        } else {
            $this->setError("CUOTA0001");
        }

        return false;
    }

    public function updateCuotaDetalleTipo($cuota_detalle_tipo_id, $nombre = null, $porcentaje = null, $valor = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $cuotaDetalleTipo = CuotaDetalleTipo::find($cuota_detalle_tipo_id);

        if($cuota_detalle_tipo_id && $cuotaDetalleTipo) {

            if($nombre) {
                $cuotaDetalleTipo->nombre = $nombre;
                $cuotaDetalleTipo->codigo = Str::slug($nombre, '_');
            }

            if($porcentaje)
                $cuotaDetalleTipo->porcentaje = $porcentaje;

            if($valor)
                $cuotaDetalleTipo->valor = $valor;

            // if(!$porcentaje && !$valor) {
            //     $this->setError("CUOTA0003");
            // }

            $cuotaDetalleTipo->update();

            return $cuotaDetalleTipo;

        } else {
            $this->setError("CUOTA0001");
        }

        return false;
    }

    public function deleteCuotaDetalleTipo($cuota_detalle_tipo_id)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        if($cuota_detalle_tipo_id) {
            $cuotaDetalleTipo = CuotaDetalleTipo::find($cuota_detalle_tipo_id);
            $cuotaDetalleTipo->delete();

            return $cuotaDetalleTipo;

        } else {
            $this->setError("CUOTA0001");
        }

        return false;
    }

}