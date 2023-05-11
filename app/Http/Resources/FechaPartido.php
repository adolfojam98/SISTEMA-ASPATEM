<?php

namespace App\Http\Resources;

use Exception;
use Illuminate\Http\Resources\Json\JsonResource;

class FechaPartido extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        try{

        $array = [
            'id' => $this->id,
            'fecha_id' => $this->fecha_id,
            'categoria_id' => $this->categoria_id,
            'sig_partido_id' => $this->sig_partido_id,
            'fase' => [
                'id' => $this->fase->id,
                'nombre' => $this->fase->nombre,
            ]
        ];
        $cantidadJugadores = count($this->jugadores);
    
        if($cantidadJugadores > 0){
            $array['jugadores']['jugador1'] = [
                'id' => $this->jugadores[0] ? $this->jugadores[0]->id : null,
                'nombre' => $this->jugadores[0] ? $this->jugadores[0]->nombre : null,
                'apellido' => $this->jugadores[0] ? $this->jugadores[0]->apellido : null,
                'sets' => $this->jugadores[0] ? $this->jugadores[0]->pivot->sets : null,
            ];
        }

        if($cantidadJugadores > 1){
            $array['jugadores']['jugador2'] = [
                'id' => $this->jugadores[1] ? $this->jugadores[1]->id : null,
                'nombre' => $this->jugadores[1] ? $this->jugadores[1]->nombre : null,
                'apellido' => $this->jugadores[1] ? $this->jugadores[1]->apellido : null,
                'sets' => $this->jugadores[1] ? $this->jugadores[1]->pivot->sets : null,
            ];
        }
        
        if ($this->grupo) {
            $array['grupo'] = [
                'id' => $this->grupo->id,
                'nombre' => $this->grupo->nombre,
            ];
        }

        return $array;
    }catch(Exception $e){
        dd($this);
    }
}
}