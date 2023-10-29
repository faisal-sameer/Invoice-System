<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDaysToTableBills extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->integer('tailor_id')->after('CustomerType')->unsigned()->index()->nullable();
            $table->foreign('tailor_id')->references('id')->on('staff')->onDelete('cascade');
            $table->string('days')->after('tailor_id')->nullable(); 
            $table->integer('Discount_id')->after('online')->unsigned()->index()->nullable();
            $table->foreign('Discount_id')->references('id')->on('discounts')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bills', function (Blueprint $table) {
            //
        });
    }
}
