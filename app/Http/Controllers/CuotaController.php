<?php

namespace App\Http\Controllers;

use Carbon\Carbon;


use App\Usuario;
use App\Configuracion;

use App\Cuota;
use App\CuotaDetalle;
use App\CuotaDetalleTipo;
use App\Pago;
use App\Http\Services\CuotaService;
use App\Http\Services\PagoService;

use App\Torneo;
use Illuminate\Http\Request;
use App\Http\Resources\Cuota as CuotaResource;
use App\Http\Resources\Pago as PagoResource;

//TODO hacer resource, services?, y funciones: get by id, pagar, 

class CuotaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //si el crone anda bien esto no iria
        $service = new CuotaService();
        $service->updateLatePayment();
        if ($service->hasErrors()) {
            return $this->sendServiceError($service->getLastError());
        }
        //end

        return CuotaResource::collection(Cuota::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

    //NUEVO>>>>>>>>>>>>>>>>>>

    public function generarCuotaIngreso(Request $request, $usuario_id)
    {
        try {
            $request->validate([
                'importe' => 'required|numeric',
            ]);

            $usuario = Usuario::findOrFail($usuario_id);

            //datos para la cuota y detalle tipo
            $periodo = date("Y-m-d H:i:s", strtotime(date("Y-m-1")));
            $importe = $request->get('importe');
            $cuotaDetalleTipoIngreso = CuotaDetalleTipo::where('codigo', 'ingreso')->first();

            if (!$cuotaDetalleTipoIngreso) {
                return $this->sendError("No existe un detalle de cuota 'Ingreso'");
            }

            //creamos el service
            $service = new CuotaService();

            //creamos la cuota
            $cuota = $service->createCuota($usuario->id, $periodo);
            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            //creamos el detalle
            $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoIngreso->id, $importe);
            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            return $this->sendResponse(new CuotaResource($cuota), 'Cuota generada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    public function generarCuotasMasivas(Request $request)
    {
        try {
            $request->validate([
                'fecha' => 'required|date'
            ]);

            $fecha = $request->get('fecha');
            $fecha = date("Y-m-d H:i:s", strtotime($fecha));

            $usuarios = Usuario::leftJoin('cuotas', 'usuario_id', 'usuarios.id')
                ->where('socio', 1)
                ->where(function ($query) use ($fecha) {
                    $query->whereNull('cuotas.id');
                    $query->orWhereRaw('usuario_id not in (select usuario_id from cuotas where periodo = ? )', [$fecha]);
                })->distinct()
                ->get('usuarios.id');

            $sociosActivos = [];
            //filtramos ya por socios, ahora por aquellos activos
            foreach ($usuarios as $key => $usuario) {
                if (Usuario::whereId($usuario->id)->first()->socio()->activo) {
                    array_push($sociosActivos, $usuario);
                }
            }
            //ahora pisamos los usuarios con este ultimo filtro de activos
            $usuarios = $sociosActivos;

            $cuotaDetalleTipoPrecioBase = CuotaDetalleTipo::where('codigo', 'precio_base')->first();

            if (!$cuotaDetalleTipoPrecioBase) {
                return $this->sendError("No existe un detalle de cuota 'Precio Base'");
            }

            if (!count($usuarios)) {
                return $this->sendError("No se encontraron cuotas para generar en el periodo", "No se encontraron cuotas para generar en el periodo");
            }

            $service = new CuotaService();
            $service->validateCuotaDetalleTiposDefaults();
            //$service->updateLatePayment(); //TODO: hora se esta usando en los getters y el crone esta, pero molesta la consola saliendo cada min asi que lo saque

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            foreach ($usuarios as $usuario) {
                //creamos la cuota
                $cuota = $service->createCuota($usuario->id, $fecha);
                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                }

                //creamos el detalle precio base
                $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoPrecioBase->id, $cuotaDetalleTipoPrecioBase->valor);
                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                }

                //creamos el detalle de relacion si corresponde
                if ($usuario->hasRelacionesWithSocios()) {
                    $cuotaDetalleTipoRelaciones = CuotaDetalleTipo::where('codigo', 'relaciones')->first();
                    if (!$cuotaDetalleTipoRelaciones) {
                        return $this->sendError("No existe un detalle de cuota 'Relaciones'");
                    }

                    $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoRelaciones->id, $cuotaDetalleTipoRelaciones->valor ?: $cuotaDetalleTipoPrecioBase->valor * $cuotaDetalleTipoRelaciones->porcentaje);
                    if ($service->hasErrors()) {
                        return $this->sendServiceError($service->getLastError());
                    }
                }
            }

            return $this->sendResponse(null, 'Cuotas generadas correctamente');
        } catch (Exception $e) {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    public function getCuotaById(Request $request, $id)
    {
        try {

            //si el crone anda bien esto no iria
            $service = new CuotaService();
            $service->updateLatePayment(null, $id);
            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }
            //end

            $cuota = Cuota::whereId($id)->first();
            return $cuota ? $this->sendResponse(new CuotaResource($cuota), 'Cuota encontrada con exito.') :
                $this->sendError('Cuota no encontrada.');
        } catch (Exception $e) {
            return $this->sendError($e->errorInfo[2]);
        }
    }



    //<<<<<<<<<<<<<<<<<<<<NUEVO

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


        try {
            $request->validate([
                'fecha' => 'required|date'
            ]);

            $fecha = $request->get('fecha');
            $fecha = date("Y-m-d H:i:s", strtotime($fecha));

            $usuario = Usuario::leftJoin('cuotas', 'cuotas.usuario_id', 'usuarios.id')
            ->where('usuarios.socio', 1)
            ->where('usuarios.id', $request->id)
            ->where(function ($query) use ($fecha) {
                $query->whereNull('cuotas.id');
                $query->orWhereRaw('usuario_id not in (select usuario_id from cuotas where periodo = ? )', [$fecha]);
            })
            ->first();


            if (!$usuario) {
                return $this->sendError("La cuota de este periodo ya fue generada", "La cuota de este periodo ya fue generada");
            }

            if (!$usuario->socio()->activo) {
                return $this->sendError("Esta persona es socio inactivo, por favor anule las cuotas e intente de nuevo");
            }

            //ahora pisamos los usuarios con este ultimo filtro de activos

            $cuotaDetalleTipoPrecioBase = CuotaDetalleTipo::where('codigo', 'precio_base')->first();

            if (!$cuotaDetalleTipoPrecioBase) {
                return $this->sendError("No existe un detalle de cuota 'Precio Base'");
            }


            $service = new CuotaService();
            $service->validateCuotaDetalleTiposDefaults();

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }


                //creamos la cuota
                $cuota = $service->createCuota($usuario->usuario_id, $fecha);
                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                }
                //creamos el detalle precio base
                $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoPrecioBase->id, $cuotaDetalleTipoPrecioBase->valor);
                if ($service->hasErrors()) {
                    return $this->sendServiceError($service->getLastError());
                }

                //creamos el detalle de relacion si corresponde
                if ($usuario->hasRelacionesWithSocios()) {
                    $cuotaDetalleTipoRelaciones = CuotaDetalleTipo::where('codigo', 'relaciones')->first();
                    if (!$cuotaDetalleTipoRelaciones) {
                        return $this->sendError("No existe un detalle de cuota 'Relaciones'");
                    }

                    $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoRelaciones->id, $cuotaDetalleTipoRelaciones->valor ?: $cuotaDetalleTipoPrecioBase->valor * $cuotaDetalleTipoRelaciones->porcentaje);
                    if ($service->hasErrors()) {
                        return $this->sendServiceError($service->getLastError());
                    }
                }

            return $this->sendResponse(null, 'Cuotas generadas correctamente');
        } catch (Exception $e) {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    public function pagar(Request $request)
    {
        $cuota = Cuota::find($request->id);

        $cuota->fechaPago = $request->fecha;

        if ($request->importe != NULL) {
            $cuota->importe = $request->importe;
        }
        if ($request->observacion != NULL) {
            $cuota->observacion = $request->observacion;
        }

        $cuota->save();


        $usuario = Usuario::where('id', $cuota->usuario_id)->first();
        $usuario->socio = true;
        $usuario->save();
    }

    public function cancelar(Request $request, $id)
    {
        try {
            $request->validate([
                'descripcion' => 'string|nullable',
            ]);

            $fechaPago = carbon::now();
            $cuota = Cuota::whereId($id)->first();
            $service = new CuotaService();

            $montoTotal = $cuota->montoTotal();
            $descripcion = $request->get('descripcion') ? $request->get('descripcion') : 'Cuota cancelada.';

            //calculamos la dif
            $dif = -$montoTotal;

            //creamos un nuevo detalle tipo Otros con la dif y la descripcion
            // dd(CuotaDetalleTipo::All());
            $cuotaDetalleTipoOtros = CuotaDetalleTipo::where('codigo', 'precio_base')->first();
            $service->createCuotaDetalle($cuota->id, $cuotaDetalleTipoOtros->id, $dif, $descripcion);

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            //luego el montoTotal pasa a ser = 0
            $montoTotal = 0;

            //ahora creamos el pago desde el service
            $service = new PagoService();
            //el montoTotal es lo que se guarda en pago
            $newPago = $service->createPago($id, $montoTotal, $fechaPago);

            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }

            return $this->sendResponse(new PagoResource($newPago), 'Cuota cancelada con exito.');
        } catch (Exception $e) {
            return $this->sendError($e->errorInfo[2]);
        }
    }
}
