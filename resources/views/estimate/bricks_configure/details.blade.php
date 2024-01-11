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
   Bricks Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('bricks_configure.print', ['bricksConfigure' => $bricksConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                            <div class="col-xs-8 text-center">
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
