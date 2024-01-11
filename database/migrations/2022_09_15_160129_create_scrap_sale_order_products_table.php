<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapSaleOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrap_sale_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchase_inventory_id');
            $table->bigInteger('project_id');
            $table->bigInteger('purchase_order_id');
            $table->bigInteger('purchase_product_id');
            $table->float('quantity',20,2);
            $table->float('unit_price',20,2);
            $table->float('total',20,2);
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
        Schema::dropIfExists('scrap_sale_order_products');
    }
}
