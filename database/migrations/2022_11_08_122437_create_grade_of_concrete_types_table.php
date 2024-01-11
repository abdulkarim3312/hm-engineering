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
        Schema::create('grade_of_concrete_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('first_ratio',8,2);
            $table->float('second_ratio',8,2);
            $table->float('third_ratio',8,2);
            $table->string('description')->nullable();
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
        Schema::dropIfExists('grade_of_concretes');
    }
};
