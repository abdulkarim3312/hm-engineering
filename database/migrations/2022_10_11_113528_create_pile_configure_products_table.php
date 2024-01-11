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
        Schema::create('pile_configure_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pile_configure_id');
            $table->bigInteger('bar_type');
            $table->tinyInteger('dia');
            $table->string('bar_value');
            $table->string('kg_by_rft');
            $table->string('kg_by_ton');
            $table->string('rft_by_ton');
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
        Schema::dropIfExists('pile_configure_products');
    }
};
