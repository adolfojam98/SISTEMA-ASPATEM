<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FechaUsuario extends JsonResource
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
            'usuario_id' => $this->usuario_id ?? $this->pivot->usuario_id,
            'fecha_id' => $this->fecha_id ?? $this->pivot->usuario_id,
            'categoria_menor_id' => $this->categoria_menor_id ?? $this->pivot->categoria_menor_id ?? null,
            'categoria_mayor_id' => $this->categoria_mayor_id ?? $this->pivot->categoria_mayor_id ?? null,
            'puntos' => $this->puntos ?? $this->pivot->puntos ?? null,
            'monto_pagado' => $this->monto_pagado ?? $this->pivot->monto_pagado ?? null,
            'socio' => $this->socio ?? $this->pivot->socio ?? null,
            'info_socio' => $this->info_socio ?? null
        ];
    }
}