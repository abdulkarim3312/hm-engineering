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
        Schema::create('receipt_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('receipt_payment_id');
            $table->unsignedInteger('account_head_id')->nullable();

            $table->float('amount',100)->default(0);

            $table->unsignedInteger('vat_account_head_id')->nullable();
            $table->float('vat_base_amount',50)->default(0);
            $table->float('vat_rate')->default(0);
            $table->float('vat_amount')->default(0);

            $table->unsignedInteger('ait_account_head_id')->nullable();
            $table->float('ait_base_amount',50)->default(0);
            $table->float('ait_rate')->default(0);
            $table->float('ait_amount',100)->default(0);

            $table->float('net_amount',100)->default(0);
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
        Schema::dropIfExists('receipt_payment_details');
    }
};
