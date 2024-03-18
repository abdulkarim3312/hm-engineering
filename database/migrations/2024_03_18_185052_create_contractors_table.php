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
        Schema::create('contractors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contractor_id');
            $table->string('name');
            $table->string('project_id')->nullable();
            $table->string('trade')->comment([
                'civil_contractor'=>1,'paint_contractor'=>2,'sanitary_contractor'=>3,'tiles_contractor'=>4,
                'ms_contractor'=>5,'wood_contractor'=>6,'electric_contractor'=>7,'thai_contractor'=>8,'other_contractor'=>9
            ]);
            $table->string('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('nid')->nullable();
            $table->string('image')->nullable();
            $table->float('total',20,2)->default(0);
            $table->float('ra_bill',20,2)->default(0);
            $table->float('final_bill',20,2)->default(0);
            $table->float('paid',20,2)->default(0);
            $table->float('due',20,2)->default(0);
            $table->float('discount',20,2)->default(0);
            $table->string('remarks')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('contractors');
    }
};
