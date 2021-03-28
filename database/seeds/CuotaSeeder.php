<?php

use Illuminate\Database\Seeder;
use App\Cuota;
use Carbon\Carbon;
use App\Usuario;


class CuotaSeeder extends Seeder
{
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $cantidadJugadores = Usuario::where("socio", 1)->get();

        foreach ($cantidadJugadores as $jugador) {
            $meses_no_pagados = rand(1, 3);//2
            $mesesTotales = rand($meses_no_pagados + 1, 12);//6

            for ($i = 1; $i<$meses_no_pagados ; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $jugador->id;
                $mesActual = Carbon::now();
                $mesRestado = $mesActual->subMonths($i);
                $cuota->mes = $mesRestado->month;
                $cuota->anio = $mesRestado->year;
                $cuota->save();
                

            }
            for ($i = $meses_no_pagados ; $i <= $mesesTotales ; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $jugador->id;
                $mesActual = Carbon::now();
                $mesRestado = $mesActual->subMonths($i);
                $cuota->mes = $mesRestado->month;
                $cuota->anio = $mesRestado->year;
                $cuota->importe = 300;
                $cuota->fechaPago =  Carbon::now();
                
                $cuota->save();
            }
        }
    }
}