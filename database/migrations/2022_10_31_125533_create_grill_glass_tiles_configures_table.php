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
        Schema::create('grill_glass_tiles_configures', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('configure_type');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('estimate_floor_id');
            $table->bigInteger('estimate_floor_unit_id');
            $table->string('grill_glass_tiles_configure_no')->nullable();
            $table->float('floor_number',8,2);
            $table->double('total_area_with_floor',20,2);
            $table->double('total_area_without_floor',20,2);
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
        Schema::dropIfExists('grill_glass_tiles_configures');
    }
};
