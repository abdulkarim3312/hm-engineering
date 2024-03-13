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
    Water Tank Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('water_tank_configure.print', ['waterTank' => $waterTank->id]) }}" class="btn btn-primary">Print</a>
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
                            <div class="col-xs-8 text-center" style="margin-left:-118px;">
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
                                        <th>Water Tank Configure No.</th>
                                        <td>{{ $waterTank->common_configure_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Water Tank Configure Date</th>
                                        <td>{{ $waterTank->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project Name</th>
                                        <td>{{ $waterTank->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Costing Segment Name</th>
                                        <td>{{ $waterTank->costingSegment->name }}</td>
                                    </tr>
                                    @if ($waterTank->course_aggregate_type == 3)

                                    @else
                                        <tr>
                                            <th>Total Cement(Cft)</th>
                                            <td>{{ $waterTank->total_cement }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Cement</th>
                                            <td>{{ $waterTank->total_cement_bag }} Bag</td>
                                        </tr>
                                        <tr>
                                            <th>Total Local Sands</th>
                                            <td>{{ $waterTank->total_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Sylhet Sands</th>
                                            <td>{{ $waterTank->total_s_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Aggregate</th>
                                            <td>{{ $waterTank->total_aggregate }} Cft</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $waterTank->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">{{$waterTank->costingSegment->name}} Info</th>
                                    </tr>
                                    <tr>
                                        <th>{{$waterTank->costingSegment->name}} Quantity</th>
                                        <td>{{ $waterTank->costing_segment_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Ton</th>
                                        <td>{{ $waterTank->total_ton }} Rod</td>
                                    </tr>
                                    <tr>
                                        <th>Total Kg</th>
                                        <td>{{ $waterTank->total_kg }} Rod</td>
                                    </tr>
                                   @if ($waterTank->course_aggregate_type == 2)
                                    <tr>
                                        <th>Total Piked</th>
                                        <td>{{ $waterTank->total_picked }} Pcs</td>
                                    </tr>
                                   @endif
                                    <tr>
                                        <th>Water Tank Length</th>
                                        <td>{{ $waterTank->slab_length }}</td>
                                    </tr>
                                    <tr>
                                        <th>Water Tank Width</th>
                                        <td>{{ $waterTank->slab_width }} </td>
                                    </tr>
                                    <tr>
                                        <th>Water Tank Thickness</th>
                                        <td>{{ $waterTank->slab_thickness }} </td>
                                    </tr>
                                    <tr>
                                        <th>Total Volume</th>
                                        <td>{{ $waterTank->total_volume }} </td>
                                    </tr>
                                    <tr>
                                        <th>Total Dry Volume</th>
                                        <td>{{ $waterTank->total_dry_volume }} </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        @php
                            $mainTotalKg = 0;
                            $mainTotalKgTwo = 0;
                            $mainTotalTon = 0;
                            $mainTotalTonTwo = 0;
                            $extraTotalKg = 0;
                            $extraTotalKgTwo = 0;
                            $extraTotalTon = 0;
                            $extraTotalTonTwo = 0;
                        @endphp

                        <div class="row">
                            <div class="col-md-12">
                                <h4>Main Bar Calculation For Slab</h4>
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
                                    @foreach($waterTank->waterTankConfigureProducts as $product)

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
                                                <td> {{ number_format($product->kg_by_rft, 3) }}</td>
                                                <td> {{ number_format($product->kg_by_ton, 3) }}</td>
                                                <td>
                                                    @if($product->length_type == 1)
                                                        X-Direction
                                                    @else
                                                    Y-Direction
                                                    @endif
                                                </td>
                                                <td> {{ number_format($product->length, 2) }}</td>
                                                <td> {{ number_format($product->spacing, 2) }}</td>
                                                <td> {{ number_format($product->type_length, 2) }}</td>
                                                <td> {{ number_format($product->layer, 2) }}</td>
                                                <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th colspan="11" class="text-right">Main Bar Total</th>
                                        <th> {{ number_format($mainTotalKg, 2) }}</th>
                                        <th> {{ number_format($mainTotalTon, 3) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Main Bar Calculation For Returing Wall</h4>
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
                                    @foreach($waterTank->waterTankConfigureProducts as $product)

                                        @if($product->status == 3)
                                            <?php
                                            $mainTotalKgTwo += $product->sub_total_kg;
                                            $mainTotalTonTwo += $product->sub_total_ton;
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
                                                <td> {{ number_format($product->kg_by_rft, 3) }}</td>
                                                <td> {{ number_format($product->kg_by_ton, 3) }}</td>
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
                                                <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th colspan="11" class="text-right">Main Bar Total</th>
                                        <th> {{ number_format($mainTotalKgTwo, 2) }}</th>
                                        <th> {{ number_format($mainTotalTonTwo, 3) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4>Extra Bar Calculation For Slab</h4>
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
                                    @foreach($waterTank->waterTankConfigureProducts as $product)

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
                                                <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th colspan="8" class="text-right" >Extra Bar Total</th>
                                        <td><b>{{ number_format($extraTotalKg, 2) }}</b></td>
                                        <td><b>{{ number_format($extraTotalTon, 3) }}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Extra Bar Calculation For Returning Wall</h4>
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
                                    @foreach($waterTank->waterTankConfigureProducts as $product)

                                        @if($product->status == 2)
                                            <?php
                                            $extraTotalKgTwo += $product->sub_total_kg;
                                            $extraTotalTonTwo += $product->sub_total_ton;
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
                                                <td> {{ number_format($product->sub_total_kg, 3) }}</td>
                                                <td> {{ number_format($product->sub_total_ton, 3) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th colspan="8" class="text-right" >Extra Bar Total</th>
                                        <td><b>{{ number_format($extraTotalKgTwo, 2) }}</b></td>
                                        <td><b>{{ number_format($extraTotalTonTwo, 3) }}</b></td>
                                    </tr>

                                    <tr>
                                        <th colspan="8" class="text-right" >Total Ton/KG</th>
                                        <td><b>{{ number_format($waterTank->total_kg, 3) }}</b></td>
                                        <td><b>{{ number_format($waterTank->total_ton, 3) }}</b></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <u><i><h2>Costing Area</h2></i></u>
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if ($waterTank->course_aggregate_type == 3)
                                        <tr>
                                            <th>Bar(Rod) Price (Kg)</th>
                                            <td>৳ {{ $waterTank->total_common_bar_price }} Taka</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Bar(Rod) Price (Kg)</th>
                                            <td>৳ {{ $waterTank->total_common_bar_price }} Taka</td>
                                        </tr>
                                        <tr>
                                            <th>Cement Price(Bag)</th>
                                            <td>৳ {{ number_format($waterTank->total_common_cement_bag_price,2) }} Taka</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if($waterTank->course_aggregate_type == 1)
                                    <tr>
                                        <th>Sands Price (Cft)</th>
                                        <td>৳ {{ $waterTank->total_common_sands_price }} Taka</td>
                                    </tr>
                                    <tr>
                                        <th>Aggregate Price (Cft)</th>
                                        <td>৳ {{ $waterTank->total_common_aggregate_price }} Taka</td>
                                    </tr>
                                    @elseif ($waterTank->course_aggregate_type == 2)
                                        <tr>
                                            <th>Sands Price (Cft)</th>
                                            <td>৳ {{ $waterTank->total_common_sands_price }} Taka</td>
                                        </tr>
                                        <tr>
                                            <th>Picked Price (Pcs)</th>
                                            <td>৳ {{ $waterTank->total_common_picked_price }} Taka</td>
                                        </tr>
                                    @else

                                    @endif
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if ($waterTank->course_aggregate_type == 3)
                                        <tr>
                                            <th>RMC Price (Cft)</th>
                                            <td>৳ {{ $waterTank->total_slab_rmc_price }} Taka</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Sylhet Sands Price (Cft)</th>
                                            <td>৳ {{ $waterTank->total_slab_s_sands_price }} Taka</td>
                                        </tr>
                                    @endif
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
