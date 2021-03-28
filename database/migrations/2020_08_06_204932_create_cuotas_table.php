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
            $table->string('observacion')->nullable();
            $table->boolean('descuento')->default(false);
            $table->decimal('importe',8,2)->nullable();
            $table->dateTimeTz('fechaPago')->nullable();
            $table->BigInteger('usuario_id')->unsigned();

            $table->foreign('usuario_id')->references('id')->on('usuarios')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
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
        Schema::dropIfExists('cuotas');
    }
}
