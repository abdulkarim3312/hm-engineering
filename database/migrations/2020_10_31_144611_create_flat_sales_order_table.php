<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlatSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flat_sales_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sales_order_id');
            $table->integer('flat_id');
            $table->string('project_name');
            $table->string('flat_name');
            $table->float('price', 20,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flat_sales_order');
    }
}
