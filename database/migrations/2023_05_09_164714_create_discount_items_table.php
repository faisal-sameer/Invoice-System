<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('Discount_id')->unsigned()->index();
            $table->foreign('Discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->integer('categorie_id')->unsigned()->index()->nullable();
            $table->foreign('categorie_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('item_id')->unsigned()->index()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
    
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
        Schema::dropIfExists('discount_items');
    }
}
