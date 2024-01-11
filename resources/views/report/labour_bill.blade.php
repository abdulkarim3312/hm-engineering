@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

        <style>
            .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            white-space: nowrap;
        }
        </style>

 @endsection

@section('title')
    Labour Bill Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('labour_bill_process.report') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="start" name="start"
                                            value="{{ request()->get('start') }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="end" name="end"
                                            value="{{ request()->get('end') }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label> &nbsp;</label>

                                    <input class="btn btn-primary form-control" type="submit" value="Search">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @isset($labourBills)
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button>
                        <br>
                        <hr>
                        <div id="prinarea" class="table-responsive">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                                <div class="col-xs-8 text-center" style="margin-left: -135px;">
                                    <div style="padding:10px; width:100%; text-align:center;">
                                        <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                        <h4><strong>Labour Bill Report</strong></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Sl</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Start Date</th>
                                            <th class="text-center">End Date</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Designation</th>
                                            <th class="text-center">Project</th>
                                            <th class="text-center">Total Working day</th>
                                            <th class="text-center">Per day amount</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Advance Amount</th>
                                            <th class="text-center">Food Cost</th>
                                            <th class="text-center">Bonus Amount</th>
                                            <th class="text-center">Net Amount</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                            $totalNet = 0;
                                            $totalAdv = 0;
                                            $totalFood = 0;
                                            $totalBonus = 0;
                                            $totalAmount=0;

                                        @endphp

                                        @foreach ($labourBills as $labourBill)
                                            @php
                                                $i++;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $i }}</td>
                                                <td class="text-center">{{ date('d-m-Y', strtotime($labourBill->date)) }}</td>
                                                <td class="text-center">
                                                    {{ date('d-m-Y', strtotime($labourBill->start_date)) }}</td>
                                                <td class="text-center">{{ date('d-m-Y', strtotime($labourBill->end_date)) }}
                                                </td>
                                                <td>{{ $labourBill->labourEmployee->name }}</td>
                                                <td class="text-center">{{ $labourBill->labourEmployee->designation->name }}
                                                </td>
                                                <td class="text-center">{{ $labourBill->labourEmployee->project->name }}
                                                </td>
                                                <td class="text-center">{{ $labourBill->total_attendance }}</td>
                                                <td class="text-center">{{ $labourBill->labourEmployee->per_day_amount }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $labourBill->labourEmployee->per_day_amount * $labourBill->total_attendance }}
                                                </td>
                                                <td class="text-center">{{ $labourBill->advance }}</td>
                                                <td class="text-center">{{ $labourBill->food_cost }}</td>
                                                <td class="text-center">{{ $labourBill->bonus }}</td>
                                                <td class="text-center">{{ $labourBill->net_bill }}</td>
                                            </tr>
                                            @php
                                                $totalNet += $labourBill->net_bill;
                                                $totalAdv += $labourBill->advance;
                                                $totalFood += $labourBill->food_cost;
                                                $totalBonus += $labourBill->bonus;
                                                $totalAmount+=$labourBill->labourEmployee->per_day_amount * $labourBill->total_attendance;
                                            @endphp
                                        @endforeach


                                    </tbody>

                                </table>
                            </div>
                            <div class="row">
                                <div class="col-xs-8 col-xs-offset-4">


                                    <table class="table table-bordered ">
                                        <tr>
                                            <th class="text-center">Total</th>

                                             <th class="text-center">{{ $totalAmount }}</th>
                                            <th class="text-center">{{ $totalAdv }}</th>
                                            <th class="text-center">{{ $totalFood }}</th>
                                            <th class="text-center">{{ $totalBonus }}</th>
                                            <th class="text-center">{{ $totalNet }}</th>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endisset
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script
        src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>

    <script>
        $(function() {
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

        var APP_URL = '{!! url()->full() !!}';

        function getprint(prinarea) {

            $('body').html($('#' + prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
