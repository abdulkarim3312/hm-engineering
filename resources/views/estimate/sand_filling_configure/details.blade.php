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
                                    <tr>
                                        <th>Sand Configure Length</th>
                                        <td>{{ $sandFillingConfigure->length ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Sand Configure Width</th>
                                        <td>{{ $sandFillingConfigure->width ?? '' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Sand Filling Info</th>
                                    </tr>
                                   
                                    <tr>
                                        <th>Height</th>
                                        <td>{{ $sandFillingConfigure->height }} </td>
                                    </tr>
                                    <tr>
                                        <th>Quantity</th>
                                        <td>{{ $sandFillingConfigure->quantity }} Psc</td>
                                    </tr>
                                    <tr>
                                        <th>Unit Price</th>
                                        <td>{{ $sandFillingConfigure->unit_price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Area</th>
                                        <td>{{ $sandFillingConfigure->total_area }} </td>
                                    </tr>
                                    <tr>
                                        <th>Total Price</th>
                                        <td>{{ number_format($sandFillingConfigure->total_price, 2) }} Tk</td>
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
