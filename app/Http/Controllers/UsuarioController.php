<?php

namespace App\Http\Controllers;

use App\Relacion;
use App\Usuario;
use App\Cuota;
use App\Configuracion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
        return Usuario::all();
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->mail = $request->mail;
        $usuario->telefono = $request->telefono;
        $usuario->dni = $request->dni;
        if($request->socio){

            $usuario->socio = 1;
        }else{
            $usuario->socio = 0;

        }

        $usuario->save();

        return response()->json([
            'message' => 'Nuevo usuario creado',
            'id' => $usuario->id
        ]);

        //Esta función guardará las tareas que enviaremos mediante vuejs
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $request)
    {
        $usuario = Usuario::findOrFail($request->mail);
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
        $usuario->socio = $request->socio;
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
        $usuario = Usuario::findOrFail($request->id);
        $usuario->delete();

        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
   
    }

    public function show_dif_id(Request $request)
    {
        
        $usuario = Usuario::where('id','!=',$request->id)
        ->get();
        return response()->json($usuario);
        //Esta función devolverá todos los usuarios que no tengan la id del usuario que se paso por parametro
    }


    public function obtenerCuotasUsuario(Request $request){
       
        $cuotas = Usuario::find($request->id)->cuotas;
        $cuotas->each(function($cuota){
            $cuota->mes = Carbon::create(null,$cuota->mes)->locale('es')->monthName;
            $cuota->fechaPagoNombre = Carbon::create($cuota->fechaPago)->locale('es')->isoFormat('LLL');
        });
        return $cuotas;
    }

public function showRelacionesExitentes(Request $request){
    $usuario = Usuario::with('relaciones.usuarios')->find($request->id);
  
    $relaciones = $usuario->relaciones;

    foreach($relaciones as $relacion){
      
        $relacion->usuario = $relacion->usuarios->firstWhere('id', '!=', $request->id);
       
        
    }
    
    return response()->json($relaciones);
}

   
}

