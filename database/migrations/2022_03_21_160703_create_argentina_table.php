<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArgentinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('argentina', function (Blueprint $table) {
            $table->id();
            $table->string('operador',256);
            $table->string('servicio', 30);
            $table->string('modalidad', 10);
            $table->string('localidad',100);
            $table->string('indicativo',8);
            $table->string('bloque',6);
            $table->string('resolucion',30);
            $table->string('fecha',10);
            $table->smallInteger('is_cel_pho');
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
        Schema::dropIfExists('argentina');
    }
}
