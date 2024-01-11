@extends('layouts.app')

@section('style')
    <style>
        .bottom-title{
            border-top: 2px solid #000;
            text-align: center;
        }

    </style>
@endsection

@section('title')
    Scrap Sale Receipt Details
@endsection

@section('content')
    <div class="row" >
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <a target="_blank" href="{{ route('scrap_sale_receipt.print', ['order' => $order->id]) }}" class="btn btn-primary">Print</a>
                </div>
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
                                        <th>Scrap Order No.</th>
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
                                     ">

                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Client Info</th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $order->client->name ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Company Name</th>
                                        <td>{{ $order->client->company_name ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile</th>
                                        <td>{{ $order->client->mobile ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $order->client->address ?? ''}}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $order->client->email ?? ''}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Project</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($order->products as $product)
                                        <tr>
                                            <td>{{ $product->project->name ?? ''}}</td>
                                            <td>{{ $product->product->name ?? ''}}</td>
                                            <td>{{ $product->product->unit->name ?? ''}}</td>
                                            <td> {{ $product->quantity ?? ''}}</td>
                                            <td> {{ $product->unit_price ?? ''}}</td>
                                            <td> {{ $product->total ?? ''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-offset-8 col-md-4">
                                <table class="table table-bordered">

                                    <tr>
                                        <th class="text-right">Sub Total Amount</th>
                                        <th class="text-right"> {{ number_format($order->total, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-right">Discount</th>
                                        <th class="text-right"> {{ number_format($order->discount, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th class="text-right">Paid</th>
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
