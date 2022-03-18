<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_plan', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio'); 
            $table->date('fecha_fin');
            $table->unsignedBigInteger('idpago');
            $table->foreign('idpago')->references('id')->on('pagos_planes')->onDelete('cascade'); 
            $table->unsignedBigInteger('iduser');
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('idestado');
            $table->foreign('idestado')->references('id')->on('estados')->onDelete('cascade');
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
        Schema::dropIfExists('cliente_plan');
    }
}
