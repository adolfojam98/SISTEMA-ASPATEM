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
use Carbon\Carbon;

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

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $tipo = $request->tipo;
        $torneo_id = $request->torneo_id;
        $fecha_id = $request->fecha_id;
        $fecha_inicio = Carbon::parse($fecha_inicio);
        $fecha_fin = Carbon::parse($fecha_fin);
        $fecha_fin = $fecha_fin->setTime(23, 59, 59);
        $informe = [];


        //Cuotas
        if (!$tipo || $tipo === 'Cuotas' || $tipo === 'Todos') {
            $query_cuotas = Pago::query();
            if ($fecha_inicio) {
               
                $query_cuotas = $query_cuotas->where('fecha_pago', '>=', $fecha_inicio);
            }
            if ($fecha_fin) {

                $query_cuotas = $query_cuotas->where('fecha_pago', '<=', $fecha_fin);
            }

            $cuotas = $query_cuotas->get();
            foreach ($cuotas as $key => $cuota) {
                $cuota_xd = (object)[
                    'id' => $cuota->id,
                    'tipo' => 'Cuota',
                    'monto' => (float) $cuota->monto_total,
                    'descripcion' => 'Cuota Pagada',
                    'fecha' => date("Y-m-d", strtotime($cuota->fecha_pago))
                ];
                array_push($informe, $cuota_xd);
            }
        }
        //Torneo
        if (!$tipo || $tipo === 'Torneos' || $tipo === 'Todos') {
            if ($tipo === 'Todos') {
                $query_torneos = Torneo::all();
            } else {
                $query_torneos = Torneo::query();

                if ($torneo_id && $torneo_id !== 0) {
                    $query_torneos = $query_torneos->where('id', $torneo_id);
                }
                if ($fecha_inicio) {
                    $query_torneos = $query_torneos->where('created_at', '>=', $fecha_inicio);
                }
                if ($fecha_fin) {
                    $query_torneos = $query_torneos->where('created_at', '<=', $fecha_fin);
                }

                $query_torneos = $query_torneos->get();
            }

            //return (TorneoResources::collection($query_torneos));
            $torneos = TorneoResources::collection($query_torneos);
            foreach ($torneos as $key => $torneo) {
                array_push($informe, $torneo);
            }
        }
        //Fecha
        if ($tipo && $tipo === 'Fechas') { //solo si tipo es igual a fecha, porque las ganancias ya estan en torneo
            $query_fechas = Fecha::query();

            if ($fecha_id && $fecha_id !== 0) {
                $query_fechas = $query_fechas->where('id', $fecha_id);
            }

            if ($torneo_id && $torneo_id !== 0) {
                $query_fechas = $query_fechas->where('torneo_id', $torneo_id);
            }

            if ($fecha_inicio) {
                $query_fechas = $query_fechas->where('created_at', '>=', $fecha_inicio);
            }
            if ($fecha_fin) {
                $query_fechas = $query_fechas->where('created_at', '<=', $fecha_fin);
            }

            //return (FechaResources::collection($query_fechas));
            $fechas = FechaResources::collection($query_fechas->get());
            foreach ($fechas as $key => $fecha) {
                array_push($informe, $fecha);
            }
        }
        //Otros
        if (!$tipo || $tipo === 'Todos' || $tipo === 'Otros') {
            $query_otros = IngresosExternos::query();

            if ($fecha_inicio) {
                $query_otros = $query_otros->where('created_at', '>=', $fecha_inicio);
            }
            if ($fecha_fin) {
                $query_otros = $query_otros->where('created_at', '<=', $fecha_fin);
            }
            //return IngresosExternosResources::collection($query_otros);
            $otros = IngresosExternosResources::collection($query_otros->get());
            foreach ($otros as $key => $otro) {
                array_push($informe, $otro);
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
