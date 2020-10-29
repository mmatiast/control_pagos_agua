<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contadores', function (Blueprint $table) {
            $table->id();
            $table->string('ubicacion');
            $table->date('fecha_ultima_lectura');
            $table->integer('ultima_lectura');
            $table->integer('ultimo_mes');
            $table->integer('ultimo_anio');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id')->comment('Usuario que registró el contador');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contadores');
    }
}
