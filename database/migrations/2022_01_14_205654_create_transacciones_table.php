<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->integer('cant');
            $table->string('origen',20);
            $table->string('ip',20);
            $table->char('free',1);
            $table->bigInteger('idcliente')->unsigned()->index();
            $table->foreign('idcliente')->references('id')->on('clientes')->onDelete('cascade');
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
        Schema::dropIfExists('transacciones');
        Schema::enableForeignKeyConstraints();
    }
}
