<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Usuario::all();
        return $usuario;
        
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
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->mail = $request->mail;
        $usuario->puntos = $request->puntos;
        $usuario->telefono = $request->telefono;
        $usuario->socio = $request->socio;

        $usuario->save();

        return response()->json([
            'message' => 'Nuevo usuario creado'
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
    public function update(Request $request, Usuario $usuario)
    {
        $usuario = Usuario::findOrFail($request->mail);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->mail = $request->mail;
        $usuario->puntos = $request->puntos;
        $usuario->telefono = $request->telefono;
        $usuario->socio = $request->socio;

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
    public function destroy(Usuario $request)
    {
        $usuario = Usuario::destroy($request->mail);
        return $usuario;
        //Esta función obtendra el id de la tarea que hayamos seleccionado y la borrará de nuestra BD
   
    }
}
