<?php

namespace App\Http\Resources;

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
        return [
            'fecha_id' => $this->fecha_id,
            'categoria_id' => $this->categoria_id,
            'fase' => [
                'id' => $this->fase->id,
                'nombre' => $this->fase->nombre,
            ],
            'grupo' => [
                'id' => $this->grupo->id,
                'nombre' => $this->grupo->nombre,
            ],
            'jugadores' => [
                'jugador1' => [
                    'id' => $this->jugadores[0]->id,
                    'nombre' => $this->jugadores[0]->nombre,
                    'apellido' => $this->jugadores[0]->apellido,
                    'sets' => $this->jugadores[0]->pivot->sets,
                ],
                'jugador2' => [
                    'id' => $this->jugadores[1]->id,
                    'nombre' => $this->jugadores[1]->nombre,
                    'apellido' => $this->jugadores[1]->apellido,
                    'sets' => $this->jugadores[1]->pivot->sets,
                ],
            ]

        ];
    }
}