@extends('layouts.app')

@section('title')
    Supplier
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
                    <a class="btn btn-primary" href="{{ route('supplier.add') }}">Add Supplier</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID NO</th>
                                <th>Project Type</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Brand Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="text-right" colspan="7">Total</th>
                                <th> {{ number_format($suppliers->sum('total'), 2) }}</th>
                                <th> {{ number_format($suppliers->sum('paid'), 2) }}</th>
                                <th> {{ number_format($suppliers->sum('due'), 2) }}</th>
                                <th> {{ number_format($suppliers->sum('discount'), 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
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
                ajax: '{{ route('supplier.datatable') }}',
                columns: [
                    {data: 'id_no', name: 'id_no'},
                    {data: 'project_type', name: 'project_type'},
                    {data: 'image', name: 'image'},
                    {data: 'name', name: 'name'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'address', name: 'address'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    {data: 'discount', name: 'discount'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });
        })
    </script>
@endsection
