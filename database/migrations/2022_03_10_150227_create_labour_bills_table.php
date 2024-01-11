<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabourBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labour_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('payment_type');
            $table->bigInteger('bank_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('bank_account_id')->nullable();
            $table->double('total',20,2);
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
        Schema::dropIfExists('labour_bills');
    }
}
