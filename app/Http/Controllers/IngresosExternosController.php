<?php

namespace App\Http\Controllers;

use App\IngresosExternos;
use Illuminate\Http\Request;

class IngresosExternosController extends Controller
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
    public function create($data_start = null, $date_end = null)
    {
        $montos = [];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ingresoExterno = new IngresosExternos();
        $ingresoExterno->monto = $request->monto;
        $ingresoExterno->descripcion = $request->descripcion;
        $ingresoExterno->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IngresosExternos  $ingresosExternos
     * @return \Illuminate\Http\Response
     */
    public function show(IngresosExternos $ingresosExternos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IngresosExternos  $ingresosExternos
     * @return \Illuminate\Http\Response
     */
    public function edit(IngresosExternos $ingresosExternos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IngresosExternos  $ingresosExternos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IngresosExternos $ingresosExternos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IngresosExternos  $ingresosExternos
     * @return \Illuminate\Http\Response
     */
    public function destroy(IngresosExternos $ingresosExternos)
    {
        //
    }
}
