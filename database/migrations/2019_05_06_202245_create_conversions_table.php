<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->float('source_amount', 40, 2);
            $table->float('rate', 40, 12);
            $table->float('converted_amount',40, 2);
            $table->dateTime('time_stamp');
            $table->bigInteger('source_currency_id')->unsigned();
            $table->foreign('source_currency_id')->references('id')->on('currencies');
            $table->bigInteger('target_currency_id')->unsigned();
            $table->foreign('target_currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversions');
    }
}
