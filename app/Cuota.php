<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usuario;
use App\CuotaDetalle;


class Cuota extends Model
{
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }

    public function detalles(){
        return $this->hasMany(CuotaDetalle::class, 'cuota_id', 'id');
    }

    public function montoTotal(){
        $montoTotal = 0;
        $detalles = $this->detalles()->get();

        foreach ($detalles as $key => $detalle) {
            $montoTotal += $detalle->monto;
        }

        return $montoTotal;
    }

    public function pago(){
        return $this->hasOne(Pago::class);
    }

}
