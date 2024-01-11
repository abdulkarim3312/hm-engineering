<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodCostItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_cost_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('food_cost_id');
            $table->bigInteger('labour_employee_id');
            $table->date('date');
            $table->double('food_cost',20,2);
            $table->string('received_by');
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
        Schema::dropIfExists('food_cost_items');
    }
}
