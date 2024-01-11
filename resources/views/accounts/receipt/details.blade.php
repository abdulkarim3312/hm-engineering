@extends('layouts.app')
@section('title')
{{ $receiptPayment->payment_type == 1 ? 'Cheque' : 'Cash' }} Receipt No:{{ $receiptPayment->receipt_payment_no }}
@endsection
@section('style')
    <style>

        table,.table,table td,

        .table-bordered{
            border: 1px solid #000000;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000000 !important;
        }
        .table.body-table td,.table.body-table th {
            padding: 2px 7px;
        }
        ul.document-list {margin: 0;padding: 0;list-style: auto;}

        ul.document-list li {display: inline-block;}
        .row {
            margin-right: 0;
            margin-left: 0;
        }
    </style>
@endsection
@section('content')
   <div class="row">
       <div class="col-md-12">
           <div class="box">
               <div class="box-header">
                   <a target="_blank" class="btn btn-default btn-lg" href="{{ route('receipt_print',['receiptPayment'=>$receiptPayment->id]) }}"><i class="fa fa-print"></i></a>
               </div>
               <div class="box-body">
                   <div class="row">
                       <div class="col-12">
                           <h1 class="text-center m-0" style="font-size: 35px !important;font-weight: bold">
                               <img height="80px" src="{{ asset('img/logo.png') }}" alt="">
                               {{ config('app.name') }}
                           </h1>
                           <h3 class="text-center m-0" style="font-size: 22px !important;">{{ $receiptPayment->payment_type == 1 ? 'Cheque' : 'Cash' }} Receipt</h3>
                           <h3 class="text-center m-0 fs-style" style="font-size: 22px !important;">FY : {{ $receiptPayment->financial_year }}</h3>
                       </div>
                   </div>
                   <div class="row" style="margin-top: 10px;">
                       <div class="col-xs-4 col-xs-offset-8">
                           <h4 style="margin: 0;font-size: 20px!important;">Receipt No: {{ $receiptPayment->receipt_payment_no }}</h4>
                           <h4 style="margin: 0;font-size: 20px!important;">Date: {{ $receiptPayment->date->format('d-m-Y') }}</h4>
                       </div>
                   </div>
                   <div class="row" style="margin-top: 10px;">
                       <div class="col-12">
                           <table class="table table-bordered">
                               <tr>
                                   <th width="24%">Depositor's Name & Designation</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="">{{ $receiptPayment->client->name ?? '' }} {{ $receiptPayment->client->designation ?? '' }}</td>
                                   <td><b>ID:</b> {{ $receiptPayment->customer_id }}</td>
                               </tr>
                               <tr>
                                   <th width="24%">Address</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="">{{ $receiptPayment->client->address ?? '' }}</td>
                                   <td><b>Project Name:</b> {{ $receiptPayment->project->name ??'' }}</td>
                               </tr>
                               @if($receiptPayment->payment_type == 1)
                               <tr>
                                   <th width="24%">Issuing Bank</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="" colspan="2">{{ $receiptPayment->issuing_bank_name.',' ?? '' }} {{ $receiptPayment->issuing_branch_name  }}</td>
                               </tr>
                               <tr>
                                   <th width="24%">Cheque No.</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="" >{{ $receiptPayment->cheque_no }}</td>
                                   <td><b>Cheque Date:</b> {{ $receiptPayment->cheque_date->format('d-m-Y') }}</td>
                               </tr>
                               @endif
                               <tr>
                                   <th width="24%">Mobile No</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="">{{ $receiptPayment->mobile_no }}</td>
                                   <td width=""><b>Email:</b> {{ $receiptPayment->email }}</td>
                               </tr>
                           </table>
                       </div>
                   </div>
                   <div class="row">
                       <div class="col-12">
                           <span style="font-size: 22px !important;"><b>Received Details:</b></span>
                           <table class="table body-table table-bordered">
                               <tr>
                                   <th  class="text-center" width="50%">Brief Description</th>
                                   <th class="text-center">Account Code</th>
                                   <th class="text-center"></th>
                                   <th class="text-center">Amount(TK)</th>
                               </tr>

                               <tr>
                                   <td style="border-bottom: 1px solid transparent !important;"><b>Received:</b></td>
                                   <td class="text-center" style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;" class="text-right"></td>
                               </tr>
                               @foreach($receiptPayment->receiptPaymentDetails as $key => $receiptPaymentDetail)
                                    {{-- {{ dd($receiptPayment->receiptPaymentDetails) }} --}}
                                   <tr>
                                       <td style="border-bottom: 1px solid transparent !important;">
                                           {{ $receiptPaymentDetail->accountHead->name }}
                                           @if($receiptPaymentDetail->narration)
                                               ({{ $receiptPaymentDetail->narration }})
                                           @endif
                                       </td>
                                       <td style="border-bottom: 1px solid transparent !important;" class="text-center">{{ $receiptPaymentDetail->accountHead->account_code }}</td>
                                       <td style="{{ count($receiptPayment->receiptPaymentDetails) == $key + 1 ? 'border-bottom: 1px solid #000 !important' : 'border-bottom: 1px solid transparent !important'  }};"></td>
                                       <td style="{{ count($receiptPayment->receiptPaymentDetails) == $key + 1 ? 'border-bottom: 1px solid #000 !important' : 'border-bottom: 1px solid transparent !important'  }};" class="text-right">{{ number_format($receiptPaymentDetail->amount,2) }}</td>
                                   </tr>
                               @endforeach

                               <tr>
                                   <td style="border-bottom: 1px solid #000 !important;"></td>
                                   <td style="border-bottom: 1px solid #000 !important;" class="text-center"></td>
                                   <td class="text-center" style="border-bottom: 1.5px solid #000 !important;">Cr</td>
                                   <th style="border-bottom: 1.5px solid #000 !important;" class="text-right">{{ number_format($receiptPayment->sub_total,2) }}</th>
                               </tr>

                               <tr>
                                   <th class="text-left">Total(in word) = {{ $receiptPayment->amount_in_word }} Only.</th>
                                   <th class="text-center"></th>
                                   <th class="text-center">Dr.</th>
                                   <th class="text-right">{{ number_format($receiptPayment->net_amount,2) }}</th>
                               </tr>
                           </table>
                           <br>
                           <div class="row">
                               <div class="col-md-6">
                                   <p><b>Note:</b> {{ $receiptPayment->notes }}</p>
                               </div>
                               @if(count($receiptPayment->files) > 0)
                                   <div class="col-md-6">
                                       <b>Supporting Documents:</b>
                                       <ul class="document-list">
                                           @foreach($receiptPayment->files as $file)
                                               <li>
                                                   <a target="_blank" class="btn btn-success btn-sm" href="{{ asset($file->file) }}"> Download <i class="fa fa-file-download"></i> </a>
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
       </div>
   </div>

@endsection



