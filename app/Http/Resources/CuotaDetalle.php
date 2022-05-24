<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CuotaDetalleTipo as CuotaDetalleTipoResource;

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
            'descripcion' => $this->descripcion,
            'cuota_detalle_tipo' => CuotaDetalleTipoResource::collection($this->tipo()->get())
        ];
    }
}
