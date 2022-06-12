<?php

namespace App\Http\Controllers;

use App\IngresosExternos;
use Illuminate\Http\Request;
use App\Http\Resources\Cuota as CuotaResources;
use App\Http\Resources\Torneo as TorneoResources;
use App\Http\Resources\Fecha as FechaResources;
use App\Http\Resources\IngresosExternos as IngresosExternosResources;

use App\Pago;
use App\Cuota;
use App\Fecha;
use App\Torneo;

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
    public function create(Request $request) //TIPO-MONTO-DESCRIPCION-FECHA
    {   
        $informe = [];
        //Cuotas
        if(!$request->tipo || $request->tipo === 'Cuotas' || $request->tipo === 'Todos') {
            $query_cuotas = Pago::all();
            
            if($request->fecha_inicio) {
                $query_cuotas = $query_cuotas->where('fecha_pago','>=',$request->fecha_inicio);
            }
            if($request->fecha_fin) {
                $query_cuotas = $query_cuotas->where('fecha_pago','<=',$request->fecha_fin.' 23:59:59');
            }
            //return(CuotaResources::collection($query_cuotas));
            $cuotas = $query_cuotas;

            foreach ($cuotas as $key => $cuota) {
                $cuota_xd = (object)[
                    'id' => $cuota->id,
                    'tipo' => 'Cuota',
                    'monto' => (float) $cuota->monto_total,
                    'descripcion' => 'Cuota Pagada',
                    'fecha' => date("Y-m-d",strtotime($cuota->fecha_pago))
                ];
                array_push($informe,$cuota_xd);
            }
        }
        //Torneo
        if(!$request->tipo || $request->tipo === 'Torneos' || $request->tipo === 'Todos') {
            $query_torneos = Torneo::all();

            if($request->torneo_id && $request->torneo_id !== 0) {
                $query_torneos = $query_torneos->where('id',$request->torneo_id);
            }
            if($request->fecha_inicio) {
                $query_torneos = $query_torneos->where('created_at','>=',$request->fecha_inicio);
            }
            if($request->fecha_fin) {
                $query_torneos = $query_torneos->where('created_at','<=',$request->fecha_fin.' 23:59:59');
            }
            //return (TorneoResources::collection($query_torneos));
            $torneos = TorneoResources::collection($query_torneos);
            foreach ($torneos as $key => $torneo) {
                array_push($informe,$torneo);
            }
            
        }
        //Fecha
        if($request->tipo && $request->tipo === 'Fechas') {//solo si tipo es igual a fecha, porque las ganancias ya estan en torneo
            $query_fechas = Fecha::all();
            
            if($request->torneo_id && $request->torneo_id !== 0) {
                $query_fechas = $query_fechas->where('torneo_id',$request->torneo_id);
            }
            if($request->fecha_inicio) {
                $query_fechas = $query_fechas->where('created_at','>=',$request->fecha_inicio);
            }
            if($request->fecha_fin) {
                $query_fechas = $query_fechas->where('created_at','<=',$request->fecha_fin.' 23:59:59');
            }
            //return (FechaResources::collection($query_fechas));
            $fechas = FechaResources::collection($query_fechas);
            foreach ($fechas as $key => $fecha) {
                array_push($informe,$fecha);
            }
            
        }
        //Otros
        if(!$request->tipo || $request->tipo === 'Todos' || $request->tipo === 'Otros') {
            $query_otros = IngresosExternos::all();

            if($request->fecha_inicio) {
                $query_otros = $query_otros->where('created_at','>=',$request->fecha_inicio);
            }
            if($request->fecha_fin) {
                $query_otros = $query_otros->where('created_at','<=',$request->fecha_fin.' 23:59:59');
            }
            //return IngresosExternosResources::collection($query_otros);
            $otros = IngresosExternosResources::collection($query_otros);
            foreach ($otros as $key => $otro) {
                array_push($informe,$otro);
            }
            
        }

        return $informe;
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
