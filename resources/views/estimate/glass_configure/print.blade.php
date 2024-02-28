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
            <div class="col-xs-8 text-center" style="margin-left: -118px;">
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
                        <th> Glass & Thai Configure No.</th>
                        <td>{{ $glassConfigure->grill_glass_tiles_configure_no }}</td>
                    </tr>
                    <tr>
                        <th> Glass & Thai Configure No</th>
                        <td>{{ $glassConfigure->floor_number }}</td>
                    </tr>
                    <tr>
                        <th> Glass & Thai Configure Date</th>
                        <td>{{ $glassConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th> Glass & Thai Configure Type Name</th>
                        <td><b>{{ $glassConfigure->configure_type }}</b></td>
                    </tr>
                    <tr>
                        <th>Note </th>
                        <td>{{ $glassConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Glass Info</th>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $glassConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor</th>
                        <td>{{ $glassConfigure->estimateFloor->name }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Floor Unit</th>
                        <td>{{ $glassConfigure->estimateFloorUnit->name }}</td>
                    </tr>
                    <tr>
                        <th>Total Glass Area(Single Floor)</th>
                        <td>{{ $glassConfigure->total_area_without_floor }}</td>
                    </tr>
                    <tr>
                        <th>Total Thai Rft</th>
                        <td>{{ $glassConfigure->total_rft_with_floor }}</td>
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
                        <th>Sub Total Rft</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalRft = 0;
                        @endphp
                    @foreach($glassConfigure->grillGlassTilesConfigureProducts as $product)
                        <tr>
                            <td>{{ $product->unitSection->name }}</td>
                            <td>{{ $product->length }}</td>
                            <td> {{ $product->height }}</td>
                            <td> {{ number_format($product->quantity, 2) }}</td>
                            <td> {{ number_format($product->sub_total_area, 2) }}</td>
                            <td> {{ number_format($product->sub_total_rft, 2) }}</td>
                        </tr>
                        @php
                            $totalRft += $product->sub_total_rft;
                        @endphp
                    @endforeach
                    </tbody>
                    <tr>
                        <th class="text-right" colspan="4">Single Floor Total</th>
                        <td> {{ number_format($glassConfigure->total_area_without_floor, 2) }} Sft</td>
                        <td> {{ number_format($totalRft, 2) }} Rft</td>
                      
                    </tr>
                    <tr>
                        <th class="text-right" colspan="4">All Floor Total</th>
                            <td> {{ number_format($glassConfigure->total_area_with_floor, 2) }} Sft</td>
                            <td> {{ number_format($totalRft, 2) }} Rft</td>
                      
                    </tr>
                    <tr>
                        <th class="text-right" colspan="4">Total Glass and Thai Cost</th>
                            <td>৳ {{ number_format($glassConfigure->total_grill_cost, 2) }} Taka</td>
                            <td>৳ {{ number_format($glassConfigure->total_thai_cost, 2) }} Taka</td>
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
