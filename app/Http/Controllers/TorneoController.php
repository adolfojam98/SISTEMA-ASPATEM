<?php

namespace App\Http\Controllers;

use App\Torneo;
use App\Usuario;

use Illuminate\Http\Request;

class TorneoController extends Controller
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
        $torneos = Torneo::all();
        return $torneos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  
        $gestionPuntos = $request->gestionPuntos;
        
        $torneo = new Torneo();
       
        $torneo->nombre = $request->nombreTorneo;
        $torneo->misma_categoria_mayor_nivel_ganador    = $gestionPuntos['mismaCat_MayorNivel_Ganador'];
        $torneo->misma_categoria_mayor_nivel_perdedor   = $gestionPuntos['mismaCat_MayorNivel_Perdedor'];
        $torneo->misma_categoria_menor_nivel_ganador    = $gestionPuntos['mismaCat_MenorNivel_Ganador'];
        $torneo->misma_categoria_menor_nivel_perdedor   = $gestionPuntos['mismaCat_MenorNivel_Perdedor'];
        $torneo->mayor_categoria_ganador                = $gestionPuntos['diferenteCat_MayorNivel_Ganador'];
        $torneo->mayor_categoria_perdedor               = $gestionPuntos['diferenteCat_MayorNivel_Perdedor'];
        $torneo->menor_categoria_ganador                = $gestionPuntos['diferenteCat_MenorNivel_Ganador'];
        $torneo->menor_categoria_perdedor               = $gestionPuntos['diferenteCat_MenorNivel_Perdedor'];
        $torneo->vigencia = 1;
        $torneo->save();
        return $torneo->id;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Torneo  $torneo
     * @return \Illuminate\Http\Response
     */
    public function show(Torneo $torneo)
    {
        //        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Torneo  $torneo
     * @return \Illuminate\Http\Response
     */
    public function edit(Torneo $torneo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Torneo  $torneo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Torneo $torneo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Torneo  $torneo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Torneo $torneo)
    {
        //
    }

    public function storeJugadores(Request $request){

        $jugadores = $request->jugadores;

        foreach ($jugadores as $jugador) {

            $usuario = Usuario::where('dni','=',$jugador['dni'])->first();

            if(empty($usuario)){
                
                $nuevoUsuario = new Usuario();

                $nuevoUsuario->nombre =     $jugador['nombre'];                
                $nuevoUsuario->apellido =   $jugador['apellido'];
                $nuevoUsuario->dni =        $jugador['dni'];
                $nuevoUsuario->socio = 0;
                $nuevoUsuario->save();
                $usuario = $nuevoUsuario;
            }
            if (!empty($jugador['puntos'])) {
                
                $usuario->torneos()->attach($request->id_torneo, ['puntos' => $jugador['puntos']]);
            }
            
            if(!empty($jugador['pivot']['puntos'])){
                $usuario->torneos()->attach($request->id_torneo, ['puntos' => $jugador['pivot']['puntos']]);
            }

        $usuario->save();

            
        }


    }

    public function getJugadores(Request $request){
       
        $torneo = Torneo::find($request->id);
        $jugadores = $torneo->jugadores;

        foreach($jugadores as $jugador){
            $jugador->montoPagado=0;
        };
        return $jugadores;
        
        // return $torneo->jugadores;
        /*
        
        $torneo = Torneo::find($request->id);
        $jugadores = $torneo->jugadores;

        $listaJugadores=[];


        foreach($jugadores as $jugador){
            $UnJugador = Usuario::where('id',$jugador->id)->get();
            array_push($listaJugadores, $UnJugador);
        }

        return $listaJugadores;
        */


    }

    public function getCategorias(Request $request){
        $torneo = Torneo::find($request->id);
        return $torneo->categorias;
    }

    public function getNameExists(Request $request){
        $torneo = Torneo::where('nombre',$request->nombre)->first();

        if(empty($torneo)){return 0;}
        else return true;
        
    }
}
