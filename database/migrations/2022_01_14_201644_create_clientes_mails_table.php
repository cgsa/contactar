<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_mails', function (Blueprint $table) {
            $table->id();
            $table->string('mail',100);
            $table->string('contacto',20);
            $table->string('datos_adicionales',50);
            $table->bigInteger('idcliente')->unsigned()->index();
            $table->foreign('idcliente')->references('id')->on('clientes')->onDelete('cascade');
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
        Schema::dropIfExists('clientes_mails');
        Schema::enableForeignKeyConstraints();
    }
}
