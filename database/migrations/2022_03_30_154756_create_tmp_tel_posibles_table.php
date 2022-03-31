<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmpTelPosiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_tel_posibles', function (Blueprint $table) {
            $table->id();
            $table->char('pais',4)->nullable();
            $table->string('dato',100)->nullable();
            $table->string('poblacion',100)->nullable();
            $table->string('tipo_red',20)->nullable();
            $table->string('indicativo',20)->nullable();
            $table->string('bloque',20)->nullable();
            $table->string('tel',20)->nullable();
            $table->string('segun',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tmp_tel_posibles');
    }
}
