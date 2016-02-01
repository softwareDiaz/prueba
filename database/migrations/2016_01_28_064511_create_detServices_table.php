<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detServices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('precioProducto',10,2);
            $table->decimal('precioVenta',10,2);
            $table->decimal('cantidad',10,2);
            $table->decimal('descuento',10,2);
            $table->decimal('subTotal',10,2);

            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('services');

            $table->integer('detPre_id')->unsigned()->nullable();
            $table->foreign('detPre_id')->references('id')->on('detPres');

            $table->integer('listService_id')->unsigned()->nullable();
            $table->foreign('listService_id')->references('id')->on('listServices');

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
        //
    }
}
