<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->BigInteger('fecha_id')->unsigned();
            $table->BigInteger('categoria_id')->unsigned();
            $table->BigInteger('jugador1_id')->unsigned();
            $table->BigInteger('jugador2_id')->unsigned();
            $table->integer('jugador1_sets');
            $table->integer('jugador2_sets');

            $table->foreign('jugador1_id')->references('id')->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('jugador2_id')->references('id')->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            $table->foreign('categoria_id')->references('id')->on('categorias')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidos');
    }
}
