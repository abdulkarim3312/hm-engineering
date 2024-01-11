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
{{--                <h3 style="margin-top: 0;margin-bottom: 5px"><strong><u>Receipt</u></strong></h3>--}}
                <h4 style="padding: 10px;"><span style="font-weight: 700" class="pull-left">Serial No: {{ $order->id+10000 }}</span> <u><span style="font-size: 30px;font-weight: bold;text-transform: uppercase; margin-right: 100px;"> Sales Receipt </span></u><span style="text-align: center; font-weight: normal;font-size: 16px;position: absolute;text-transform: capitalize;right: 20px;"><b>Date: </b> {{ $order->date }}</span></h4>

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
                        <td>{{ $order->client->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Company Name</th>
                        <td>{{ $order->client->company_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Mobile</th>
                        <td>{{ $order->client->mobile }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Address</th>
                        <td>{{ $order->client->address }}</td>
                    </tr>
                    <tr>
                        <th class="text-right">Email</th>
                        <td>{{ $order->client->email }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th class="text-center">Project Name</th>
                        <th class="text-center">Floor Name</th>
                        <th class="text-center">{{ $order->flats[0]->type == 1 ? 'Flat Name' : 'Shop' }}</th>
                        <th class="text-center">Price</th>
                    </tr>
                    @foreach($order->flats as $flat)
                        <tr>
                            <td class="text-center">{{ $order->project->name }}</td>
                            <td class="text-center">{{ $order->floor['name'] }}</td>
                            <td class="text-center">{{ $flat->pivot->flat_name }}</td>
                            <td class="text-right">৳ {{ $flat->pivot->price}}</td>
                        </tr>
                    @endforeach
                    @foreach ( $order->flats as $flat )
                        <tr>
                            <th colspan="3" class="text-right">Car Parking</th>
                            <th class="text-right">৳ {{ number_format($flat->pivot->car, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3"  class="text-right">Utility</th>
                            <th class="text-right">৳ {{ number_format($flat->pivot->utility, 2) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3"  class="text-right">Other</th>
                            <th class="text-right">৳ {{ number_format($flat->pivot->other, 2) }}</th>
                        </tr>
                    @endforeach
                    @if($order->discount)
                        <tr>
                            <th colspan="3"  class="text-right">Discount</th>
                            <th class="text-right">৳ {{ number_format($order->discount, 2) }}</td>
                        </tr>
                    @endif
                    @if($order->vat_percent > 0)
                        <tr>
                            <th colspan="3" class="text-right">Vat({{ number_format($order->vat_percent,2) }})%</th>
                            <th class="text-right">৳ {{ number_format($order->vat, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th colspan="3"  class="text-right">Sub Total Amount</th>
                        <th class="text-right">৳ {{ number_format($order->total, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Total Amount</th>
                        <th class="text-right">৳ {{ number_format($order->total - $order->discount, 2) }}</td>
                    </tr>

                    <tr>
                        <th colspan="3" class="text-right">Paid/Booking Money</th>
                        <th class="text-right">৳ {{ number_format($order->paid, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Due</th>
                        <th class="text-right">৳ {{ number_format($order->due, 2) }}</td>
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
