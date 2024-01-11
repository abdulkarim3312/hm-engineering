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
        Schema::create('bricks_configures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('estimate_floor_id');
            $table->bigInteger('estimate_floor_unit_id');
            $table->bigInteger('unit_section_id');
            $table->string('bricks_configure_no')->nullable();
            $table->float('brick_size',8,2);
            $table->float('morter',8,2);
            $table->float('dry_morter',8,2);
            $table->float('first_ratio',8,2);
            $table->float('second_ratio',8,2);
            $table->double('total_bricks',20,2);
            $table->double('total_morters',20,2);
            $table->double('total_cement',20,2);
            $table->double('total_cement_bag',20,2);
            $table->double('total_sands',20,2);
            $table->double('total_aggregate',20,2);
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
        Schema::dropIfExists('bricks_configures');
    }
};
