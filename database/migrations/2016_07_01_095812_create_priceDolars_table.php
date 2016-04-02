<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceDolarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('priceDolars', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->string('fecha2',12);
            $table->decimal('preDolar',10,2);
            $table->string('mes',10);
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
        Schema::drop('priceDolars');
    }
}
