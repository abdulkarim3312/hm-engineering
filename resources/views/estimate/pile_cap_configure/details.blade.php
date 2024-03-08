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
    Pile Cap Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('pile_cap_configure.print', ['pileCapConfigure' => $pileCapConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                            <div class="col-xs-8 text-center" style="margin-left: -118px;">
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
                                        <th> Pile Cap Type.</th>
                                        <td>{{ $pileCapConfigure->footingType->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th> Pile Cap Configure Date</th>
                                        <td>{{ $pileCapConfigure->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project Name</th>
                                        <td>{{ $pileCapConfigure->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Costing Segment Name</th>
                                        <td>{{ $pileCapConfigure->costingSegment->name }}</td>
                                    </tr>
                                    @if ($pileCapConfigure->course_aggregate_type == 3)

                                    @elseif ($pileCapConfigure->course_aggregate_type == 2)
                                        <tr>
                                            <th>Total Cement</th>
                                            <td>{{ $pileCapConfigure->total_cement_bag }} Bag</td>
                                        </tr>
                                        <tr>
                                            <th>Local Sands</th>
                                            <td>{{ $pileCapConfigure->total_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Sylhet Sands</th>
                                            <td>{{ $pileCapConfigure->total_s_sands }} Cft</td>
                                        </tr>

                                    @else
                                        <tr>
                                            <th>Total Cement</th>
                                            <td>{{ $pileCapConfigure->total_cement_bag }} Bag</td>
                                        </tr>
                                        <tr>
                                            <th>Local Sands</th>
                                            <td>{{ $pileCapConfigure->total_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Sylhet Sands</th>
                                            <td>{{ $pileCapConfigure->total_s_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Aggregate</th>
                                            <td>{{ $pileCapConfigure->total_aggregate }} Cft</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $pileCapConfigure->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">{{$pileCapConfigure->costingSegment->name}} Info</th>
                                    </tr>
                                    <tr>
                                        <th>{{$pileCapConfigure->costingSegment->name}} Quantity</th>
                                        <td>{{ $pileCapConfigure->costing_segment_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Ton</th>
                                        <td>{{ $pileCapConfigure->total_ton }} Rod</td>
                                    </tr>
                                    <tr>
                                        <th>Total Kg</th>
                                        <td>{{ $pileCapConfigure->total_kg }} Rod</td>
                                    </tr>
                                    @if ($pileCapConfigure->course_aggregate_type == 2)
                                        <tr>
                                            <th>Total Piked</th>
                                            <td>{{ $pileCapConfigure->total_picked }} Pcs</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Pile Cap Length</th>
                                        <td>{{ $pileCapConfigure->segment_length }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pile Cap Width</th>
                                        <td>{{ $pileCapConfigure->segment_width }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pile Cap Thickness</th>
                                        <td>{{ $pileCapConfigure->segment_thickness }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Volume</th>
                                        <td>{{ $pileCapConfigure->total_volume }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Dry Volume</th>
                                        <td>{{ $pileCapConfigure->total_dry_volume }}</td>
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
                                    @foreach($pileCapConfigure->pileCapConfigureProducts as $product)

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
                                                        X-Direction
                                                    @else
                                                    Y-Direction
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
                                        <th colspan="11" class="text-right">Main Bar Total</th>
                                        <th> {{ number_format($mainTotalKg, 2) }}</th>
                                        <th> {{ number_format($mainTotalTon, 3) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <u><i><h2>Costing Area</h2></i></u>
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if ($pileCapConfigure->course_aggregate_type == 3)
                                        <tr>
                                            <th>Bar(Rod) Price (Kg)</th>
                                            <td>৳ {{ $pileCapConfigure->total_common_bar_price }} Taka</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Bar(Rod) Price (Kg)</th>
                                            <td>৳ {{ $pileCapConfigure->total_common_bar_price }} Taka</td>
                                        </tr>
                                        <tr>
                                            <th>Cement Price(Bag)</th>
                                            <td>৳ {{ number_format($pileCapConfigure->total_common_cement_bag_price,2) }} Taka</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if($pileCapConfigure->course_aggregate_type == 1)
                                    <tr>
                                        <th>Sands Price (Cft)</th>
                                        <td>৳ {{ $pileCapConfigure->total_common_sands_price }} Taka</td>
                                    </tr>
                                    <tr>
                                        <th>Aggregate Price (Cft)</th>
                                        <td>৳ {{ $pileCapConfigure->total_common_aggregate_price }} Taka</td>
                                    </tr>
                                    @elseif ($pileCapConfigure->course_aggregate_type == 2)
                                        <tr>
                                            <th>Sands Price (Cft)</th>
                                            <td>৳ {{ $pileCapConfigure->total_common_sands_price }} Taka</td>
                                        </tr>
                                        <tr>
                                            <th>Picked Price (Pcs)</th>
                                            <td>৳ {{ $pileCapConfigure->total_common_picked_price }} Taka</td>
                                        </tr>
                                    @else

                                    @endif
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if ($pileCapConfigure->course_aggregate_type == 3)
                                        <tr>
                                            <th>RMC Price (Cft)</th>
                                            <td>৳ {{ $pileCapConfigure->total_pile_cap_rmc_price }} Taka</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Sylhet Sands Price (Cft)</th>
                                            <td>৳ {{ $pileCapConfigure->total_beam_s_sands_price }} Taka</td>
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
