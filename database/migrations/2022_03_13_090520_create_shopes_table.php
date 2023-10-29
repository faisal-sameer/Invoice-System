<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->index()->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('type_id')->unsigned()->index()->nullable();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->string('Name');
            $table->string('IDnumber');
            $table->string('VTENumber');
            $table->string('Address');

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
        Schema::dropIfExists('shopes');
    }
}
