@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">

    <style>
        .timepicker_wrap{
            width: max-content;
        }
    </style>

@endsection

@section('title')
    Labour Employee Attendance
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('error') }}
        </div>
        @endif
    @isset($employees)
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body table-responsive">

                    <form action="{{route('labour.attendance')}}" method="post">
                        @csrf
                        <div class="col-md-2">
                            <label for="attend_date">Date</label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input id="attend_date" value="{{old('date')}}" name="attend_date" required type="text" class="form-control" autocomplete="off">
                                <span class="text-danger">{{$errors->first('attend_date')}}</span>
                            </div>
                        </div>

                        <table id="table" class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Present</th>
                                <th width="5%">Present Time</th>
                                <th>Note</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($employees as $employee)
                                <span class="item">
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$employee->name}}</td>
                                    <td>{{$employee->designation->name}}</td>
                                    <td>
                                        <input value="1" class="present_check" name="present_{{ $employee->id }}" type="checkbox" checked>
                                    </td>
                                    <td><input class="timepicker present_time" required name="present_time_{{ $employee->id }}" type="text"></td>
                                    <td><input  name="note_{{ $employee->id }}" placeholder="Note" type="text"></td>

                                </tr>
                                </span>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>
                                    <button class="btn btn-primary form-control">Submit</button>

                                </td>
                            </tr>
                            </tfoot>
                        </table>

                        <input type="hidden" name="category" value="{{ request()->get('category') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endisset


@endsection

@section('script')

    <script src="{{ asset('themes/backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

    <script>
        //Date picker
        $('#attend_date').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom'
        });

        //Timepicker
        $('.timepicker').timepicker({
            showInputs: false,
            defaultTime: null
        });

        $(".present_check").change(function () {
            var check =$(this)
            if ($(this).prop('checked')) {
                check.closest('tr').find('.present_time').prop( "disabled", false );
            }else{
                check.closest('tr').find('.present_time').prop( "disabled", true ).val(' ');

            }
        });

    </script>
@endsection
