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
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>Plaster Configure No.</th>
                        <td>{{ $plasterConfigure->plaster_configure_no }}</td>
                    </tr>
                    <tr>
                        <th>Plaster Configure Date</th>
                        <td>{{ $plasterConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $plasterConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Plaster Info</th>
                    </tr>
                    <tr>
                        <th>Ratio</th>
                        <td>{{ $plasterConfigure->first_ratio }}:{{ $plasterConfigure->second_ratio }}</td>
                    </tr>
                    <tr>
                        <th>Total Cement</th>
                        <td>{{ $plasterConfigure->total_cement }} Cft</td>
                    </tr>
                    <tr>
                        <th>Total Cement Bag</th>
                        <td>{{ $plasterConfigure->total_cement_bag }} Bag</td>
                    </tr>
                    <tr>
                        <th>Total Sands</th>
                        <td>{{ $plasterConfigure->total_sands }} Cft</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Floor</th>
                        <th>Unit</th>
                        <th>Unit Section</th>
                        <th>Plaster Wall Side</th>
                        <th>Plaster Side</th>
                        <th>Plaster Thickness</th>
                        <th>Sub Total Area</th>
                        <th>Sub Total Cement</th>
                        <th>Sub Total Sands</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($plasterConfigure->plasterConfigureProducts as $product)
                        <tr>
                            <td>{{ $product->bricksConfigureProduct->project->name }}</td>
                            <td>{{ $product->bricksConfigureProduct->estimateFloor->name }}</td>
                            <td>{{ $product->bricksConfigureProduct->estimateFloorUnit->name }}</td>
                            <td>{{ $product->bricksConfigureProduct->unitSection->plaster_area }}</td>
                            <td>
                                @if($product->bricksConfigureProduct->wall_direction == 1)
                                    East
                                @elseif($product->bricksConfigureProduct->wall_direction == 2)
                                    West
                                @elseif($product->bricksConfigureProduct->wall_direction == 3)
                                    North
                                @elseif($product->bricksConfigureProduct->wall_direction == 4)
                                    South
                                @else
                                @endif
                            </td>
                            <td> {{ $product->plaster_side }}</td>
                            <td> {{ $product->plaster_thickness }}</td>
                            <td> {{ number_format($product->sub_total_dry_area, 2) }}</td>
                            <td> {{ number_format($product->sub_total_cement, 2) }}</td>
                            <td> {{ number_format($product->sub_total_sands, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <th class="text-right" colspan="7">Total</th>
                        <td> {{ number_format($plasterConfigure->total_plaster_area, 2) }} Cft</td>
                        <td> {{ number_format($plasterConfigure->total_cement, 2) }} Cft</td>
                        <td> {{ number_format($plasterConfigure->total_sands, 2) }} Cft</td>
                    </tr>
                </table>
            </div>
        </div>

        <u><i><h2>Costing Area</h2></i></u>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-center">Cement Price(Bag)</th>
                        <th class="text-center">Sands Price (Cft)</th>
                    </tr>
                    <tr>
                        <td class="text-center">৳ {{ number_format($plasterConfigure->total_plaster_cement_cost,2) }} Taka</td>
                        <td class="text-center">৳ {{ $plasterConfigure->total_plaster_sands_cost }} Taka</td>
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

</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
