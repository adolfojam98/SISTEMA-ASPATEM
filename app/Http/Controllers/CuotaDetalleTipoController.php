<?php

namespace App\Http\Controllers;

use App\CuotaDetalleTipo;
use Illuminate\Http\Request;
use App\Http\Services\CuotaService;
use App\Http\Resources\CuotaDetalleTipo as CuotaDetalleTipoResource;

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
        try
        {
            $cuotaDetalleTipos = CuotaDetalleTipo::all();

            if ($cuotaDetalleTipos)
            {
                return $this->sendResponse(CuotaDetalleTipoResource::collection($cuotaDetalleTipos), 'Tipos de detalles listados con exito');
            }

            return $this->sendResponse(null, 'Tipo de detalle no se han podido listar');
        }
        
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
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
    public function show($cuotaDetalleTipoId)
    {
        try
        {
            $cuotaDetalleTipo = CuotaDetalleTipo::find($cuotaDetalleTipoId);

            if ($cuotaDetalleTipo)
            {
                return $this->sendResponse(new CuotaDetalleTipoResource($cuotaDetalleTipo), 'Tipo de detalle encontrado con exito.');
            }
            else
            {
                return $this->sendError('Tipo de detalle no encontrado');
            }
        }
        
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        } 
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
    public function update(Request $request, $id)
    {
        try
        {
            $request->validate([
                'nombre' => 'string|nullable',
                'porcentaje' => 'numeric|nullable',
                'valor' => 'numeric|nullable'
            ]);

            $service = new CuotaService();

            $service->updateCuotaDetalleTipo($id, $request->get('nombre'), $request->get('porcentaje'), $request->get('valor'));

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            return $this->sendResponse(null, 'Tipo de detalle actualizada con exito');

        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CuotaDetalleTipo  $cuotaDetalleTipo
     * @return \Illuminate\Http\Response
     */
    public function destroy($cuotaDetalleTipoId)
    {
        try
        {
            $cuotaDetalleTipo = CuotaDetalleTipo::find($cuotaDetalleTipoId)->exists();

            if ($cuotaDetalleTipo)
            {
                $service = new CuotaService();
                $service->deleteCuotaDetalleTipo($cuotaDetalleTipoId);

                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                }
                
                return $this->sendResponse(null, 'Tipo de detalle eliminado con exito'); 
            }
            else
            {
                return $this->sendError('Tipo de detalle no encontrado');
            }
        }
        
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }
}
