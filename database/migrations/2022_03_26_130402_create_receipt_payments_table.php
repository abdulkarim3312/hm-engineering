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
        Schema::create('receipt_payments', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year');
            $table->date('date');
            $table->text('receipt_payment_no');
            $table->tinyInteger('transaction_type')->comment('1=receipt,2=payment');
            $table->unsignedInteger('account_head_id');
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();

            $table->string('issuing_bank_name')->nullable();
            $table->string('issuing_branch_name')->nullable();
            $table->string('issuing_account_name')->nullable();
            $table->string('issuing_account_no')->nullable();

            $table->string('designation')->nullable();
            $table->string('address')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('e_tin')->nullable();
            $table->string('nature_of_organization')->nullable();

            $table->float('sub_total',100)->default(0);
            $table->float('vat_total',100)->default(0);
            $table->float('ait_total',100)->default(0);
            $table->float('net_amount',100)->default(0);

            $table->text('notes')->nullable();
            $table->integer('is_delete')->default(0);
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
        Schema::dropIfExists('receipt_payments');
    }
};
