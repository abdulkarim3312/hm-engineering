<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWiseBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_wise_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('labour_bill_id');
            $table->bigInteger('employee_id');
            $table->date('date');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('total_attendance');
            $table->double('per_day_amount',20,2);
            $table->double('food_cost',20,2);
            $table->double('net_bill',20,2);
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
        Schema::dropIfExists('employee_wise_bills');
    }
}
