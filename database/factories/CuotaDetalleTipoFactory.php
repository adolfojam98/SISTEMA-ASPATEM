<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CuotaDetalleTipo;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(CuotaDetalleTipo::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'porcentaje' => rand(0*10, 100.00*10) / 10,
        'valor' => rand(500*10, 7500*10) / 10,
        'codigo' => Str::slug($faker->name, '_')
    ];
});