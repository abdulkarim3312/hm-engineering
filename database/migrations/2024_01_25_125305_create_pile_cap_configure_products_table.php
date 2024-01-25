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
        Schema::create('pile_cap_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('common_configure_id');
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('costing_segment_id');
            $table->integer('bar_type');
            $table->tinyInteger('dia');
            $table->string('bar_value');
            $table->string('kg_by_rft');
            $table->string('kg_by_ton');
            $table->tinyInteger('length_type');
            $table->float('length',8,2);
            $table->float('spacing',8,2);
            $table->float('length_two',8,2);
            $table->float('layer',8,2);
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
        Schema::dropIfExists('pile_cap_configure_products');
    }
};
