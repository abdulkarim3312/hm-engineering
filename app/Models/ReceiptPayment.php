<?php

namespace App\Models;

use App\Floor;
use App\Model\Client;
use App\Model\Flat;
use App\Model\Project;
use App\Model\SalesOrder;
use App\Model\TransactionLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SakibRahaman\DecimalToWords\DecimalToWords;

class ReceiptPayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['date','cheque_date'];

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
    public function amount_in_word()
    {
        return DecimalToWords::convert(ReceiptPayment::find($this->id)->net_amount,'Taka','Poisa');
    }

    public function files()
    {
        return $this->hasMany(ReceiptPaymentFile::class);
    }

    public function getVatAccountHead()
    {
        return AccountHead::where('id',4)->first();
    }
    public function getAitAccountHead()
    {
        return AccountHead::where('id',4)->first();
    }
    public function vatAccountHead($id)
    {
        return TransactionLog::where('receipt_payment_id',$id)
            ->where('account_head_id',4)->get();

    }
    public function aitAccountHead($id)
    {
        return TransactionLog::where('receipt_payment_id',$id)
            ->where('account_head_id',4)->get();

    }


    public function receiptPaymentDetail()
    {
        return $this->hasOne(ReceiptPaymentDetail::class)
            ->where('other_head',0);
    }

    public function receiptPaymentDetails()
    {
        return $this->hasMany(ReceiptPaymentDetail::class);
    }
    public function receiptPaymentVatDetails()
    {
        return $this->hasMany(ReceiptPaymentDetail::class)->whereNotNull('vat_account_head_id');
    }
    public function receiptPaymentAitDetails()
    {
        return $this->hasMany(ReceiptPaymentDetail::class)->whereNotNull('ait_account_head_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function flat()
    {
        return $this->belongsTo(flat::class);
    }
    public function saleOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
