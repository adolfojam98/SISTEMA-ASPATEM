<?php

namespace App\Http\Services;

use App\Error;
use App\Configuracion;
use App\Http\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ConfiguracionService extends BaseService
{
    function __construct() 
    {
        parent::__construct();
           
        $this->errorDefinitions[] = new Error("CON0001", "Unespected error", "Unespected", 500);
        $this->errorDefinitions[] = new Error("CON0002", "Duplicated name", "Duplicated", 500);
    }

    public function createConfiguracion($nombre, $valor)
    {
        // siempre LIMPIAR errores al iniciar un proceso de servicio
        $this->clearErrors();

        $codigo = Str::slug($nombre, '_');
        $configuracion = Configuracion::where('codigo', $codigo)->first();

        if($configuracion) {
            $this->setError("CON0002");
            return false;
        }
        
        if($nombre && isset($valor)) {
            $newConfiguracion = new Configuracion();
            $newConfiguracion->nombre = $nombre;
            $newConfiguracion->codigo = $codigo;
            $newConfiguracion->valor = $valor;
            $newConfiguracion->save();

            return $newConfiguracion;

        } else {
            $this->setError("CON0001");
        }

        return false;
    }

}