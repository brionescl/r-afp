<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// @codingStandardsIgnoreLine
class CreateRentabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fund_administrator_id');
            $table->string('investment_fund');
            $table->date('date');
            $table->float('rentability');
            $table->timestamps();
            $table->foreign('fund_administrator_id')->references('id')->on('fund_administrators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentabilities');
    }
}
