@extends('layouts.app')

@section('title')
    Stock Inventory
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
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Project</th>
{{--                            <th>Segment</th>--}}
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
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
    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('purchase_inventory.datatable') }}',
                columns: [
                    {data: 'warehouse_name', name: 'warehouse.name'},
                    {data: 'project', name: 'project.name'},
                    //{data: 'segment_name', name: 'segment.name'},
                    {data: 'product', name: 'product.name'},
                    {data: 'avg_unit_price', name: 'avg_unit_price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'action', name: 'action'},
                ],
            });
        });
    </script>
@endsection
