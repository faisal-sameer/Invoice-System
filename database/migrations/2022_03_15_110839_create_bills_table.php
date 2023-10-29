<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->startingValue(1200);
            $table->integer('staff_id')->unsigned()->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->integer('branch_id')->unsigned()->index()->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->integer('sequence_id')->unsigned()->index()->nullable();
            $table->foreign('sequence_id')->references('id')->on('sequence_bills')->onDelete('cascade');
            $table->double('total');
            $table->double('Tax')->nullable();
            $table->double('cash')->nullable();
            $table->double('online')->nullable();
            $table->tinyInteger('isUpload')->default(0);
            $table->string('CustomerName')->nullable();
            $table->string('CustomerPhone')->nullable();
            $table->tinyInteger('CustomerType')->default(1);
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
        Schema::dropIfExists('bills');
    }
}
