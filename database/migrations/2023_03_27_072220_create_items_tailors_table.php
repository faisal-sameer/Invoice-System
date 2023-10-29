<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTailorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_tailors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('shope_id')->unsigned()->index()->nullable();
            $table->foreign('shope_id')->references('id')->on('shopes')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('path')->nullable();

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
        Schema::dropIfExists('items_tailors');
    }
}
