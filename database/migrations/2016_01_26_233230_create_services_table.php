<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numeroServicio');
            $table->timestamp('fechaServicio');
            $table->tinyInteger('tipo');
            $table->string('cliente');
            $table->string('ruc');
            $table->string('direcion');
            $table->string('telefono');
            $table->string('empresa');
            $table->string('descripcion'); 
            $table->string('modelo'); 
            $table->string('serie');            
            $table->string('accesorios');
            $table->string('diagnostico');
            $table->string('accionCorrectiva');
            $table->tinyInteger('estado');
            $table->string('ordenTrabajo');

            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->integer('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('employees');

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
        Schema::drop('services');
    }
}
