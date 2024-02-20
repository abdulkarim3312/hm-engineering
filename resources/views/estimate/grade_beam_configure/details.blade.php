@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .bottom-title{
            border-top: 1.5px solid #4f56be;
            text-align: center;
        }
        .logo i h2 {
            margin-left: 103px;
            font-weight: bold;
            color: #4f56be;
            font-family: Monotype Corsiva;
            font-size: 50px;
        }

        .logo p {
            margin-left: 97px;
            margin-top: -20px;
            color: #f5f507;
            font-family: Monotype Corsiva;
            font-size: 19px;
        }
        .logo hr {
            margin-top: -18px;
            margin-bottom: 20px;
            border: 0;
            border-top: 4px solid #c41702;
            width: 156px;
            margin-right: 89px;
            border-radius: 1px;
        }
        .img-overlay {
            position: absolute;
            left: 0;
            top: 150px;
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
@endsection

@section('title')
   Grade Beam Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('grade_beam_configure.print', ['gradeBeamConfigure' => $gradeBeamConfigure->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>
                    <div id="prinarea">
                        <div class="img-overlay">
                            <img src="{{ asset('img/logo.png') }}">
                        </div>
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
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Grade Beam Configure No.</th>
                                        <td>{{ $gradeBeamConfigure->beam_configure_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Grade Beam Configure Date</th>
                                        <td>{{ $gradeBeamConfigure->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project</th>
                                        <td>{{ $gradeBeamConfigure->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Ton</th>
                                        <td>{{ $gradeBeamConfigure->total_ton }} Rod</td>
                                    </tr>
                                    <tr>
                                        <th>Total Kg</th>
                                        <td>{{ $gradeBeamConfigure->total_kg }} Rod</td>
                                    </tr>
                                    <tr>
                                        <th>Total Cement</th>
                                        <td>{{ $gradeBeamConfigure->total_cement_bag }} Bag</td>
                                    </tr>
                                    <tr>
                                        <th>Total Local Sands</th>
                                        <td>{{ $gradeBeamConfigure->total_sands }} Cft</td>
                                    </tr>
                                    <tr>
                                        <th>Total Sylhet Sands</th>
                                        <td>{{ $gradeBeamConfigure->total_s_sands }} Cft</td>
                                    </tr>
                                    <tr>
                                        <th>Total Aggregate</th>
                                        <td>{{ $gradeBeamConfigure->total_aggregate }} Cft</td>
                                    </tr>
                                    <tr>
                                        <th>Total Piked</th>
                                        <td>{{ $gradeBeamConfigure->total_picked }} Pcs</td>
                                    </tr>
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $gradeBeamConfigure->note??'' }}</td>
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
                                        <td>{{ $gradeBeamConfigure->tie_bar }}</td>
                                    </tr>
                                    <tr>
                                        <th>Stirrup Interval</th>
                                        <td>{{ $gradeBeamConfigure->tie_interval }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ratio</th>
                                        <td>{{ $gradeBeamConfigure->first_ratio }}:{{ $gradeBeamConfigure->second_ratio }}:{{ $gradeBeamConfigure->third_ratio }}</td>
                                    </tr>
                                    <tr>
                                        <th>Height</th>
                                        <td>{{ $gradeBeamConfigure->beam_length }}</td>
                                    </tr>
                                    <tr>
                                        <th>Grade Beam Height</th>
                                        <td>{{ $gradeBeamConfigure->grade_beam_length }}</td>
                                    </tr>
                                    <tr>
                                        <th>Grade Beam Width</th>
                                        <td>{{ $gradeBeamConfigure->grade_beam_width }}</td>
                                    </tr>
                                    <tr>
                                        <th>Grade Beam Quantity</th>
                                        <td>{{ $gradeBeamConfigure->beam_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Volume</th>
                                        <td>{{ $gradeBeamConfigure->total_volume }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Dry Volume</th>
                                        <td>{{ $gradeBeamConfigure->total_dry_volume }}</td>
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
                                    @foreach($gradeBeamConfigure->gradeBeamConfigureProducts as $product)
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
                                    @foreach($gradeBeamConfigure->gradeBeamConfigureProducts as $product)
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
                                    @foreach($gradeBeamConfigure->gradeBeamConfigureProducts as $product)
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
                                        <th> {{ number_format($gradeBeamConfigure->total_kg, 3) }}</th>
                                        <th> {{ number_format($gradeBeamConfigure->total_ton, 3) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <u><i><h2>Costing Area</h2></i></u>
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Bar(Rod) Price (Kg)</th>
                                        <td>৳ {{ $gradeBeamConfigure->total_beam_bar_price }} Taka</td>
                                    </tr>
                                    <tr>
                                        <th>Cement Price(Bag)</th>
                                        <td>৳ {{ $gradeBeamConfigure->total_beam_cement_bag_price }} Taka</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Local Sands Price (Cft)</th>
                                        <td>৳ {{ $gradeBeamConfigure->total_beam_sands_price }} Taka</td>
                                    </tr>
                                    @if($gradeBeamConfigure->total_picked == 0)
                                        <tr>
                                            <th>Aggregate Price (Cft)</th>
                                            <td>৳ {{ $gradeBeamConfigure->total_beam_aggregate_price }} Taka</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Picked Price (Pcs)</th>
                                            <td>৳ {{ $gradeBeamConfigure->total_beam_picked_price }} Taka</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Sylhet Sands Price (Cft)</th>
                                        <td>৳ {{ $gradeBeamConfigure->total_beam_s_sands_price }} Taka</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": [[ 0, "desc" ]],
            });
        });
    </script>
    <script>
        var APP_URL = '{!! url()->full()  !!}';
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            //window.location.replace(APP_URL)
        }
    </script>
@endsection
