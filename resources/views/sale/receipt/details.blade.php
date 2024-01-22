@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .bottom-title{
            border-top: 2px solid #000;
            text-align: center;
        }

    </style>
@endsection

@section('title')
    Sale Receipt Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 text-right">
{{--         <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>--}}
            <a target="_blank" href="{{ route('sale_receipt.print', ['order' => $order->id]) }}" class="btn btn-primary">Print</a>
        </div>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div id="prinarea">
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
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order No.</th>
                                    <td>{{ $order->order_no }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td>{{ $order->date }}</td>
                                </tr>
                            </table>
                        </div>

                        <img src="{{ asset('img/logo.png') }}"
                             style="border-radius: 50%;
                                     opacity: 0.4;
                                     width: 330px;
                                     height: 300px;
                                     position: absolute;


                                     margin-left: -151px;
                                     margin-top: 50px;
                                     z-index: 1;
                                     "
                        >

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-center">Client Info</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $order->client->name }}</td>
                                </tr>
                                <tr>
                                    <th>Profession</th>
                                    <td>{{ $order->client->company_name }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>{{ $order->client->mobile_no }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $order->client->address }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $order->client->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">Project Name</th>
                                    <th class="text-center">Floor Name</th>
                                    <th class="text-center"> {{ $order->flats[0]->type == 1 ? 'Flat Name' : 'Shop' }}</th>
                                    <th class="text-center"> Price</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($order->flats as $flat)
                                    <tr>
                                        <td>{{ $order->project->name }}</td>
                                        <td>{{ $order->floor['name'] }}</td>
                                        <td>{{ $flat->pivot->flat_name }}</td>
                                        <td class="text-right"> {{ $flat->pivot->price}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-8 col-md-4">
                            <table class="table table-bordered">
                                @foreach ( $order->flats as $flat )
                                <tr>
                                    <th class="text-right">Car Parking</th>
                                    <th class="text-right" > {{ number_format($flat->pivot->car, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Utility</th>
                                    <th class="text-right"> {{ number_format($flat->pivot->utility, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Other</th>
                                    <th class="text-right"> {{ number_format($flat->pivot->other, 2) }}</td>
                                </tr>
                                @endforeach
                                @if($order->discount)
                                <tr>
                                    <th class="text-right">Discount</th>
                                    <th class="text-right"> {{ number_format($order->discount, 2) }}</td>
                                </tr>
                               @endif
                                @if($order->vat_percent > 0)
                                    <tr>
                                        <th class="text-right">Vat({{ number_format($order->vat_percent,2) }})%</th>
                                        <th class="text-right"> {{ number_format($order->vat, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="text-right">Sub Total Amount</th>
                                    <th class="text-right"> {{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Total Amount</th>
                                    <th class="text-right"> {{ number_format($order->total - $order->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Paid/Booking Money</th>
                                    <th class="text-right"> {{ number_format($order->paid, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right">Due</th>
                                    <th class="text-right"> {{ number_format($order->due, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px !important;">
                        <div class="col-xs-3" style="margin-top: 25px;">
                            <div class="text-left" style="margin-left: 10px;">
                                <h5 class="bottom-title">Received By</h5>
                            </div>
                        </div>
                        <div class="col-xs-3" style="margin-top: 25px">
                            <div class="text-center">
                                <h5 class="bottom-title" style="text-align: center!important;">Prepared By</h5>
                            </div>
                        </div>
                        <div class="col-xs-3" style="margin-top: 25px">
                            <div class="text-right">
                                <h5 class="bottom-title">Checked By</h5>
                            </div>
                        </div>
                        <div class="col-xs-3" style="margin-top: 25px">
                            <div class="text-right">
                                <h5 class="bottom-title">Approved By</h5>
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
            window.location.replace(APP_URL)
        }
    </script>
@endsection
