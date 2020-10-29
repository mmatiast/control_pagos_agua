<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_emision');
            $table->unsignedInteger('mes');
            $table->unsignedInteger('anio');
            $table->date('fecha_ultima_lectura');
            $table->unsignedInteger('ultima_lectura');
            $table->date('fecha_lectura_actual');
            $table->unsignedInteger('lectura_actual');
            $table->string('foto_lectura_actual')->nullable();
            $table->unsignedInteger('consumo')->comment('consumo en metros cúbicos');
            $table->double('monto_consumo', 10, 2);
            $table->unsignedInteger('exceso')->comment('exceso en metros cúbicos')->nullable();
            $table->double('monto_exceso', 10, 2)->nullable();
            $table->date('fecha_pago');
            $table->char('estado', 1)->comment('P=pagado, S=sin pago')->default('S');
            $table->string('pdf_recibo')->nullable();
            $table->unsignedBigInteger('contador_id');
            $table->unsignedBigInteger('user_id')->comment('Usuario que generó el recibo, si el id es 0, el recibo fue generado por el sistema');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contador_id')->references('id')->on('contadores');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unique(['mes', 'anio', 'contador_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumos');
    }
}
