<?php

namespace App\Http\Controllers;

use App\Cuota;
use Illuminate\Http\Request;

class CuotaController extends Controller
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
        $cuota = new Cuota();
        $cuota->id_usuario = $request->id_usuario;
        $cuota->mes = $request->mes;
        $cuota->anio = $request->anio;
        $cuota->importe = $request->importe;
        //el importe tiene que ser igual al importe por defecto que este en configuracion

        $cuota->save();

        return response()->json([
            'message' => 'Cuota creada',
            'id' => $cuota->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cuota  $cuota
     * @return \Illuminate\Http\Response
     */
    public function show(Cuota $cuota)
    {
        $cuota = Cuota::findOrFail($request->id);
        return $cuota;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cuota  $cuota
     * @return \Illuminate\Http\Response
     */
    public function edit(Cuota $cuota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cuota  $cuota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuota $cuota)
    {
        $cuota = Cuota::findOrFail($request->id);

        $cuota->id_usuario = $cuota->id_usuario; //no es requerido
        $cuota->mes = $cuota->mes;
        $cuota->anio = $cuota->anio;
        $cuota->descuento = $request->descuento;
        $cuota->importe = $cuota->importe;
        $cuota->fechaPago = $request->fechaPago;

        $cuota->save();

        return $cuota;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cuota  $cuota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuota $cuota)
    {
        $cuota = Cuota::findOrFail($request->id);
        $cuota->delete();
        
    }
}
