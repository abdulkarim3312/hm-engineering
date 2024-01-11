<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no');
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('supplier_id');
            $table->float('total', 20,2)->default(0);
            $table->float('paid', 20,2)->default(0);
            $table->float('due', 20,2)->default(0);
            $table->date('date');
            $table->text('supplier_invoice')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->date('received_at')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
