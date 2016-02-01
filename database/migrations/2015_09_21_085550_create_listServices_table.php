<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listServices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomServicio');
            $table->string('descripcion');
            $table->string('tipo');
            $table->tinyInteger('estado');
            $table->decimal('costoAprox',10,2);

            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores');

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
        Schema::drop('listServices');
    }
}
