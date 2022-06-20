<?php

use Illuminate\Database\Seeder;
use App\CuotaDetalleTipo;
use App\Http\Services\CuotaService;
use App\Constants;


class CuotaDetalleTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = new CuotaService();

        $detallesTiposDefault = Constants::CuotaDetalleTipos;

        foreach ($detallesTiposDefault as $key => $tipo) {
            $service->createCuotaDetalleTipo($key, null, null);
        }

        $detallesTiposExceptionals = Constants::CUOTA_DETALLES_TIPOS_EXCEPTIONLS;
        
        foreach ($detallesTiposExceptionals as $key => $tipo) {
            $service->createCuotaDetalleTipo($key, null, null);
        }

        // $cuotaDetalleTipo = new CuotaDetalleTipo();
        // $cuotaDetalleTipo->nombre = 'precio base';
        // $cuotaDetalleTipo->porcentaje = rand(0*10, 100.00*10) / 10;
        // $cuotaDetalleTipo->valor = rand(500*10, 7500*10) / 10;
        // $cuotaDetalleTipo->save();

        //factory(CuotaDetalleTipo::class, 3)->create();
    }
}
