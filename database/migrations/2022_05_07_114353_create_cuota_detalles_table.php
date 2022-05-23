<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuota_detalles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->decimal('monto', 8, 2); 
            $table->string('descripcion')->nullable();
            $table->BigInteger('cuota_id')->unsigned();
            $table->foreign('cuota_id')->references('id')->on('cuotas')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->BigInteger('cuota_detalle_tipo_id')->unsigned();
            $table->foreign('cuota_detalle_tipo_id')->references('id')->on('cuota_detalle_tipos')
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
        Schema::dropIfExists('cuota_detalles');
    }
}
