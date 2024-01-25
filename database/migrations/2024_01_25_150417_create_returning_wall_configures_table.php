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
        Schema::create('returning_wall_configures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('costing_segment_id');
            $table->string('common_configure_no');
            $table->integer('costing_segment_quantity');
            $table->date('date');
            $table->string('note');
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
        Schema::dropIfExists('returning_wall_configures');
    }
};
