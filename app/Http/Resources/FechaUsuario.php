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
            'id' => $this->id,
            'usuario_id' => $this->pivot->usuario_id,
            'fecha_id' => $this->pivot->fecha_id,
            'categoria_menor_id' => $this->pivot->categoria_menor_id,
            'categoria_mayor_id' => $this->pivot->categoria_mayor_id,
            'puntos' => $this->pivot->puntos,
            'monto_pagado' => $this->pivot->monto_pagado,
        ];
    }
}