<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_trans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('Bill_id')->unsigned()->index()->nullable();
            $table->foreign('Bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->string('item')->nullable();
            $table->integer('city_id')->unsigned()->index()->nullable();
            $table->foreign('city_id')->references('id')->on('items')->onDelete('cascade');
            $table->integer('to_city_id')->unsigned()->index()->nullable();
            $table->foreign('to_city_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('total')->nullable();

                 
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
        Schema::dropIfExists('bill_trans');
    }
}
