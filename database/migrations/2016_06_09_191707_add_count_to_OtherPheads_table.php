<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountToOtherPheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('OtherPheads', function (Blueprint $table) {
            $table->integer('acount_id')->unsigned()->nullable();
            $table->foreign('acount_id')->references('id')->on('SunatAcounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('OtherPheads', function (Blueprint $table) {
            //
        });
    }
}
