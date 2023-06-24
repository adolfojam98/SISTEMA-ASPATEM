<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Torneo extends JsonResource
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
            'tipo' => 'Torneo',
            'monto' => $this->calcularIngresos(),
            'descripcion' => $this->vigencia ? "Torneo vigente: {$this->nombre}" : "Torneo cerrado: {$this->nombre}",
            'fecha' => substr($this->created_at,0,10),
        ];
    }
}
