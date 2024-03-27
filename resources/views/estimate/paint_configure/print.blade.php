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
                        <th>Paint Category:</th>
                        <td>
                            @if($paintConfigure->main_paint_type == 1)
                                <b>Polish Work</b>
                            @elseif($paintConfigure->main_paint_type == 2)
                                <b>Inside Work</b>
                            @elseif($paintConfigure->main_paint_type == 3)
                                <b>Outside Weather Coad Work</b>
                            @elseif($paintConfigure->main_paint_type == 4)
                                <b>Putty Work</b>
                            @else
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Paint Configure Date</th>
                        <td>{{ $paintConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Paint Total Area:</th>
                        <td>
                            @if($paintConfigure->main_paint_type == 1)
                                <b>{{ $paintConfigure->total_polish_area }}</b>
                            @elseif($paintConfigure->main_paint_type == 2)
                                <b>{{ $paintConfigure->total_inside_area }}</b>
                            @elseif($paintConfigure->main_paint_type == 3)
                                <b>{{ $paintConfigure->total_outside_area }}</b>
                            @elseif($paintConfigure->main_paint_type == 4)
                                <b>{{ $paintConfigure->total_putty_area }}</b>
                            @else
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $paintConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Paint Info</th>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $paintConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor</th>
                        <td>{{ $paintConfigure->estimateFloor->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor Unit</th>
                        <td>{{ $paintConfigure->estimateFloorUnit->name }}</td>
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
                        <th>Paint Type</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Length</th>
                        <th>Height/Width</th>
                        <th>Side</th>
                        <th>Code Nos</th>
                        <th>Sub Total Deduction</th>
                        <th>Sub Total Area</th>
                        <th>Sub Total Paint</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($paintConfigure->main_paint_type == 1)
                        @foreach($paintConfigure->paintConfigureProducts as $product)
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

                            <td>
                                @if($product->polish_type == 1)
                                    Spirit
                                @elseif($product->polish_type == 2)
                                    Gala
                                @elseif($product->polish_type == 3)
                                    Markin Cloth
                                @elseif($product->polish_type == 4)
                                    120 Paper
                                @elseif($product->polish_type == 5)
                                    1.5 Paper
                                @elseif($product->polish_type == 6)
                                    Chalk Paper
                                @elseif($product->polish_type == 7)
                                    Candle
                                @elseif($product->polish_type == 8)
                                    Brown
                                @elseif($product->polish_type == 9)
                                    Sidur
                                @elseif($product->polish_type == 10)
                                    Elamati
                                @elseif($product->polish_type == 11)
                                    Zink Oxaid
                                @elseif($product->polish_type == 12)
                                    Woodkeeper
                                @elseif($product->polish_type == 13)
                                    T6 Thiner
                                @else
                                    NC Thiner
                                @endif
                            </td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ $product->side }}</td>
                            <td> {{ $product->code_nos }}</td>
                            <td> {{ number_format($product->sub_total_deduction, 2) }} Sft</td>
                            <td> {{ number_format($product->sub_total_area, 2) }} Sft</td>
                            <td> {{ number_format($product->sub_total_paint_liter, 2) }} Liter</td>
                        </tr>
                        @endforeach
                    @elseif ($paintConfigure->main_paint_type == 2)
                        @foreach($paintConfigure->paintConfigureProducts as $product)
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

                            <td>
                                @if($product->inside_type == 1)
                                    Plastic Paint
                                @elseif($product->inside_type == 2)
                                    Enamel
                                @elseif($product->inside_type == 3)
                                    Water Sealer
                                @else
                                    Snow Seen
                                @endif
                            </td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ $product->side }}</td>
                            <td> {{ $product->code_nos }}</td>
                            <td> {{ number_format($product->sub_total_deduction, 2) }} Sft</td>
                            <td> {{ number_format($product->sub_total_area, 2) }} Sft</td>
                            <td> {{ number_format($product->sub_total_paint_liter, 2) }} Liter</td>
                        </tr>
                        @endforeach
                    @elseif ($paintConfigure->main_paint_type == 3)
                        @foreach($paintConfigure->paintConfigureProducts as $product)
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

                            <td>
                                @if($product->outside_type == 1)
                                    Weather Coat
                                @elseif($product->outside_type == 2)
                                    Plastic Paint
                                @elseif($product->outside_type == 3)
                                    White Cement
                                @else
                                    120 no Paper
                                @endif
                            </td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ $product->side }}</td>
                            <td> {{ $product->code_nos }}</td>
                            <td> {{ number_format($product->sub_total_deduction, 2) }} Sft</td>
                            <td> {{ number_format($product->sub_total_area, 2) }} Sft</td>
                            <td> {{ number_format($product->sub_total_paint_liter, 2) }} Liter</td>
                        </tr>
                        @endforeach
                    @else
                        @foreach($paintConfigure->paintConfigureProducts as $product)
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

                            <td>
                                @if($product->putty_type == 1)
                                    Chack Powder
                                @elseif($product->putty_type == 2)
                                    Plastic Paint
                                @else
                                    Enamel Paint
                                @endif
                            </td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->length }}</td>
                            <td>{{ $product->height }}</td>
                            <td>{{ $product->side }}</td>
                            <td>{{ $product->code_nos }}</td>
                            <td>{{ number_format($product->sub_total_deduction, 2) }} Sft</td>
                            <td>{{ number_format($product->sub_total_area, 2) }} Sft</td>
                            <td>{{ number_format($product->sub_total_paint_liter, 2) }}</td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
                    {{-- <tr>
                        <th class="text-right" colspan="8">Single Floor Total</th>
                        <td> {{ number_format($paintConfigure->total_area_without_floor, 2) }} Sft</td>
                        <td> {{ number_format($paintConfigure->total_paint_liter_without_floor, 2) }} Liter</td>
                    </tr> --}}
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
