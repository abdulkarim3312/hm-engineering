@extends('layouts.app')
@section('style')
    <style>
        .img-overlay {
            position: absolute;
            left: 0;
            top: 150px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-align: center;
            z-index: 9;
            opacity: 0.1;
            display: none;
        }

        .img-overlay img {
            width: 400px;
            height: auto;
        }
        .table>thead:first-child>tr:first-child>th {
            border-top: 0;
            vertical-align: middle;
        }
    </style>
@endsection

@section('title')
    Year Wise Payment Report
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
                    <form action="{{ route('report.year_wise_payment') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="project">Project</label>
                                    <select id="project" class="form-control select2" name="project" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option {{ request()->get('project') == $project->id ? 'selected' : '' }} value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_year">Start Year <span class="text-danger">*</span></label>
                                    <select id="start_year" class="form-control select2" name="start_year" required>
                                        <option value="">Select Year</option>
                                        @for($i=2021; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ request('start_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_year">End Year <span class="text-danger">*</span></label>
                                    <select id="end_year" class="form-control select2" name="end_year" required>
                                        <option value="">Select Year</option>
                                        @for($i=2021; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}" {{ request('end_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
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
            <section class="panel">
                <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>
                        <div class="adv-table" id="prinarea">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px;" src="{{ asset('img/head_logo.jpeg') }}">
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
                                    <h3><strong>Receipt</strong></h3>
                                    <h4>{{ $project_single->name??'' }}</h4>
                                </div>
                            </div>
                            <div style="clear: both">
                                <div class="img-overlay">
                                    <img src="{{ asset('img/logo.png') }}">
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="2">SL</th>
                                                <th class="text-left" rowspan="2">Client Name</th>
                                                <th class="text-left" rowspan="2">Mobile</th>
                                                <th class="text-left" rowspan="2">Floor No.</th>
                                                <th class="text-center" rowspan="2">Flat No.</th>
                                                <th class="text-center" colspan="{{ count($yearRanges) }}">Year-wise Payment Details</th>
                                            </tr>
                                            <tr>
                                                @foreach($yearRanges as $key=>$yearRange)
                                                <th class="text-right">{{ $yearRange }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($salesOrders as $salesOrder)
                                            <tr>
                                                <td class="text-center">{{ $i++ }}</td>
                                                <td class="text-left">{{ $salesOrder->client->name }}</td>
                                                <td class="text-left">{{ $salesOrder->client->mobile_no }}</td>
                                                <td class="text-center">{{ $salesOrder->floor->name }}</td>
                                                <td class="text-center">{{ $salesOrder->flat->name }}</td>
                                                @foreach($yearRanges as $year)
                                                    <td class="text-right">
                                                       {{ number_format(getOrderClientPaymentTotal($year,$salesOrder->client_id,$salesOrder->id),2) }}
                                                    </td>
                                                @endforeach
                                            </tr>

                                        @endforeach
                                        <tr>
                                            <th class="text-right" colspan="5">Total</th>
                                            @foreach($yearRanges as $year)
                                                <th  class="text-right">{{ number_format(getOrderClientPaymentTotalSum($year,$salesOrder), 2) }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th class="text-right" colspan="5">Cumulative Total</th>
                                            @foreach($yearRanges as $year)
                                                <?php
                                                    $paymentSum = getOrderClientPaymentTotalCalSum(request('start_year'),$year,$salesOrder);
                                                ?>
                                                <th  class="text-right">{{ number_format($paymentSum, 2) }}</th>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    </div>
@endsection

@section('script')
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
            $(".img-overlay").show();
            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
