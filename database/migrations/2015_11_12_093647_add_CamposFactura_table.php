<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposFacturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::table('headInvoices', function (Blueprint $table) {
            //$table->biginteger('numero')->after("id");
            //$table->string('tipoDoc',1)->after("cliente_id");
        //});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headInvoices', function (Blueprint $table) {
            //
        });
    }
}
