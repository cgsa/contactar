<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 60);
            $table->text('descripcion');
            $table->integer('moneda');
            $table->float('valor', 10, 0);
            $table->integer('solicitudes');
            $table->bigInteger('idestado')->unsigned()->index();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('planes');
        Schema::enableForeignKeyConstraints();
    }
}
