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
        Schema::create('grade_of_concretes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('estimate_project_id');
            $table->bigInteger('grade_of_concrete_type_id');
            $table->string('grade_of_concrete_no');
            $table->float('total_volume',8,2);
            $table->date('date');
            $table->double('total_water',20,2);
            $table->double('total_cement',20,2);
            $table->double('total_sands',20,2);
            $table->double('total_aggregate',20,2);
            $table->string('note')->nullable();
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
