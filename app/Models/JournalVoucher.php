<?php

namespace App\Models;

use App\Model\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SakibRahaman\DecimalToWords\DecimalToWords;

class JournalVoucher extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
    public function amount_in_word()
    {
        return DecimalToWords::convert(JournalVoucher::find($this->id)->debit_total,'Taka',
            'Poisa');
    }

    public function journalVoucherDebitDetails()
    {
        return $this->hasMany(JournalVoucherDetail::class)
                 ->where('type',1);
    }
    public function journalVoucherCreditDetails()
    {
        return $this->hasMany(JournalVoucherDetail::class)
                 ->where('type',2);
    }

    public function files()
    {
        return $this->hasMany(ReceiptPaymentFile::class)
                ->where('journal_voucher_id','!=','');
    }
}
