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
                        <th>Bricks Configure No.</th>
                        <td>{{ $bricksConfigure->bricks_configure_no }}</td>
                    </tr>
                    <tr>
                        <th>Bricks Configure Date</th>
                        <td>{{ $bricksConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Floor Quantity</th>
                        <td>{{ $bricksConfigure->floor_number }}</td>
                    </tr>
                    <tr>
                        <th>Total Cement</th>
                        <td>{{ $bricksConfigure->total_cement }} Cft</td>
                    </tr>
                    <tr>
                        <th>Total Cement</th>
                        <td>{{ $bricksConfigure->total_cement_bag }} Bag</td>
                    </tr>
                    <tr>
                        <th>Total Sands</th>
                        <td>{{ $bricksConfigure->total_sands }} Cft</td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $bricksConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Bricks Info</th>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $bricksConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor</th>
                        <td>{{ $bricksConfigure->estimateFloor->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor Unit</th>
                        <td>{{ $bricksConfigure->estimateFloorUnit->name }}</td>
                    </tr>
                    <tr>
                        <th>Ratio</th>
                        <td>{{ $bricksConfigure->first_ratio }}:{{ $bricksConfigure->second_ratio }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Unit Section</th>
                        <th>Wall Direction</th>
                        <th>Length</th>
                        <th>Height</th>
                        <th>Wall Number</th>
                        <th>Total Deduction</th>
                        <th>Sub Total Area</th>
                        <th>Sub Total Bricks</th>
                        <th>Sub Total Morters</th>
                        <th>Sub Total Cement</th>
                        <th>Sub Total Sands</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bricksConfigure->bricksConfigureProducts as $product)
                        <tr>
                            <td>{{ $product->unitSection->name }}</td>
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
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ number_format($product->wall_number, 2) }}</td>
                            <td> {{ number_format($product->sub_total_deduction, 2) }}</td>
                            <td> {{ number_format($product->sub_total_area, 2) }}</td>
                            <td> {{ number_format($product->sub_total_bricks, 2) }}</td>
                            <td> {{ number_format($product->sub_total_morters, 2) }}</td>
                            <td> {{ number_format($product->sub_total_cement, 2) }}</td>
                            <td> {{ number_format($product->sub_total_sands, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <th class="text-right" colspan="7">Total</th>
                        <td> {{ number_format($bricksConfigure->total_bricks, 2) }} Pcs</td>
                        <td> {{ number_format($bricksConfigure->total_morters, 2) }} Cft</td>
                        <td> {{ number_format($bricksConfigure->total_cement, 2) }} Cft</td>
                        <td> {{ number_format($bricksConfigure->total_sands, 2) }} Cft</td>
                    </tr>
                </table>
            </div>
        </div>

        <u><i><h2>Costing Area</h2></i></u>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-center">Bricks Price (Pcs)</th>
                        <th class="text-center">Cement Price(Bag)</th>
                        <th class="text-center">Sands Price (Cft)</th>
                    </tr>
                    <tr>
                        <td class="text-center">৳ {{ $bricksConfigure->total_bricks_cost }} Taka</td>
                        <td class="text-center">৳ {{ number_format($bricksConfigure->total_bricks_cement_cost,2) }} Taka</td>
                        <td class="text-center">৳ {{ $bricksConfigure->total_bricks_sands_cost }} Taka</td>
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
