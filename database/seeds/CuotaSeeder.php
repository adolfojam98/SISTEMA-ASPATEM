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
        $cantidadJugadores = Usuario::count();
        for ($j = 1 ; $j <= $cantidadJugadores ; $j++) {
            $meses_pagados = rand(1,3);
            $mesesTotales = rand($meses_pagados + 1, 12);

            for ($i = $meses_pagados ; $i<$mesesTotales ; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $j;
                $cuota->mes = $i;
                $cuota->anio = 2020;
                $cuota->importe = 300;
                $cuota->fechaPago = Carbon::now('GMT-3');
                $cuota->save();
            }

            for ($i = $mesesTotales ; $i <= 7 ; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $j;
                $cuota->mes = $i;
                $cuota->anio = 2020;
                $cuota->importe = 300;
                $cuota->save();
            }
        }
    }
}