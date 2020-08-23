<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->integer('mes');
            $table->integer('anio');
            $table->boolean('descuento')->default(false);
            $table->decimal('importe',8,2);
            $table->dateTimeTz('fechaPago')->nullable();
            $table->BigInteger('usuario_id')->unsigned();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios')
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
        Schema::dropIfExists('cuotas');
    }
}
