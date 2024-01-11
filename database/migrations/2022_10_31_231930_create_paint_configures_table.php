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
        Schema::create('paint_configures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('estimate_floor_id');
            $table->bigInteger('estimate_floor_unit_id');
            $table->string('paint_configure_no')->nullable();
            $table->float('floor_number',8,2);
            $table->float('color_paint_per_cft',8,2);
            $table->float('seller_paint_per_cft',8,2);
            $table->double('total_area_with_floor',20,2);
            $table->double('total_area_without_floor',20,2);
            $table->double('total_paint_liter_with_floor',20,2);
            $table->double('total_paint_liter_without_floor',20,2);
            $table->double('total_seller_liter_with_floor',20,2);
            $table->double('total_seller_liter_without_floor',20,2);
            $table->date('date');
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
        Schema::dropIfExists('paint_configures');
    }
};
