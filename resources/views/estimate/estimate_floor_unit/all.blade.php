@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Estimate Floor Unit
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
                    <a class="btn btn-primary" href="{{ route('estimate_floor_unit.add') }}">Add Estimate Floor Unit</a>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Estimate Floor Unit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('estimate_floor_unit.datatable') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection
