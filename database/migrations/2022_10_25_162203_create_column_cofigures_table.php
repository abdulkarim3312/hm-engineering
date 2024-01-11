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
        Schema::create('column_cofigures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->string('column_configure_no');
            $table->float('ring_quantity',8,2);
            $table->float('tie_interval',8,2);
            $table->float('column_quantity',8,2);
            $table->float('first_ratio',8,2);
            $table->float('second_ratio',8,2);
            $table->float('third_ratio',8,2);
            $table->float('column_length',8,2);
            $table->float('tie_length_volume',8,2);
            $table->float('tie_width_volume',8,2);
            $table->float('total_volume',8,2);
            $table->float('dry_volume',8,2);
            $table->float('total_dry_volume',8,2);
            $table->date('date');
            $table->string('note')->nullable();
            $table->double('total_ton_straight',20,2);
            $table->double('total_kg_straight',20,2);
            $table->double('total_ton_tie',20,2);
            $table->double('total_kg_tie',20,2);
            $table->double('total_kg',20,2);
            $table->double('total_ton',20,2);
            $table->double('total_cement',20,2);
            $table->double('total_sands',20,2);
            $table->double('total_aggregate',20,2);
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
        Schema::dropIfExists('column_cofigures');
    }
};
