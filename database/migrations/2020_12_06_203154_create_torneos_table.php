<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorneosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",30);
            $table->boolean('vigencia');
            $table->integer('misma_categoria_mayor_nivel_ganador');
            $table->integer('misma_categoria_menor_nivel_ganador');
            $table->integer('misma_categoria_mayor_nivel_perdedor');
            $table->integer('misma_categoria_menor_nivel_perdedor');

            $table->integer('mayor_categoria_ganador');
            $table->integer('menor_categoria_ganador');
            $table->integer('mayor_categoria_perdedor');
            $table->integer('menor_categoria_perdedor');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('torneos');
    }
}
