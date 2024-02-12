<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ $receiptPayment->payment_type == 1 ? 'Cheque' : 'Cash' }} Receipt No:{{ $receiptPayment->receipt_payment_no }}
    </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <style>
        .wrapper {
            position: relative;
        }
        /*html { background-color: #ffd21b; }*/
        body { margin: 0 50px;
            /*background-color: #ffd21b;*/
        }
        table,.table,table td,
        /*table th{background-color: #ffd21b !important;}*/
        .table-bordered{
            border: 1px solid #000000;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000000 !important;
           /*background-color: #ffd21b !important;*/
            font-size: 17px !important;
        }
        .table.body-table td,.table.body-table th {
            padding: 2px 7px;
        }
        .footer-signature-area {
            position: absolute;
            left: 0;
            bottom: 144px;
            width: 100%;
        }
        .fs-style{
            font-size: 24px !important;
            letter-spacing: 7px!important;
            font-weight: 900!important;
        }
        .invoice {
            border: none;
        }
        @page {
            margin: 0;
        }
        .invoice {
            background-color: #fff;
            position: relative;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: 80px;
            width: auto;
            margin-top: 110px;
            margin-right: 150px;
            margin-left: 150px;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.2;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-center m-0" style="font-size: 30px !important;font-weight: bold">
                    <img height="80px" src="{{ asset('img/logo.png') }}" alt="">
                    {{ config('app.name') }}
                </h1>
                <h3 class="text-center m-0" style="margin-top:0;font-size: 22px !important;">{{ $receiptPayment->payment_type == 1 ? 'Bank' : 'Cash' }} Voucher</h3>
                <h3 class="text-center m-0 fs-style" style="margin-top:0;font-size: 22px !important;">FY : {{ $receiptPayment->financial_year }}</h3>

            </div>
            <!-- /.col -->
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-xs-5 col-xs-offset-7">
                <h4 style="margin: 0;font-size: 18px!important;">Receipt No: {{ $receiptPayment->receipt_payment_no }}</h4>
                <h4 style="margin: 0;font-size: 18px!important;">Date: {{ $receiptPayment->date->format('d-m-Y') }}</h4>
            </div>
        </div>
        <div class="row"  style="margin-top: 15px">
            <div class="col-xs-12">
                <div class="img-overlay">
                    <img src="{{ asset('img/logo.png') }}">
               </div>
                <table class="table table-bordered">

                    <tr>
                        <th width="24%">Payee Name & Designation</th>
                        <th width="2%" class="text-center">:</th>
                        <td width="">{{ $receiptPayment->client->name ?? '' }} , {{ $receiptPayment->client->company_name ?? '' }}</td>
                        <td><b>ID:</b> {{ $receiptPayment->customer_id }}</td>
                    </tr>
                    <tr>
                        <th width="24%">Address</th>
                        <th width="2%" class="text-center">:</th>
                        <td width="">{{ $receiptPayment->client->address ?? '' }}</td>
                        <td><b>Project Name:</b> {{ $receiptPayment->project->name??'' }}</td>
                    </tr>
                    <tr>
                        <th width="24%">Payment Step</th>
                        <th width="2%" class="text-center">:</th>
                       @if ($receiptPayment->payment_step == 1)
                         <td width="">Booking Money</td>
                       @elseif ($receiptPayment->payment_step == 2)
                         <td width="">Down Payment</td>
                       @elseif ($receiptPayment->payment_step == 3)
                         <td width="">{{ $receiptPayment->installment_name ?? '' }}</td>
                       @else
                         <td width=""></td>
                       @endif
                        {{-- <td><b>Payment Installment Step: </b> {{ $receiptPayment->installment_name ?? '' }}</td> --}}
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
                        <td width="">{{ $receiptPayment->client->mobile_no ?? ''}}</td>
                        <td width=""><b>Email:</b> {{ $receiptPayment->client->email ?? ''}}</td>
                    </tr>
                    <tr>
                        <th width="24%">Floor Name</th>
                        <th width="2%" class="text-center">:</th>
                        <td width="">{{ $receiptPayment->floor->name ?? ''}}</td>
                        <td width=""><b>Flat Name:</b> {{ $receiptPayment->flat->name ?? ''}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row " style="min-height: 600px !important;margin-top: 30px">
            <div class="col-xs-12">
                <span><b>Received Details:</b></span>
                <table class="table body-table table-bordered">
                    <tr>
                        <th  class="text-center" width="55%">Brief Description</th>
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

                        <tr>
                            <td style="border-bottom: 1px solid transparent !important;"> {{ $receiptPaymentDetail->accountHead->name }}
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
                </div>

            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<div class="footer-signature-area">
    <div class="row signature-area" style="padding:0 50px!important;">
        <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 17px;font-weight: bold">Prepared By</span></div>
        <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 15px;font-weight: bold">Checked By A. H</span></div>
        <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 17px;font-weight: bold">Approved By</span></div>
        <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 17px;font-weight: bold">Received By</span></div>
    </div>
</div>
<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
