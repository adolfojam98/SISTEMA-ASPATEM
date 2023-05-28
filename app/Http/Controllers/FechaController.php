<?php

namespace App\Http\Controllers;

use App\Fecha;
use App\Partido;
use App\Grupo;
use App\Torneo;
use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Services\FechaService;
use App\Http\Services\PartidoService;
use App\Http\Resources\FechaUsuario as FechaUsuarioResource;
use App\Http\Resources\FechaPartido as FechaPartidosResource;

class FechaController extends ApiController
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
        return Fecha::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return crearNuevaFecha($request);
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
    public function update(Request $request, $fecha_id)
    {
        try
            {
                $request->validate([
                    "nombre" => 'string|nullable',
                    "vigencia" => 'boolean|nullable',
                    "monto_no_socios_dos_categorias" => 'numeric|nullable',
                    "monto_no_socios_una_categoria" => 'numeric|nullable',
                    "monto_socios_dos_categorias" => 'numeric|nullable',
                    "monto_socios_una_categoria" => 'numeric|nullable'
                ]);
                
                $service = new FechaService();
                $fecha = $service->update(
                    $fecha_id,
                    $request->get('nombre'),
                    $request->get('vigencia'),
                    $request->get('monto_no_socios_dos_categorias'), 
                    $request->get('monto_no_socios_una_categoria'),
                    $request->get('monto_socios_dos_categorias'),
                    $request->get('monto_socios_una_categoria')
                );
                
                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                } 

                return $fecha;
            }
        catch(Exception $e)
            {
                return $this->sendError($e->errorInfo[2]);
            }
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

    public function getFechaById($id) {
        $fecha = Fecha::find($id);
        $resumen_jugadores = $fecha->resumen_jugadores();

        //TODO hacer el resource
        return [
            "fecha" => $fecha,
            "resumen_jugadores" => $resumen_jugadores
        ];
    }


    public function guardarFecha(Request $request)
    {
       return crearNuevaFecha($request);
        // $categorias = $request->categorias;

        // crearRelacionFechaJugador($fecha, $categorias, $request->jugadores);

        // foreach ($categorias as $categoria) {

        //     guardarCategoriaFecha($categoria, $fecha->id);
        // }
        // return;
    }

    public function storeUsuario(Request $request, $id, $usuario_id)
    {
        try
        {
            $request->validate([
                'categoria_mayor_id' => ['nullable'],
                'categoria_menor_id' => ['nullable'],
                // 'monto_pagado' => ['nullable'],
                // 'puntos' => ['nullable']
            ]);

            $service = new FechaService();
            $fecha_usuario = $service->updateFechaUsuario($id, $usuario_id, $request->get('categoria_mayor_id'), $request->get('categoria_menor_id'), $request->get('monto_pagado'), $request->get('puntos'));
            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            return $this->sendResponse(new FechaUsuarioResource($fecha_usuario), 'Jugador modificado con exito.');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    public function getUsuariosAnotados($id)
    {
        try
        {
            $fecha_usuarios = Fecha::find($id)->jugadores()->get();
           
            foreach($fecha_usuarios as $key => $fecha_usuario) {
                $fecha_usuario->info_socio = $fecha_usuario->socio();
            }
            
            return $this->sendResponse(FechaUsuarioResource::collection($fecha_usuarios), 'Success');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }

    }

    public function getPartidos($id, $categoria_id)
    {
        try
        {
            $fecha_partidos = Fecha::find($id)
            ->partidos()
            ->where('categoria_id', $categoria_id)
            ->with('fase', 'grupo', 'jugadores')
            ->get();

            return $this->sendResponse(FechaPartidosResource::collection($fecha_partidos), 'Success');
        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }
    

    public function getFecha(Request $request){
        $fecha = Fecha::whereId($request->id)->first();
        $ranking_hasta_esta_fecha = [];

        $torneo_usuarios = Torneo::where('torneos.id',$fecha->torneo_id)
            ->join('torneo_usuario','torneo_usuario.torneo_id','=','torneos.id')
            ->join('usuarios','usuarios.id','=','torneo_usuario.usuario_id')
            ->get();

            $categorias = Categoria::where('torneo_id',$fecha->torneo_id)->get();
           
            foreach ($torneo_usuarios as $key => $torneo_usuario) {
                $fechas_usuarios = Fecha::where('fechas.torneo_id',$fecha->torneo_id)
                ->where('fechas.created_at','<=',$fecha->created_at) //TODO voy a traer todas las fechas anteriores y esta
                ->where('fecha_usuario.usuario_id',$torneo_usuario->usuario_id)
                ->join('fecha_usuario','fecha_usuario.fecha_id','=','fechas.id')
                ->get();

                foreach ($fechas_usuarios as $key => $fecha_usuario) {
                    $torneo_usuario->puntos += $fecha_usuario->puntos;
                }

                $jugador = [
                "usuario_id" => $torneo_usuario->usuario_id,
                "dni" => $torneo_usuario->dni,
                "nombre" => $torneo_usuario->nombre,
                "apellido" => $torneo_usuario->apellido,
                "puntos" => $torneo_usuario->puntos,
                "puntos_ganados" => calcularPuntosGanados($torneo_usuario->usuario_id, $fecha->id),
                "categoria" => calcularCategoria($categorias, $torneo_usuario->puntos)
                ];
                
                array_push($ranking_hasta_esta_fecha,$jugador);
                
            }

            $data = [
                "ranking" => $ranking_hasta_esta_fecha,
                "fecha_nombre" => $fecha->nombre,
                "categorias" => $categorias
            ];
        
        return $data;
    }

    public function storeCategoriaPartidos(Request $request, $id, $categoria_id) 
    {
        try
            {
                $request->validate([
                    'partidos' => 'required|array',
                    'partidos.*' => 'required',
                    'partidos.*.fase' => 'required | string',
                    // 'partidos.*.id_jugador1' => 'required',
                    // 'partidos.*.id_jugador2' => 'required',
                    // 'partidos.*.set_jugador1' => 'required',
                    // 'partidos.*.set_jugador2' => 'required | different:partidos.*.set_jugador1'
                ]);

                $servicePartido = new PartidoService();
                $partidosCreados = [];
                
                //borramos los partidos de esta fecha-categoria por si ya se había guardado antes
                $servicePartido->deletePartidos($id, $categoria_id);

                foreach ($request->get('partidos') as $key => $partido) {
                    $partido = (object) $partido;

                    if($partido->fase === "grupos") {
                        if(!$partido->grupo_nombre) {
                            return $this->sendError('Los partiods en fase "grupos" requieren de un nombre de grupo.');
                        }
                    } else {
                        if($partido->fase !== "final" && !$partido->sig_partido_id) {
                            return $this->sendError('Los partiods en fase "llaves" requieren de un id de partido padre.');  
                        }
                    }

                    $partido_info = (object) [
                        "id_partido" => $partido->id ?? null,
                        "id_jugador1" => $partido->id_jugador1 ?? null,
                        "id_jugador2" => $partido->id_jugador2 ?? null,
                        "set_jugador1" => $partido->set_jugador1 ?? null,
                        "set_jugador2" => $partido->set_jugador2 ?? null
                    ];

                    $partidoNuevo = $servicePartido->createPartido($id, $categoria_id, $partido->fase, $partido->grupo_nombre ?? null, $partido_info);
                    $partidoNuevo->fake_id = $partido->id ?? null;
                    $partidoNuevo->fake_padre_id = $partido->sig_partido_id ?? null;
                    array_push($partidosCreados, $partidoNuevo);
                
                }
                $servicePartido->asociarPartidoPadre($partidosCreados);
                if ($servicePartido->hasErrors()) {
                    return $this->sendServiceError($servicePartido->getLastError());
                }


                $serviceFecha = new FechaService();
                $serviceFecha->updatePuntos($id);

                if ($serviceFecha->hasErrors()) {
                    return $this->sendServiceError($serviceFecha->getLastError());
                }

                return $this->sendOK();
            }
            catch(Exception $e)
            {
                return $this->sendError($e->errorInfo[2]);
            }
        }
    }



//????????????????
function calcularCategoria($categorias, $puntos){
    foreach ($categorias as $key => $categoria) {
        if ($puntos >= $categoria->puntos_minimos && $puntos <= $categoria->puntos_maximos) 
        {
            return $categoria->nombre;
        }
    }
}


function calcularPuntosGanados($usuario_id, $fecha_id) {
    $fecha_usuario = DB::table('fecha_usuario')
    ->where('usuario_id',$usuario_id)
    ->where('fecha_id',$fecha_id)->first();

    if(!empty($fecha_usuario)){
        return $fecha_usuario->puntos;
    } else {
        return 0;
    }
}

function crearRelacionFechaJugador($fecha, $categorias, $jugadores) {
    $torneo = Torneo::where('id',$fecha->torneo_id)->first();
    foreach($jugadores as $jugador){
        $puntos = 0;
        $jugo = false;
        foreach($categorias as $categoria){
            foreach($categoria['listaGrupos'] as $grupo){
                if(is_array($grupo)){
                    foreach($grupo['partidos'] as $partido) {
                        if($partido['jugador1']['id'] === $jugador['id']){//es igual al jugador1
                            $jugo = true;
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
                            $jugo = true;
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
                        $jugo = true;
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
                        $jugo = true;
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
        if($jugo) {
            if(($jugador['pivot']['puntos'] + $puntos) < 0) {
                $puntos -= $jugador['pivot']['puntos'];
            }
            $fecha->jugadores()->attach($jugador['id'], ['puntos' => $puntos, 'monto_pagado' => $jugador['montoPagado']]);
        }
    }
}

function crearNuevaFecha(Request $request)
{
    $fecha                                  = new Fecha();
    $fecha->nombre                          = $request->nombreFecha;
    $fecha->monto_socios_una_categoria      = $request->montoSociosUnaCategoria;
    $fecha->monto_socios_dos_categorias     = $request->montoSociosDosCategorias;
    $fecha->monto_no_socios_una_categoria   = $request->montoNoSociosUnaCategoria;
    $fecha->monto_no_socios_dos_categorias  = $request->montoNoSociosDosCategorias;
    $fecha->torneo_id                       = $request->torneoId;
    $fecha->vigencia                        = true;
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
    //dd(DB::table('partido_fase')->get());
    //if($partido['fase']!= 'final') dd($partido['fase']);
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