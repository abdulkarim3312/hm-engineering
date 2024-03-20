@extends('layouts.app')
@section('title')
{{ $receiptPayment->payment_type == 1 ? 'Bank' : 'Cash' }} Voucher No:{{ $receiptPayment->receipt_payment_no }}
@endsection
@section('style')
    <style>

        table,.table,table td,

        .table-bordered{
            border: 1px solid #000000;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000000 !important;
            font-size: 18px !important;
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
                   <a target="_blank" class="btn btn-default btn-lg" href="{{ route('voucher_print',['receiptPayment'=>$receiptPayment->id]) }}"><i class="fa fa-print"></i></a>
               </div>
               <div class="box-body">
                   <div class="row">
                       <div class="col-12">
                           <h1 class="text-center m-0" style="font-size: 28px !important;font-weight: bold">
                               <img height="80px" src="{{ asset('img/logo.png') }}" alt="">
                               {{ config('app.name') }}
                           </h1>
                           <h3 class="text-center m-0" style="font-size: 22px !important;">{{ $receiptPayment->payment_type == 1 ? 'Bank' : 'Cash' }} Voucher</h3>
                           <h3 class="text-center m-0 fs-style" style="font-size: 23px !important;">FY : {{ $receiptPayment->financial_year }}</h3>
                       </div>
                   </div>
                   <div class="row" style="margin-top: 10px;">
                       <div class="col-xs-4 col-xs-offset-8">
                           <h4 style="margin: 0;font-size: 20px!important;">Voucher No: {{ $receiptPayment->receipt_payment_no }}</h4>
                           <h4 style="margin: 0;font-size: 20px!important;">Date: {{ $receiptPayment->date->format('d-m-Y') }}</h4>
                       </div>
                   </div>
                   <div class="row" style="margin-top: 10px;display: none" >
                       <div class="col-12">
                           <table class="table table-bordered">
                               <tr>
                                   @if($receiptPayment->payment_type == 1)
                                   <th width="24%">Bank Name & A/c No.</th>
                                   @else
                                   <th width="24%">Cash In Hand</th>
                                   @endif
                                   <th width="2%" class="text-center">:</th>
                                   <td width="" colspan="2">{{ $receiptPayment->accountHead->name }}</td>
                               </tr>
                               @if($receiptPayment->payment_type == 1)
                               <tr>
                                   <th width="24%">Cheque No.</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="" colspan="2">{{ $receiptPayment->cheque_no }}</td>
                               </tr>
                               @endif
                               <tr>
                                   <th width="24%">Payee Name & Designation</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="">{{ $receiptPayment->client->name ?? $receiptPayment->notes??'' }}, {{ $receiptPayment->client->designation ?? '' }}</td>
                                   <td><b>ID:</b> {{ $receiptPayment->customer_id }}</td>
                               </tr>
                               <tr>
                                   <th width="24%">Address</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="">{{ $receiptPayment->client->address ?? '' }}</td>
                                   <td><b>Project Name:</b> {{ $receiptPayment->project->name??'' }}</td>
                               </tr>
                               <tr>
                                   <th width="24%">Payee e-tin</th>
                                   <th width="2%" class="text-center">:</th>
                                   <td width="">{{ $receiptPayment->e_tin }}</td>
                                   <td width=""><b>Category:</b> {{ $receiptPayment->nature_of_organization }}</td>
                               </tr>
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
                           <span><b>Payment Details:</b></span>
                           <table class="table body-table table-bordered">
                               <tr>
                                   <th  class="text-center" width="43%">Brief Description</th>
                                   @if ($receiptPayment->client_id != null)
                                        <th  class="text-center">Client Name</th>
                                   @endif
                                   @if ($receiptPayment->vendor_id != null)
                                        <th  class="text-center">Vendor Name</th>
                                   @endif
                                   @if ($receiptPayment->contractor_id != null)
                                        <th  class="text-center">Contractor Name</th>
                                   @endif
                                   <th class="text-center">Project</th>
                                   <th class="text-center">Account Code</th>
                                   <th class="text-center"></th>
                                   <th class="text-center">Amount(TK)</th>
                               </tr>

                               <tr>
                                   <td style="border-bottom: 1px solid transparent !important;"><b>Expenses:</b></td>
                                   <td style="border-bottom: 1px solid transparent !important;"></td>
                                   <td class="text-center" style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;" class="text-right"></td>
                               </tr>
                               @foreach($receiptPayment->receiptPaymentDetails as $key => $receiptPaymentDetail)
                                {{-- {{ dd($receiptPaymentDetail) }} --}}
                                   <tr>
                                       <td style="border-bottom: 1px solid transparent !important;"> {{ $receiptPaymentDetail->accountHead->name ?? ''}}
                                        @if($receiptPaymentDetail->narration)
                                            ({{ $receiptPaymentDetail->narration }})
                                         @endif
                                       </td>
                                       @if ($receiptPayment->client_id != null)
                                            <td style="border-bottom: 1px solid transparent !important; text-align: center;">{{ $receiptPayment->client->name??'' }}</td>
                                       @endif
                                       @if ($receiptPayment->vendor_id != null)
                                            <td style="border-bottom: 1px solid transparent !important; text-align: center;">{{ $receiptPayment->vendor->name??'' }}</td>
                                       @endif
                                       @if ($receiptPayment->contractor_id != null)
                                            <td style="border-bottom: 1px solid transparent !important; text-align: center;">{{ $receiptPayment->contractor->name??'' }}</td>
                                       @endif
                                       <td style="border-bottom: 1px solid transparent !important;">{{ $receiptPayment->project->name??'' }}</td>
                                       <td style="border-bottom: 1px solid transparent !important;" class="text-center">{{ $receiptPaymentDetail->accountHead->account_code ?? ''}}</td>
                                       <td style="border-bottom: 1px solid transparent !important;"></td>
                                       <td style="{{ count($receiptPayment->receiptPaymentDetails) == $key + 1 ? 'border-bottom: 1px solid #000 !important' : 'border-bottom: 1px solid transparent !important'  }};" class="text-right">{{ number_format($receiptPaymentDetail->amount,2) }}</td>
                                   </tr>
                               @endforeach

                               <tr>
                                   <td style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;"></td>
                                   <td style="border-bottom: 1px solid transparent !important;" class="text-center"></td>
                                   <td class="text-center" style="border-top: 1.5px solid #000 !important;border-bottom: 1.5px solid #000 !important;">DR.</td>
                                   <th style="border-bottom: 1px solid #000 !important;" class="text-right">{{ number_format($receiptPayment->sub_total,2) }}</th>
                               </tr>

                               @if($receiptPayment->vat_total > 0 || $receiptPayment->ait_total > 0)
                                   <tr>
                                       <td style="border-bottom: 1px solid transparent !important;"><b>Deductions:</b></td>
                                       <td style="border-bottom: 1px solid transparent !important;"></td>
                                       <td style="border-bottom: 1px solid transparent !important;" class="text-center"></td>
                                       <td style="border-bottom: 1px solid transparent !important;"></td>
                                       <td style="border-bottom: 1px solid transparent !important;" class="text-right"></td>
                                   </tr>
                               @endif

                               @if($receiptPayment->vat_total > 0)
                                   @foreach($receiptPayment->receiptPaymentVatDetails as $vatDetails)
                                       <tr>
                                           <td style="border-bottom: 1px solid transparent !important;">{{ $vatDetails->vatAccountHead->name }}(Base Amount:{{ number_format($vatDetails->vat_base_amount,2) }}, Vat Rate: {{ $vatDetails->vat_rate }}%)</td>
                                           <td style="border-bottom: 1px solid transparent !important;"></td>
                                           <td style="border-bottom: 1px solid transparent !important;" class="text-center">{{ $vatDetails->vatAccountHead->account_code ?? '' }}</td>
                                           <td style="border-bottom: 1px solid transparent !important;"></td>
                                           <td style="border-bottom: 1px solid transparent !important;" class="text-right">{{ number_format($vatDetails->vat_amount,2) }}</td>
                                       </tr>
                                   @endforeach
                               @endif
                               @if($receiptPayment->ait_total > 0)
                                   @foreach($receiptPayment->receiptPaymentAitDetails as $aitDetails)
                                       <tr>
                                           <td style="border-bottom: 1px solid transparent !important;">{{ $aitDetails->aitAccountHead->name }}(Base Amount:{{ number_format($aitDetails->ait_base_amount,2) }}, Ait Rate: {{ $aitDetails->ait_rate }}%)</td>
                                           <td style="border-bottom: 1px solid transparent !important;"></td>
                                           <td style="border-bottom: 1px solid transparent !important;" class="text-center">{{ $aitDetails->aitAccountHead->account_code ?? '' }}</td>
                                           <td style="border-bottom: 1px solid transparent !important;"></td>
                                           <td style="border-bottom: 1px solid transparent !important;" class="text-right">{{ number_format($aitDetails->ait_amount ,2)}}</td>
                                       </tr>
                                   @endforeach
                               @endif
                               <tr>
                                   <th class="text-left">Total(in word) = {{ $receiptPayment->amount_in_word }} Only.</th>
                                   <td></td>
                                   <th class="text-center"></th>
                                   <th class="text-center"></th>
                                   <th class="text-center">CR.</th>
                                   <th class="text-right">{{ number_format($receiptPayment->net_amount,2) }}</th>
                               </tr>
                           </table>
                           <br>
                           <div class="row">
{{--                               <div class="col-md-6">--}}
{{--                                   <p><b>Note:</b> {{ $receiptPayment->notes }}</p>--}}
{{--                               </div>--}}
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



