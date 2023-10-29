<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('staff_id')->unsigned()->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            
            $table->integer('to_staff_id')->unsigned()->index()->nullable();
            $table->foreign('to_staff_id')->references('id')->on('staff')->onDelete('cascade');
           
            
            $table->integer('vacation_id')->unsigned()->index()->nullable();
            $table->foreign('vacation_id')->references('id')->on('vacations')->onDelete('cascade');
           
            $table->integer('type_id')->unsigned()->index()->nullable();
            $table->foreign('type_id')->references('id')->on('type_notifications')->onDelete('cascade');
           
            $table->string('resend_id')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('Seen')->default(1);
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
        Schema::dropIfExists('notifications');
    }
}
