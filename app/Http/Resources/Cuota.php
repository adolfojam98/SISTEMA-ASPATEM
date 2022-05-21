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
            'usuario_id' => $this->usuario_id,
            'periodo' => $this->periodo,
            'cuota_detalle' => $this->detalles(),
        ];
    }
}
