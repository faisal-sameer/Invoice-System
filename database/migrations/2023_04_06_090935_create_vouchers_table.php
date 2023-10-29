<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->tinyInteger('type_voucher');

            $table->integer('Shope_id')->unsigned()->index();
            $table->foreign('Shope_id')->references('id')->on('shopes')->onDelete('cascade');
                       
            $table->string('SirName');
            $table->string('CT')->nullable();
            $table->string('nameCT')->nullable();
            $table->string('BillNo')->nullable();
            $table->string('user_ID')->nullable();
            $table->string('city')->nullable();
            $table->string('price');
            $table->string('for')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('checkNo')->nullable();
            $table->string('Bank')->nullable();
            $table->dateTime('Date');
            $table->dateTime('Date_second')->nullable();
            
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
        Schema::dropIfExists('vouchers');
    }
}
