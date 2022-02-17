<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempTelefonosFiltradosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_telefonos_filtrados', function (Blueprint $table) {
            $table->id();
            $table->integer('idtelefono');
            $table->string('telefono',35);
            $table->smallInteger('is_agregado')->default('0');
            $table->smallInteger('filtrar')->default('0');
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
        Schema::dropIfExists('temp_telefonos_filtrados');
        Schema::enableForeignKeyConstraints();
    }
}
