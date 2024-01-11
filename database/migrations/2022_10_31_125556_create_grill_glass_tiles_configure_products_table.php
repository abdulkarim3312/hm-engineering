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
        Schema::create('grill_glass_tiles_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('grill_glass_tiles_configure_id');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('estimate_floor_id');
            $table->bigInteger('estimate_floor_unit_id');
            $table->bigInteger('unit_section_id');
            $table->float('length',8,2);
            $table->float('height',8,2);
            $table->float('quantity',8,2);
            $table->double('sub_total_area',20,2);
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
        Schema::dropIfExists('grill_glass_tiles_configure_products');
    }
};
