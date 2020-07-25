<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relacionado extends Model
{
    protected $fillable = ['id_socio_A','id_socio_B','relacion'];
}
