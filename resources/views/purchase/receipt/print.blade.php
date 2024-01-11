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
            top: 250px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.1;
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
        .footer-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
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
            <div class="col-xs-8 text-center">
                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-center">
                <h4 style="padding: 10px;"><span style="font-weight: 700" class="pull-left">Serial No: {{ $order->id+10000 }}</span> <u><span style="font-size: 30px;font-weight: bold;text-transform: uppercase; margin-right: 100px;"> Purchase Receipt </span></u><span style="text-align: center; font-weight: normal;font-size: 16px;position: absolute;text-transform: capitalize;right: 20px;"><b>Date: </b> {{ date('j F, Y') }}</span></h4>

            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-right">Order No.</th>
                        <th>{{ $order->order_no }}</th>
                    </tr>
                    <tr>
                        <th class="text-right">Order Date</th>
                        <th>{{ $order->date->format('j F, Y') }}</th>
                    </tr>
                    <tr>
                        <th class="text-right">Warehouse</th>
                        <th>{{ $order->warehouse_id == null ? 'No warehouse' : $order->warehouse->name }}</th>
                    </tr>
                    <tr>
                        <th class="text-right">Received Date</th>
                        <th>{{ $order->received_at == null ? 'Not Received' : $order->received_at->format('j F, Y') }}</th>
                    </tr>
                     <tr>
                        <th class="text-right"> Note</th>
                        <th>{{ $order->note }}</th>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Supplier Info</th>
                    </tr>
                    <tr>
                        <th class="text-right">Name</th>
                        <th>{{ $order->supplier->name }}</th>
                    </tr>
                    <tr>
                        <th class="text-right">Company Name</th>
                        <th>{{ $order->supplier->company_name }}</th>
                    </tr>
                    <tr>
                        <th class="text-right">Mobile</th>
                        <th>{{ $order->supplier->mobile_no }}</th>
                    </tr>
                    <tr>
                        <th class="text-right">Address</th>
                        <th>{{ $order->supplier->address }}</th>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Product Name</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">Total</th>
                    </tr>
                    @foreach($order->products as $product)
                        <tr>
                            <td>{{ $product->pivot->name }}</td>
                            <td class="text-right">{{ $product->pivot->quantity.' '.$product->pivot->unit }}</td>
                            <td class="text-right">৳ {{ number_format($product->pivot->unit_price, 2) }}</td>
                            <td class="text-right">৳ {{ number_format($product->pivot->total, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="3" class="text-right">Total Amount</th>
                        <th class="text-right">৳ {{ number_format($order->total, 2) }}</th>
                    </tr>
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
    <div class="row">
        <div class="footer-container">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
            <img style="text-align: center; width: 60px;" src="{{ asset('img/logo.png') }}">
        </div>
    </div>
</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
