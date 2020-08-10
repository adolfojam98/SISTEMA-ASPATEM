<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usuario;

class Cuota extends Model
{
    public function usuario(){
        
        return $this->belongsTo(Usuario::class);
    }
}
