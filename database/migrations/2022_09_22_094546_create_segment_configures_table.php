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
        Schema::create('segment_configures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('costing_segment_id');
            $table->bigInteger('segment_unit_type');
            $table->string('segment_height');
            $table->string('segment_width');
            $table->string('segment_length');
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
        Schema::dropIfExists('segment_configures');
    }
};
