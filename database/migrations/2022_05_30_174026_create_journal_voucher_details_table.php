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
        Schema::create('journal_voucher_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('journal_voucher_id');
            $table->tinyInteger('type')->comment('1=debit,2=credit');
            $table->unsignedInteger('account_head_id')->nullable();
            $table->float('amount',100);
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
        Schema::dropIfExists('journal_voucher_details');
    }
};
