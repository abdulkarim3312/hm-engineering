@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Sale Inventory
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
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project</label>

                                    <select class="form-control project-select" id="project">
                                        <option value="all">All Projects</option>
                                            @foreach($projects as $project)
                                                <option value="{{$project->id}}">{{$project->name}}</option>
                                            @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                        <hr>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Project</th>
                                <th>Floor</th>
                                <th>Flat</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 100,
                ajax: {
                    url: "{{ route('sale_inventory.datatable') }}",
                    data: function (d) {
                        // d.project = $('#project').val();
                        d.project = $('#project').val() === 'all' ? '' : $('#project').val();
                    }
                },
                columns: [
                    {data: 'project', name: 'project'},
                    {data: 'floor', name: 'floor'},
                    {data: 'flat', name: 'flat'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
                ],
                "responsive": true, "autoWidth": false,
            });

            $('#project').change(function () {
                table.ajax.reload();
            });
        });
    </script>
@endsection
