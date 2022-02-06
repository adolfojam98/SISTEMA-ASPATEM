<?php

namespace App\Http\Controllers;

use App\Torneo;
use App\Usuario;
use App\Categoria;
use DB;
use App\Fecha;
use Carbon\Carbon;

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

        $torneo_usuario = DB::table('torneo_usuario')
            ->where('torneo_id', $request->id_torneo)
            ->where('usuario_id', $usuario->id)->first();

        $usuario->pivot = $torneo_usuario;
        $usuario->montoPagado = 0;
       
        return $usuario;

    }

    public function getJugadores(Request $request){
       
        $torneo = Torneo::find($request->id);
        $jugadores = $torneo->jugadores;
        $fechas = Fecha::where('torneo_id',$torneo->id)->get();

        foreach($jugadores as $jugador){
            $jugador->montoPagado = 0;
            
            foreach ($fechas as $key => $fecha) {
                $fecha_usuario = DB::table('fecha_usuario')
                ->where('usuario_id',$jugador->id)
                ->where('fecha_id',$fecha->id)->first();

                if($fecha_usuario){
                    $jugador->pivot->puntos += $fecha_usuario->puntos;
                }
            }
        };
        return $jugadores;
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

    public function editPuntos(Request $request, $torneo_id, $usuario_id){
        $puntos = 0;

        $fechas = Fecha::where('torneo_id',$torneo_id)->get();
        foreach ($fechas as $key => $fecha) {
            $fecha_usuario = DB::table('fecha_usuario')
            ->where('usuario_id',$usuario_id)
            ->where('fecha_id',$fecha->id)->first();

            if($fecha_usuario){
                $puntos += $fecha_usuario->puntos;
            }
        }
        
        $torneo_usuario = DB::table('torneo_usuario')
        ->where('torneo_id',$torneo_id)
        ->where('usuario_id',$usuario_id);
        
        $puntos += $torneo_usuario->first()->puntos;

        $torneo_usuario->update(['puntos' => $torneo_usuario->first()->puntos += ($request->puntos - $puntos)]);

            //TODO En caso de que se haya jugado una fecha anterior:
            //TODO esto esta mal, aca hay que calcular cuantos puntos tiene en la ULTIMA FECHA - cuantos puntos le estoy seteando AHORA
            //TODO y esa diferencia se suma directamente sobre los PUNTOS BASE de ESTE TORNEO
            //TODO no lo arreglo ahora porque se supone que en la vista tiene que mostrar estos datos, asi que para no calcular dos veces, hay que devolver
            //TODO este dato ya calculado
        
    }

    public function updatePuntos(Request $request){
        $torneo = Torneo::whereId($request->torneo['id'])->first();
        
        $torneo->misma_categoria_mayor_nivel_ganador = intval($request->torneo['misma_categoria_mayor_nivel_ganador']);
        $torneo->misma_categoria_mayor_nivel_perdedor = intval($request->torneo['misma_categoria_mayor_nivel_perdedor']);
        $torneo->misma_categoria_menor_nivel_ganador = intval($request->torneo['misma_categoria_menor_nivel_ganador']);
        $torneo->misma_categoria_menor_nivel_perdedor = intval($request->torneo['misma_categoria_menor_nivel_perdedor']);

        $torneo->mayor_categoria_ganador = intval($request->torneo['mayor_categoria_ganador']);
        $torneo->mayor_categoria_perdedor = intval($request->torneo['mayor_categoria_perdedor']);
        $torneo->menor_categoria_ganador = intval($request->torneo['menor_categoria_ganador']);
        $torneo->menor_categoria_perdedor = intval($request->torneo['menor_categoria_perdedor']);

        $torneo->save();
    }

    public function getFechas(Request $request){
        $fechas = Fecha::where('torneo_id',$request->id)->get();
        foreach ($fechas as $key => $fecha) {
            $jugadores = $fecha->jugadores();
            $fecha->participantes = $jugadores->count();
            $fecha->date = date('Y-m-d h:i:s', strtotime($fecha->created_at));
            $fecha->ingresos = 0;

            foreach ($jugadores->get() as $key => $jugador) {
                $fecha->ingresos += $jugador->pivot->monto_pagado;
            }
        }
        return $fechas;
    }

    public function getInfoGraficasCategorias($id)
    {
        $categorias = Categoria::where('torneo_id',$id)->get();

        $jugadores = DB::table('torneo_usuario')->where('torneo_id',$id)->get();

        foreach ($categorias as $key => $categoria) {
            $total_jugadores = 0;
            foreach ($jugadores as $key => $jugador) {
                if($jugador->puntos >= $categoria->puntos_minimos && $jugador->puntos <= $categoria->puntos_maximos) {
                    $total_jugadores++;
                }
            }
            $categoria->total_jugadores = $total_jugadores;
        }

        return $categorias;
    }
}
