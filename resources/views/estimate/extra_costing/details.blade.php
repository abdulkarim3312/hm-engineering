@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .bottom-title{
            border-top: 2px solid #000;
            text-align: center;
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
    Extra Costing Details
@endsection

@section('content')
    <div class="box">
        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><br>
        <div id="prinarea">
            <div class="img-overlay">
                <img src="{{ asset('img/logo.png') }}">
            </div>
            <div class="container" style="padding: 10px !important;width: 700px !important;">
                <div class="row" style="padding:10px; width:100%; text-align:center;">
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
                    <div class="col-xs-6">
                        <div class="text-left">
                            <span  style="border: 1px solid #999;padding: 5px">Voucher No:</span>
                            <span  style="border: 1px solid #999;padding: 5px">JV#{{ $extraCosting->order_no }}</span>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="text-right">
                            <span  style="border: 1px solid #999;padding: 5px">Date :</span>
                            <span  style="border: 1px solid #999;padding: 5px">{{ $extraCosting->date }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div style="width:100%; text-align:center;">
                        <h4><strong>Project Name: {{$extraCosting->estimateProject->name ?? ''}}</strong></h4>
                    </div>
                </div>
                <div class="row" style="border: 2px solid #000;margin-top: 5px !important;background: #ededed;">
                    <div class="col-xs-3 text-center"><strong>Estimate Product</strong></div>
                    <div class="col-xs-3 text-center"><strong>Costing Amount(Per Unit)</strong></div>
                    <div class="col-xs-3 text-center"><strong>Total Amount</strong></div>
                </div>
                <div class="row" style="padding: 5px;border: 1px solid #000;">

                    <div class="col-xs-3 text-center">
                        @foreach($extraCosting->products as $product)
                            {{$product->name}}-<b>{{$product->unit->name ?? ''}}</b><br>
                        @endforeach
                    </div>

                    <div class="col-xs-3 text-center">
                        @foreach($extraCosting->products as $product)
                            {{$product->costing_amount_per_unit ?? ''}}<br>
                        @endforeach
                    </div>

                    <div class="col-xs-3 text-center">
                        @foreach($extraCosting->products as $product)
                            {{$product->total ?? ''}}<br>
                        @endforeach
                    </div>
                </div>
                <div class="row" style="padding: 5px;border: 1px solid #000;">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-3 text-right"><strong>Total:</strong></div>
{{--                    <div class="col-xs-3 text-right"><strong>{{number_format($extraCosting->total,2)}}</strong></div>--}}
                    <div class="col-xs-3 text-center"><strong>{{number_format($extraCosting->total,2)}}</strong></div>
                </div>
                <div class="row" style="padding: 5px;border: 1px solid #000;">
                    <div class="col-xs-12"><strong>Amount In Word (in BDT):</strong> {{ $extraCosting->amount_in_word }}</div>
                </div>
                <div class="row" style="padding: 5px;border: 1px solid #000;">
                    <div class="col-xs-12"><strong>Narration: </strong> {{ $extraCosting->note ?? ''}}</div>
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
