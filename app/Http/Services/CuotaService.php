<?php

namespace App\Http\Services;

use App\Constants;
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
           
        $this->errorDefinitions[] = new Error("CUOTA0001", "Error inesperado", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0002", "Un monto especifico necesita una descripcion", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0003", "Un nuevo tipo de detalle necesita un porcentaje o valor", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0004", "Los tipos de detalles defaults aun no se han configurado", "Unespected", 500);
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

        if(!$cuota_id || !$cuota_detalle_tipo_id || $monto === null) {
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

            //verifico si es default para que valide si puede ser null o no el porcentaje y valor
            $detallesTiposDefault = Constants::CuotaDetalleTipos;
            $isDefult = in_array($cuotaDetalleTipo->codigo, $detallesTiposDefault);

            if($porcentaje)
                $cuotaDetalleTipo->porcentaje = $porcentaje;

            if($valor)
                $cuotaDetalleTipo->valor = $valor;

            if(!$isDefult)
                if((!$porcentaje && !$valor) || ($porcentaje && $valor)) {
                    $this->setError("CUOTA0003");
                }

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

            if((!$porcentaje && !$valor) || ($porcentaje && $valor)) {
                $this->setError("CUOTA0003");
            }

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

    public function validateCuotaDetalleTiposDefaults()
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();
        
        $detallesTiposDefault = Constants::CuotaDetalleTipos;

        $validatedDetallesTiposDefault = CuotaDetalleTipo::whereIn('codigo', $detallesTiposDefault)
            ->where(function ($query) {
                $query->whereNotNull('porcentaje')
                    ->orWhereNotNull('valor');
            })->count();

        if(count($detallesTiposDefault) === $validatedDetallesTiposDefault) {
            return true;
        } else {
            $this->setError("CUOTA0004");
            return false;
        }
    }

    public function updateLatePayment($usuario_id = null, $cuota_id = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $detallesTiposDefault = Constants::CuotaDetalleTipos;
        
        //traemos las cuotas no pagadas
        $cuotasNoPagas = Cuota::All()->filter(function ($cuota) {
            return !$cuota->pago;
        })->values();

        //filtramos si es que viene usuario_id o cuota_id
        if($usuario_id) {
            $cuotasNoPagas = $cuotasNoPagas->where('usuario_id', $usuario_id);
        }

        if($cuota_id) {
            $cuotasNoPagas = $cuotasNoPagas->where('cuota_id', $cuota_id);
        }

        //eliminamos si es que existe un detalle de morosidad y agregamos el que corresponde
        foreach ($cuotasNoPagas as $key => $cuota) {
            foreach ($cuota->detalles as $key => $detalle) {
                $detalleTipo = CuotaDetalleTipo::find($detalle->cuota_detalle_tipo_id);
                $codigosDetallesMorosos = [$detallesTiposDefault['Moroso Bajo'], $detallesTiposDefault['Moroso Medio'], $detallesTiposDefault['Moroso Alto']];

                if(in_array($detalleTipo->codigo, $codigosDetallesMorosos)) {
                    $detalle->delete();
                }
            }

            //calculamos si esta atrasado
            if(date_create(date('Y-m-d')) > date_create($cuota->periodo)) {
                # obtenemos la diferencia entre las dos fechas
                $interval = date_create(date('Y-m-d'))->diff(date_create($cuota->periodo));
                # obtenemos la diferencia en meses + obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses
                $intervalMeses = $interval->format("%m") + $interval->format("%y")*12;

                //tomamos el precio base
                $detalleTipoPrecioBase = CuotaDetalleTipo::where('codigo', $detallesTiposDefault['Precio Base'])->first()->valor;

                //generamos el detalle segun el tipo de morosidad
                switch ($intervalMeses) {
                    case $intervalMeses == 1:
                        $detalleTipoMorosoBajo = CuotaDetalleTipo::where('codigo', $detallesTiposDefault['Moroso Bajo'])->first();
                        $this->createCuotaDetalle($cuota->id, $detalleTipoMorosoBajo->id, $detalleTipoMorosoBajo->porcentaje ? $detalleTipoPrecioBase * $detalleTipoMorosoBajo->porcentaje : $detalleTipoMorosoBajo->valor, 'Moroso Bajo');
                        break;

                    case $intervalMeses == 2:
                        $detalleTipoMorosoMedio = CuotaDetalleTipo::where('codigo', $detallesTiposDefault['Moroso Medio'])->first();
                        $this->createCuotaDetalle($cuota->id, $detalleTipoMorosoMedio->id, $detalleTipoMorosoMedio->porcentaje ? $detalleTipoPrecioBase * $detalleTipoMorosoMedio->porcentaje : $detalleTipoMorosoMedio->valor, 'Moroso Medio');
                        break;
                        
                    case $intervalMeses >= 3:
                        $detalleTipoMorosoAlto = CuotaDetalleTipo::where('codigo', $detallesTiposDefault['Moroso Alto'])->first();
                        $this->createCuotaDetalle($cuota->id, $detalleTipoMorosoAlto->id, $detalleTipoMorosoAlto->porcentaje ? $detalleTipoPrecioBase * $detalleTipoMorosoAlto->porcentaje : $detalleTipoMorosoAlto->valor, 'Moroso Alto');
                        break;
                    
                    default:
                        break;
                }
            }
        }
    }

}