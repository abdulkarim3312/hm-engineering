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
        Schema::create('assign_segments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('costing_segment_id');
            $table->float('segment_height',)->default(1);
            $table->float('segment_width')->default(1);
            $table->float('segment_length')->default(1);
            $table->date('date')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('assign_segments');
    }
};
