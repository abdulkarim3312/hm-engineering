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
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #000000 !important;
            padding: 2px 2px !important;
            font-size: 13px;
        }
        .box-body {
            position: relative;
        }

        .img-overlay {
            position: absolute;
            left: 0;
            top: 80px;
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
</head>

<body>
<div class="container-fluid">
    <div class="img-overlay">
        <img src="{{ asset('img/logo.png') }}">
    </div>
    <div>
        <div class="col-xs-4 text-left">
            <div class="logo-area-report">
                <img src="{{ asset('img/head_logo.jpeg') }}">
            </div>
        </div>
        <div class="col-xs-8 text-center">
            <h2>{{\App\Enumeration\Text::$companyName}}</h2>
            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <h4 style="padding: 10px;"><span style="font-weight: 700" class="pull-left">Serial No: {{ $order->id+10000 }}</span> <u><span style="font-size: 30px;font-weight: bold;text-transform: uppercase; margin-right: 100px;">Scrap Sales Receipt </span></u><span style="text-align: center; font-weight: normal;font-size: 16px;position: absolute;text-transform: capitalize;right: 20px;"><b>Date: </b> {{ $order->date }}</span></h4>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-right">Order No.</th>
                        <td>{{ $order->order_no }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Order Date</th>
                        <td>{{ $order->date }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Client Info</th>
                    </tr>
                    <tr>
                        <th class="text-right">Name</th>
                        <td>{{ $order->client->name ?? ''}}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Company Name</th>
                        <td>{{ $order->client->company_name ?? ''}}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Mobile</th>
                        <td>{{ $order->client->mobile ?? ''}}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Address</th>
                        <td>{{ $order->client->address ?? ''}}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Email</th>
                        <td>{{ $order->client->email ?? ''}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">Project</th>
                        <th class="text-center">Product</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Price</th>
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($order->products as $product)
                        <tr>
                            <td>{{ $product->project->name }}</td>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->product->unit->name }}</td>
                            <td class="text-right"> {{ number_format($product->quantity,2) }}</td>
                            <td class="text-right"> {{ number_format($product->unit_price,2) }}</td>
                            <td class="text-right"> {{ number_format($product->total,2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-offset-8 col-md-4">
                <table class="table table-bordered">

                    <tr>
                        <th class="text-right">Sub Total Amount</th>
                        <th class="text-right"> {{ number_format($order->total, 2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Discount</th>
                        <th class="text-right"> {{ number_format($order->discount, 2) }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Paid</th>
                        <th class="text-right"> {{ number_format($order->paid, 2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Due</th>
                        <th class="text-right"> {{ number_format($order->due, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row" style="margin-top: 10px !important;">
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
