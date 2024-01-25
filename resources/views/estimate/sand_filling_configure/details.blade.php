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
   Plaster Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('plaster_configure.print', ['plasterConfigure' => $plasterConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                                        <th>Plaster Configure No.</th>
                                        <td>{{ $plasterConfigure->plaster_configure_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Plaster Configure Date</th>
                                        <td>{{ $plasterConfigure->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $plasterConfigure->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Plaster Info</th>
                                    </tr>
                                    <tr>
                                        <th>Ratio</th>
                                        <td>{{ $plasterConfigure->first_ratio }}:{{ $plasterConfigure->second_ratio }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Cement</th>
                                        <td>{{ $plasterConfigure->total_cement }} Cft</td>
                                    </tr>
                                    <tr>
                                        <th>Total Cement Bag</th>
                                        <td>{{ $plasterConfigure->total_cement_bag }} Bag</td>
                                    </tr>
                                    <tr>
                                        <th>Total Sands</th>
                                        <td>{{ $plasterConfigure->total_sands }} Cft</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Floor</th>
                                        <th>Unit</th>
                                        <th>Unit Section</th>
                                        <th>Plaster Area</th>
                                        <th>Plaster Side</th>
                                        <th>Plaster Thickness</th>
                                        <th>Sub Total Area</th>
                                        <th>Sub Total Cement</th>
                                        <th>Sub Total Sands</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($plasterConfigure->plasterConfigureProducts as $product)
                                        <tr>
                                            <td>{{ $product->bricksConfigureProduct->project->name }}</td>
                                            <td>{{ $product->bricksConfigureProduct->estimateFloor->name }}</td>
                                            <td>{{ $product->bricksConfigureProduct->estimateFloorUnit->name }}</td>
                                            <td>{{ $product->bricksConfigureProduct->unitSection->plaster_area }}</td>
                                            <td>
                                            @if($product->bricksConfigureProduct->wall_direction == 1)
                                                East
                                                @elseif($product->bricksConfigureProduct->wall_direction == 2)
                                                West
                                                @elseif($product->bricksConfigureProduct->wall_direction == 3)
                                                North
                                                @elseif($product->bricksConfigureProduct->wall_direction == 4)
                                                 South
                                                @else
                                                @endif
                                            </td>
                                            <td> {{ $product->plaster_side }}</td>
                                            <td> {{ $product->plaster_thickness }}</td>
                                            <td> {{ number_format($product->sub_total_dry_area, 2) }}</td>
                                            <td> {{ number_format($product->sub_total_cement, 2) }}</td>
                                            <td> {{ number_format($product->sub_total_sands, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th class="text-right" colspan="7">Total</th>
                                        <td> {{ number_format($plasterConfigure->total_plaster_area, 2) }} Cft</td>
                                        <td> {{ number_format($plasterConfigure->total_cement, 2) }} Cft</td>
                                        <td> {{ number_format($plasterConfigure->total_sands, 2) }} Cft</td>
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
