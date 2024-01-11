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
        Schema::create('journal_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year');
            $table->date('date');
            $table->text('jv_no');

            $table->unsignedInteger('client_id')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('e_tin')->nullable();
            $table->string('nature_of_organization')->nullable();

            $table->float('debit_total',100)->default(0);
            $table->float('credit_total',100)->default(0);
            $table->text('notes')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('journal_vouchers');
    }
};
