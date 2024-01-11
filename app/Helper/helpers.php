<?php

use App\Model\TransactionLog;
use App\Models\AccountHead;

if (! function_exists('financialYear')) {
    function financialYear($year)
    {
        $start = $year;
        $end = ($year + 1);
        return $financialYear = $start.'-'.$end;

    }
}
if (! function_exists('generateVoucherReceiptNo')) {
    function generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType)
    {

        // dd($financialYear);
        $financialYear = financialYear($financialYear);
        $query = TransactionLog::whereNull('jv_type')
            ->where('financial_year',$financialYear);
        $vR = '';
        if ($transactionType == 2){
            $accountHead = AccountHead::where('id',$accountHeadId)->first();

            if ($payType == 1){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);

                $vR = 'BV-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 2){
                $query->where('transaction_type',$transactionType);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'CV-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 3){
                $query->where('transaction_type',$transactionType);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'BGMV-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 4){
                $query->where('transaction_type',$transactionType);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'LCV-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 5){
                $query->where('transaction_type',$transactionType);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'CHAV-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 6){
                $query->where('transaction_type',$transactionType);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'TTV-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 7){
                $query->where('transaction_type',$transactionType);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'BFTNV-'.'1-'.$accountHead->account_code;
            }
        }else{
            $accountHead = AccountHead::where('id',$accountHeadId)->first();
            if ($payType == 1){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'BMR-'.'1-'.$accountHead->account_code;

            }
            if ($payType == 2){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'CMR-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 3){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'BGMMR-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 4){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'LCMR-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 5){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'CHAMR-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 6){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'TTMR-'.'1-'.$accountHead->account_code;
            }
            if ($payType == 7){
                $query->whereIn('transaction_type',[$transactionType]);
                $query->where('account_head_id',$accountHeadId);
                $vR = 'BFTNMR-'.'1-'.$accountHead->account_code;
            }
        }

            $maxVoucherReceiptNo = $query->max('receipt_payment_sl');
            $receiptPayment = $query->orderBy('receipt_payment_sl','desc')
                ->first();

        if ($receiptPayment)
            $receiptPaymentNo = $maxVoucherReceiptNo;



        if ($transactionType == 2){
            $accountHead = AccountHead::where('id',$accountHeadId)->first();
            if ($receiptPayment){
                if ($payType == 2){
                    $vR = 'CV-'.($receiptPaymentNo + 1).'-'.$accountHead->account_code;
                }
                if ($payType == 1){
                    $vR = 'BV-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 3){
                    $vR = 'BGMV-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 4){
                    $vR = 'LCV-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 5){
                    $vR = 'CHAV-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 6){
                    $vR = 'TTV-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 7){
                    $vR = 'BFTNV-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
            }
        }else{
            $accountHead = AccountHead::where('id',$accountHeadId)->first();

            if ($receiptPayment){
                if ($payType == 2){
                    $vR = 'CMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 1){

                    $vR = 'BMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 3){

                    $vR = 'BGMMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 4){

                    $vR = 'LCMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 5){

                    $vR = 'CHAMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 6){

                    $vR = 'TTMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
                if ($payType == 7){

                    $vR = 'BFTNMR-'.($receiptPaymentNo+1).'-'.$accountHead->account_code;
                }
            }
        }
        return $vR;

    }
    if (! function_exists('financialYearToYear')) {
        function financialYearToYear($financialYear)
        {

            $financialYear = explode("-",$financialYear);

            if ($financialYear)
                return $financialYear[0];

            return null;

        }
    }
    if (! function_exists('voucherExplode')) {
        function voucherExplode($receipt_payment_no)
        {

            $receipt_payment_no = explode("-",$receipt_payment_no);

            if ($receipt_payment_no)
                return $receipt_payment_no;

            return null;

        }
    }

    if (! function_exists('previousAccountTrailDebitCredit')) {
        function previousAccountTrailDebitCredit($start_date,$end_date,$account_head_code)
        {

            $start = date('Y-m-d', strtotime($start_date));
            $end = date('Y-m-d', strtotime($end_date));


            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($start)));

            $debitTotal = 0;
            $creditTotal = 0;

            $accountOpeningHead = AccountHead::where('id',$account_head_code)->first();

            if ($accountOpeningHead){
                if ($accountOpeningHead->account_head_type_id == 1){
                    $debitTotal += $accountOpeningHead->opening_balance;
                }elseif ($accountOpeningHead->account_head_type_id == 2 || $accountOpeningHead->type_id == 3){
                    $creditTotal += $accountOpeningHead->opening_balance;
                }
            }

            $debitTotal += TransactionLog::where('account_head_id',$account_head_code)
                            ->whereDate('date', '<=', $previousDay)
                            ->where('transaction_type',1)
                            ->sum('amount');
            $creditTotal += TransactionLog::where('account_head_id',$account_head_code)
                            ->whereDate('date', '<=', $previousDay)
                            ->where('transaction_type',2)
                            ->sum('amount');

            return [
                'debitOpeningTotal'=>$debitTotal,
                'creditOpeningTotal'=>$creditTotal,
            ];
        }
    }
    if (! function_exists('accountTrailDebitCredit')) {
        function accountTrailDebitCredit($start_date,$end_date,$account_head_code)
        {

            $start = date('Y-m-d', strtotime($start_date));
            $end = date('Y-m-d', strtotime($end_date));



            $debitTotal = 0;
            $creditTotal = 0;

            $debitTotal += TransactionLog::where('account_head_id',$account_head_code)
                ->where('transaction_type',1)
                ->whereBetween('date', [$start, $end])
                ->sum('amount');
            $creditTotal += TransactionLog::where('account_head_id',$account_head_code)
                ->where('transaction_type',2)
                ->whereBetween('date', [$start, $end])
                ->sum('amount');

            return [
                'debitTotal'=>$debitTotal,
                'creditTotal'=>$creditTotal,
            ];
        }
    }

    if (! function_exists('previousLedger')) {
        function previousLedger($start_date,$end_date,$account_head_id)
        {

            $start = date('Y-m-d', strtotime($start_date));
            $end = date('Y-m-d', strtotime($end_date));

            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($start)));

            $debitTotal = 0;
            $creditTotal = 0;
            $accountOpeningHead = AccountHead::where('id',$account_head_id)->first();

            if ($accountOpeningHead){
                if ($accountOpeningHead->account_head_type_id == 1){
                    $debitTotal = $accountOpeningHead->opening_balance;
                }elseif ($accountOpeningHead->account_head_type_id == 2 || $accountOpeningHead->type_id == 3){
                    $creditTotal = $accountOpeningHead->opening_balance;
                }
            }

            $debitTotal += TransactionLog::where('account_head_id',$account_head_id)
                ->where('transaction_type',1)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $creditTotal += TransactionLog::where('account_head_id',$account_head_id)
                ->where('transaction_type',2)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            return [
                'debitOpening'=>$debitTotal,
                'creditOpening'=>$creditTotal,
                'accountOpeningHead'=>$accountOpeningHead,
            ];
        }
    }
    if (! function_exists('ledger')) {
        function ledger($start_date,$end_date,$account_head_id)
        {
            $start = date('Y-m-d', strtotime($start_date));
            $end = date('Y-m-d', strtotime($end_date));

            return TransactionLog::where('account_head_id',$account_head_id)
                ->whereBetween('date', [$start, $end])
                ->orderBy('date')
                ->orderBy('receipt_payment_no')
                ->get();

        }
    }
    if (! function_exists('getOrderClientPaymentTotal')) {
        function getOrderClientPaymentTotal($year,$clientId,$saleOrderId)
        {

            return \App\Models\ReceiptPayment::where('client_id',$clientId)
                ->where('sales_order_id',$saleOrderId)
                ->whereYear('date',$year)
                ->sum('net_amount');

        }
    }
    if (! function_exists('getOrderClientPaymentTotalSum')) {
        function getOrderClientPaymentTotalSum($year,$saleOrder)
        {

            return \App\Models\ReceiptPayment::where('client_id',$saleOrder->pluck('client_id')->toArray())
                ->where('sales_order_id',$saleOrder->pluck('id')->toArray())
                ->whereYear('date',$year)
                ->sum('net_amount');

        }
    }
    if (! function_exists('getOrderClientPaymentTotalCalSum')) {
        function getOrderClientPaymentTotalCalSum($startYear,$endYear,$saleOrder)
        {

            return \App\Models\ReceiptPayment::where('client_id',$saleOrder->pluck('client_id')->toArray())
                ->where('sales_order_id',$saleOrder->pluck('id')->toArray())
                ->whereYear('date','>=',$startYear)
                ->whereYear('date','<=',$endYear)
                ->sum('net_amount');

        }
    }
//   }
}