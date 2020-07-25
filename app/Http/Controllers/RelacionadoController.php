<?php

namespace App\Http\Controllers;

use App\Relacionado;
use Illuminate\Http\Request;

class RelacionadoController extends Controller
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
        $relacionado = new Relacionado();
        $relacionado->id_socio_A = $request->id_socio_A;
        $relacionado->id_socio_B = $request->id_socio_B;
        $relacionado->relacionado = $request->relacionado;


        $relacionado->save();
        //Esta función guardará las tareas que enviaremos mediante vuejs
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Relacionado  $relacionado
     * @return \Illuminate\Http\Response
     */
    public function show(Relacionado $relacionado)
    {
        $relacionado = Relacionado::findOrFail($request->id);
        return $relacionado;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Relacionado  $relacionado
     * @return \Illuminate\Http\Response
     */
    public function edit(Relacionado $relacionado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Relacionado  $relacionado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $relacionado = Relacionado::findOrFail($request->id);

        $relacionado->id_socio_A = $request->id_socio_A;
        $relacionado->id_socio_B = $request->id_socio_B;
        $relacionado->relacionado = $request->relacionado;

        $relacionado->save();

        return $relacionado;
        //Esta función actualizará la tarea que hayamos seleccionado
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relacionado  $relacionado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relacionado $relacionado)
    {
        $relacionado = Relacionado::findOrFail($request->id);
        $relacionado->delete();

        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
    }














}
