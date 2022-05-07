<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    public function cuota(){
        return $this->belongsTo(Cuota::class);
    }
}
