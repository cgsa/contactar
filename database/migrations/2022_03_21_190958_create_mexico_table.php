<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMexicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mexico', function (Blueprint $table) {
            $table->id();
            $table->integer('clave_censal')->nullable();
            $table->string('poblacion',120)->nullable();
            $table->string('municipio',150)->nullable();
            $table->string('estado',50)->nullable();
            $table->string('presuscripcion',50)->nullable();
            $table->integer('region')->nullable();
            $table->integer('asl')->nullable();
            $table->integer('nir')->nullable();
            $table->integer('serie')->nullable();
            $table->integer('numeracion_inicial')->nullable();
            $table->integer('numeracion_final');
            $table->integer('ocupacion')->nullable();
            $table->string('tipo_red',50)->nullable();
            $table->string('modalidad',50)->nullable();
            $table->string('razon_social',100)->nullable();
            $table->string('fecha_asignacion',20)->nullable();
            $table->string('fecha_consolidacion',20)->nullable();
            $table->string('fecha_migracion',50)->nullable();
            $table->string('nir_anterior',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mexico');
    }
}
