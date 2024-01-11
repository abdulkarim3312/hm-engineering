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
                        <th>Footing Configure No.</th>
                        <td>{{ $columnConfigure->common_configure_no }}</td>
                    </tr>
                    <tr>
                        <th>Footing Configure Date</th>
                        <td>{{ $columnConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Project Name</th>
                        <td>{{ $columnConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Costing Segment Name</th>
                        <td>{{ $columnConfigure->costingSegment->name }}</td>
                    </tr>
                    <tr>
                        <th>Total Cement</th>
                        <td>{{ $columnConfigure->total_cement_bag }} Bag</td>
                    </tr>
                    <tr>
                        <th>Total Sands</th>
                        <td>{{ $columnConfigure->total_sands }} Cft</td>
                    </tr>
                    <tr>
                        <th>Total Aggregate</th>
                        <td>{{ $columnConfigure->total_aggregate }} Cft</td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $columnConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">{{$columnConfigure->costingSegment->name}} Info</th>
                    </tr>
                    <tr>
                        <th>{{$columnConfigure->costingSegment->name}} Quantity</th>
                        <td>{{ $columnConfigure->costing_segment_quantity }}</td>
                    </tr>
                    <tr>
                        <th>Total Ton</th>
                        <td>{{ $columnConfigure->total_ton }} Rod</td>
                    </tr>
                    <tr>
                        <th>Total Kg</th>
                        <td>{{ $columnConfigure->total_kg }} Rod</td>
                    </tr>
                    <tr>
                        <th>Total Piked</th>
                        <td>{{ $columnConfigure->total_picked }} Pcs</td>
                    </tr>

                </table>
            </div>
        </div>
        @php
            $mainTotalKg = 0;
            $mainTotalTon = 0;
            $extraTotalKg = 0;
            $extraTotalTon = 0;
        @endphp

        <div class="row">
            <div class="col-md-12">
                <h4>Main Bar Calculation</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Bar</th>
                        <th>Dia</th>
                        <th>Dia(D^2)</th>
                        <th>Value of Bar</th>
                        <th>Kg/Rft</th>
                        <th>Kg/Ton</th>
                        <th>Length Type</th>
                        <th>Length</th>
                        <th>Spacing</th>
                        <th>Type Length</th>
                        <th>Layer</th>
                        <th>Sub Total Kg</th>
                        <th>Sub Total Ton</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($columnConfigure->commonConfigureProducts as $product)
                        @if($product->status == null)
                            <?php
                            $mainTotalKg += $product->sub_total_kg;
                            $mainTotalTon += $product->sub_total_ton;
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
                                <td>
                                    @if($product->length_type == 1)
                                        Horizontal
                                    @else
                                        Vertical
                                    @endif
                                </td>
                                <td> {{ number_format($product->length, 2) }}</td>
                                <td> {{ number_format($product->spacing, 2) }}</td>
                                <td> {{ number_format($product->type_length, 2) }}</td>
                                <td> {{ number_format($product->layer, 2) }}</td>
                                <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tr>
                        <th colspan="11" class="text-right" >Total Ton/KG</th>
                        <td> {{ number_format($mainTotalKg, 2) }}</td>
                        <td> {{ number_format($mainTotalTon, 3) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4>Extra Bar Calculation</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Bar</th>
                        <th>Dia</th>
                        <th>Dia(D^2)</th>
                        <th>Value of Bar</th>
                        <th>Kg/Rft</th>
                        <th>Kg/Ton</th>
                        <th>Extra Bar</th>
                        <th>Extra Length</th>
                        <th>Sub Total Kg</th>
                        <th>Sub Total Ton</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($columnConfigure->commonConfigureProducts as $product)

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
                                <td> {{ number_format($product->number_of_bar, 2) }}</td>
                                <td> {{ number_format($product->extra_length, 2) }}</td>
                                <td> {{ number_format($product->sub_total_kg, 2) }}</td>
                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tr>
                        <th colspan="8" class="text-right" >Extra Bar Total</th>
                        <td> {{ number_format($extraTotalKg, 2) }}</td>
                        <td> {{ number_format($extraTotalTon, 3) }}</td>
                    </tr>

                    <tr>
                        <th colspan="8" class="text-right" >Total Ton/KG</th>
                        <td> {{ number_format($columnConfigure->total_kg, 2) }}</td>
                        <td> {{ number_format($columnConfigure->total_ton, 3) }}</td>
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
                        <td>৳ {{ $columnConfigure->total_common_bar_price }} Taka</td>
                    </tr>
                    <tr>
                        <th>Cement Price(Bag)</th>
                        <td>৳ {{ $columnConfigure->total_common_cement_bag_price }} Taka</td>
                    </tr>
                </table>
            </div>

            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Sands Price (Cft)</th>
                        <td>৳ {{ $columnConfigure->total_common_sands_price }} Taka</td>
                    </tr>
                    @if($columnConfigure->total_picked == 0)
                        <tr>
                            <th>Aggregate Price (Cft)</th>
                            <td>৳ {{ $columnConfigure->total_common_aggregate_price }} Taka</td>
                        </tr>
                    @else
                        <tr>
                            <th>Picked Price (Pcs)</th>
                            <td>৳ {{ $columnConfigure->total_common_picked_price }} Taka</td>
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
