<?php

namespace App\Http\Controllers;

use App\Fecha;
use App\Partido;
use App\Grupo;
use App\Torneo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FechaController extends Controller
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
     * @param  \App\Fecha  $fecha
     * @return \Illuminate\Http\Response
     */
    public function show(Fecha $fecha)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fecha  $fecha
     * @return \Illuminate\Http\Response
     */
    public function edit(Fecha $fecha)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fecha  $fecha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fecha $fecha)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fecha  $fecha
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fecha $fecha)
    {
        //
    }


    public function guardarFecha(Request $request)
    {


        $fecha = crearNuevaFecha($request);
        $categorias = $request->categorias;

        crearRelacionFechaJugador($fecha, $categorias, $request->jugadores);

        foreach ($categorias as $categoria) {

            guardarCategoriaFecha($categoria, $fecha->id);
        }
        return;
    }
}

function crearRelacionFechaJugador($fecha, $categorias, $jugadores) {
    $torneo = Torneo::where('id',$fecha->torneo_id)->first();

    foreach($jugadores as $jugador){
        $puntos = 0;
        foreach($categorias as $categoria){
            foreach($categoria['listaGrupos'] as $grupo){
                if(is_array($grupo)){
                    foreach($grupo['partidos'] as $partido) {
                        if($partido['jugador1']['id'] === $jugador['id']){//es igual al jugador1
                            if($partido['setsJugador1'] > $partido['setsJugador2']){ //jugador1 gano

                                if(($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'])
                                || ($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'])) { //misma categoria
                                    if($partido['jugador1']['pivot']['puntos'] >= $partido['jugador2']['pivot']['puntos']) { //ganador misma categoria mayor nivel
                                        $puntos += $torneo->misma_categoria_mayor_nivel_ganador;
                                    }
                                    else { //ganador misma categoria menor nivel
                                        $puntos += $torneo->misma_categoria_menor_nivel_ganador;
                                    }
                                }

                                if($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos']) { //jugador1 menor categoria
                                    $puntos += $torneo->menor_categoria_ganador;
                                }

                                if($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos']) { //jugador1 mayor categoria
                                    $puntos += $torneo->mayor_categoria_ganador;
                                }

                            }

                            else { //jugador1 perdio

                                if(($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'])
                                || ($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'])) { //misma categoria
                                    if($partido['jugador1']['pivot']['puntos'] >= $partido['jugador2']['pivot']['puntos']) { //perdio misma categoria mayor nivel
                                        $puntos -= $torneo->misma_categoria_mayor_nivel_perdedor;
                                    }
                                    else { //perdio misma categoria menor nivel
                                        $puntos -= $torneo->misma_categoria_menor_nivel_perdedor;
                                    }
                                }

                                if($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos']) { //jugador1 menor categoria
                                    $puntos -= $torneo->menor_categoria_perdedor;
                                }

                                if($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos']) { //jugador1 mayor categoria
                                    $puntos -= $torneo->mayor_categoria_perdedor;
                                }

                            }
                        }

                        else if($partido['jugador2']['id'] === $jugador['id']) { //es igual al jugador2
                            if($partido['setsJugador2'] > $partido['setsJugador1']){ //jugador2 gano

                                if(($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'])
                                || ($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'])) { //misma categoria
                                    if($partido['jugador2']['pivot']['puntos'] >= $partido['jugador1']['pivot']['puntos']) { //ganador misma categoria mayor nivel
                                        $puntos += $torneo->misma_categoria_mayor_nivel_ganador;
                                    }
                                    else { //ganador misma categoria menor nivel
                                        $puntos += $torneo->misma_categoria_menor_nivel_ganador;
                                    }
                                }

                                if($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos']) { //jugador2 menor categoria
                                    $puntos += $torneo->menor_categoria_ganador;
                                }

                                if($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos']) { //jugador2 mayor categoria
                                    $puntos += $torneo->mayor_categoria_ganador;
                                }

                            }

                            else { //jugador2 perdio

                                if(($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'])
                                || ($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'])) { //misma categoria
                                    if($partido['jugador2']['pivot']['puntos'] >= $partido['jugador1']['pivot']['puntos']) { //perdio misma categoria mayor nivel
                                        $puntos -= $torneo->misma_categoria_mayor_nivel_perdedor;
                                    }
                                    else { //perdio misma categoria menor nivel
                                        $puntos -= $torneo->misma_categoria_menor_nivel_perdedor;
                                    }
                                }

                                if($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos']) { //jugador2 menor categoria
                                    $puntos -= $torneo->menor_categoria_perdedor;
                                }

                                if($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos']) { //jugador2 mayor categoria
                                    $puntos -= $torneo->mayor_categoria_perdedor;
                                }

                            }

                        }
                    }
                }
            }

            foreach($categoria['partidosLlaves'] as $partido){//ahora con los partidos de las llaves
                if(is_array($partido)){
                    if($partido['jugador1']['id'] === $jugador['id']){//es igual al jugador1
                        if($partido['set1'] > $partido['set2']){ //jugador1 gano

                            if(($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'])
                            || ($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'])) { //misma categoria
                                if($partido['jugador1']['pivot']['puntos'] >= $partido['jugador2']['pivot']['puntos']) { //ganador misma categoria mayor nivel
                                    $puntos += $torneo->misma_categoria_mayor_nivel_ganador;
                                }
                                else { //ganador misma categoria menor nivel
                                    $puntos += $torneo->misma_categoria_menor_nivel_ganador;
                                }
                            }

                            if($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos']) { //jugador1 menor categoria
                                $puntos += $torneo->menor_categoria_ganador;
                            }

                            if($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos']) { //jugador1 mayor categoria
                                $puntos += $torneo->mayor_categoria_ganador;
                            }

                        }

                        else { //jugador1 perdio

                            if(($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'])
                            || ($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'])) { //misma categoria
                                if($partido['jugador1']['pivot']['puntos'] >= $partido['jugador2']['pivot']['puntos']) { //perdio misma categoria mayor nivel
                                    $puntos -= $torneo->misma_categoria_mayor_nivel_perdedor;
                                }
                                else { //perdio misma categoria menor nivel
                                    $puntos -= $torneo->misma_categoria_menor_nivel_perdedor;
                                }
                            }

                            if($categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos']) { //jugador1 menor categoria
                                $puntos -= $torneo->menor_categoria_perdedor;
                            }

                            if($categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos']) { //jugador1 mayor categoria
                                $puntos -= $torneo->mayor_categoria_perdedor;
                            }

                        }
                    }

                    else if($partido['jugador2']['id'] === $jugador['id']) { //es igual al jugador2
                        
                        if($partido['set2'] > $partido['set1']){ //jugador2 gano

                            if(($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'])
                            || ($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'])) { //misma categoria
                                if($partido['jugador2']['pivot']['puntos'] >= $partido['jugador1']['pivot']['puntos']) { //ganador misma categoria mayor nivel
                                    $puntos += $torneo->misma_categoria_mayor_nivel_ganador;
                                }
                                else { //ganador misma categoria menor nivel
                                    $puntos += $torneo->misma_categoria_menor_nivel_ganador;
                                }
                            }

                            if($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos']) { //jugador2 menor categoria
                                $puntos += $torneo->menor_categoria_ganador;
                            }

                            if($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos']) { //jugador2 mayor categoria
                                $puntos += $torneo->mayor_categoria_ganador;
                            }

                        }

                        else { //jugador2 perdio

                            if(($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos'])
                            || ($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos'])) { //misma categoria
                                if($partido['jugador2']['pivot']['puntos'] >= $partido['jugador1']['pivot']['puntos']) { //perdio misma categoria mayor nivel
                                    $puntos -= $torneo->misma_categoria_mayor_nivel_perdedor;
                                }
                                else { //perdio misma categoria menor nivel
                                    $puntos -= $torneo->misma_categoria_menor_nivel_perdedor;
                                }
                            }

                            if($categoria['puntos_minimos'] > $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] <= $partido['jugador1']['pivot']['puntos']) { //jugador2 menor categoria
                                $puntos -= $torneo->menor_categoria_perdedor;
                            }

                            if($categoria['puntos_minimos'] <= $partido['jugador2']['pivot']['puntos'] && $categoria['puntos_minimos'] > $partido['jugador1']['pivot']['puntos']) { //jugador2 mayor categoria
                                $puntos -= $torneo->mayor_categoria_perdedor;
                            }

                        }

                    }
                }
            }
        }
        if($jugador['nombre']=='Federico')dd($jugador,$puntos); 
        //hasta aca ya se calculan los puntos bien por lo visto, queda guardarlos
        //tambien hay que guardar los que pago un jugador en una fecha
    }
}

function crearNuevaFecha(Request $request)
{

    $fecha                                  = new Fecha();
    $fecha->nombre                          = $request->nombreFecha;
    $fecha->monto_socios_una_categoria      = $request->montos['montoSociosUnaCategoria'];
    $fecha->monto_socios_dos_categorias     = $request->montos['montoSociosDosCategorias'];
    $fecha->monto_no_socios_una_categoria   = $request->montos['montoNoSociosUnaCategoria'];
    $fecha->monto_no_socios_dos_categorias  = $request->montos['montoNoSociosDosCategorias'];
    $fecha->torneo_id                       = $request->categorias[0]['torneo_id'];
    $fecha->save();
    return $fecha;


}

function guardarCategoriaFecha($categoria, $fecha_id)
{

    foreach ($categoria['listaGrupos'] as $grupo) {

        $nuevoGrupo         =  new Grupo();
        $nuevoGrupo->nombre = $grupo['nombre'];
        $nuevoGrupo->save();
        $faseGrupo          = DB::table('partido_fase')->where('nombre', 'grupos')->get()->first();

        foreach ($grupo['partidos'] as $partido) {


            $nuevoPartido                   = new Partido();
            $nuevoPartido->categoria_id     = $categoria['id'];
            $nuevoPartido->partido_fase_id  = $faseGrupo->id;
            $nuevoPartido->grupo_id         = $nuevoGrupo->id;
            $nuevoPartido->fecha_id         = $fecha_id;
            $nuevoPartido->save();

            $nuevoPartido->jugadores()->sync(
                [
                    $partido['jugador1']['id']  =>  ['sets' => $partido['setsJugador1']],
                    $partido['jugador2']['id']  =>  ['sets' => $partido['setsJugador2']]
                ]
            );
        }

        guardarPartidosLlaves($categoria['partidosLlaves'], $fecha_id, $categoria['id'], null);
    }
}
function guardarPartidosLlaves($partidos, $fecha_id, $categoria_id, $sigPartido)
{
    $partido = array_shift($partidos);

    $nuevoPartido = new Partido();
    $nuevoPartido->partido_fase_id = DB::table('partido_fase')->where('nombre', $partido['fase'])->first()->id;

    $nuevoPartido->categoria_id = $categoria_id;
    $nuevoPartido->fecha_id = $fecha_id;
    $nuevoPartido->sig_partido_id = $sigPartido;
    $nuevoPartido->save();

    $nuevoPartido->jugadores()->sync(
        [
            $partido['jugador1']['id']  =>  ['sets' => $partido['set1']],
            $partido['jugador2']['id']  =>  ['sets' => $partido['set2']]
        ]
    );
    $sigPartido = $nuevoPartido->id;

    foreach ($partidos as $partidoo) {
        if ($partido['id'] == $partidoo['sigPartidoID']) {
            guardarPartidosLlaves($partidos, $fecha_id, $categoria_id, $sigPartido);
        }
    }
    return;
}