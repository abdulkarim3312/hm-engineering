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
   Short Column Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('short_column_configure.print', ['shortColumnConfigure' => $shortColumnConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                            <div class="col-xs-8 text-center" style="margin-left: -128px;">
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
                                        <th>Short Column Configure No.</th>
                                        <td>{{ $shortColumnConfigure->column_configure_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Short Column Configure Date</th>
                                        <td>{{ $shortColumnConfigure->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project</th>
                                        <td>{{ $shortColumnConfigure->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Ton</th>
                                        <td>{{ $shortColumnConfigure->total_ton }} Rod</td>
                                    </tr>
                                    <tr>
                                        <th>Total Kg</th>
                                        <td>{{ $shortColumnConfigure->total_kg }} Rod</td>
                                    </tr>
                                    @if ($shortColumnConfigure->course_aggregate_type == 1)
                                        <tr>
                                            <th>Total Cement</th>
                                            <td>{{ $shortColumnConfigure->total_cement_bag }} Bag</td>
                                        </tr>
                                        <tr>
                                            <th>Total Local Sands</th>
                                            <td>{{ $shortColumnConfigure->total_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Sylhet Sands</th>
                                            <td>{{ $shortColumnConfigure->total_s_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Aggregate</th>
                                            <td>{{ $shortColumnConfigure->total_aggregate }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Piked</th>
                                            <td>{{ $shortColumnConfigure->total_picked }} Pcs</td>
                                        </tr>
                                    @elseif ($shortColumnConfigure->course_aggregate_type == 2)
                                        <tr>
                                            <th>Total Cement</th>
                                            <td>{{ $shortColumnConfigure->total_cement_bag }} Bag</td>
                                        </tr>
                                        <tr>
                                            <th>Total Local Sands</th>
                                            <td>{{ $shortColumnConfigure->total_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Sylhet Sands</th>
                                            <td>{{ $shortColumnConfigure->total_s_sands }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Aggregate</th>
                                            <td>{{ $shortColumnConfigure->total_aggregate }} Cft</td>
                                        </tr>
                                        <tr>
                                            <th>Total Piked</th>
                                            <td>{{ $shortColumnConfigure->total_picked }} Pcs</td>
                                        </tr>
                                    @else    
                                    @endif
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $shortColumnConfigure->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Column Info</th>
                                    </tr>
                                    <tr>
                                        <th>Total Ring</th>
                                        <td>{{ $shortColumnConfigure->ring_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tie Interval</th>
                                        <td>{{ $shortColumnConfigure->tie_interval }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ratio</th>
                                        <td>{{ $shortColumnConfigure->first_ratio }}:{{ $shortColumnConfigure->second_ratio }}:{{ $shortColumnConfigure->third_ratio }}</td>
                                    </tr>
                                    <tr>
                                        <th>Short Column Height</th>
                                        <td>{{ $shortColumnConfigure->column_length }}</td>
                                    </tr>
                                    <tr>
                                        <th>Short Column Length</th>
                                        <td>{{ $shortColumnConfigure->tie_length_volume }}</td>
                                    </tr>
                                    <tr>
                                        <th>Column Width</th>
                                        <td>{{ $shortColumnConfigure->tie_width_volume }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Volume</th>
                                        <td>{{ $shortColumnConfigure->total_volume }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Dry Volume</th>
                                        <td>{{ $shortColumnConfigure->total_dry_volume }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @php
                        $tieTotalKg = 0;
                        $tieTotalTon = 0;
                        $straightTotalKg = 0;
                        $straightTotalTon = 0;
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
                                    <h4>Main Rod Description</h4>
                                    @foreach($shortColumnConfigure->shortColumnConfigureProducts as $product)
                                        @if($product->bar_type != null)


                                        <?php
                                        $straightTotalKg += $product->sub_total_kg_straight;
                                        $straightTotalTon += $product->sub_total_ton_straight;
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
                                                <td> {{ number_format($product->sub_total_kg_straight, 2) }}</td>
                                                <td> {{ number_format($product->sub_total_ton_straight, 3) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th colspan="6" class="text-right">Sub Total</th>
                                        <th> {{ number_format($straightTotalKg, 2) }}</th>
                                        <th> {{ number_format($straightTotalTon, 3) }}</th>
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
                                    <h4>Tie Rod Description</h4>
                                    @foreach($shortColumnConfigure->shortColumnConfigureProducts as $product)
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
                                                <td> {{ number_format($product->sub_total_kg_tie, 2) }}</td>
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
                                        <th> {{ number_format($shortColumnConfigure->total_kg, 2) }}</th>
                                        <th> {{ number_format($shortColumnConfigure->total_ton, 3) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <u><i><h2>Costing Area</h2></i></u>
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if ($shortColumnConfigure->course_aggregate_type == 3)
                                        <tr>
                                            <th>Bar(Rod) Price (Kg)</th>
                                            <td>৳ {{ $shortColumnConfigure->total_column_bar_price }} Taka</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th>Bar(Rod) Price (Kg)</th>
                                            <td>৳ {{ $shortColumnConfigure->total_column_bar_price }} Taka</td>
                                        </tr>
                                        <tr>
                                            <th>Cement Price(Bag)</th>
                                            <td>৳ {{ number_format($shortColumnConfigure->total_column_cement_bag_price,2) }} Taka</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if($shortColumnConfigure->course_aggregate_type == 1)
                                    <tr>
                                        <th>Sands Price (Cft)</th>
                                        <td>৳ {{ $shortColumnConfigure->total_column_sands_price }} Taka</td>
                                    </tr>
                                    <tr>
                                        <th>Aggregate Price (Cft)</th>
                                        <td>৳ {{ $shortColumnConfigure->total_column_aggregate_price }} Taka</td>
                                    </tr>
                                    @elseif ($shortColumnConfigure->course_aggregate_type == 2)
                                        <tr>
                                            <th>Sands Price (Cft)</th>
                                            <td>৳ {{ $shortColumnConfigure->total_column_sands_price }} Taka</td>
                                        </tr>
                                        <tr>
                                            <th>Picked Price (Pcs)</th>
                                            <td>৳ {{ $shortColumnConfigure->total_column_picked_price }} Taka</td>
                                        </tr>
                                    @else

                                    @endif
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    @if ($shortColumnConfigure->course_aggregate_type == 3)
                                    <tr>
                                        <th>RMC Price (Cft)</th>
                                        <td>৳ {{ $shortColumnConfigure->total_short_column_rmc_price }} Taka</td>
                                    </tr>
                                    @else
                                        <tr>
                                            <th>Sylhet Sands Price (Cft)</th>
                                            <td>৳ {{ $shortColumnConfigure->total_column_s_sands_price }} Taka</td>
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
