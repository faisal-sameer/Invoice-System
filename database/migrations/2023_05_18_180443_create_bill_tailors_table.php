<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillTailorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_tailors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('Bill_id')->unsigned()->index()->nullable();
            $table->foreign('Bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->integer('item_id')->unsigned()->index()->nullable();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            
            $table->tinyInteger('count_no')->default(1);
            $table->string('length')->nullable();
            $table->string('shoulder')->nullable();
            $table->string('sleeves')->nullable();
            $table->string('neck')->nullable();
            $table->string('chest')->nullable();
            $table->string('expand_hand')->nullable();
            $table->string('under_poket')->nullable();
            $table->boolean('zipper')->nullable();
            $table->boolean('double_line')->nullable();
            $table->boolean('under')->nullable();
            $table->boolean('cuff')->nullable();
            $table->boolean('under_poket_check')->nullable();

            $table->string('under_details')->nullable();
            $table->string('cuff_details')->nullable();
            $table->string('under_poket_details')->nullable();

            $table->string('price');
            $table->string('name')->nullable();
            $table->string('model_name')->nullable();

            $table->integer('up_poket_id')->unsigned()->index()->nullable();
            $table->foreign('up_poket_id')->references('id')->on('items_tailors')->onDelete('cascade');
            $table->string('up_poket_details')->nullable();

            $table->integer('neck_id')->unsigned()->index()->nullable();
            $table->foreign('neck_id')->references('id')->on('items_tailors')->onDelete('cascade');
            $table->string('neck_details')->nullable();

            $table->integer('hand_id')->unsigned()->index()->nullable();
            $table->foreign('hand_id')->references('id')->on('items_tailors')->onDelete('cascade');
            $table->string('hand_details')->nullable();

            $table->integer('midstyle_id')->unsigned()->index()->nullable();
            $table->foreign('midstyle_id')->references('id')->on('items_tailors')->onDelete('cascade');
            $table->string('midstyle_details')->nullable();
            
            $table->string('downhand_up_details')->nullable();
            $table->string('downhand_right_details')->nullable();
            $table->string('downhand_down_details')->nullable();



            $table->text('notes')->nullable();
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
        Schema::dropIfExists('bill_tailors');
    }
}
