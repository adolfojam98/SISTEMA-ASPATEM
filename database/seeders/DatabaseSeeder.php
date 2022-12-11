<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(UsuariosTableSeeder::class);
        $this->call(CuotaDetalleTipoSeeder::class);
        $this->call(CuotaSeeder::class);
        $this->call(TorneoSeeder::class);
    }
}
