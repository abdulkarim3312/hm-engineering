<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('purchase_product_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('project_id');
            $table->float('quantity', 20);
            $table->float('avg_unit_price', 20,2);
            $table->float('last_unit_price', 20,2);
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
        Schema::dropIfExists('purchase_inventories');
    }
}
