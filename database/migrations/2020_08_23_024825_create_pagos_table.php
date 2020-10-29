<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_deposito');
            $table->string('numero_deposito');
            $table->double('monto_deposito', 10, 2);
            $table->string('deposito')->nullable()->comment('Foto del comprobante de depósito');
            $table->unsignedBigInteger('contador_id');
            $table->unsignedBigInteger('consumo_id');
            $table->unsignedBigInteger('banco_id');
            $table->unsignedBigInteger('forma_pago_id');
            $table->unsignedBigInteger('user_id')->comment('Usuario que registró el pago');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contador_id')->references('id')->on('contadores');
            $table->foreign('consumo_id')->references('id')->on('consumos');
            $table->foreign('banco_id')->references('id')->on('bancos');
            $table->foreign('forma_pago_id')->references('id')->on('forma_pagos');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unique(['contador_id', 'consumo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
