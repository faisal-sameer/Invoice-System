<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCompoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_compounds', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('item_id')->unsigned()->index()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->integer('store_id')->unsigned()->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->tinyInteger('size');
            $table->double('count')->nullable();

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
        Schema::dropIfExists('item_compounds');
    }
}
