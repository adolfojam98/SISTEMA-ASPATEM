<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usuario;

class Cuota extends Model
{
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }

    public function detalles(){
        return $this->hasMany(CuotaDetalle::class);
    }
}
