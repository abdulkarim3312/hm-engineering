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
            opacity: 0.2;
        }

        .img-overlay img {
            width: 400px;
            height: auto;
        }
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
    Project Wise Report
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
                    <form action="{{ route('report.project_report') }}">
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
                                    <label>Project</label>
                                    <select class="form-control" name="project" required="">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option {{ request()->get('project') == $project->id ? 'selected' : '' }} value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach
                                    </select>
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
            @if($customers)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>

                        <div class="adv-table" id="prinarea">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                                <div class="col-xs-8 text-center" style="margin-left: -133px;">
                                    <div style="padding:10px; width:100%; text-align:center;">
                                    <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                                    <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                                    <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                                    <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                    </div>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <h4><strong>Project Wise Report</strong></h4>
                                    <h4>Project Name:<strong> {{ $project_single->name }}</strong></h4>
                                </div>
                            </div>
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>
                                </tr>
                            </table>

                            <div style="clear: both">
                                <div class="img-overlay">
                                    <img src="{{ asset('img/logo.png') }}">
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered" style="width:100%;">
                                        <tr>
                                            {{-- <th rowspan="2" colspan="2" class="text-center">Particular</th> --}}
                                            {{-- <th colspan="2" class="text-center">Transaction</th>
                                            <th colspan="2" class="text-center">Closing</th> --}}
                                        </tr>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">Voucher No</th>
                                            <th class="text-center">Head of Expenditure</th>
                                            <th class="text-center">Debit (in BDT)</th>
                                            {{-- <th class="text-center" width="20%">Debit (in BDT)</th>
                                            <th class="text-center" width="10%">Credit (in BDT)</th> --}}
                                        </tr>
                                        @php
                                            $total=0;
                                        @endphp
                                        @foreach($customers as $row)
                                            <tr>
                                                {{-- <td class="text-center">{{ $row->date ?? '' }}</td> --}}
                                                <td class="text-center">{{ date('Y-m-d',strtotime($row->date)) ?? '' }}</td>
                                                <td class="text-center">{{ $row->client->name ?? ''}}</td>
                                                <td class="text-center">{{ $row->receipt_payment_no ?? ''}}</td>
                                                <td class="text-center">{{ $row->accountHead->name ?? ''}}</td>
                                                <td class="text-center">{{ number_format($row->amount,2) }}</td>
                                                {{-- <td class="text-center">{{ number_format($row->paid,2) }}</td>
                                                <td class="text-center">{{ number_format($row->due,2) }}</td> --}}
                                                @php
                                                    $total += $row->amount;
                                                @endphp
                                          </tr>
                                        @endforeach
                                        <tr>
                                            <th  colspan="2">Total</th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            {{-- <th class="text-center"> {{ number_format($customers->sum('paid'),2) }}</th>
                                            <th class="text-center"> {{ number_format($customers->sum('due'),2) }}</th> --}}
                                            <th class="text-center"> {{ number_format($total,2) }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered" style="width:100%;">
                                        <tr>
                                            {{-- <th rowspan="2" colspan="2" class="text-center">Particular</th> --}}
                                            {{-- <th colspan="2" class="text-center">Transaction</th>
                                            <th colspan="2" class="text-center">Closing</th> --}}
                                        </tr>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Client Name</th>
                                            <th class="text-center">Voucher No</th>
                                            <th class="text-center">Head of Expenditure</th>
                                            <th class="text-center">Credit (in BDT)</th>
                                            {{-- <th class="text-center" width="20%">Debit (in BDT)</th>
                                            <th class="text-center" width="10%">Credit (in BDT)</th> --}}
                                        </tr>
                                        @php
                                            $totalCredit=0;
                                        @endphp
                                        @foreach($credits as $row)
                                            <tr>
                                                <td class="text-center">{{ date('Y-m-d',strtotime($row->date)) ?? '' }}</td>
                                                <td class="text-center">{{ $row->client->name ?? ''}}</td>
                                                <td class="text-center">{{ $row->receipt_payment_no ?? ''}}</td>
                                                <td class="text-center">{{ $row->accountHead->name ?? ''}}</td>
                                                <td class="text-center">{{ number_format($row->amount,2) }}</td>
                                                @php
                                                    $totalCredit += $row->amount;
                                                @endphp
                                          </tr>
                                        @endforeach
                                        <tr>
                                            <th  colspan="2">Total</th>
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                            <th class="text-center"> {{ number_format($totalCredit,2) }}</th>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <h4 style="margin-left: 23px;"><b>Balance: {{ $totalCredit - $total }}</b></h4>
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
