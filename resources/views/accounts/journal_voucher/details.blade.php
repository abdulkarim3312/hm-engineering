@extends('layouts.app')
@section('title')
    Journal Voucher No:{{ $journalVoucher->jv_no }}
@endsection
@section('style')
    <style>
        .row{
            margin: 0;
        }
        .table-bordered td{
            border: 1px solid #000;
        }
        .table-bordered th{
            border: 1px solid #000;
        }
        .table thead th {
            vertical-align: bottom;
            border: 2px solid #000 !important;
        }
        .table body td {
            vertical-align: bottom;
            border-bottom: 1px solid #000 !important;
        }
        .table tfoot th {
            vertical-align: bottom;
            border: 1px solid #000 !important;
        }
        tbody {
            border: 1px solid #000 !important;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #000000 !important;
        }
    </style>
@endsection
@section('content')
    <div class="box">
        <div class="box-header">
            <a target="_blank" class="btn btn-default btn-lg" href="{{ route('journal_voucher_print',['journalVoucher'=>$journalVoucher->id]) }}"><i class="fa fa-print"></i></a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center m-0" style="font-size: 50px !important;font-weight: bold">
                        <img height="80px" src="{{ asset('img/logo.png') }}" alt="">
                        {{ config('app.name') }}
                    </h1>
                    <h3 class="text-center m-0" style="font-size: 30px !important;">Journal Voucher</h3>
                    <h3 class="text-center m-0 fs-style" style="font-size: 30px !important;">FY : {{ $journalVoucher->financial_year }}</h3>

                </div>
                <!-- /.col -->
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-4 offset-8">
                    <h4 style="margin: 0;font-size: 20px!important;">JV No: {{ $journalVoucher->jv_no }}</h4>
                    <h4 style="margin: 0;font-size: 20px!important;">Date: {{ $journalVoucher->date->format('d-m-Y') }}</h4>
                </div>
            </div>
            <div class="row"  style="margin-top: 15px">
                <div class="col-12">
                    <table class="table table-bordered">
                        <tr>
                            <th width="24%">Party</th>
                            <th width="2%" class="text-center">:</th>
                            <td width="">{{ $journalVoucher->client->name ?? '' }}, {{ $journalVoucher->client->designation ?? '' }}</td>
                            <td><b>ID:</b> {{ $journalVoucher->customer_id }}</td>
                        </tr>
                        <tr>
                            <th width="24%">Address</th>
                            <th width="2%" class="text-center">:</th>
                            <td width="" colspan="2">{{ $journalVoucher->client->address ?? '' }}</td>
                        </tr>
                        <tr>
                            <th width="24%">Payee e-tin</th>
                            <th width="2%" class="text-center">:</th>
                            <td width="">{{ $journalVoucher->e_tin }}</td>
                            <td width=""><b>Category:</b> {{ $journalVoucher->nature_of_organization }}</td>
                        </tr>
                        <tr>
                            <th width="24%">Mobile No</th>
                            <th width="2%" class="text-center">:</th>
                            <td width="">{{ $journalVoucher->mobile_no }}</td>
                            <td width=""><b>Email:</b> {{ $journalVoucher->email }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row" style="margin-top: 50px;">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th  class="text-center" width="40%">Brief Description</th>
                                <th class="text-center">Account</th>
                                <th class="text-center">Debit</th>
                                <th class="text-center">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($journalVoucher->journalVoucherDebitDetails as $key => $journalVoucherDebitDetail)
                            <tr>
                                <td>{{ $journalVoucherDebitDetail->accountHead->name }}</td>
                                <td class="text-center">{{ $journalVoucherDebitDetail->accountHead->account_code }}</td>
                                <td class="text-right">{{ number_format($journalVoucherDebitDetail->amount,2) }}</td>
                                <td class="text-right"></td>
                            </tr>
                        @endforeach
                        @foreach($journalVoucher->journalVoucherCreditDetails as $key => $journalVoucherCreditDetail)
                            <tr>
                                <td ><b style="margin-left: 20px !important;">To, </b>{{ $journalVoucherCreditDetail->accountHead->name }}</td>

                                <td>{{ $journalVoucherCreditDetail->accountHead->account_code }}</td>
                                <td class="text-right"></td>
                                <td  class="text-right">{{ number_format($journalVoucherCreditDetail->amount,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-left" colspan="2">Total(in word) = {{ $journalVoucher->amount_in_word }} Only.</th>
                                    <th class="text-right">{{ number_format($journalVoucher->credit_total,2) }}</th>
                                    <th class="text-right">{{ number_format($journalVoucher->debit_total,2) }}</th>
                                </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>Note:</b> {{ $journalVoucher->notes }}</p>
                        </div>
                        @if(count($journalVoucher->files) > 0)
                            <div class="col-md-6">
                                <b>Supporting Documents:</b>
                                <ul class="document-list">
                                    @foreach($journalVoucher->files as $file)
                                        <li>
                                            <a target="_blank" class="btn btn-success btn-sm" href="{{ asset($file->file) }}">Download <i class="fa fa-file-download"></i></a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row signature-area" style="margin-top: 30px">
                <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Prepared By</span></div>
                <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Checked By</span></div>
                <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Approved By</span></div>
                <div class="col-md-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 20px;font-weight: bold">Received By</span></div>
            </div>
        </div>
    </div>

@endsection
