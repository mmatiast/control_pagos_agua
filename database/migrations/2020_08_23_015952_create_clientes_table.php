<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 25)->nullable();
            $table->string('cui', 13)->unique();
            $table->string('nombres', 45);
            $table->string('apellidos', 45);
            $table->char('sexo', 1)->comment('F=femenino, M=masculino');
            $table->char('estado_civil', 1)->comment('S=soltero, C=casado, U=unido, V=viudo');
            $table->integer('celular')->nullable();
            $table->string('correo')->nullable();
            $table->unsignedBigInteger('user_id')->comment('Usuario que registró al cliente');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('clientes');
    }
}
