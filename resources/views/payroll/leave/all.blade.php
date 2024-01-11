@extends('layouts.app')

@section('title')
    Leave
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <a class="btn btn-primary" href="{{ route('payroll.leave.index') }}">Add Leave</a>

                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Year</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Total Days</th>
                            <th>Note</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->employee->name??'' }}</td>
                                <td>{{ $leave->year }}</td>
                                <td>{{ date('d-m-Y',strtotime($leave->from)) }}</td>
                                <td>{{ date('d-m-Y',strtotime($leave->to)) }}</td>
                                <td>{{ $leave->total_days }}</td>
                                <td>{{ $leave->note }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#table').DataTable();
        })
    </script>
@endsection
