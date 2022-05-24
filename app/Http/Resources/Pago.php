<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Cuota as CuotaResource;

class Pago extends JsonResource
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
            'fecha_pago' => $this->fecha_pago,
            'monto_total' => $this->monto_total,
            'cuota' => new CuotaResource($this->cuota()->first())
        ];
    }
}
