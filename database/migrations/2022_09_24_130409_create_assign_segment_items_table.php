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
        Schema::create('assign_segment_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assign_segment_id');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('segment_configure_id');
            $table->float('segment_height',)->default(1);
            $table->float('segment_width')->default(1);
            $table->float('segment_length')->default(1);
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
        Schema::dropIfExists('assign_segment_items');
    }
};
