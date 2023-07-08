<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configuracion = Configuracion::all();
        return $configuracion;
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
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function show(Configuracion $configuracion)
    {
        $configuracion = Configuracion::first();
        return $configuracion;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
                $request->validate([
                    'codigo' => 'required|string',
                    'valor' => 'required'
                ]);

                $configuracion = Configuracion::where('codigo', $request->codigo)->first();

                if (!$configuracion) {
                    return $this->sendError("No existe una configuracion con el codigo ".$request->codigo);
                }

                $configuracion->valor = $request->valor;
                $configuracion->save();

                return $configuracion;
        }
        catch(Exception $e)
        {
            return $this->sendError($e->errorInfo[2]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Configuracion  $configuracion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }

    public function modificarAutomatizacion(Request $request)
    {
        $configuracion = Configuracion::first();

        if ($configuracion != NULL) {
            $configuracion->automatizarBajasSocios = $request->automatizarBajasSocios;
            $configuracion->save();
        } else {
            $configuracion = new Configuracion();
            $configuracion->automatizarBajasSocios = $request->automatizarBajasSocios;
            $configuracion->save();
        }
    }

    public function downloadBackup(Request $request)
    {
        setlocale(LC_TIME, "es_ES");
        $nombre = strftime("%Y%m%d") . "database.sqlite";


        $file = database_path('database.sqlite');
        return response()->download($file, $nombre);
    }

    public function uploadBackup(Request $request)
    {

        if ($request->hasFile("file")) {
            $file = $request->file("file");

            $ruta = database_path('database.sqlite');

            copy($file, $ruta);
        }
    }

    public function modificarMail(Request $request){
        config(['MAIL_USERNAME' => 'prueba']);

        return "Llego";
    }

    public function traerMail(Request $request){
        return config('MAIL_USERNAME');
    }
}
