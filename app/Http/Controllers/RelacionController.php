<?php

namespace App\Http\Controllers;

use App\Relacion;
use App\Usuario;
use Illuminate\Http\Request;

class RelacionController extends Controller
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
        $relacion = new Relacion();
        $relacion->relacion = $request->relacion;
        $relacion->save();
        $relacion->usuarios()->attach($request->id_socio_B);
        $relacion->save();
        $response = Relacion::with('usuarios')->find($relacion->id);
        $response->usuario = $response->usuarios->first();
       $relacion->usuarios()->attach($request->id_socio_A);
        $relacion->save();

        return $response;

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Relacion  $relacion
     * @return \Illuminate\Http\Response
     */
    public function show(Relacion $relacion)
    {
        $relacionado = Relacion::findOrFail($relacion->id);
        return $relacionado;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Relacion  $relacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Relacion $relacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Relacion  $relacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relacion $relacion)
    {
        $relacion = Relacion::findOrFail($request->id);

        $relacion->id_socio_A = $request->id_socio_A;
        $relacion->id_socio_B = $request->id_socio_B;
        $relacion->relacion = $request->relacion;

        $relacion->save();

        return $relacion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relacion  $relacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $relacion)
    {
        $relacionEliminar= Relacion::findOrFail($relacion->id);
        $relacionEliminar->delete();
        return $relacionEliminar;
    }


    public function existe(Request $request)
    {
        $relaciones = Relacion::all();


        foreach($relaciones as $relacion){
            if($relacion->usuarios->contains($request->id_socio_A) && $relacion->usuarios->contains($request->id_socio_B)){
                
                return response()->json(true);
            }

        }

        return response()->json(false);
        
        // if(Relacion::with('usuarios')->where('id'))
        // if (!(Relacion::where("id_socio_A", $request->id_socio_A)
        //             ->where("id_socio_B", $request->id_socio_B)->get())->isEmpty()) {
        //     $existe=true;
        // } elseif (!(Relacion::where("id_socio_A", $request->id_socio_B)
        //                 ->where("id_socio_B", $request->id_socio_A)->get())->isEmpty()) {
        //     $existe=true;
        // }
                        
        
    }
}