<?php

namespace App\Http\Controllers;

use App\Pago;
use App\Cuota;
use App\CuotaDetalleTipo;
use Illuminate\Http\Request;

use App\Http\Services\CuotaService;
use App\Http\Services\PagoService;

use App\Http\Resources\Cuota as CuotaResource;
use App\Http\Resources\Pago as PagoResource;

class PagoController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $cuota_id) { 
        //TODO si viene monto y descripcion relacionamos con cuota_detalle_tipo de Otros sin valor ni porcentaje, en cuota_detalle ponemos la descripcion y el monto necesario para que +- lo que se desea que valga
        try
        {
            $request->validate([
                'monto' => 'numeric|nullable',
                'descripcion' => 'string|nullable',
                'fechaPago' => 'date|nullable'
            ]);
            $monto = $request->get('monto');
            $descripcion = $request->get('descripcion');
            $fechaPago = $request->get('fechaPago');
            $cuota = Cuota::whereId($cuota_id)->first();
            $montoTotal = $cuota->montoTotal();
            $service = new CuotaService();

            if($monto) {
                //calculamos la dif
                //creamos un nuevo detalle tipo Otros con la dif y la descripcion si la hay
                //luego el montoTotal pasa a ser = monto
                //y por ultimo el montoTotal es lo que se guarda en pago
                $dif = $monto - $montoTotal;
                $cuotaDetalleTipoOtros = CuotaDetalleTipo::where('codigo', 'otros')->first();
                $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoOtros->id, $dif, $descripcion);//TODO falta agregar la descripcion

                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                }

                $montoTotal = $monto;
            }

            if(!$fechaPago) {
                $fechaPago = date('Y/m/d H:i:s');
            }
            //ahora creamos el pago desde el service
            $service = new PagoService();
            $newPago = $service->createPago($cuota->id, $montoTotal, $fechaPago);

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }
            
            return $this->sendResponse(new PagoResource($newPago), 'Pago realizado con exito.');
            
        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
