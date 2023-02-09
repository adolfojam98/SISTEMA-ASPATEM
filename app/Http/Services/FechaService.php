<?php

namespace App\Http\Services;

use App\Error;
use App\Fecha;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FechaService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("FEC0001", "Unespected error", "Unespected", 500);
    }

    

}