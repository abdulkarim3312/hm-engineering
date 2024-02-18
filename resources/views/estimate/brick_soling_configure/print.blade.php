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
            <div class="col-xs-8 text-center" style="margin-left: -118px;">
                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Estimate Project Name.</th>
                        <td>{{ $bricksSolingConfigure->project->name ?? ''}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <h3><b><u>Bricks Soling Calculation</u></b></h3>
                    <thead>
                    <tr>
                        <th>Length</th>
                        <th>Width</th>
                        <th>Nos</th>
                        <th>Unit Price</th>
                        <th>Total Volume</th>
                        <th>Sub Total Price</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalArea = 0;
                            $totalPrice = 0;
                        @endphp
                    @foreach($bricksSolingConfigure->bricksSolingConfigureProducts as $product)
                        <tr>
                            <td>{{ number_format($product->length, 2) ?? ''}}</td>
                            <td>{{ number_format($product->width, 2) ?? ''}}</td>
                            <td>{{ number_format($product->height, 2) ?? ''}}</td>
                            <td>{{ number_format($product->unit_price, 2) }} Taka</td>
                            <td>{{ number_format($product->total_area, 2) }} Cft</td>
                            <td>{{ number_format($product->total_price, 2) }} Taka</td>
                        </tr>
                        @php
                            $totalArea += $product->total_area;
                            $totalPrice += $product->total_price;
                        @endphp
                    @endforeach
                    <tr>
                        <th class="text-right" colspan="4">Total</th>
                            <td><b>৳ {{ number_format($totalArea, 2) }} Cft</b></td>
                            <td><b>৳ {{ number_format($totalPrice, 2) }} Taka</b></td>
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
