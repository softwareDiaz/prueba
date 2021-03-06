<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaldoToOtherPheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('OtherPheads', function (Blueprint $table) {
            $table->decimal('montoPagado',10,2)->default(0);
            $table->decimal('Saldo',10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otherPheads', function (Blueprint $table) {
            //
        });
    }
}
