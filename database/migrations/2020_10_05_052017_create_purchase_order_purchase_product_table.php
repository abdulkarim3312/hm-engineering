<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderPurchaseProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_purchase_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('purchase_product_id');
            $table->string('name');
            $table->string('unit');
            $table->float('quantity');
            $table->float('unit_price', 20,2);
            $table->float('total', 20,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_purchase_product');
    }
}
