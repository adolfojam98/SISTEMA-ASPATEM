<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuotaDetalleTipo extends Model
{
    public function cuotaDetalles(){
        return $this->hasMany(Cuota::class);
    }
}
