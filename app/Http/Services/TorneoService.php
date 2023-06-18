<?php

namespace App\Http\Services;

use App\Error;
use App\Pago;
use App\Torneo;
use App\Http\Services\BaseService;
use App\Usuario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TorneoService extends BaseService
{
    function __construct()
    {
        parent::__construct();

        $this->errorDefinitions[] = new Error("TOR0001", "Unespected error", "Unespected", 500);
        $this->errorDefinitions[] = new Error("TOR0002", "El jugador ya estaba anotado al torneo", "bad request", 400);
    }

    public function calculateCategoria($torneo_id, $puntos_jugador)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $torneo = Torneo::whereId($torneo_id)->first();
        $categorias = $torneo->categorias;

        if ($torneo && $categorias && $puntos_jugador >= 0) {
            $categoria_jugador = null;

            foreach ($categorias as $key => $categoria) {
                if ($categoria->puntos_minimos <= $puntos_jugador && $categoria->puntos_maximos >= $puntos_jugador) {
                    $categoria_jugador = $categoria;
                }
            }

            return $categoria_jugador;
        } else {
            $this->setError("TOR0001");
        }

        return false;
    }

    public function agregarJugadorATorneo($jugador, $torneo_id)
    {

        $usuario = Usuario::where('dni', '=', $jugador['dni'])->first();
        $this->clearErrors();

        if (empty($usuario)) {

            $nuevoUsuario = new Usuario();

            $nuevoUsuario->nombre =     $jugador['nombre'];
            $nuevoUsuario->apellido =   $jugador['apellido'];
            $nuevoUsuario->dni =        $jugador['dni'];
            $nuevoUsuario->socio = 0;
            $nuevoUsuario->save();
            $usuario = $nuevoUsuario;
        }

        $existeRelacion = $usuario->torneos()->where('torneo_id', $torneo_id)->exists();
        if ($existeRelacion) {
            $this->setError("TOR0002");
            return $usuario;
        }

        if (array_key_exists('puntos', $jugador) && $jugador['puntos'] != null) {
            $usuario->torneos()->attach($torneo_id, ['puntos' => $jugador['puntos']]);
        }

        if (array_key_exists('pivot', $jugador) && $jugador['pivot']['puntos'] != null) {
            $usuario->torneos()->attach($torneo_id, ['puntos' => $jugador['pivot']['puntos']]);
        }


        $usuario->save();
        return $usuario;
    }
}
