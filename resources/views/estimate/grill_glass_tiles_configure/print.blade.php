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
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th>@if($grillGlassTilesConfigure->configure_type == 1)
                                Grill Configure No.
                            @elseif($grillGlassTilesConfigure->configure_type == 2)
                                Glass Configure No.
                            @else
                                Tiles Configure No.
                            @endif

                        </th>
                        <td>{{ $grillGlassTilesConfigure->grill_glass_tiles_configure_no }}</td>
                    </tr>
                    <tr>
                        <th>@if($grillGlassTilesConfigure->configure_type == 1)
                                Grill Configure Date
                            @elseif($grillGlassTilesConfigure->configure_type == 2)
                                Glass Configure Date
                            @else
                                Tiles Configure Date
                            @endif

                        </th>
                        <td>{{ $grillGlassTilesConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $grillGlassTilesConfigure->note??'' }}</td>
                    </tr>
                    <th>Gril Information: </th>
                    <td>@if($grillGlassTilesConfigure->configure_type == 1)
                         <span class="label label-success">MS</span>
                    @elseif($grillGlassTilesConfigure->configure_type == 2)
                         <span class="label label-success">SS</span>
                    @else
                        Tiles Info
                    @endif</td>
                </table>
            </div>

            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">
                            @if($grillGlassTilesConfigure->configure_type == 1)
                                Grill Info
                            @elseif($grillGlassTilesConfigure->configure_type == 2)
                                Glass Info
                            @else
                                Tiles Info
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $grillGlassTilesConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor</th>
                        <td>{{ $grillGlassTilesConfigure->estimateFloor->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor Unit</th>
                        <td>{{ $grillGlassTilesConfigure->estimateFloorUnit->name }}</td>
                    </tr>
                    <tr>
                        <th>
                            @if($grillGlassTilesConfigure->configure_type == 1)
                                Total Grill KG(Single Floor)
                            @elseif($grillGlassTilesConfigure->configure_type == 2)
                                Total Glass Area(Single Floor)
                            @else
                                Total Tiles Area(Single Floor)
                            @endif
                        </th>
                        <td>{{ $grillGlassTilesConfigure->total_area_without_floor }}</td>
                    </tr>
                    <tr>
                        <th>
                            @if($grillGlassTilesConfigure->configure_type == 1)
                                Total Grill KG(All Floor)
                            @elseif($grillGlassTilesConfigure->configure_type == 2)
                                Total Glass Area(All Floor)
                            @else
                                Total Tiles Area(All Floor)
                            @endif
                        </th>
                        <td>{{ $grillGlassTilesConfigure->total_area_with_floor }}</td>
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
                        <th>Length</th>
                        <th>Height/Width</th>
                        <th>Quantity</th>
                        <th>Sub Total Area</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($grillGlassTilesConfigure->grillGlassTilesConfigureProducts as $product)
                        <tr>
                            <td>{{ $product->unitSection->name }}</td>
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ number_format($product->quantity, 2) }}</td>
                            <td> {{ number_format($product->sub_total_area, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
                        <th class="text-right" colspan="4">Single Floor Total</th>
                        @if($grillGlassTilesConfigure->configure_type == 1)
                            <td> {{ number_format($grillGlassTilesConfigure->total_area_without_floor, 2) }} KG</td>
                        @else
                            <td> {{ number_format($grillGlassTilesConfigure->total_area_without_floor, 2) }} Area</td>
                        @endif
                    </tr>
                    <tr>
                        <th class="text-right" colspan="4">All Floor Total</th>
                        @if($grillGlassTilesConfigure->configure_type == 1)
                            <td> {{ number_format($grillGlassTilesConfigure->total_area_with_floor, 2) }} KG</td>
                        @else
                            <td> {{ number_format($grillGlassTilesConfigure->total_area_with_floor, 2) }} Area</td>
                        @endif
                    </tr>
                    <tr>
                        <th class="text-right" colspan="4">Total Cost</th>
                        @if($grillGlassTilesConfigure->configure_type == 1)
                            <td>৳ {{ number_format($grillGlassTilesConfigure->total_grill_cost, 2) }} Taka</td>
                        @else
                            <td>৳ {{ number_format($grillGlassTilesConfigure->total_tiles_glass_cost, 2) }} Taka</td>
                        @endif
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
