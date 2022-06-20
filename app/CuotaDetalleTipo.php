<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CuotaDetalleTipo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function cuotaDetalles(){
        return $this->hasMany(Cuota::class);
    }

}
