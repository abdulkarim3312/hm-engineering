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
        Schema::create('column_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('column_configure_id');
            $table->bigInteger('estimate_project_id');
            $table->integer('bar_type');
            $table->integer('dia');
            $table->integer('dia_square');
            $table->string('bar_value');
            $table->string('kg_by_rft');
            $table->string('kg_by_ton');
            $table->float('number_of_bar',8,2);
            $table->float('lapping_length',8,2);
            $table->float('lapping_nos',8,2);
            $table->double('sub_total_kg_straight',20,2);
            $table->double('sub_total_ton_straight',20,2);
            $table->integer('tie_bar_type');
            $table->integer('tie_dia');
            $table->integer('tie_dia_square');
            $table->string('tie_value_of_bar');
            $table->string('tie_kg_by_rft');
            $table->string('tie_kg_by_ton');
            $table->float('tie_length',8,2);
            $table->float('tie_width',8,2);
            $table->float('tie_clear_cover',8,2);
            $table->double('sub_total_kg_tie',20,2);
            $table->double('sub_total_ton_tie',20,2);
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
        Schema::dropIfExists('column_configure_products');
    }
};
