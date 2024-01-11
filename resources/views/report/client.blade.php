@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>

        .img-overlay {
            position: absolute;
            left: 0;
            top: 250px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.1;
        }

        .img-overlay img {
            width: 400px;
            height: auto;
        }
    </style>
@endsection

@section('title')
    Client Report
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
                    <form action="{{ route('report.client_report') }}">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Client</label>
                                    <select class="form-control" name="client">
                                        <option value="">Select Client</option>
                                            <option value="">All Client</option>
                                        @foreach($clients as $client)
                                        <option {{ request()->get('client') == $client->id ? 'selected' : '' }} value="{{$client->id}}">{{$client->name??''}}</option>
                                        @endforeach
                                    </select>
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
            @if($incomes && $orders)
                <section class="box">

                    <div class="box-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea" style="padding: 0 5px!important;">

                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%" src="{{ asset('img/head_logo.jpeg') }}">

                                </div>
                                <div class="col-xs-8 text-center">
                                    <div style="padding:10px; width:100%; text-align:center;">
                                        <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                    </div>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <h4><strong>Receipt</strong></h4>
                                </div>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">
                                        @if (request()->get('start') != '' && request()->get('end') != '')
                                            {{ date("d-m-Y",strtotime(request()->get('start')))}} to {{ date("d-m-Y",strtotime(request()->get('end')))}}
                                        @else
                                            {{ $first_date ? date("d-m-Y",strtotime($first_date->date)) : ''}} to {{ date("d-m-Y")}}
                                        @endif

                                    </th>
                                </tr>
                            </table>

                            <div style="clear: both">
                                <div class="img-overlay">
                                    <img src="{{ asset('img/logo.png') }}">
                                </div>
                                <table class="table table-bordered" style="width:100%; float:left">
                                    <tr>
                                        <th width="10%">Transaction Date</th>
                                        <th width="40%">Particulars</th>
                                        <th width="10%">Vch. Type</th>
                                        <th width="10%">Vch. Number</th>
                                        <th width="15%" class="text-right" width="20%">Debit(in BDT)</th>
                                        <th width="15%" class="text-right" width="10%">Credit(in BDT)</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span style="font-size: 13px;font-weight: bold">Opening balance</span></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right"> {{ number_format($opening_balance, 2) }} </td>
                                        <td class="text-right">0.00</td>
                                    </tr>

                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->created_at->format('d-m-Y')}}</td>
                                        <td>{{$order->client->name}}</td>
                                        <td>Journal</td>
                                        <td>JV#{{$order->order_no}}</td>
                                        <td class="text-right">{{ number_format($order->total,2) }}</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                     <tr>
                                         <td colspan="6"><strong>Purpose By:</strong> {{ $order->note }}</td>
                                     </tr>
                                    @endforeach

                                    @foreach($incomes as $income)

                                        <tr>
                                            <td class="text-center">{{ $income->date->format('d-m-Y') }}</td>
                                            <td>{{ $income->particular }}</td>
                                            <td>Receipt</td>
                                            <td >RV#{{ $income->salepayment->receipt_no }}</td>
                                            <td class="text-right">0.00</td>
                                            <td class="text-right">{{ number_format($income->amount,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"><strong>Narration:</strong> {{ $income->transaction_method==1?"Cash":"Bank: ".$income->bank.", Branch: ".$income->branch.",Account: ".$income->bank_account.", Date: ".date("d-m-Y",strtotime($income->date))  }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th class="text-right" colspan="4">Current Total:</th>
                                        <th class="text-right">{{ number_format($orders->sum('total') + $opening_balance,2) }}</th>
                                        <th class="text-right">{{ number_format($orders->sum('paid') - $opening_balance,2) }}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Balance c/d:</th>
                                        <th colspan="2" class="text-right">{{ number_format(($orders->sum('paid')),2)  }} </th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Total:</th>
                                        <th class="text-right">{{ number_format($orders->sum('total') + $opening_balance,2) }}</th>
                                        <th class="text-right">{{ number_format($orders->sum('total') + $opening_balance,2) }} </th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Closing Balance </th>
                                        <th class="text-right" colspan="2">{{ number_format($orders->sum('paid'),2) }}&nbsp;Dr</th>
                                    </tr>
                                </table>
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
