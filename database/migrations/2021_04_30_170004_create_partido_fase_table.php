<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePartidoFaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partido_fase', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre');
        });

        DB::table('partido_fase')->insert([
            'nombre' => 'ajuste'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => 'grupos'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => '32avos'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => '16avos'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => 'octavos'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => 'cuartos'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => 'semis'
        ]);
        DB::table('partido_fase')->insert([
            'nombre' => 'final'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partido_fase');
    }
}
