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
   Paint Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('paint_configure.print', ['paintConfigure' => $paintConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                                        <th>Paint Configure No.</th>
                                        <td>{{ $paintConfigure->paint_configure_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Paint Floor Quantity</th>
                                        <td>{{ $paintConfigure->floor_number }}</td>
                                    </tr>

                                    <tr>
                                        <th>Total Paint Liter Without Floor</th>
                                        <td>{{ $paintConfigure->total_paint_liter_without_floor }} Liter</td>
                                    </tr>

                                    <tr>
                                        <th>Total Paint Liter With Floor</th>
                                        <td>{{ $paintConfigure->total_paint_liter_with_floor }} Liter</td>
                                    </tr>
                                    <tr>
                                        <th>Total Seller Liter Without Floor</th>
                                        <td>{{ $paintConfigure->total_seller_liter_without_floor }} Liter</td>
                                    </tr>

                                    <tr>
                                        <th>Total Seller Liter With Floor</th>
                                        <td>{{ $paintConfigure->total_seller_liter_with_floor }} Liter</td>
                                    </tr>
                                    <tr>
                                        <th>Paint Configure Date</th>
                                        <td>{{ $paintConfigure->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $paintConfigure->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Paint Info</th>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project</th>
                                        <td>{{ $paintConfigure->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Floor</th>
                                        <td>{{ $paintConfigure->estimateFloor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Floor Unit</th>
                                        <td>{{ $paintConfigure->estimateFloorUnit->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Paint Area(Single Floor)</th>
                                        <td>{{ $paintConfigure->total_area_without_floor }} Sft</td>
                                    </tr>
                                    <tr>
                                        <th>Total Paint Area(All Floor)</th>
                                        <td>{{ $paintConfigure->total_area_with_floor }} Sft</td>
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
                                        <th>Paint Type</th>
                                        <th>Length</th>
                                        <th>Height/Width</th>
                                        <th>Side</th>
                                        <th>Code Nos</th>
                                        <th>Sub Total Deduction</th>
                                        <th>Sub Total Area</th>
                                        <th>Sub Total Paint Liter</th>
                                        <th>Sub Total Seller Liter</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($paintConfigure->paintConfigureProducts as $product)
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

                                            <td>
                                                @if($product->paint_type == 1)
                                                    Wheathar Code
                                                @elseif($product->paint_type == 2)    
                                                    Dis-Temper
                                                @elseif($product->paint_type == 3)    
                                                    Plastic
                                                @elseif($product->paint_type == 4)    
                                                    Enamel
                                                @else
                                                    Polish
                                                @endif
                                            </td>
                                            <td>{{ $product->length }}</td>
                                            <td> {{ $product->height }}</td>
                                            <td> {{ $product->side }}</td>
                                            <td> {{ $product->code_nos }}</td>
                                            <td> {{ number_format($product->sub_total_deduction, 2) }} Sft</td>
                                            <td> {{ number_format($product->sub_total_area, 2) }} Sft</td>
                                            <td> {{ number_format($product->sub_total_paint_liter, 2) }} Liter</td>
                                            <td> {{ number_format($product->sub_total_seller_liter, 2) }} Liter</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th class="text-right" colspan="8">Single Floor Total</th>
                                        <td> {{ number_format($paintConfigure->total_area_without_floor, 2) }} Sft</td>
                                        <td> {{ number_format($paintConfigure->total_paint_liter_without_floor, 2) }} Liter</td>
                                        <td> {{ number_format($paintConfigure->total_seller_liter_without_floor, 2) }} Liter</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="8">All Floor Total</th>
                                        <td> {{ number_format($paintConfigure->total_area_with_floor, 2) }} Sft</td>
                                        <td> {{ number_format($paintConfigure->total_paint_liter_with_floor, 2) }} Liter</td>
                                        <td> {{ number_format($paintConfigure->total_seller_liter_with_floor, 2) }} Liter</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="9">Total Cost Paint/Seller</th>
                                        <td>৳ {{ number_format($paintConfigure->total_paint_cost, 2) }} Taka</td>
                                        <td>৳ {{ number_format($paintConfigure->total_seller_cost, 2) }} Taka</td>
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
