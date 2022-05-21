<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CuotaDetalle extends JsonResource
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
            //'cuota_id' => $this->cuota_id,
            'monto' => $this->monto,
            'cuota_detalle_tipo' => $this->tipo()
        ];
    }
}
