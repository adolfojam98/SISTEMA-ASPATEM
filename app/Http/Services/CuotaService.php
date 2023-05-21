<?php

namespace App\Http\Services;

use App\Constants;
use App\Error;
use App\CuotaDetalleTipo;
use App\CuotaDetalle;
use App\Cuota;
use App\Usuario;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

use App\Http\Services\UsuarioService;

class CuotaService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("CUOTA0001", "Error inesperado", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0002", "Un monto especifico necesita una descripcion", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0003", "Un nuevo tipo de detalle necesita un porcentaje o valor", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0004", "Los tipos de detalles defaults aun no se han configurado", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0005", "No puede eliminar un tipo de detalle que ya esta en uso", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CUOTA0006", "Ya existe un tipo de detalle con el nombre ingresado", "Unespected", 500);

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
            if(CuotaDetalleTipo::where('nombre', $nombre)->first()) {
                $this->setError("CUOTA0006");
                return false;
            }

            $cuotaDetalleTipo = new CuotaDetalleTipo();
            $cuotaDetalleTipo->nombre = $nombre;
            $cuotaDetalleTipo->codigo = Str::slug($nombre, '_');

            //verifico si es default para que valide si puede ser null o no el porcentaje y valor
            $detallesTiposDefault = Constants::CuotaDetalleTipos;
            $isDefult = in_array($cuotaDetalleTipo->codigo, $detallesTiposDefault);

            if(isset($porcentaje)) {
                $cuotaDetalleTipo->porcentaje = $porcentaje;
                $cuotaDetalleTipo->valor = null;
            }

            if(isset($valor)) {
                $cuotaDetalleTipo->valor = $valor;
                $cuotaDetalleTipo->porcentaje = null;
            }

            if(!$isDefult) {
                if((!isset($porcentaje) && !isset($valor)) || (isset($porcentaje) && isset($valor))) {
                    $this->setError("CUOTA0003");
                    return false;
                }
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

            if(trim($nombre)) {
                if($cuotaDetalleTipo->nombre !== $nombre && CuotaDetalleTipo::where('nombre', $nombre)->first()) {
                    $this->setError("CUOTA0006");
                    return false;
                }

                $cuotaDetalleTipo->nombre = $nombre;
                $cuotaDetalleTipo->codigo = Str::slug($nombre, '_');
            }

            if(isset($porcentaje)) {
                $cuotaDetalleTipo->porcentaje = $porcentaje;
                $cuotaDetalleTipo->valor = null;
            }

            if(isset($valor)) {
                $cuotaDetalleTipo->valor = $valor;
                $cuotaDetalleTipo->porcentaje = null;
            }

            if((!isset($porcentaje) && !isset($valor)) || (isset($porcentaje) && isset($valor))) {
                $this->setError("CUOTA0003");
                return false;
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
            //verificamos que no haya cuotas con este tipo de detalle
            $hasCuotaDetalle = CuotaDetalle::where('cuota_detalle_tipo_id', $cuota_detalle_tipo_id)->exists();
            
            if($hasCuotaDetalle) {
                $this->setError("CUOTA0005");
                return false;
            }

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
            $today = new \DateTime();
            $period = new \DateTime($cuota->periodo);
            if($today > $period) {
                # obtenemos la diferencia entre las dos fechas
                $interval = $today->diff($period);
                # obtenemos la diferencia en meses + obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses
                $intervalMeses = $interval->format("%m") + $interval->format("%y")*12;
                //tomamos el precio base
                $detalleTipoPrecioBase = CuotaDetalleTipo::where('codigo', $detallesTiposDefault['Precio Base'])->first()->valor;

                //generamos el detalle segun el tipo de morosidad
                if($intervalMeses)
                    switch ($intervalMeses) {
                        case $intervalMeses === 1:
                            $detalleTipoMorosoBajo = CuotaDetalleTipo::where('codigo', $detallesTiposDefault['Moroso Bajo'])->first();
                            $this->createCuotaDetalle($cuota->id, $detalleTipoMorosoBajo->id, $detalleTipoMorosoBajo->porcentaje ? $detalleTipoPrecioBase * $detalleTipoMorosoBajo->porcentaje : $detalleTipoMorosoBajo->valor, 'Moroso Bajo');
                            break;

                        case $intervalMeses === 2:
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

        $service = new UsuarioService();
        
        foreach (Usuario::all() as $key => $usuario) {
            $service->recalculateValueSocio($usuario->id);
        }

    }

}