@extends('layouts.app')

@section('title')
    Scrap Product
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
                    <a class="btn btn-primary" href="{{ route('scrap_product.add') }}">Add Scrap Product</a>
                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
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
                ajax: '{{ route('scrap_product.datatable') }}',
                columns: [
                    {data: 'project', name: 'project.name'},
                    {data: 'product', name: 'product.name'},
                    {data: 'unit', name: 'product.unit.name'},
                    {data: 'last_unit_price', name: 'last_unit_price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                ],
                order: [[ 0, "desc" ]],
            });

        });
    </script>
@endsection
