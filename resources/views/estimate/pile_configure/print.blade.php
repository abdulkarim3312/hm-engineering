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
            <div class="col-xs-8 text-center">
                <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 text-center">
{{--                <h4 style="padding: 10px;"><span style="font-weight: 700" class="pull-left">Serial No: {{ $order->id+10000 }}</span> <u><span style="font-size: 30px;font-weight: bold;text-transform: uppercase; margin-right: 100px;"> Purchase Receipt </span></u><span style="text-align: center; font-weight: normal;font-size: 16px;position: absolute;text-transform: capitalize;right: 20px;"><b>Date: </b> {{ date('j F, Y') }}</span></h4>--}}

            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Pile Configure No.</th>
                        <td>{{ $pileConfigure->pile_configure_no }}</td>
                    </tr>
                    <tr>
                        <th>Pile Configure Date</th>
                        <td>{{ $pileConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $pileConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Total Rft/Ton</th>
                        <td>{{ $pileConfigure->total_ton }}</td>
                    </tr>
                    <tr>
                        <th>Total Cement</th>
                        <td>{{ $pileConfigure->total_cement }} Bag</td>
                    </tr>
                    <tr>
                        <th>Total Sands</th>
                        <td>{{ $pileConfigure->total_sands }} Cft</td>
                    </tr>
                    <tr>
                        <th>Total Aggregate</th>
                        <td>{{ $pileConfigure->total_aggregate }} Cft</td>
                    </tr>
                    <tr>
                        <th>Total Piked</th>
                        <td>{{ $pileConfigure->total_picked }} Pcs</td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $pileConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Pilling Info</th>
                    </tr>
                    <tr>
                        <th>Spiral Bar</th>
                        <td>{{ $pileConfigure->spiral_bar }}</td>
                    </tr>
                    <tr>
                        <th>Spiral Interval</th>
                        <td>{{ $pileConfigure->spiral_interval }}</td>
                    </tr>
                    <tr>
                        <th>Ratio</th>
                        <td>{{ $pileConfigure->first_ratio }}:{{ $pileConfigure->second_ratio }}:{{ $pileConfigure->third_ratio }}</td>
                    </tr>
                    <tr>
                        <th>Height</th>
                        <td>{{ $pileConfigure->pile_height }}</td>
                    </tr>
                    <tr>
                        <th>Radius</th>
                        <td>{{ $pileConfigure->radius }}</td>
                    </tr>
                    <tr>
                        <th>Pile Quantity</th>
                        <td>{{ $pileConfigure->pile_quantity }}</td>
                    </tr>
                    <tr>
                        <th>Total Volume</th>
                        <td>{{ $pileConfigure->total_volume }}</td>
                    </tr>
                    <tr>
                        <th>Total Dry Volume</th>
                        <td>{{ $pileConfigure->total_dry_volume }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Bar</th>
                        <th>Dia</th>
                        <th>Dia(D^2)</th>
                        <th>Value of Bar</th>
                        <th>Kg/Rft</th>
                        <th>Kg/Ton</th>
                        <th>Rft/Ton</th>
                        <th>Sub Total Kg</th>
                        <th>Sub Total Ton</th>
                    </tr>
                    </thead>
                    <tbody>
                    <h4>Main Bar Description</h4>
                    @foreach($pileConfigure->pileConfigureProducts as $product)
                        <tr>
                            <td>
                                @if($product->bar_type == 6)
                                    6mm
                                @elseif($product->bar_type == 8)
                                    8mm
                                @elseif($product->bar_type == 10)
                                    10mm
                                @elseif($product->bar_type == 12)
                                    12mm
                                @elseif($product->bar_type == 16)
                                    16mm
                                @elseif($product->bar_type == 18)
                                    18mm
                                @elseif($product->bar_type == 20)
                                    20mm
                                @elseif($product->bar_type == 22)
                                    22mm
                                @elseif($product->bar_type == 25)
                                    25mm
                                @elseif($product->bar_type == 28)
                                    28mm
                                @elseif($product->bar_type == 32)
                                    32mm
                                @elseif($product->bar_type == 36)
                                    36mm
                                @endif

                            </td>
                            <td>{{ $product->dia }}</td>
                            <td> {{ $product->dia_square }}</td>
                            <td> {{ number_format($product->value_of_bar, 2) }}</td>
                            <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                            <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                            <td> {{ number_format($product->rft_by_ton, 2) }}</td>
                            <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <th colspan="7" class="text-right">Total Ton/KG</th>
                        <th> {{ number_format($pileConfigure->total_kg, 2) }}</th>
                        <th> {{ number_format($pileConfigure->total_ton, 3) }}</th>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Bar</th>
                        <th>Dia</th>
                        <th>Dia(D^2)</th>
                        <th>Value of Bar</th>
                        <th>Kg/Rft</th>
                        <th>Kg/Ton</th>
                        <th>Sub Total Kg</th>
                        <th>Sub Total Ton</th>
                    </tr>
                    </thead>
                    <tbody>
                    <h4>Extra Bar Description</h4>
                    @foreach($pileConfigure->pileConfigureProducts as $product)
                        @if($product->status == 1)
                            <?php
                            $extraTotalKg += $product->sub_total_kg;
                            $extraTotalTon += $product->sub_total_ton;
                            ?>
                            <tr>
                                <td>
                                    @if($product->bar_type == 6)
                                        6mm
                                    @elseif($product->bar_type == 8)
                                        8mm
                                    @elseif($product->bar_type == 10)
                                        10mm
                                    @elseif($product->bar_type == 12)
                                        12mm
                                    @elseif($product->bar_type == 16)
                                        16mm
                                    @elseif($product->bar_type == 18)
                                        18mm
                                    @elseif($product->bar_type == 20)
                                        20mm
                                    @elseif($product->bar_type == 22)
                                        22mm
                                    @elseif($product->bar_type == 25)
                                        25mm
                                    @elseif($product->bar_type == 28)
                                        28mm
                                    @elseif($product->bar_type == 32)
                                        32mm
                                    @elseif($product->bar_type == 36)
                                        36mm
                                    @endif

                                </td>
                                <td>{{ $product->dia }}</td>
                                <td> {{ $product->dia_square }}</td>
                                <td> {{ number_format($product->value_of_bar, 2) }}</td>
                                <td> {{ number_format($product->kg_by_rft, 2) }}</td>
                                <td> {{ number_format($product->kg_by_ton, 2) }}</td>
                                <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>

                    {{-- <tr>
                        <th colspan="6" class="text-right" >Extra Bar Total</th>
                        <td> {{ number_format($extraTotalKg, 2) }}</td>
                        <td> {{ number_format($extraTotalTon, 3) }}</td>
                    </tr> --}}

                    <tr>
                        <th colspan="6" class="text-right" >Total Ton/KG</th>
                        <td> {{ number_format($pileConfigure->total_kg, 2) }}</td>
                        <td> {{ number_format($pileConfigure->total_ton, 3) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <u><i><h2>Costing Area</h2></i></u>
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Bar(Rod) Price (Kg)</th>
                        <td>৳ {{ $pileConfigure->total_pile_bar_price }} Taka</td>
                    </tr>
                    <tr>
                        <th>Cement Price(Bag)</th>
                        <td>৳ {{ $pileConfigure->total_pile_cement_bag_price }} Taka</td>
                    </tr>
                </table>
            </div>

            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Sands Price (Cft)</th>
                        <td>৳ {{ $pileConfigure->total_pile_sands_price }} Taka</td>
                    </tr>
                    @if($pileConfigure->total_picked == 0)
                        <tr>
                            <th>Aggregate Price (Cft)</th>
                            <td>৳ {{ $pileConfigure->total_pile_aggregate_price }} Taka</td>
                        </tr>
                    @else
                        <tr>
                            <th>Picked Price (Pcs)</th>
                            <td>৳ {{ $pileConfigure->total_pile_picked_price }} Taka</td>
                        </tr>
                    @endif
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
