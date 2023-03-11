<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Torneo;

class Fecha extends Model
{
    public function jugadores() {
        return $this->belongsToMany(Usuario::class)->withPivot('puntos', 'monto_pagado', 'categoria_mayor_id', 'categoria_menor_id');
    }
    
    public function resumen_jugadores() {//todos los jugadores anotados al torneo hasta esta fecha
        $ranking_hasta_esta_fecha = [];
        $categorias = $this->torneo->categorias;
        $torneo_usuarios = $this->torneo->jugadores;
        

        foreach ($torneo_usuarios as $key => $torneo_usuario) {
            $fechas_usuarios = $this->torneo->fechas()
            ->where('fechas.created_at', '=<', $this->created_at) //TODO voy a traer todas las fechas anteriores y esta
            ->where('fecha_usuario.usuario_id', $torneo_usuario->usuario_id)
            ->join('fecha_usuario', 'fecha_usuario.fecha_id', '=', 'fechas.id')
            ->get();

            foreach ($fechas_usuarios as $key => $fecha_usuario) {
                $torneo_usuario->puntos += $fecha_usuario->puntos;
            }

            $jugador = [//TODO hacer el resource
                "usuario_id" => $torneo_usuario->usuario_id,
                "dni" => $torneo_usuario->dni,
                "nombre" => $torneo_usuario->nombre,
                "apellido" => $torneo_usuario->apellido,
                "puntos" => $torneo_usuario->puntos,
                "puntos_ultima_fecha" => $this->fecha_usuario($torneo_usuario->usuario_id)->first()->puntos ?? 0,
                "categoria" => $this->calcularCategoria($categorias, $torneo_usuario->puntos)
            ];
                
            array_push($ranking_hasta_esta_fecha, $jugador);
                
        }

        $data = [//TODO hacer el resource
            "ranking" => $ranking_hasta_esta_fecha,
            "fecha_nombre" => $this->nombre,
            "categorias" => $categorias
        ];
        
        return $data;
    }

    public function calcularIngresos(){
        return DB::table('fecha_usuario')
            ->where('fecha_id',$this->id)
            ->sum('monto_pagado');
    }

    public function getTorneoNombre(){
        return Torneo::whereId($this->torneo_id)->first()->nombre;
    }

    public function torneo(){
        return $this->belongsTo(Torneo::class);
    }

    function jugador_fecha($jugador_id) {
        $fecha_usuario = DB::table('fecha_usuario')
        ->where('usuario_id',$jugador_id)
        ->where('fecha_id', $this->id)->first();
    
        return $fecha_usuario;
    }

    function fecha_usuario($usuario_id) {
        $fecha_usuario = $this->belongsToMany(Usuario::class)
        ->where('usuario_id',$usuario_id)
        ->where('fecha_id', $this->id)->withPivot('puntos', 'monto_pagado', 'categoria_mayor_id', 'categoria_menor_id');

        return $fecha_usuario;
    }

    function calcularCategoria($categorias, $puntos){//TODO mover
        foreach ($categorias as $key => $categoria) {
            if ($puntos >= $categoria->puntos_minimos && $puntos <= $categoria->puntos_maximos) 
            {
                return $categoria->nombre;
            }
        }
    }

}
