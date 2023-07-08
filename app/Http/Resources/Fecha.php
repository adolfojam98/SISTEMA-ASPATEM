<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Fecha extends JsonResource
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
            'id' => $this->id,
            'tipo' => 'Fecha',
            'monto' => $this->calcularIngresos(),
            'descripcion' => "Fecha: {$this->nombre} - Torneo: {$this->getTorneoNombre()}",
            'fecha' => substr($this->created_at,0,10),
        ];
    }
}
