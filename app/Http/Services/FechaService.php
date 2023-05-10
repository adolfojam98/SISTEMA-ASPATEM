<?php

namespace App\Http\Services;

use App\Error;
use App\Fecha;
use App\FechaUsuario;
use App\Partido;
use App\Usuario;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FechaService extends BaseService
{
    function __construct()
    {
        parent::__construct();

        $this->errorDefinitions[] = new Error("FEC0001", "Unespected error", "Unespected", 500);
        $this->errorDefinitions[] = new Error("FEC0002", "Hay partidos sin usuarios asignados.", "Unespected", 500);
    }

    function fechaUsuarioDelete($fecha_id, $usuario_id)
    {
        $this->clearErrors();

        $fecha_usuario = DB::table('fecha_usuario')
            ->where('usuario_id', $usuario_id)
            ->where('fecha_id', $fecha_id)->delete();
    }

    function resetPuntosFecha($fecha_id)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $fecha_usuarios = FechaUsuario::where('fecha_id', $fecha_id)->get();

        foreach ($fecha_usuarios as $key => $fecha_usuario) {
            $fecha_usuario->puntos = 0;
            $fecha_usuario->save();
        }
    }

    function validarPartidosFecha($fecha_id)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();
        $valid = true;

        $fecha_usuarios = FechaUsuario::where('fecha_id', $fecha_id)->get();

        foreach ($fecha_usuarios as $key => $fecha_usuario) {
            if(!$fecha_usuario->usuario_id) {
                $valid = false;
            }
        }

        return $valid;
    }

    function update($fecha_id, $nombre = null, $vigencia = null, $monto_no_socios_dos_categorias = null, $monto_no_socios_una_categoria = null, $monto_socios_dos_categorias = null, $monto_socios_una_categoria = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $fecha = Fecha::find($fecha_id)->first();

        if($nombre)
            $fecha->nombre = $nombre;

        if(isset($vigencia)) {
            $fecha->vigencia = $vigencia;

            $partidosValidos = $this->validarPartidosFecha($fecha_id);
            if(!$partidosValidos) {
                $this->setError("FEC0002");
            }
        }

        if(isset($monto_no_socios_dos_categorias))
            $fecha->monto_no_socios_dos_categorias = $monto_no_socios_dos_categorias;

        if(isset($monto_no_socios_una_categoria))
            $fecha->monto_no_socios_una_categoria = $monto_no_socios_una_categoria;

        if(isset($monto_socios_dos_categorias))
            $fecha->monto_socios_dos_categorias = $monto_socios_dos_categorias;

        if(isset($monto_socios_una_categoria))
            $fecha->monto_socios_una_categoria = $monto_socios_una_categoria;

        $fecha->save();
        return $fecha;
    }

    function updateFechaUsuario($fecha_id, $usuario_id, $categoria_mayor_id = null, $categoria_menor_id = null, $monto_pagado = null, $puntos = null)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $fecha = Fecha::whereId($fecha_id)->first();
        $fecha_usuario = $fecha->fecha_usuario($usuario_id)->first();
        $numCategorias = 0;

        if ($fecha_id && $usuario_id) { //TODO esto que borro para volver a agregar se puede arreglar como la funcion de arriba "resetPuntosFecha"

            if ($fecha_usuario) { //guardamos los datos que necesitamos antes de borrar
                $categoria_menor_id = $categoria_menor_id !== false ? $categoria_menor_id  :  $fecha_usuario->categoria_menor_id;
                $categoria_mayor_id = $categoria_mayor_id !== false ? $categoria_mayor_id :  $fecha_usuario->categoria_mayor_id;
                $puntos = $fecha_usuario->puntos + $puntos ?? 0; //voy sumando los puntos
                $this->fechaUsuarioDelete($fecha_id, $usuario_id);
            }
            $fecha_usuario = new FechaUsuario();
            $fecha_usuario->fecha_id = $fecha_id;
            $fecha_usuario->usuario_id = $usuario_id;

            if (isset($categoria_menor_id)) {
                $fecha_usuario->categoria_menor_id = $categoria_menor_id;
                $numCategorias++;
            }

            if (isset($categoria_mayor_id)) {
                $fecha_usuario->categoria_mayor_id = $categoria_mayor_id;
                $numCategorias++;
            }

            $fecha_usuario->monto_pagado = $this->calculateMonto($fecha_id, $usuario_id, $numCategorias) ?? 0;

            $fecha_usuario->puntos = $puntos ?? 0;

            $fecha_usuario->save();

            return $fecha_usuario;
        } else {
            $this->setError("FEC0001");
        }

        return false;
    }

    function calculateMonto($fechaId, $usuarioId, $numCategorias)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        if ($numCategorias == 0)
            return 0;

        $fecha = Fecha::find($fechaId)->first();
        $statusSocio = Usuario::find($usuarioId)->socio();

        if ($statusSocio->activo) {
            return $numCategorias == 1 ? $fecha->monto_socios_una_categoria : $fecha->monto_socios_dos_categorias;
        } else {
            return $numCategorias == 1 ? $fecha->monto_no_socios_una_categoria : $fecha->monto_no_socios_dos_categorias;
        }

        return 0;
    }

    function updatePuntos($fecha_id)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        //reiniciamos los puntos de la fecha, ya que los vamos a recalcular
        $this->resetPuntosFecha($fecha_id);

        //fecha, de la fecha vamos a tomar algunos datos del modelo
        $fecha = Fecha::find($fecha_id)->first();

        //del torneo vamos a tomar los puntos que se ganan/pierden para cada caso
        $torneo = $fecha->torneo()->first();

        //todo los jugadores que jugaron el torneo con su categoria y puntos hasta la fecha anterior a esta
        $jugadores = $fecha->resumen_jugadores();

        //todos los partidos de esta fecha
        $partidos = $fecha->partidos()->get();

        //recoro los partidos
        foreach ($partidos as $key => $partido) {
            //por cada partido busco los jugadores por su id en la lista de jugadores (reutilizamos para tomar los puntos ya calculados)
            $jugadores_partido = $partido->jugadores()->get();

            //chequeamos si el partido tiene todos los datos cargados (puede cargarse a medias)
            if(count($jugadores_partido) == 2 && isset($jugadores_partido[0]->pivot->sets) && isset($jugadores_partido[1]->pivot->sets)) {
                $keyJugadorUno = array_search($jugadores_partido[0]->id, array_column($jugadores['ranking'], 'usuario_id'));
                $keyJugadorDos = array_search($jugadores_partido[1]->id, array_column($jugadores['ranking'], 'usuario_id'));

                $puntosParaJugadorUno = 0;
                $puntosParaJugadorDos = 0;

                //aqui tenemos los jugadores
                $jugadorUno = $jugadores['ranking'][$keyJugadorUno];
                $jugadorDos = $jugadores['ranking'][$keyJugadorDos];

                //TODO checkear que los puntos esten bien cuando haya más de una fecha
                //TODO falta ver y asignar lo que pagaron los jugadores

                //vemos si son de la misma categoria
                $mismaCategoria = $jugadorUno['categoria'] == $jugadorDos['categoria'];

                //vemos si jugador1 tiene puntos >= a jugador2
                $jugadorUnoMasPuntos = $jugadorUno['puntos'] >= $jugadorDos['puntos'];

                //vemos si jugador1 gano
                $jugadorUnoGano = $jugadores_partido[0]->pivot->sets > $jugadores_partido[1]->pivot->sets;

                //asigno los puntos en funcion de lo anterior y del ganador y perdedor
                $puntosObtenidosPorJugador = $this->calcularPuntosJugadoresPartidos($torneo, $mismaCategoria, $jugadorUnoMasPuntos, $jugadorUnoGano);

                $this->updateFechaUsuario($fecha_id, $jugadores_partido[0]->id, false, false, null, $puntosObtenidosPorJugador->puntosJugadorUno); //se van sumando los puntos
                $this->updateFechaUsuario($fecha_id, $jugadores_partido[1]->id, false, false, null, $puntosObtenidosPorJugador->puntosJugadorDos); //se van sumando los puntos
            }
        }
    }

    function calcularPuntosJugadoresPartidos($torneo, $mismaCategoria, $jugadorUnoMasPuntos, $jugadorUnoGano)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $puntos = (object) [
            "puntosJugadorUno" => 0,
            "puntosJugadorDos" => 0
        ];

        if ($mismaCategoria && $jugadorUnoMasPuntos && $jugadorUnoGano) {
            $puntos->puntosJugadorUno += $torneo->misma_categoria_mayor_nivel_ganador;
            $puntos->puntosJugadorDos -= $torneo->misma_categoria_menor_nivel_perdedor;
            return $puntos;
        }

        if ($mismaCategoria && $jugadorUnoMasPuntos && !$jugadorUnoGano) {
            $puntos->puntosJugadorUno -= $torneo->misma_categoria_mayor_nivel_perdedor;
            $puntos->puntosJugadorDos += $torneo->misma_categoria_menor_nivel_ganador;
            return $puntos;
        }

        if ($mismaCategoria && !$jugadorUnoMasPuntos && $jugadorUnoGano) {
            $puntos->puntosJugadorUno += $torneo->misma_categoria_menor_nivel_ganador;
            $puntos->puntosJugadorDos -= $torneo->misma_categoria_mayor_nivel_perdedor;
            return $puntos;
        }

        if ($mismaCategoria && !$jugadorUnoMasPuntos && !$jugadorUnoGano) {
            $puntos->puntosJugadorUno -= $torneo->misma_categoria_menor_nivel_perdedor;
            $puntos->puntosJugadorDos += $torneo->misma_categoria_mayor_nivel_ganador;
            return $puntos;
        }

        if (!$mismaCategoria && $jugadorUnoMasPuntos && $jugadorUnoGano) {
            $puntos->puntosJugadorUno += $torneo->mayor_categoria_ganador;
            $puntos->puntosJugadorDos -= $torneo->menor_categoria_perdedor;
            return $puntos;
        }

        if (!$mismaCategoria && $jugadorUnoMasPuntos && !$jugadorUnoGano) {
            $puntos->puntosJugadorUno -= $torneo->mayor_categoria_perdedor;
            $puntos->puntosJugadorDos += $torneo->menor_categoria_ganador;
            return $puntos;
        }

        if (!$mismaCategoria && !$jugadorUnoMasPuntos && $jugadorUnoGano) {
            $puntos->puntosJugadorUno += $torneo->menor_categoria_ganador;
            $puntos->puntosJugadorDos -= $torneo->mayor_categoria_perdedor;
            return $puntos;
        }
    }
}
