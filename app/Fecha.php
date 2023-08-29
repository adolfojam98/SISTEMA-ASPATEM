<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Torneo;
use App\Partido;

class Fecha extends Model
{
    public function jugadores() {
        return $this->belongsToMany(Usuario::class)->withPivot('puntos', 'monto_pagado', 'categoria_mayor_id', 'categoria_menor_id');
    }

    public function partidos() {
        return $this->hasMany(Partido::class);
    }
    
    public function resumen_jugadores() {//todos los jugadores anotados al torneo hasta la fecha anterior a la ultima
        $ranking_hasta_esta_fecha = [];
        $categorias = $this->torneo->categorias;
        $torneo_usuarios = $this->torneo->jugadores;
        foreach ($torneo_usuarios as $key => $torneo_usuario) {

            $fechas_usuarios = $this->torneo->fechas()
            ->join('fecha_usuario', 'fecha_usuario.fecha_id', '=', 'fechas.id')
            ->where('fechas.created_at', '<', $this->created_at) //traigo todas las fechas anteriores
            ->where('fecha_usuario.usuario_id', $torneo_usuario->id)
            ->get();
        $torneo_usuario->puntos = 0;

            foreach ($fechas_usuarios as $key => $fecha_usuario) {
                $torneo_usuario->puntos += $fecha_usuario->puntos;
            }

            $jugador = [
                "usuario_id" => $torneo_usuario->pivot->usuario_id,
                "dni" => $torneo_usuario->dni,
                "nombre" => $torneo_usuario->nombre,
                "apellido" => $torneo_usuario->apellido,
                "puntos" => 0, // Valor predeterminado
                "puntos_ultima_fecha" => 0, // Valor predeterminado
                "categoria" => ""
            ];
            
            // Validar y asignar puntos si son valores numéricos
            if (is_numeric($torneo_usuario->pivot->puntos) && is_numeric($torneo_usuario->puntos)) {
                $jugador["puntos"] = $torneo_usuario->pivot->puntos + $torneo_usuario->puntos;
            }
            
            // Validar y asignar puntos de última fecha si son valores numéricos
            $ultimaFechaPuntos = $this->fecha_usuario($torneo_usuario->pivot->usuario_id)->first()->puntos ?? 0;
            if (is_numeric($ultimaFechaPuntos)) {
                $jugador["puntos_ultima_fecha"] = $ultimaFechaPuntos;
            }
            
            // Asignar la categoría si es un valor válido
            $categoria = $this->calcularCategoria($categorias, $torneo_usuario->pivot->puntos);
            if ($categoria !== null) {
                $jugador["categoria"] = $categoria;
            }
            
                
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

    function fecha_usuario($usuario_id) {
        $fecha_usuario = DB::table('fecha_usuario')
        ->where('usuario_id',$usuario_id)
        ->where('fecha_id', $this->id);
    
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
