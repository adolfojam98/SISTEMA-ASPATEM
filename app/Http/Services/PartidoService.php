<?php

namespace App\Http\Services;

use App\Error;
use App\Partido;
use Illuminate\Support\Facades\DB;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PartidoService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("PART0001", "Unespected error", "Unespected", 500);
    }

    function createPartido($fecha_id, $categoria_id, $fase_nombre, $grupo_nombre = null, $partido_info)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $partidoFase = $this->createPartidoFase($fase_nombre);

        if($fecha_id && $categoria_id) {
            $newPartido = [
                'fecha_id' => $fecha_id,
                'categoria_id' => $categoria_id,
                'partido_fase_id' => $partidoFase->id,
                // 'sig_partido_id' => $sig_partido_id,
            ];

            if($grupo_nombre) {
                $partidoGrupo = $this->createPartidoGrupo($grupo_nombre);

                $newPartido['grupo_id'] = $partidoGrupo->id;
            }

            $newPartido = Partido::create($newPartido);

            $newPartidoUsuario1 = $this->createPartidoUsuario($newPartido->id, $partido_info->id_jugador1, $partido_info->set_jugador1);
            $newPartidoUsuario2 = $this->createPartidoUsuario($newPartido->id, $partido_info->id_jugador2, $partido_info->set_jugador2);

            return $newPartido;


        } else {
            $this->setError("PARO0001");
        }
    }

    function deletePartidos($fecha_id, $categoria_id) {
        $this->clearErrors();
        
        Partido::where('fecha_id', $fecha_id)
            ->where('categoria_id', $categoria_id)
            ->delete();
    }

    function createPartidoFase($fase_nombre)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $partidoFase = DB::table('partido_fase')->where('nombre', $fase_nombre)->first();

        if($partidoFase) {
            return $partidoFase;
        } else {
            DB::table('partido_fase')->insert([
                'nombre' => $fase_nombre
            ]);

            return DB::table('partido_fase')->where('nombre', $fase_nombre)->first();
        }
    }

    function createPartidoGrupo($grupo_nombre)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $partidoFase = DB::table('grupos')->where('nombre', $grupo_nombre)->first();

        if($partidoFase) {
            return $partidoFase;
        } else {
            DB::table('grupos')->insert([
                'nombre' => $grupo_nombre
            ]);

            return DB::table('grupos')->where('nombre', $grupo_nombre)->first();
        }
    }

    function createPartidoUsuario($partido_id, $jugador_id, $sets)
    {
        DB::table('partido_usuario')->insert([
            'partido_id' => $partido_id,
            'usuario_id' => $jugador_id,
            'sets' => $sets
        ]);

        return DB::table('partido_usuario')
            ->where('partido_id', $partido_id)
            ->where('usuario_id', $jugador_id)->first();
    }

}