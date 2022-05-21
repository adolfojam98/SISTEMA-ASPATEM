<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usuario;
use App\CuotaDetalle;
use App\Http\Resources\CuotaDetalle as CuotaDetalleResource;

class Cuota extends Model
{
    public function usuario(){
        return $this->belongsTo(Usuario::class)->get();
    }

    public function detalles(){
        return CuotaDetalleResource::collection($this->hasMany(CuotaDetalle::class, 'cuota_id', 'id')->get());
    }
}
