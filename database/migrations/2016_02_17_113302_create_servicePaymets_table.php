<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicePaymetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicePayments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('fecha');
            $table->decimal('monto',10,2);
            $table->integer('numCaja');
            $table->char('tipoPago',1);
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('services');
            $table->integer('saleMethodPayment_id')->unsigned();
            $table->foreign('saleMethodPayment_id')->references('id')->on('saleMethodPayments');
            $table->integer('detCash_id')->unsigned()->nullable();
            $table->foreign('detCash_id')->references('id')->on('detCash');
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
        Schema::drop('servicePayments');
    }
}
