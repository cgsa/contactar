<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_original',20);
            $table->string('numero_encontrado',20);
            $table->string('operador',256);
            $table->string('servicio', 30)->default('');
            $table->string('localidad',100);
            $table->string('es_movil', 20);
            $table->bigInteger('idestado')->unsigned()->index();
            $table->foreign('idestado')->references('id')->on('estados')->onDelete('cascade');
            $table->bigInteger('iduser')->unsigned()->index();
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('solicitudes');
    }
}
