@extends('layouts.app')

@section('title')
    Vendor
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
                    <a class="btn btn-primary" href="{{ route('vendor.add') }}">Add Vendor</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="10%">ID NO</th>
                                <th>Name</th>
                                <th width="30%">Purpose</th>
                                <th>Mobile</th>
                                <th>NID</th>
                                <th width="30%">Address</th>
                                <th>Status</th>
                                <th width="15%">Action</th>
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

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('vendor.datatable') }}',
                columns: [
                    {data: 'vendor_id', name: 'vendor_id'},
                    {data: 'name', name: 'name'},
                    {data: 'purpose', name: 'purpose'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'nid', name: 'nid'},
                    {data: 'address', name: 'address'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection
