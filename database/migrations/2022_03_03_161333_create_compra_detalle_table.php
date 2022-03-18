<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_detalle', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('cantidad');
            $table->bigInteger('idcompra')->unsigned()->index();
            $table->foreign('idcompra')->references('id')->on('compras')->onDelete('cascade');
            $table->bigInteger('idplan')->unsigned()->index();
            $table->foreign('idplan')->references('id')->on('planes')->onDelete('cascade');
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
        Schema::dropIfExists('compra_detalle');
    }
}
