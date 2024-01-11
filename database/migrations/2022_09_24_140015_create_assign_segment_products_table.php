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
        Schema::create('assign_segment_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assign_segment_id');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('segment_configure_id');
            $table->bigInteger('costing_segment_id');
            $table->tinyInteger('segment_unit_type');
            $table->string('estimate_product_id');
            $table->string('quantity');
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
        Schema::dropIfExists('assign_segment_products');
    }
};
