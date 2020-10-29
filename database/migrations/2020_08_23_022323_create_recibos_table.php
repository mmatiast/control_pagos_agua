<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_emision');
            $table->unsignedInteger('mes');
            $table->unsignedInteger('anio');
            $table->unsignedInteger('ultima_lectura');
            $table->unsignedInteger('lectura_actual');
            $table->string('foto_lectura_actual')->nullable();
            $table->unsignedInteger('consumo')->comment('consumo en metros cúbicos');
            $table->double('monto_consumo', 10, 2);
            $table->double('monto_exceso', 10, 2);
            $table->date('fecha_pago');
            $table->char('estado', 1)->comment('P=pagado, S=sin pago')->default('S');
            $table->string('pdf_recibo')->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id')->comment('Usuario que generó el recibo, si el id es 0, el recibo fue generado por el sistema');
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
        Schema::dropIfExists('recibos');
    }
}
