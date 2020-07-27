<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelacionadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relacionados', function (Blueprint $table) {
            $table->id();
            $table->integer('id_socio_A');
            $table->integer('id_socio_B');
            $table->string('ralacion',10);
            $table->timestamps();
            $table->foreign('id_socio_A')->references('id')->on('usuarios');
            $table->foreign('id_socio_B')->references('id')->on('usuarios');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relacionados');
    }
}
