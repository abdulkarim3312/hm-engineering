
@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Earth Work Configure
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
                    <a class="btn btn-primary" href="{{ route('earth_work_configure.add') }}">Earth Work Configure</a>
                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Earth Work Type</th>
                            <th>Estimate Project</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($earthWorkConfigures as $earthWorkConfigure)
                        <tr>
                            <td>
                                @if ($earthWorkConfigure->earth_work_type == 1)
                                    <span class="label label-success">Earth Cutting</span>
                                @else
                                    <span class="label label-info">Earth Filling</span>
                                @endif
                            </td>
                            <td>{{$earthWorkConfigure->project->name ?? ''}}</td>
                            <td>
                                <a href="{{ route('earth_work_configure.details', $earthWorkConfigure->id) }}" class="btn btn-primary btn-sm">Details</a>
                            </td>
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
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            var selectedOrderId;

            $('#table').DataTable();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
