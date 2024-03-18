@extends('layouts.app')

@section('title')
    Contractor
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
                    <a class="btn btn-primary" href="{{ route('contractor.add') }}">Add Contractor</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID NO</th>
                                <th>Name</th>
                                <th>Project Name</th>
                                <th>Trade</th>
                                <th>Mobile</th>
                                <th>NID</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            {{-- <tr>
                                <th class="text-right" colspan="7">Total</th>
                                <th> {{ number_format($suppliers->sum('total'), 2) }}</th>
                                <th> {{ number_format($suppliers->sum('paid'), 2) }}</th>
                                <th> {{ number_format($suppliers->sum('due'), 2) }}</th>
                                <th> {{ number_format($suppliers->sum('discount'), 2) }}</th>
                                <th colspan="2"></th>
                            </tr> --}}
                            </tfoot>
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
                ajax: '{{ route('contractor.datatable') }}',
                columns: [
                    // {data: 'image', name: 'image'},
                    {data: 'contractor_id', name: 'contractor_id'},
                    {data: 'name', name: 'name'},
                    {data: 'project', name: 'project.name'},
                    {data: 'trade', name: 'trade'},
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
