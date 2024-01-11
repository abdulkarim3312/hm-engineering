<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segment_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('segment_configure_id');
            $table->bigInteger('costing_segment_id');
            $table->tinyInteger('segment_unit_type');
            $table->string('estimate_product_id');
            $table->string('minimum_quantity');
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
        Schema::dropIfExists('segment_configure_products');
    }
};
