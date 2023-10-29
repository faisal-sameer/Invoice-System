<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillExtraToppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill__extra__toppings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('Bill_details_id')->unsigned()->index()->nullable();
            $table->foreign('Bill_details_id')->references('id')->on('bill_details')->onDelete('cascade');

            $table->integer('extra_topping_id')->unsigned()->index()->nullable();
            $table->foreign('extra_topping_id')->references('id')->on('extra_toppings')->onDelete('cascade');

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
        Schema::dropIfExists('bill__extra__toppings');
    }
}
