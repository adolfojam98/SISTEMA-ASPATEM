<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Configuracion $configuracion)
    {
        $configuracion = Configuracion::first();
        return $configuracion;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $configuracion = Configuracion::first();

        if($configuracion!=null){
            $configuracion->montoCuota = $request->montoCuota;
            $configuracion->montoCuotaDescuento = $request->montoCuotaDescuento;
            $configuracion->save();
            return $configuracion;
        }

        else {
            $configuracion = new Configuracion();
            $configuracion->montoCuota = $request->montoCuota;
            $configuracion->montoCuotaDescuento = $request->montoCuotaDescuento;
            $configuracion->save();
            return response()->json([
                'message' => 'Creada y guardada'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }

    public function modificarAutomatizacion(Request $request)
    {
        $configuracion = Configuracion::first();

        if($configuracion!=NULL){
            $configuracion->automatizarBajasSocios = $request->automatizarBajasSocios;
            $configuracion->save();
        }
        else{
            $configuracion = new Configuracion();
            $configuracion->automatizarBajasSocios = $request->automatizarBajasSocios;
            $configuracion->save();
        }
    }



}
