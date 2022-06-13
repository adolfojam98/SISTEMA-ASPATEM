<?php

namespace App\Http\Controllers;

use App\CuotaDetalleTipo;
use Illuminate\Http\Request;
use App\Http\Services\CuotaService;

class CuotaDetalleTipoController extends ApiController
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
        $cuotaDetalleTipo = CuotaDetalleTipo::all();
        return $cuotaDetalleTipo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $request->validate([
                'nombre' => 'required',
                'porcentaje' => 'numeric|nullable',
                'valor' => 'numeric|nullable'
            ]);

            $service = new CuotaService();

            $service->createCuotaDetalleTipo($request->get('nombre'), $request->get('porcentaje'), $request->get('valor'));

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            return $this->sendResponse(null, 'Tipo de detalle generado con exito');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CuotaDetalleTipo  $cuotaDetalleTipo
     * @return \Illuminate\Http\Response
     */
    public function show(CuotaDetalleTipo $cuotaDetalleTipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CuotaDetalleTipo  $cuotaDetalleTipo
     * @return \Illuminate\Http\Response
     */
    public function edit(CuotaDetalleTipo $cuotaDetalleTipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CuotaDetalleTipo  $cuotaDetalleTipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CuotaDetalleTipo $cuotaDetalleTipo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CuotaDetalleTipo  $cuotaDetalleTipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(CuotaDetalleTipo $cuotaDetalleTipo)
    {
        //
    }
}
