<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Services\ConfiguracionService;
use App\Constants;


class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = new ConfiguracionService();

        $defaultConfiguraciones = Constants::CONFIGURACIONES_DEFAULT;

        foreach ($defaultConfiguraciones as $key => $value) {
            $service->createConfiguracion($key, $value);
        }
    }
}
