<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartidoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partido_usuario', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

        
            $table->integer('sets');

            $table->BigInteger('usuario_id')->unsigned();
            $table->BigInteger('partido_id')->unsigned();

            $table->foreign('usuario_id')->references('id')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');

        $table->foreign('partido_id')->references('id')->on('partidos')
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
        Schema::dropIfExists('partido_usuario');
    }
}
