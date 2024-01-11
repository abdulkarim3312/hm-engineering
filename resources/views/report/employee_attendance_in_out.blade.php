@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

@endsection

@section('title')
    Employee Attendance report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('report.employee_attendance_in_out') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Employee</label>
                                    <select name="employee" id="employee" class="form-control select2">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option {{ request('employee') == $employee->id ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date <span class="text-danger">*</span></label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date <span class="text-danger">*</span></label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>	&nbsp;</label>

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
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>
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
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Sl</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Designation</th>
                                    <th class="text-center">department</th>
                                    <th class="text-center">In/OutCount</th>

                                </tr>
                                </thead>
                                <tbody>

                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{date('d-m-Y',strtotime($attendance->date))}}</td>
                                        <td >{{$attendance->employee->name}}</td>
                                        <td class="text-center">{{$attendance->employee->designation->name}}</td>
                                        <td class="text-center">{{$attendance->employee->department->name}}</td>
                                        <td class="text-center">{{$attendance->count}}</td>

                                    </tr>
                                @endforeach

                                </tbody>
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
        function getprint(prinarea) {

            $('body').html($('#'+prinarea).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
@endsection
