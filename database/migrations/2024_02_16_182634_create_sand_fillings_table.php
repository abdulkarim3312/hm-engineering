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
        Schema::create('sand_fillings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->double('length',20,2);
            $table->double('width',20,2);
            $table->float('height',8,2);
            $table->string('quantity');
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
        Schema::dropIfExists('sand_fillings');
    }
};
