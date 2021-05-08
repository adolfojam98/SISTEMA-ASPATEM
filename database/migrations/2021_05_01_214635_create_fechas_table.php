<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFechasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fechas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
            $table->integer('monto_no_socios_dos_categorias');
            $table->integer('monto_no_socios_una_categoria');
            $table->integer('monto_socios_dos_categorias');
            $table->integer('monto_socios_una_categoria');
           
            $table->BigInteger('torneo_id')->unsigned();

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
        Schema::dropIfExists('fechas');
    }
}
