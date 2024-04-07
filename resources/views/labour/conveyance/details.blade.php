@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .bottom-title{
            border-top: 1.5px solid #4f56be;
            text-align: center;
        }
        .logo i h2 {
            margin-left: 103px;
            font-weight: bold;
            color: #4f56be;
            font-family: Monotype Corsiva;
            font-size: 50px;
        }

        .logo p {
            margin-left: 97px;
            margin-top: -20px;
            color: #f5f507;
            font-family: Monotype Corsiva;
            font-size: 19px;
        }
        .logo hr {
            margin-top: -18px;
            margin-bottom: 20px;
            border: 0;
            border-top: 4px solid #c41702;
            width: 156px;
            margin-right: 89px;
            border-radius: 1px;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: 150px;
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
        .row{
            margin: 0;
        }
        hr {
            margin-top: 0rem;
            margin-bottom: 0rem;
            border: 0;
            border-top: 1px solid black;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000 !important;
            vertical-align: middle;
            border-bottom-width: 1px !important;
            border-left-width: 1px !important;
            text-align: center;
            padding: 0.2rem !important;
        }
        @media print{
            @page {
                size: auto;
                margin: 20px !important;
            }
        }
        button, html input[type=button], input[type=reset], input[type=submit] {
            background: #367FA9;
            color: #fff;
        }
    </style>
@endsection

@section('title')
   Bill Form Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('bill_form.print', ['billForm' => $billForm->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>
                    <div id="prinarea">
                        <div class="img-overlay">
                            <img src="{{ asset('img/logo.png') }}">
                        </div>
                        <div class="row">
                            <div class="col-xs-4 text-left">
                                <div class="logo-area-report">
                                    <img width="35%" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                            </div>
                            <div class="col-xs-8 text-center" style="margin-left: -130px;">
                                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                                <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                                <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                                <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                            </div>
                        </div>
                        <hr>
                        <h2 class="text-center"><u>Bill Form</u></h2>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th style="float:left">Project Name:</th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">
                                            {{ $billForm->project->name ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="float:left">Project Address:</th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->address }}</td>
                                    </tr>
                                    <tr>
                                        <th style="float:left">Cheque Holder Name: </th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->acc_holder_name??'' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="float:left">Duration: </th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->duration??'' }}</td>
                                    </tr>

                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <th style="float:left;">For The Month:</th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->for_the_month }}</td>
                                    </tr>
                                    <tr>
                                        <th style="float:left;">Trade:</th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->trade }}</td>
                                    </tr>
                                    <tr>
                                        <th style="float:left;">Bill No:</th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->bill_no }}</td>
                                    </tr>
                                    <tr>
                                        <th style="float:left;">Date:</th>
                                        <td style="text-decoration:underline dotted;text-underline-position:under;float:left">{{ $billForm->date }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl No</th>
                                        <th width="60%">Particulars</th>
                                        <th>Amount(Tk)</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($billForm->billFormProduct as $product)
                                        <tr>
                                            <td>{{ $loop->iteration}}</td>
                                            <td>{{ $product->product ?? ''}}</td>
                                            <td> {{ number_format($product->amount,2) }}</td>
                                            <td> </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <th>Total</th>
                                            <td>{{ $billForm->total_amount ?? ''}}</td>
                                            <td> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": [[ 0, "desc" ]],
            });
        });
    </script>
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            //window.location.replace(APP_URL)
        }
    </script>
@endsection
