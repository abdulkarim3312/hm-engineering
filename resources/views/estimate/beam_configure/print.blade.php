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
                        <th>Beam Configure No.</th>
                        <td>{{ $beamConfigure->beam_configure_no }}</td>
                    </tr>
                    <tr>
                        <th>Beam Configure Date</th>
                        <td>{{ $beamConfigure->date }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Project</th>
                        <td>{{ $beamConfigure->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Total Ton</th>
                        <td>{{ $beamConfigure->total_ton }} Rod</td>
                    </tr>
                    <tr>
                        <th>Total Kg</th>
                        <td>{{ $beamConfigure->total_kg }} Rod</td>
                    </tr>
                    @if ($beamConfigure->course_aggregate_type == 1)
                        <tr>
                            <th>Total Cement</th>
                            <td>{{ $beamConfigure->total_cement_bag }} Bag</td>
                        </tr>
                        <tr>
                            <th>Total Local Sands</th>
                            <td>{{ $beamConfigure->total_sands }} Cft</td>
                        </tr>
                        <tr>
                            <th>Total Sylhet Sands</th>
                            <td>{{ $beamConfigure->total_s_sands }} Cft</td>
                        </tr>
                        <tr>
                            <th>Total Aggregate</th>
                            <td>{{ $beamConfigure->total_aggregate }} Cft</td>
                        </tr>
                    @elseif ($beamConfigure->course_aggregate_type == 2)
                        <tr>
                            <th>Total Cement</th>
                            <td>{{ $beamConfigure->total_cement_bag }} Bag</td>
                        </tr>
                        <tr>
                            <th>Total Local Sands</th>
                            <td>{{ $beamConfigure->total_sands }} Cft</td>
                        </tr>
                        <tr>
                            <th>Total Sylhet Sands</th>
                            <td>{{ $beamConfigure->total_s_sands }} Cft</td>
                        </tr>
                        <tr>
                            <th>Total Piked</th>
                            <td>{{ $beamConfigure->total_picked }} Pcs</td>
                        </tr>
                    @else    
                    @endif
                    <tr>
                        <th>Note </th>
                        <td>{{ $beamConfigure->note??'' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2" class="text-center">Beam Info</th>
                    </tr>
                    <tr>
                        <th>Stirrup Bar</th>
                        <td>{{ $beamConfigure->tie_bar }}</td>
                    </tr>
                    <tr>
                        <th>Stirrup Interval</th>
                        <td>{{ $beamConfigure->tie_interval }}</td>
                    </tr>
                    <tr>
                        <th>Ratio</th>
                        <td>{{ $beamConfigure->first_ratio }}:{{ $beamConfigure->second_ratio }}:{{ $beamConfigure->third_ratio }}</td>
                    </tr>
                    <tr>
                        <th>Height</th>
                        <td>{{ $beamConfigure->beam_length }}</td>
                    </tr>
                    <tr>
                        <th>Beam Height</th>
                        <td>{{ $beamConfigure->grade_beam_length }}</td>
                    </tr>
                    <tr>
                        <th>Beam Width</th>
                        <td>{{ $beamConfigure->grade_beam_width }}</td>
                    </tr>
                    <tr>
                        <th>Beam Quantity</th>
                        <td>{{ $beamConfigure->beam_quantity }}</td>
                    </tr>
                    <tr>
                        <th>Total Volume</th>
                        <td>{{ $beamConfigure->total_volume }}</td>
                    </tr>
                    <tr>
                        <th>Total Dry Volume</th>
                        <td>{{ $beamConfigure->total_dry_volume }}</td>
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
                    <h4>Main Bar Description</h4>
                    @foreach($beamConfigure->beamConfigureProducts as $product)
                        @if($product->status == 2)
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
                            <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                            <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                    <tr>
                        <th colspan="6" class="text-right">Main Bar Total</th>
                        <th> {{ number_format($mainTotalKg, 3) }}</th>
                        <th> {{ number_format($mainTotalTon, 3) }}</th>
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
                    @foreach($beamConfigure->beamConfigureProducts as $product)
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
                                <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>

                    <tr>
                        <th colspan="6" class="text-right" >Extra Bar Total</th>
                        <td> {{ number_format($extraTotalKg, 3) }}</td>
                        <td> {{ number_format($extraTotalTon, 3) }}</td>
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
                        @php
                             $tieTotalKg = 0;
                             $tieTotalTon = 0;
                        @endphp
                    <h4>Tie Rod Description</h4>
                    @foreach($beamConfigure->beamConfigureProducts as $product)
                        @if($product->tie_bar_type != null )

                            <?php
                            $tieTotalKg += $product->sub_total_kg_tie;
                            $tieTotalTon += $product->sub_total_ton_tie;
                            ?>
                            <tr>
                                <td>
                                    @if($product->tie_bar_type == 6)
                                        6mm
                                    @elseif($product->tie_bar_type == 8)
                                        8mm
                                    @elseif($product->tie_bar_type == 10)
                                        10mm
                                    @elseif($product->tie_bar_type == 12)
                                        12mm
                                    @elseif($product->tie_bar_type == 16)
                                        16mm
                                    @elseif($product->tie_bar_type == 18)
                                        18mm
                                    @elseif($product->tie_bar_type == 20)
                                        20mm
                                    @elseif($product->tie_bar_type == 22)
                                        22mm
                                    @elseif($product->tie_bar_type == 25)
                                        25mm
                                    @elseif($product->tie_bar_type == 28)
                                        28mm
                                    @elseif($product->tie_bar_type == 32)
                                        32mm
                                    @elseif($product->tie_bar_type == 36)
                                        36mm
                                    @endif

                                </td>
                                <td>{{ $product->tie_dia }}</td>
                                <td> {{ $product->tie_dia_square }}</td>
                                <td> {{ number_format($product->tie_value_of_bar, 2) }}</td>
                                <td> {{ number_format($product->tie_kg_by_rft, 2) }}</td>
                                <td> {{ number_format($product->tie_kg_by_ton, 2) }}</td>
                                <td> {{ number_format($product->sub_total_kg_tie, 3) }}</td>
                                <td> {{ number_format($product->sub_total_ton_tie, 3) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>

                    <tr>
                        <th colspan="6" class="text-right">Sub Total</th>
                        <th> {{ number_format($tieTotalKg, 2) }}</th>
                        <th> {{ number_format($tieTotalTon, 3) }}</th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">Total Ton/KG</th>
                        <th> {{ number_format($beamConfigure->total_kg, 3) }}</th>
                        <th> {{ number_format($beamConfigure->total_ton, 3) }}</th>
                    </tr>
                </table>
            </div>
        </div>

        <u><i><h2>Costing Area</h2></i></u>
        <div class="row">
            <div class="col-md-4">
                <table class="table table-bordered">
                    @if ($beamConfigure->course_aggregate_type == 3)
                        <tr>
                            <th>Bar(Rod) Price (Kg)</th>
                            <td>৳ {{ $beamConfigure->total_beam_bar_price }} Taka</td>
                        </tr>
                    @else
                        <tr>
                            <th>Bar(Rod) Price (Kg)</th>
                            <td>৳ {{ $beamConfigure->total_beam_bar_price }} Taka</td>
                        </tr>
                        <tr>
                            <th>Cement Price(Bag)</th>
                            <td>৳ {{ number_format($beamConfigure->total_beam_cement_bag_price,2) }} Taka</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="col-md-4">
                <table class="table table-bordered">
                    @if($beamConfigure->course_aggregate_type == 1)
                    <tr>
                        <th>Sands Price (Cft)</th>
                        <td>৳ {{ $beamConfigure->total_beam_sands_price }} Taka</td>
                    </tr>
                    <tr>
                        <th>Aggregate Price (Cft)</th>
                        <td>৳ {{ $beamConfigure->total_beam_aggregate_price }} Taka</td>
                    </tr>
                    @elseif ($beamConfigure->course_aggregate_type == 2)
                        <tr>
                            <th>Sands Price (Cft)</th>
                            <td>৳ {{ $beamConfigure->total_beam_sands_price }} Taka</td>
                        </tr>
                        <tr>
                            <th>Picked Price (Pcs)</th>
                            <td>৳ {{ $beamConfigure->total_beam_picked_price }} Taka</td>
                        </tr>
                    @else

                    @endif
                </table>
            </div>
            <div class="col-md-4">
                <table class="table table-bordered">
                    @if ($beamConfigure->course_aggregate_type == 3)
                        <tr>
                            <th>RMC Price (Cft)</th>
                            <td>৳ {{ $beamConfigure->total_beam_rmc_price }} Taka</td>
                        </tr>
                    @else
                        <tr>
                            <th>Sylhet Sands Price (Cft)</th>
                            <td>৳ {{ $beamConfigure->total_beam_s_sands_price }} Taka</td>
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
