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
            $table->BigInteger('partido_fase_id')->unsigned();
            $table->BigInteger('grupo_id')->unsigned()->nullable();
            $table->BigInteger('sig_partido_id')->unsigned()->nullable();
            


            $table->foreign('categoria_id')->references('id')->on('categorias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('partido_fase_id')->references('id')->on('partido_fase')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('fecha_id')->references('id')->on('fechas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('partidos', function (Blueprint $table) {
            $table->foreign('sig_partido_id')->references('id')->on('partidos')
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
