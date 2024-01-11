<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptPaymentDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getParentCode($childId)
    {
        $fourDigitCode = substr($childId, 0, 5);
        $specialArray = ['33001','33010','33004','33051','33052'];

        if (in_array($fourDigitCode,$specialArray)){
            return AccountHead::where('existing_account_code',$fourDigitCode)->first()->name ?? '';
        }else{
            return false;
        }


    }

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vatAccountHead()
    {
        return $this->belongsTo(AccountHead::class,'vat_account_head_id','id');
    }
    public function aitAccountHead()
    {
        return $this->belongsTo(AccountHead::class,'ait_account_head_id','id');
    }

    public function vatAccountLog($id)
    {
        return TransactionLog::where('receipt_payment_detail_id',$id)
            ->where('account_head_id',2740)->first();

    }
    public function aitAccountLog($id)
    {
        return TransactionLog::where('receipt_payment_detail_id',$id)
            ->where('account_head_id',2741)->first();

    }

}
