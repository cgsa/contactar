<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosPlanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_planes', function (Blueprint $table) {
            $table->id();
            $table->string('idpay');
            $table->string('token'); 
            $table->string('state');
            $table->text('link');  
            $table->unsignedBigInteger('idplan');
            $table->foreign('idplan')->references('id')->on('planes')->onDelete('cascade'); 
            $table->unsignedBigInteger('iduser');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pagos_planes');
        Schema::enableForeignKeyConstraints();
    }
}
