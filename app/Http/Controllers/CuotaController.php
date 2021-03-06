<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Cuota;
use App\Usuario;
use App\Configuracion;
use App\Torneo;
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
        $fechaActual = new Carbon();

        $cuota = new Cuota();
        $cuota->usuario_id = $request->id_usuario;
        $cuota->mes = $fechaActual->month;
        $cuota->anio = $fechaActual->year;
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
    public function show(Request $request)
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
    public function update(Request $request)
    {
        $cuota = Cuota::findOrFail($request->id);

        
        $cuota->descuento = $request->descuento;

        $cuota->fechaPago = Carbon::today();

        $cuota->save();

        return $cuota;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cuota  $cuota
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $cuota)
    {
        $cuota = Cuota::findOrFail($cuota->id);
        $cuota->delete();
    }



    public function generarCuotasFaltantes()
    {
        $socios = Usuario::where('socio', true)->get();
        $fechaActual = Carbon::today();
        foreach ($socios as $socio) {
            $cuota = new Cuota();
            $cuota->usuario_id = $socio->id;
            $cuota->importe = 1000;
            $cuota->mes = $fechaActual->month;
            $cuota->anio = $fechaActual->year;
            $cuota->save();
        }
    }


    public function generarCuota(Request $request)
    {

        //antes de generar la cuota hace un control de quienes son socios para que aplique el descuento solo si es debido
        $configuracion = Configuracion::first();

        if($configuracion == NULL){
            return response()->json([
                'message' => 'Las configuraciones aun no existen'
            ]);
        }

        if($configuracion->automatizarBajasSocios){

            $fechaActual = carbon::now();
            $fechaMesSiguiente = carbon::now();
            $fechaUnMesAntes = carbon::now();
            $fechaDosMesesAntes = carbon::now();
            $fechaTresMesesAntes = carbon::now();

            $fechaMesSiguiente->addMonth(1);
            $fechaUnMesAntes->subMonth(1);
            $fechaDosMesesAntes->subMonth(2);
            $fechaTresMesesAntes->subMonth(3);

            $socios = Usuario::where('socio', true)->get();


            foreach ($socios as $socio) {

                $cuotas = Cuota::where('mes', $fechaActual->month)
                                ->where('anio', $fechaActual->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaUnMesAntes->month)
                                ->where('anio', $fechaUnMesAntes->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaDosMesesAntes->month)
                                ->where('anio', $fechaDosMesesAntes->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaTresMesesAntes->month)
                                ->where('anio', $fechaTresMesesAntes->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaMesSiguiente->month)
                                ->where('anio', $fechaMesSiguiente->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')->get();
                
                    if($cuotas=='[]'){
                        
                        $socio->socio=false;
                        $socio->save();
                    }

            }
       
        }
        //aqui termina el calculo de si es socio
        

       
        $ExisteEstaCuota = Cuota::where('mes', $request->mes)
        ->where('anio', $request->anio)
        ->where('usuario_id', $request->usuario_id)->first();

     
        if ($ExisteEstaCuota==null) {
            $cuota = new Cuota();
            $cuota->mes = $request->mes;
            $cuota->anio = $request->anio;
            $cuota->usuario_id = $request->usuario_id;
        
        $usuario = Usuario::find($request->usuario_id);
        $relaciones = $usuario->relaciones;
     
        

        foreach ($relaciones as $relacion) {
            foreach ($relacion->usuarios as $usuario) {
                if ($usuario->socio = true && $usuario->id != $request->usuario_id) {
                  
                    $cuota->descuento = true;
                    $cuota->observacion = 'Se aplico el descuento de Familiar/Amigo';
                }
            }
        }

        
        if($configuracion->montoCuota!=NULL && $configuracion->montoCuotaDescuento!=NULL){
            if($cuota->descuento){$cuota->importe = $configuracion->montoCuotaDescuento;}
            else{$cuota->importe = $configuracion->montoCuota;}
        }
        else
            {
                return response()->json([
                    'message' => 'Primero debe establecer los montos desde configuracion'
                ]);
            }
        
    
        $cuota->save();
    
        return response()->json([
            'message' => 'Cuota creada'
        ]);

        }
        

        else {
            return response()->json([
                'message' => 'Esta cuota ya existe'
            ]);
        }

    }



    public function pagar(Request $request){
        $cuota = Cuota::find($request->id);

        $cuota->fechaPago = $request->fecha;

        if($request->importe!=NULL){
            $cuota->importe = $request->importe;
        }
        if($request->observacion != NULL){
            $cuota->observacion = $request->observacion;
        }

        $cuota->save();


        $usuario = Usuario::where('id',$cuota->usuario_id)->first();
        $usuario->socio = true;
        $usuario->save();
    }



}