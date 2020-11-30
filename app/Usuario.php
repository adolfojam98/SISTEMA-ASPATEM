<?php

namespace App;
use App\Relacion;
use App\Cuota;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre','apellido','mail','puntos','telefono','socio','dni'];

    public function relaciones()
    {
        return $this->belongsToMany(Relacion::class)->withPivot('usuario_id');
    }

   public function cuotas(){
       return $this->hasMany(Cuota::class)->orderByDesc('id');
   }
}

