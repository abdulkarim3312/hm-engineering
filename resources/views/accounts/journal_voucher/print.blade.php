<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Journal_Voucher_{{$journalVoucher->jv_no}}</title>

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
            font-size: 10px;
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
    </style>
</head>
<body>
<div class="single-page">
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row" style="">
                <div class="col-md-12">
                    <h1 class="text-center m-0" style="font-size: 60px !important;font-weight: bold">
                        <img height="100px" src="{{ asset('img/logo.png') }}" alt="">
                        {{ config('app.name') }}
                    </h1>
                    <h3 class="text-center m-0" style="font-size: 35px !important;">Journal Voucher</h3>
                    <h3 class="text-center m-0 fs-style" style="font-size: 25px !important;">FY : {{ $journalVoucher->financial_year }}</h3>
                </div>
                <!-- /.col -->
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-xs-4 col-xs-offset-8">
                    <h4 style="margin: 0;font-size: 20px!important;">JV No: {{ $journalVoucher->jv_no }}</h4>
                    <h4 style="margin: 0;font-size: 20px!important;">Date: {{ $journalVoucher->date->format('d-m-Y') }}</h4>
                </div>
            </div>
            <div class="row"  style="margin-top: 15px">
                <div class="col-12">
                    <table class="table table-bordered">
                        <tr>
                            <th width="24%">Employee/Party</th>
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
                    <table class="table body-table table-bordered">
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
                                <td >{{ $journalVoucherDebitDetail->accountHead->name }}</td>

                                <td  class="text-center">{{ $journalVoucherDebitDetail->accountHead->account_code }}</td>
                                <td  class="text-right">{{ number_format($journalVoucherDebitDetail->amount,2) }}</td>
                                <td  class="text-right"></td>
                            </tr>
                        @endforeach
                        @foreach($journalVoucher->journalVoucherCreditDetails as $key => $journalVoucherCreditDetail)
                            <tr>
                                <td><b style="margin-left: 20px !important;">To, </b>{{ $journalVoucherCreditDetail->accountHead->name }}</td>
                                <td class="text-center">{{ $journalVoucherCreditDetail->accountHead->account_code }}</td>
                                <td class="text-right"></td>
                                <td class="text-right">{{ number_format($journalVoucherCreditDetail->amount,2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th class="text-left" colspan="2">Total(in word) = {{ $journalVoucher->amount_in_word }} Only.</th>
                            <th class="text-right">{{ number_format($journalVoucher->credit_total,2) }}</th>
                            <th class="text-right">{{ number_format($journalVoucher->debit_total,2) }}</th>
                        </tr>
                        </tbody>

                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>Note:</b> {{ $journalVoucher->notes }}</p>
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
            <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 17px;font-weight: bold">Checked By</span></div>
            <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 17px;font-weight: bold">Approved By</span></div>
            <div class="col-xs-3 text-center"><span style="border: 1px solid #000 !important;display: block;padding: 18px;font-size: 17px;font-weight: bold">Received By</span></div>
        </div>
    </div>
</div>

<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
