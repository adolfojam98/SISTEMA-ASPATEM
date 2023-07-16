<?php

namespace App\Http\Controllers;


use App\Http\Services\CuotaService;
use App\Pago;
use App\Usuario;
use App\Cuota;
use App\Torneo;
use App\Fecha;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Cuota as CuotaResource;
use Illuminate\Support\Facades\URL;


class UsuarioController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dni = $request->dni;
        $perPage = $request->perPage ?? 10; // Número de elementos por página
        $page = $request->page ?? 1;
        $orderBy = $request->orderBy ?? 'created_at'; // Campo por el que deseas ordenar
        $orderByDesc = filter_var($request->orderByDesc, FILTER_VALIDATE_BOOLEAN) ?? false;
        $socio = filter_var($request->socio, FILTER_VALIDATE_BOOLEAN) ?? false;

        $query = Usuario::with('cuotas')->when($dni, function ($query, $dni) {
            $query->where('dni', $dni);
        });
        if ($socio) {
            $query->where('socio', true);
        }
        if ($search && strlen($search) >= 3) {
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('apellido', 'like', '%' . $search . '%')
                    ->orWhere('dni', 'like', '%' . $search . '%');
            });
        }
        $query->orderBy($orderBy, $orderByDesc ? 'desc' : 'asc');

        $usuarios = $query->paginate($perPage, ['*'], 'page', $page);

        // Carga relaciones adicionales
        foreach ($usuarios as $key => $usuario) {
            $usuario->socio = $usuario->socio();
            foreach ($usuario->cuotas as $k => $cuota) {
                $cuota->pago;
            }
        }

        return [
            'usuarios' => $usuarios
        ];
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
        $request->validate([
            'nombre' => ['required'],
            'apellido' => ['required'],
            'dni' => ['required']

        ]);

        $usuarios = Usuario::where('dni', '=', $request->dni)->first();

        if (empty($usuarios)) {

            $usuario = new Usuario();
            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->mail = $request->mail;
            $usuario->telefono = $request->telefono;
            $usuario->dni = $request->dni;
            if ($request->socio) {

                $usuario->socio = 1;
            } else {
                $usuario->socio = 0;
            }

            $usuario->save();

            return response()->json([
                'message' => 'Nuevo usuario creado',
                'id' => $usuario->id
            ]);
        } else {
            return $this->sendError('El usuario ya existe', ['El usuario ya existe'], 400);
        }

        //Esta función guardará las tareas que enviaremos mediante vuejs
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $usuario = Usuario::findOrFail('dni', $request->dni);
        return $usuario;
        //Esta función devolverá los datos de una tarea que hayamos seleccionado para cargar el formulario con sus datos
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $usuario = Usuario::findOrFail($request->id);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->mail = $request->mail;
        $usuario->telefono = $request->telefono;
        // $usuario->socio = $request->socio;
        $usuario->dni = $request->dni;

        $usuario->save();

        return $usuario;
        //Esta función actualizará la tarea que hayamos seleccionado
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $motivo = $request->motivo;

        $usuario = Usuario::findOrFail($request->id);
        $usuario->motivo_baja = $motivo;
        $usuario->update();
        $usuario->delete();

        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD

    }

    public function show_dif_id(Request $request)
    {

        $usuario = Usuario::where('id', '!=', $request->id)
            ->get();
        return response()->json($usuario);
        //Esta función devolverá todos los usuarios que no tengan la id del usuario que se paso por parametro
    }


    public function obtenerCuotasUsuario(Request $request)
    {

        $cuotas = Usuario::find($request->id)->cuotas;
        $cuotas->each(function ($cuota) {
            $cuota->mes = Carbon::create(null, $cuota->mes)->locale('es')->monthName;
            $cuota->fechaPagoNombre = Carbon::create($cuota->fechaPago)->locale('es')->isoFormat('LLL');
        });
        return $cuotas;
    }

    public function showRelacionesExitentes(Request $request)
    {
        $usuario = Usuario::with('relaciones.usuarios')->find($request->id);

        $relaciones = $usuario->relaciones;

        foreach ($relaciones as $relacion) {

            $relacion->usuario = $relacion->usuarios->firstWhere('id', '!=', $request->id);
        }

        return response()->json($relaciones);
    }

    public function getHistory($id)
    {
        $torneos_usuario = DB::table('torneo_usuario')->where('usuario_id', $id)->get();
        $torneos = [];

        foreach ($torneos_usuario as $key => $torneo_usuario) {
            $torneo = Torneo::whereId($torneo_usuario->torneo_id)->first();
            $jugador_puntos = $torneo_usuario->puntos;
            $fechas_data = [];

            $fechas = Fecha::where('torneo_id', $torneo->id)
                ->orderByDesc('created_at')->get();

            foreach ($fechas as $key => $fecha) {
                $fecha_usuario = DB::table('fecha_usuario')->where('fecha_id', $fecha->id)->where('usuario_id', $id)->first();

                if ($fecha_usuario) {
                    $jugador_puntos += $fecha_usuario->puntos;
                    $jugador_puntos = $jugador_puntos < 0 ? 0 : $jugador_puntos;
                }


                $fecha_data = (object)[
                    'nombre' => $fecha->nombre,
                    'puntos_totales' => $jugador_puntos,
                    'nuevos_puntos' => $fecha_usuario ? $fecha_usuario->puntos : '-',
                    'monto_pagado' => $fecha_usuario ? $fecha_usuario->monto_pagado : '-',
                    'created_at' => $fecha->created_at
                ];
                array_push($fechas_data, $fecha_data);
            }

            $torneo_data = (object)[
                'id' => $torneo->id,
                'nombre' => $torneo->nombre,
                'fechas' => $fechas_data,
                'puntos_base' => $torneo_usuario->puntos
            ];
            array_push($torneos, $torneo_data);
        }

        return $torneos;
    }

    public function getCuotas(Request $request, $id)
    {
        try {
            //si el crone anda bien esto no iria
            $service = new CuotaService();
            // $service->updateLatePayment();
            if ($service->hasErrors()) {
                return $this->sendServiceError($service->getLastError());
            }
            //end

            $request->validate([
                'id' => 'string|numeric|nullable',
                'pagas' => 'bool|nullable'
            ]);
            $pagas = (int)$request->input('pagas');

            // $query = Cuota::where('id', $id);


            // $query = $query->Leftjoin('pagos', 'pagos.cuota_id', 'cuotas.id');

            $cuotas = Cuota::with('pago')->where('usuario_id', $id)->orderBy('periodo', 'desc')->get();

            // $cuotas = $query->get();

            return $this->sendResponse(CuotaResource::collection($cuotas), 'Cuotas listadas con exito.');
        } catch (Exception $e) {
            return $this->sendError($e->errorInfo[2]);
        }
    }
}
