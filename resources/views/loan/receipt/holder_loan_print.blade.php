@extends('layouts.app')

@section('style')
    <style>
        #receipt-content{
            font-size: 18px;
        }

        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid black !important;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 6px !important;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: -45px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.2;
        }

        .img-overlay img {
            width: 400px;
            height: auto;
        }
    </style>
@endsection

@section('title')
    Loan Details
@endsection

@section('content')

    <div class="row" id="receipt-content">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button style="margin-right: 8px" class="btn btn-primary" onclick="getprint('printarea')">Voucher Print</button>
                            <br><hr>
                        </div>
                    </div>
                    <div class="row">
                        <div id="printarea">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <div class="logo-area-report">
                                        <img width="35%" src="{{ asset('img/head_logo.jpeg') }}">
                                    </div>
                                </div>
                                <div class="col-xs-8 text-center">
                                    <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                </div>
                            </div>
                            <div class="col-xs-12 text-center">
                                <h4 ><span style="font-weight: 700" class="pull-left">Serial No: {{ $loan->loan_number }}</span> <span style="font-size: 20px;font-weight: bold;text-transform: uppercase; margin-right: 100px;"> @if($loan->loan_type==1) Debit Voucher @else Credit Voucher @endif </span><span style="text-align: center; font-weight: normal;font-size: 16px;position: absolute;text-transform: capitalize;right: 20px;"><b>Date: </b> {{ date('Y-m-d') }}</span></h4>

                            </div>

                            <div class="col-xs-12">
                                <div class="img-overlay">
                                    <img src="{{ asset('img/logo.png') }}">
                                </div>
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <td width="80%" style="border-bottom: 1px solid #fff !important;" colspan="7">Holder Name: &nbsp;&nbsp;<strong>{{ $loan->loanHolder->name }}</strong><p style="border-bottom: 1px dotted #000;margin-left: 150px;"></p></td>
                                        <td width="20%" class="text-center"><strong>Amount</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom: 1px solid #fff !important;" colspan="7">Purpose of: <strong>

                                                {{ $loan->loan_type==1 ? 'Loan Taken' : 'Loan Given' }}


                                            </strong> <p style="border-bottom: 1px dotted #000;margin-left: 60px;"></p></td>
                                        <th rowspan="3" class="text-right">৳ {{ number_format($loan->total, 2) }}</th>

                                    </tr>
                                    <tr>
                                        <td width="80%" style="border-bottom: 1px solid #fff !important;" colspan="7">Address: <strong>{{ $loan->loanHolder->address }}</strong><p style="border-bottom: 1px dotted #000;margin-left: 50px;"></p></td>

                                    </tr>

                                    <tr>
                                        <td style="border-right:1px solid #fff !important;border-bottom: 1px solid #fff !important;" colspan="2">Loan Duration: <strong>{{$loan->duration}} year</strong><p style="border-bottom: 1px dotted #000;margin-left: 80px;"></p></td>
                                         <td><strong>Loan Interest: &nbsp;{{$loan->interest}}%</strong><p style="border-bottom: 1px dotted #000;margin-left: 80px;"></p></td>
                                        <td style="border-left:2px solid #fff !important;border-right:2px solid #fff !important; border-bottom: 1px solid #fff !important;" colspan="2"><strong> Date: {{ $loan->date }} </strong><p style="border-bottom: 1px dotted #000;margin-left: 38px;"></p></td>

                                    </tr>
                                    <tr>

                                        {{-- <td style="border-bottom: 1px solid #fff !important;" colspan="8">
                                            <div style="width: 33.33%;float: left;text-align: left" class="three-half">Bank Name: <strong>@if (!empty($payment->bank->name)) {{$payment->bank->name}} @endif</strong><p style="border-bottom: 1px dotted #000;margin-left: 85px;"></p></div>
                                            <div style="width: 33.33%;float: left;text-align: left" class="three-half">Branch: <strong>@if (!empty($payment->branch->name)){{$payment->branch->name}}@endif</strong><p style="border-bottom: 1px dotted #000;margin-left: 52px;"></p></div>
                                            <div style="width: 33.33%;float: left;text-align: left" class="three-half">Account No.: <strong>@if (!empty($payment->account->account_no)){{$payment->account->account_no}}@endif</strong><p style="border-bottom: 1px dotted #000;margin-left: 84px;"></p></div>
                                        </td> --}}
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 0;" colspan="8">Amount in word: <strong>{{ $loan->amount_in_word }}</strong><p style="border-bottom: 1px dotted #000;margin-left: 117px;"></p></td>
                                    </tr>

                                    </tbody>
                                    <tfoot>
                                    <tr style="margin-top: 50px !important;border: 1px solid #fff !important;">
                                        <th style="border:0px solid !important;border: 0px solid !important;padding-top: 40px;padding-bottom: 0px;text-align: center;padding-top: 45px !important;" width="25%" colspan="2">Sig. of Receiver <p style="border-top: 1px solid #000;margin-top: -26px;margin-left: 30px;margin-right: 30px;"></p></th>
                                        <th style="border:0px solid !important;padding-top: 40px;padding-bottom: 0px;text-align: center;padding-top: 45px !important;" width="25%" colspan="2">Prepared By <p style="border-top: 1px solid #000;margin-top: -26px;margin-left: 40px;margin-right: 40px;"></p></th>
                                        <th style="border:0px solid !important;padding-top: 40px;padding-bottom: 0px;text-align: center;padding-top: 45px !important;" width="25%" colspan="2">Checked By <p style="border-top: 1px solid #000;margin-top: -26px;margin-left: 45px;margin-right: 45px;"></p></th>
                                        <th style="border:0px solid !important;padding-top: 40px;padding-bottom: 0px;text-align: center;padding-top: 45px !important;" width="25%" colspan="2">Authorised By <p style="border-top: 1px solid #000;margin-top: -26px;margin-left: 35px;margin-right: 35px;"></p></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
@section('script')
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
