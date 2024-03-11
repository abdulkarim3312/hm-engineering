
@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    All Plaster Configure
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
                    <a class="btn btn-primary" href="{{ route('plaster_configure.add') }}">Add Plaster Configure</a>
                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Project Name</th>
                            <th>Floor Name</th>
                            <th>Unit Name</th>
                            <th>Plaster Area(Dry)</th>
                            <th>Total Cement(Bag)</th>
                            <th>Total Sands(Cft)</th>
                            <th width="15%">Action</th>
                        </tr>
                        </thead>
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

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('plaster_configure.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'project_name', name: 'project.name'},
                    {data: 'estimate_floor', name: 'floor.name'},
                    {data: 'estimate_floor_unit', name: 'floorUnit.name'},
                    {data: 'total_plaster_area', name: 'total_plaster_area'},
                    {data: 'total_cement_bag', name: 'total_cement_bag'},
                    {data: 'total_sands', name: 'total_sands'},
                    {data: 'action', name: 'action'},
                ],
                //order: [[ 0, "desc" ]],
            });

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
