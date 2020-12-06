<?php

namespace App;
use App\Torneo;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}
