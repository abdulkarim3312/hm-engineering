<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <style>
        .bottom-title{
            border-top: 1.5px solid black;
            text-align: center;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: 50px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.2;
        }

        .img-overlay img {
            width: 300px;
            height: auto;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #000000 !important;
            padding: 2px 2px !important;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="img-overlay">
        <img src="{{ asset('img/logo.png') }}">
    </div>
    <div>
        <div class="row">
            <div class="col-xs-4 text-left">
                <div class="logo-area-report">
                    <img width="35%" src="{{ asset('img/head_logo.jpeg') }}">
                </div>
            </div>
            <div class="col-xs-8 text-center" style="margin-left: -128px;">
                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
            </div>
        </div>

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

        <div class="row" style="margin-top: 50px !important;">
            <div class="col-xs-3" style="margin-top: 25px;">
                <div class="text-left" style="margin-left: 10px;">
                    <h5 class="bottom-title">Received By</h5>
                </div>
            </div>
            <div class="col-xs-3" style="margin-top: 25px">
                <div class="text-center">
                    <h5 class="bottom-title" style="text-align: center!important;">Prepared By</h5>
                </div>
            </div>
            <div class="col-xs-3" style="margin-top: 25px">
                <div class="text-right">
                    <h5 class="bottom-title">Checked By</h5>
                </div>
            </div>
            <div class="col-xs-3" style="margin-top: 25px">
                <div class="text-right">
                    <h5 class="bottom-title">Approved By</h5>
                </div>
            </div>
        </div>
    </div>

</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
