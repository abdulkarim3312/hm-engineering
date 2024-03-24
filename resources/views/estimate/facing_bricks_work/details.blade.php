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
        .row{
            margin: 0;
        }
        hr {
            margin-top: 0rem;
            margin-bottom: 0rem;
            border: 0;
            border-top: 1px solid black;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #000 !important;
            vertical-align: middle;
            border-bottom-width: 1px !important;
            border-left-width: 1px !important;
            text-align: center;
            padding: 0.2rem !important;
        }
        @media print{
            @page {
                size: auto;
                margin: 20px !important;
            }
        }
        button, html input[type=button], input[type=reset], input[type=submit] {
            background: #367FA9;
            color: #fff;
        }
    </style>
@endsection

@section('title')
    Facing Bricks Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('facing_bricks_work.print', ['facingBricks' => $facingBricks->id]) }}" class="btn btn-primary">Print</a>
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
                                        <th>Facing Bricks Date</th>
                                        <td>{{ $facingBricks->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $facingBricks->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Facing Info</th>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project</th>
                                        <td>{{ $facingBricks->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Floor</th>
                                        <td>{{ $facingBricks->estimateFloor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Floor Unit</th>
                                        <td>{{ $facingBricks->estimateFloorUnit->name }}</td>
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
                                        <th>Wall Direction</th>
                                        <th>Facing Work</th>
                                        <th>Length</th>
                                        <th>Height/Width</th>
                                        <th>Side</th>
                                        <th>Sub Total Deduction</th>
                                        <th>Sub Total Area</th>
                                        <th>Sub Total</th>
                                        <th>Total Cost</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $total = 0;
                                        $totalBrick = 0;
                                        $totalSilicon = 0;
                                    @endphp
                                    <tbody>
                                    @foreach($facingBricks->facingBricksProducts as $product)
                                        <tr>
                                            <td>{{ $product->unitSection->name ?? ''}}</td>
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
                                                @if($product->facing_brick == 1)
                                                    Facing Bricks
                                                @else
                                                    Silicon
                                                @endif
                                            </td>
                                            <td>{{ $product->length }}</td>
                                            <td> {{ $product->height }}</td>
                                            <td> {{ $product->side }}</td>
                                            <td> {{ $product->sub_total_deduction }}</td>
                                            <td> {{ number_format($product->sub_total_area, 2) }} Sft</td>
                                            @if($product->facing_brick == 1)
                                                <td> {{ number_format($product->sub_total_bricks, 2) }} Nos</td>
                                            @endif
                                            @if($product->facing_brick == 2)
                                                <td> {{ number_format($product->sub_total_bricks, 2) }} Galon</td>
                                            @endif
                                            <td> {{ number_format($product->total_bricks_cost, 2) }} Tk</td>
                                        </tr>
                                        @php
                                             $total += $product->sub_total_area;
                                             $totalBrick += $product->sub_total_bricks;
                                             $totalSilicon += $product->total_bricks_cost;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th colspan="5">Total</th>
                                        <td>{{ $total }}</td>
                                        <td>{{ $totalBrick }}</td>
                                        <td>{{ $totalSilicon }} Tk</td>
                                    </tr>
                                    </tbody>
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
