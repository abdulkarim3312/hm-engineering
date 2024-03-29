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
            <div class="col-xs-8 text-center" style="margin-left: -123px;">
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
                        <th>Facing Bricks Date</th>
                        <td>{{ $facingBricks->date }}</td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $facingBricks->note??'' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Facing Info</th>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $facingBricks->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor</th>
                        <td>{{ $facingBricks->estimateFloor->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor Unit</th>
                        <td>{{ $facingBricks->estimateFloorUnit->name }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Unit Section Name</th>
                        <th>Wall Direction</th>
                        <th>Facing Work</th>
                        <th>Length</th>
                        <th>Height/Width</th>
                        <th>Side</th>
                        <th>Sub Total Deduction</th>
                        <th>Sub Total Area</th>
                        <th>Sub Total</th>
                        <th>Total Cost</th>
                    </tr>
                    </thead>
                    @php
                        $total = 0;
                        $totalBrick = 0;
                        $totalSilicon = 0;
                    @endphp
                    <tbody>
                    @foreach($facingBricks->facingBricksProducts as $product)
                        <tr>
                            <td>{{ $product->unitSection->name ?? ''}}</td>
                            <td>
                                @if($product->wall_direction == 1)
                                    East
                                @elseif($product->wall_direction == 2)
                                    West
                                @elseif($product->wall_direction == 3)
                                    North
                                @elseif($product->wall_direction == 4)
                                    South
                                @else
                                @endif
                            </td>

                            <td>
                                @if($product->facing_brick == 1)
                                    Facing Bricks
                                @else
                                    Silicon
                                @endif
                            </td>
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ $product->side }}</td>
                            <td> {{ $product->sub_total_deduction }}</td>
                            <td> {{ number_format($product->sub_total_area, 2) }} Sft</td>
                            @if($product->facing_brick == 1)
                                <td> {{ number_format($product->sub_total_bricks, 2) }} Nos</td>
                            @endif
                            @if($product->facing_brick == 2)
                                <td> {{ number_format($product->sub_total_bricks, 2) }} Galon</td>
                            @endif
                            <td> {{ number_format($product->total_bricks_cost, 2) }} Tk</td>
                        </tr>
                        @php
                             $total += $product->sub_total_area;
                             $totalBrick += $product->sub_total_bricks;
                             $totalSilicon += $product->total_bricks_cost;
                        @endphp
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th colspan="5">Total</th>
                        <td>{{ $total }}</td>
                        <td>{{ $totalBrick }}</td>
                        <td>{{ $totalSilicon }} Tk</td>
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
