<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorneoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torneo_usuario', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('puntos');
            $table->BigInteger('usuario_id')->unsigned();
            $table->BigInteger('torneo_id')->unsigned();

            $table->foreign('usuario_id')->references('id')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('torneo_id')->references('id')->on('torneos')
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
        Schema::dropIfExists('torneo_usuario');
    }
}
