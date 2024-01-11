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
        Schema::create('beam_confogure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('beam_configure_id');
            $table->bigInteger('estimate_project_id');
            $table->integer('bar_type');
            $table->integer('dia');
            $table->integer('dia_square');
            $table->string('value_of_bar');
            $table->string('kg_by_rft');
            $table->string('kg_by_ton');
            $table->float('number_of_bar',8,2);
            $table->float('lapping_length',8,2);
            $table->float('lapping_nos',8,2);
            $table->double('sub_total_kg',20,2);
            $table->double('sub_total_ton',20,2);
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
        Schema::dropIfExists('beam_confogure_products');
    }
};
