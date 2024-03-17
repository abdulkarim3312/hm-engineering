@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
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
    Receive Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <form action="{{ route('report.receive_and_payment') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')  }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')  }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>	&nbsp;</label>

                                    <input class="btn btn-primary form-control" type="submit" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            @if($receipts)
            <section class="panel">

                <div class="panel-body">
                    <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                    <div class="adv-table" id="prinarea">
                        <div style="padding:10px; width:100%; text-align:center;">
                            <div class="row print-heading">
                                <div class="col-12">
                                    <h1 class="text-center m-0" style="font-size: 40px !important;font-weight: bold">
                                        <img height="50px" src="{{ asset('img/logo.png') }}" alt="">
                                        {{ config('app.name') }}
                                    </h1>
                                    <h3 class="text-center m-0" style="font-size: 25px !important;">Receive Report</h3>
                                </div>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>
                                </tr>
                            </table>

                            <div style="clear: both">
                                <table class="table table-bordered" style="width:100%;">
                                    <tr>
                                        <th colspan="7" class="text-center">Receipt</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" width="20%">Date</th>
                                        <th class="text-center" width="20%">Client Name</th>
                                        <th class="text-center" width="15%">Receipt No</th>
                                        <th class="text-center" width="15%">Notes</th>
                                        <th class="text-center" width="15%">Receipt Type</th>
                                        <th class="text-center" width="25%">Account Head</th>
                                        <th class="text-center" width="25%">Amount</th>
                                    </tr>

                                    @foreach($receipts as $receipt)
                                        <tr>
                                            <td>{{ date('d-m-Y',strtotime($receipt->date)) }}</td>
                                            <td class="text-center">{{ $receipt->client->name ?? ''}}</td>
                                            <td>{{ $receipt->receipt_payment_no ?? ''}}</td>
                                            <td>{{ $receipt->notes ?? ''}}</td>
                                            <td>
                                                @if($receipt->payment_type == 1)
                                                Bank
                                                @else
                                                Cash
                                                @endif

                                            </td>
                                            <td>{{ $receipt->accountHead->name ?? ''}}</td>
                                            <td class="text-center">৳ {{ number_format($receipt->net_amount,2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="6" class="text-right">Total</th>
                                        <th class="text-center">৳ {{ number_format($receipts->sum('net_amount'),2) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
             @endif
        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            var selectedProduct = '{{ request()->get('product') }}';
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

        });

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>

@endsection
