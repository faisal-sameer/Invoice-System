<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('Shope_id')->unsigned()->index();
            $table->foreign('Shope_id')->references('id')->on('shopes')->onDelete('cascade');
            $table->integer('categories_id')->unsigned()->index()->nullable();
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('barCode')->nullable();
            $table->string('Name')->nullable();
            $table->string('Small_Name')->nullable();
            $table->double('Small_Price')->nullable();
            $table->string('Mid_Name')->nullable();
            $table->double('Mid_Price')->nullable();
            $table->string('Big_Name')->nullable();
            $table->double('Big_Price')->nullable();
            $table->string('Gallon_Name')->nullable();
            $table->double('Gallon_Price')->nullable();
            $table->string('Extra_Name')->nullable();
            $table->double('Extra_Price')->nullable();
            $table->integer('count')->default(0);
            $table->string('file')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('items');
    }
}
