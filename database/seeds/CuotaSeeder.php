<?php

use Illuminate\Database\Seeder;
use App\Cuota;

class CuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cuota = new Cuota();
        $cuota->id_usuario = 12;
        $cuota->mes = 03;
        $cuota->anio = 2020;
        $cuota->importe = 230.033;

        $cuota->save();
    }
}
