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
        Schema::create('paint_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paint_configure_id');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('estimate_floor_id');
            $table->bigInteger('estimate_floor_unit_id');
            $table->bigInteger('unit_section_id');
            $table->tinyInteger('wall_direction');
            $table->tinyInteger('paint_type');
            $table->float('length',8,2);
            $table->float('height',8,2);
            $table->float('deduction_length_one',8,2);
            $table->float('deduction_height_one',8,2);
            $table->float('deduction_length_two',8,2);
            $table->float('deduction_height_two',8,2);
            $table->float('side',8,2);
            $table->double('sub_total_deduction',20,2);
            $table->double('sub_total_area',20,2);
            $table->double('sub_total_paint_liter',20,2);
            $table->double('sub_total_seller_liter',20,2);
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
        Schema::dropIfExists('paint_configure_products');
    }
};
