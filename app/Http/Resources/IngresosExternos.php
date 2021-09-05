<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IngresosExternos extends JsonResource
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
            'tipo' => 'Otros',
            'monto' => $this->monto,
            'descripcion' => $this->descripcion,
            'fecha' => substr($this->created_at,0,10),
        ];
    }
}
