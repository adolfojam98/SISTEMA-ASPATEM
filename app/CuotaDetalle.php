<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuotaDetalle extends Model
{
    public function cuota(){
        return $this->belongsTo(Cuota::class);
    }

    public function tipo(){
        return $this->belongsTo(TipoDetalle::class);
    }
}
