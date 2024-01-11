<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no');
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('flat_id');
            $table->unsignedInteger('client_id');
            $table->date('date');
            $table->date('next_date')->nullable();
            $table->date('delivery_at')->nullable();
            $table->float('total', 20,2);
            $table->float('paid', 20,2);
            $table->float('due', 20,2);
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
        Schema::dropIfExists('sales_orders');
    }
}
