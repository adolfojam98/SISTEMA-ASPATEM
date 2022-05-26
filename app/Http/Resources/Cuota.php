<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CuotaDetalle as CuotaDetalleResource;

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
            'monto_total' => $this->montoTotal(),
            'cuota_detalle' => CuotaDetalleResource::collection($this->detalles()->get()),
            'pago' => $this->pago,
        ];
    }
}
