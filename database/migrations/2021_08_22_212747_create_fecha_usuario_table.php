<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFechaUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::create('fecha_usuario', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
    
                $table->integer('puntos');
    
                $table->BigInteger('usuario_id')->unsigned();
                $table->BigInteger('fecha_id')->unsigned();
    
                $table->foreign('usuario_id')->references('id')->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');
    
            $table->foreign('fecha_id')->references('id')->on('fechas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fecha_usuario');
    }
}
