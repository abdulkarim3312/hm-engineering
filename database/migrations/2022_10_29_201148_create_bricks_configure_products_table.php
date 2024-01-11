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
        Schema::create('bricks_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bricks_configure_id');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('estimate_floor_id');
            $table->bigInteger('estimate_floor_unit_id');
            $table->bigInteger('unit_section_id');
            $table->tinyInteger('wall_direction');
            $table->float('length',8,2);
            $table->float('height',8,2);
            $table->float('wall_number',8,2);
            $table->float('deduction_length_one',8,2);
            $table->float('deduction_height_one',8,2);
            $table->float('deduction_height_two',8,2);
            $table->float('deduction_length_three',8,2);
            $table->float('deduction_height_three',8,2);
            $table->double('sub_total_area',20,2);
            $table->double('sub_total_deduction',20,2);
            $table->double('sub_total_cement',20,2);
            $table->double('sub_total_sands',20,2);
            $table->double('sub_total_bricks',20,2);
            $table->double('sub_total_morters',20,2);
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
        Schema::dropIfExists('bricks_configure_products');
    }
};
