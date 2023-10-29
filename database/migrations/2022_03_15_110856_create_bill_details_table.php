<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('Bill_id')->unsigned()->index()->nullable();
            $table->foreign('Bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->integer('item_id')->unsigned()->index()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->tinyInteger('size')->nullable();
            $table->tinyInteger('count')->nullable();
            $table->double('price');
            $table->tinyInteger('isUpload')->default(0);
            $table->tinyInteger('Status')->default(1);

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
        Schema::dropIfExists('bill_details');
    }
}
