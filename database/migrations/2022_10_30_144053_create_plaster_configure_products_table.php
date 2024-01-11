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
        Schema::create('plaster_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bricks_configure_id');
            $table->bigInteger('bricks_configure_product_id');
            $table->float('plaster_area',8,2);
            $table->float('plaster_side',8,2);
            $table->float('plaster_thickness',8,2);
            $table->float('sub_total_area',8,2);
            $table->float('sub_total_dry_area',8,2);
            $table->float('sub_total_cement',8,2);
            $table->float('sub_total_sands',8,2);
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
        Schema::dropIfExists('plaster_configure_products');
    }
};
