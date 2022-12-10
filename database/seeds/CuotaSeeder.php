<?php

use Illuminate\Database\Seeder;
use App\Cuota;
use Carbon\Carbon;
use App\Usuario;
use App\CuotaDetalle;
use App\CuotaDetalleTipo;
use App\Pago;
use App\Http\Resources\Cuota as ResourcesCuota;

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
            $meses_no_pagados = rand(1, 3); //2
            $mesesTotales = rand($meses_no_pagados + 1, 12); //6

            for ($i = 1; $i < $meses_no_pagados; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $jugador->id;
                $mesActual = Carbon::now();
                $mesRestado = $mesActual->subMonths($i);
                $cuota->periodo = $mesRestado;
                $cuota->save();
                $monto_base = CuotaDetalleTipo::where('codigo', 'precio_base');
                $cuotaDetalle = new CuotaDetalle();
                $cuotaDetalle->monto = 123;
                $cuotaDetalle->cuota_id = $cuota->id;
                $cuotaDetalle->cuota_detalle_tipo_id = $monto_base->id;
                $cuotaDetalle->save();
            }
            for ($i = $meses_no_pagados; $i <= $mesesTotales; $i++) {
                $cuota = new Cuota();
                $cuota->usuario_id = $jugador->id;
                $mesActual = Carbon::now();
                $mesRestado = $mesActual->subMonths($i);
                $cuota->periodo = $mesRestado;
                $cuota->save();
                $monto_base = CuotaDetalleTipo::where('codigo', 'precio_base');
                $cuotaDetalle = new CuotaDetalle();
                $cuotaDetalle->monto = 123;
                $cuotaDetalle->cuota_id = $cuota->id;
                $cuotaDetalle->cuota_detalle_tipo_id = $monto_base->id;
                $cuotaDetalle->save();

                $pago = new Pago();
                $pago->monto_total = 123;
                $pago->cuota_id = $cuota->id;
                $pago->save();
            }
        }
    }
}
