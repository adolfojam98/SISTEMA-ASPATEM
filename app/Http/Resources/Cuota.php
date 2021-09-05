<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cuota extends JsonResource
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
            'tipo' => 'Cuota',
            'monto' => $this->importe,
            'descripcion' => $this->observacion ? $this->observacion : 'No se registro ninguna observación',
            'fecha' => substr($this->fechaPago,0,10),
        ];
    }
}
