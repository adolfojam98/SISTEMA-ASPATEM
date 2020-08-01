<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Relacion extends Model
{
    public function usuarios()
    {
        return $this->BelongsToMany(Usuario::class);
    }
}
