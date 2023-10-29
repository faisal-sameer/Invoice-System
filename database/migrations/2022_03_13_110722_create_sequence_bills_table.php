<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSequenceBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequence_bills', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('staff_id')->unsigned()->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->integer('branch_id')->unsigned()->index()->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->integer('schedule_id')->unsigned()->index()->nullable();
            $table->foreign('schedule_id')->references('id')->on('staff_schedulings')->onDelete('cascade');

            $table->dateTime('Start_Date');
            $table->dateTime('End_Date')->nullable();
            $table->double('Start_Custody');
            $table->double('End_Custody')->nullable();
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
        Schema::dropIfExists('sequence_bills');
    }
}
