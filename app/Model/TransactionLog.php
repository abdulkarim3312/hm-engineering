<?php

namespace App\Model;

use App\Models\AccountHead;
use App\Models\BalanceTransfer;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];
    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function account() {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
    public function salepayment(){
        return $this->belongsTo(SalePayment::class,'sale_payment_id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function accountSubHead(){
        return $this->belongsTo(AccountHeadSubType::class,'account_head_sub_type_id','id');
    }
    public function accountHead(){
        return $this->belongsTo(AccountHead::class);
    }
    public function accountHeadExpenditure(){
        return $this->belongsTo(ReceiptPaymentDetail::class,'receipt_payment_detail_id');
    }

    public function purchasePayment(){
        return $this->belongsTo(PurchasePayment::class,'purchase_payment_id','id');
    }
    public function balanceTransfer()
    {
        return $this->belongsTo(BalanceTransfer::class);
    }
    public function transaction()
    {
        return $this->belongsTo(ReceiptPayment::class, 'receipt_payment_id');
    }
}
