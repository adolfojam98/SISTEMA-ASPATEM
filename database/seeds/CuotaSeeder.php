<?php

use Illuminate\Database\Seeder;
use App\Cuota;
use Carbon\Carbon;

class CuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($j = 1 ; $j <= 14 ; $j++) {
            $aux2 = rand(1,3);
            $aux = rand($aux2 + 1, 7);

            for ($i = $aux2 ; $i<$aux ; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $j;
                $cuota->mes = $i;
                $cuota->anio = 2020;
                $cuota->importe = 300;
                $cuota->fechaPago = Carbon::now('GMT-3');
                $cuota->save();
            }

            for ($i = $aux ; $i <= 7 ; $i++) {
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