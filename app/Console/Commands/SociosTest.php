<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use App\Cuota;
use App\Usuario;
use App\Configuracion;


class SociosTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SociosTest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convierte en no socios a los socios que no pagaron ninguna las ultimas 3 cuotas. Se ejecuta cada mes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $configuracion = Configuracion::firstOrFail();
        if($configuracion->automatizarBajasSocios){

            $fechaActual = carbon::now();
            $fechaMesSiguiente = carbon::now();
            $fechaUnMesAntes = carbon::now();
            $fechaDosMesesAntes = carbon::now();
            $fechaTresMesesAntes = carbon::now();

            $fechaMesSiguiente->addMonth(1);
            $fechaUnMesAntes->subMonth(1);
            $fechaDosMesesAntes->subMonth(2);
            $fechaTresMesesAntes->subMonth(3);

            $socios = Usuario::where('socio', true)->get();


            foreach ($socios as $socio) {

                $cuotas = Cuota::where('mes', $fechaActual->month)
                                ->where('anio', $fechaActual->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaUnMesAntes->month)
                                ->where('anio', $fechaUnMesAntes->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaDosMesesAntes->month)
                                ->where('anio', $fechaDosMesesAntes->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaTresMesesAntes->month)
                                ->where('anio', $fechaTresMesesAntes->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')

                                ->orWhere('mes',$fechaMesSiguiente->month)
                                ->where('anio', $fechaMesSiguiente->year)
                                ->where('usuario_id', $socio->id)
                                ->where('fechaPago','<>','')->get();
                
                    if($cuotas=='[]'){
                        YaNoSonSocios:info($socio->dni);
                        $socio->socio=false;
                        $socio->save();
                    }

            }
       
        }

        Log::info('-------------'); 

    }
}
