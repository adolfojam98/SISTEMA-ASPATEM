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
            'usuario_id' => $this->usuario_id,
            'fecha_id' => $this->fecha_id,
            'categoria_menor_id' => $this->categoria_menor_id,
            'categoria_mayor_id' => $this->categoria_mayor_id,
            'puntos' => $this->puntos,
            'monto_pagado' => $this->monto_pagado,
        ];
    }
}