
@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    All Grade Of Concrete
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
                <div class="box-body">
                    <a class="btn btn-primary" href="{{ route('grade_of_concrete.add') }}">Add Grade Of Concrete</a>

                    <div class="text-right">
                        <a target="_blank" href="{{route('grade_of_concrete.print')}}" class="btn btn-primary">Print</a>
                    </div>
                    <hr>


                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>

                            <th>Date</th>
                            <th>Concrete No</th>
                            <th>Batch</th>
                            <th>Project Name</th>
                            <th>Chemical</th>
                            <th>Cement Bags</th>
                            <th>Water</th>
                            <th>Total Sands</th>
                            <th>Total Aggregate</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gradeOfConcretes as $gradeOfConcrete)
                            <tr>

                                <td>{{ $gradeOfConcrete->date }}</td>
                                <td>{{ $gradeOfConcrete->grade_of_concrete_no }}</td>
                                <td>{{ $gradeOfConcrete->batch->name }}</td>
                                <td>{{ $gradeOfConcrete->project->name }}</td>
                                <td>{{ $gradeOfConcrete->chemical }}</td>
                                <td>{{ $gradeOfConcrete->total_cement }} Bags</td>
                                <td>{{ $gradeOfConcrete->total_water }} Liters</td>
                                <td>{{ $gradeOfConcrete->total_sands }} cft</td>
                                <td>{{ $gradeOfConcrete->total_aggregate }} cft</td>
                                <td>{{ $gradeOfConcrete->note }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table').DataTable();
        })
    </script>
@endsection
