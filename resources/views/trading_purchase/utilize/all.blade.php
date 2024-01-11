@extends('layouts.app')
@section('title')
    Utilize Logs
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
                    <a class="btn btn-primary" href="{{ route('purchase_product.utilize.add') }}">Add</a>
                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Warehouse</th>
                            <th>Project</th>
                            <th>Segment</th>
                            <th>Product</th>
                            <th>Unit price</th>
                            <th>Quantity</th>
                            <th>Note</th>
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
                ajax: '{{ route('purchase_product.utilize.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'warehouse', name: 'warehouse.name'},
                    {data: 'project', name: 'project'},
                    {data: 'segment', name: 'segment.name'},
                    {data: 'product', name: 'product.name'},
                    {data: 'unit_price', name: 'unit_price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'note', name: 'note'},
                ],
                order: [[ 0, "desc" ]],
            });
        });
    </script>
@endsection
