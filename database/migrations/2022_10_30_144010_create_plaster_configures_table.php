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
        Schema::create('plaster_configures', function (Blueprint $table) {
            $table->id();
            $table->string('bricks_configure_no')->nullable();
            $table->float('first_ratio',8,2);
            $table->float('second_ratio',8,2);
            $table->float('dry_morter',8,2);
            $table->double('total_plaster_area',20,2);
            $table->double('total_cement',20,2);
            $table->double('total_cement_bag',20,2);
            $table->double('total_sands',20,2);
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
        Schema::dropIfExists('plaster_configures');
    }
};
