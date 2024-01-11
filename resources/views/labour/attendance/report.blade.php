@extends('layouts.app')

@section('title')
   Labour Employee Attendance report
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <form action="{{ route('labour_employee_attendance.report') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>

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
                                    <label>End Date</label>

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
                    <div class="box-header">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <div class="box-body">
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
                                        <h4><strong>Receipt</strong></h4>
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
                                    <th class="text-center">present</th>
                                    <th class="text-center">present Time</th>
                                    <th class="text-center">Note</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{date('d-m-Y',strtotime($attendance->date))}}</td>
                                        <td >{{$attendance->employee->name}}</td>
                                        <td class="text-center">{{$attendance->employee->designation->name}}</td>
                                        <td class="text-center">
                                            @if ($attendance->present_or_absent==1)
                                                <span class="text-success"><i class="fa fa-check" aria-hidden="true"></i></span>
                                            @else
                                                <span class="text-red"><i class="fa fa-times" aria-hidden="true"></i></span>
                                            @endif

                                        </td>
                                        <td><span class="text-success">{{$attendance->present_time ? date('h:i A', strtotime($attendance->present_time)) : ''}}</span></td>
                                        <td class="text-center">{{$attendance->note}}</td>
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
