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
   Sand Configure Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('sand_filling_configure.print', ['sandFillingConfigure' => $sandFillingConfigure->id]) }}" class="btn btn-primary">Print</a>
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
                                        <th>Estimate Project Name.</th>
                                        <td>{{ $sandFillingConfigure->project->name ?? ''}}</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <h3><b><u>Sand Filling Calculation</u></b></h3>
                                    <thead>
                                    <tr>
                                        <th>Length</th>
                                        <th>Width</th>
                                        <th>Height</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Volume</th>
                                        <th>Sub Total Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalArea = 0;
                                            $totalPrice = 0;
                                        @endphp
                                    @foreach($sandFillingConfigure->sandFillingConfigureProducts as $product)
                                        <tr>
                                            <td>{{ number_format($product->length, 2) ?? ''}}</td>
                                            <td>{{ number_format($product->width, 2) ?? ''}}</td>
                                            <td>{{ number_format($product->height, 2) ?? ''}}</td>
                                            <td>{{ number_format($product->quantity, 2) }}</td>
                                            <td>{{ number_format($product->unit_price, 2) }} Taka</td>
                                            <td>{{ number_format($product->total_area, 2) }} Cft</td>
                                            <td>{{ number_format($product->total_price, 2) }} Taka</td>
                                        </tr>
                                        @php
                                            $totalArea += $product->total_area;
                                            $totalPrice += $product->total_price;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th class="text-right" colspan="5">Total</th>
                                            <td><b>৳ {{ number_format($totalArea, 2) }} Cft</b></td>
                                            <td><b>৳ {{ number_format($totalPrice, 2) }} Taka</b></td>
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
