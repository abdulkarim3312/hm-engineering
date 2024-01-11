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
        Schema::create('mobilization_works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('mobilization_project_id');
            $table->date('date');
            $table->double('total',20,2);
            $table->string('costing_no');
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
        Schema::dropIfExists('mobilization_works');
    }
};
