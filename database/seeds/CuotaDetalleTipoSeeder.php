<?php

use Illuminate\Database\Seeder;
use App\CuotaDetalleTipo;


class CuotaDetalleTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cuotaDetalleTipo = new CuotaDetalleTipo();
        $cuotaDetalleTipo->nombre = 'precio base';
        $cuotaDetalleTipo->porcentaje = rand(0*10, 100.00*10) / 10;
        $cuotaDetalleTipo->valor = rand(500*10, 7500*10) / 10;
        $cuotaDetalleTipo->save();

        factory(CuotaDetalleTipo::class, 3)->create();
    }
}
