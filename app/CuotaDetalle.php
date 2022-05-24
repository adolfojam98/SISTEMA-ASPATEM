<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CuotaDetalleTipo;
use App\Http\Resources\CuotaDetalleTipo as CuotaDetalleTipoResource;

class CuotaDetalle extends Model
{
    public function cuota(){
        return $this->belongsTo(Cuota::class);
    }

    public function tipo(){
        return $this->belongsTo(CuotaDetalleTipo::class, 'cuota_detalle_tipo_id');
    }
}
