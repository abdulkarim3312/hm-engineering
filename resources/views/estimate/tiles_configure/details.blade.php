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
   Tiles Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('tiles_configure.print', ['tilesConfigure' => $tilesConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                            <div class="col-xs-8 text-center" style="margin-left: -135px;">
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
                                        <th>Tiles Configure No.</th>
                                        <td>{{ $tilesConfigure->grill_glass_tiles_configure_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiles Number of Floor No.
                                        </th>
                                        <td>{{ $tilesConfigure->floor_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiles Configure Date:</th>
                                        <td>{{ $tilesConfigure->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiles Configure Names:</th>
                                        <td>{{ $tilesConfigure->configure_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiles Size:</th>
                                        <td>{{ $tilesConfigure->tiles_size }}</td>
                                    </tr>
                                    <tr>
                                        <th>Note </th>
                                        <td>{{ $tilesConfigure->note??'' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Tiles Info</th>
                                    </tr>
                                    <tr>
                                        <th>Estimate Project</th>
                                        <td>{{ $tilesConfigure->project->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Floor</th>
                                        <td>{{ $tilesConfigure->estimateFloor->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimate Floor Unit</th>
                                        <td>{{ $tilesConfigure->estimateFloorUnit->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Tiles Area(Single Floor)</th>
                                        <td>{{ $tilesConfigure->total_area_without_floor }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Tiles Area(All Floor)</th>
                                        <td>{{ $tilesConfigure->total_area_with_floor }}</td>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tilesConfigure->grillGlassTilesConfigureProducts as $product)
                                        <tr>
                                            <td>{{ $product->unitSection->name }}</td>
                                            <td>{{ $product->length }}</td>
                                            <td> {{ $product->height }}</td>
                                            <td> {{ number_format($product->quantity, 2) }}</td>
                                            <td> {{ number_format($product->sub_total_area, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tr>
                                        <th class="text-right" colspan="4">Single Floor Total</th>
                                        @if($tilesConfigure->configure_type == 1)
                                        <td> {{ number_format($tilesConfigure->total_area_without_floor, 2) }} KG</td>
                                        @else
                                            <td> {{ number_format($tilesConfigure->total_area_without_floor, 2) }} Sft</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">All Floor Total</th>
                                        @if($tilesConfigure->configure_type == 1)
                                            <td> {{ number_format($tilesConfigure->total_area_with_floor, 2) }} KG</td>
                                        @else
                                            <td> {{ number_format($tilesConfigure->total_area_with_floor, 2) }} Sft</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Total Cost</th>
                                        @if($tilesConfigure->configure_type == 1)
                                            <td>৳ {{ number_format($tilesConfigure->total_grill_cost, 2) }} Taka</td>
                                        @else
                                            <td>৳ {{ number_format($tilesConfigure->total_tiles_glass_cost, 2) }} Taka</td>
                                        @endif
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
