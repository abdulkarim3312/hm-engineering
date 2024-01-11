@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Labour Bill Report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('food_cost.report') }}">
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
    @isset($attendances)
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br>
                        <hr>
                        <div id="prinarea">
                            <div class="row">
                                <div class="col-xs-4 text-left">
                                    <img style="width: 35%;margin-top: 20px" src="{{ asset('img/head_logo.jpeg') }}">
                                </div>
                                <div class="col-xs-8 text-center">
                                    <div style="padding:10px; width:100%; text-align:center;">
                                        <h2>{{\App\Enumeration\Text::$companyName}}</h2>
                            <h4>{{\App\Enumeration\Text::$companyAddress}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyMobileNumber}}</h4>
                            <h4>{{\App\Enumeration\Text::$companyEmail}}</h4>
                                        <h4><strong>Labour Advance Report</strong></h4>
                                        <h4><strong>{{ request()->get('start') }} to {{ request()->get('end') }}</strong></h4>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        {{-- <th class="text-center">Day</th> --}}
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Attendee</th>
                                        <th class="text-center">Per Day</th>
                                        <th class="text-center">Total Bill</th>
                                        <th class="text-center">Advance</th>
                                        <th class="text-center">Food Cost</th>
                                        <th class="text-center">Payable Bill</th>
                                        <th class="text-center">Signature</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                        $totalBill = 0;
                                        $totalAdvance = 0;
                                        $totalFoodCost = 0;
                                        $totalPayable = 0;
                                    @endphp
                                    @foreach ($attendances as $foodCost)
                                        @php
                                            $i++;

                                            $totalBill += $foodCost->labourEmployeeAttendence($start, $end) * $foodCost->labourEmployee->per_day_amount;
                                            $totalAdvance += $foodCost->advance;
                                            $totalFoodCost += $foodCost->food_cost;
                                            $totalPayable += $foodCost->labourEmployeeAttendence($start, $end) * $foodCost->labourEmployee->per_day_amount - ($foodCost->advance + $foodCost->food_cost);
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $foodCost->labour_employee_id }}</td>
                                            {{-- <td class="text-center">{{ $foodCost->ccc }}</td> --}}
                                            <td>{{ $foodCost->labourEmployee->name }}</td>
                                            <td>{{ $foodCost->labourEmployeeAttendence($start, $end) }}</td>
                                            <td>{{ $foodCost->labourEmployee->per_day_amount }}</td>
                                            <td class="text-center">
                                                {{ $foodCost->labourEmployeeAttendence($start, $end) * $foodCost->labourEmployee->per_day_amount }}
                                            </td>
                                            <td class="text-center">{{ $foodCost->advance }}</td>
                                            <td class="text-center">{{ $foodCost->food_cost }}</td>
                                            <td class="text-center">
                                                {{ $foodCost->labourEmployeeAttendence($start, $end) * $foodCost->labourEmployee->per_day_amount -($foodCost->advance + $foodCost->food_cost) }}
                                            </td>
                                            <td class="text-center"></td>

                                        </tr>

                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-center">Total</th>
                                        <th class="text-center">{{$totalBill}}</th>
                                        <th class="text-center">{{$totalAdvance}}</th>
                                        <th class="text-center">{{$totalFoodCost}}</th>
                                        <th class="text-center">{{$totalPayable}}</th>
                                    </tr>
                                </tfoot>
                            </table>
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
