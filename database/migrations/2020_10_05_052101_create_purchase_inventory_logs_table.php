<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInventoryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_inventory_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('purchase_product_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->tinyInteger('type')->comment('1=In; 2=Out');
            $table->date('date');
            $table->float('quantity', 20,2);
            $table->float('unit_price', 20,2)->nullable();
            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('purchase_inventory_logs');
    }
}
