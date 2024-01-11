@extends('layouts.app')

@section('title')
    Warehouse
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
                    <a class="btn btn-primary" href="{{ route('warehouse.add') }}"> Add Warehouse </a>

                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Serial</th>
                                <th>Warehouse</th>
                                <th>Location</th>
                                <th>Maintainer Name</th>
                                <th>Maintainer Mobile</th>
                                <th>Status</th>
                                <th width="12%">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($warehouses as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>{{ $item->maintainer_name }}</td>
                                    <td>{{ $item->maintainer_mobile }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('warehouse.edit', $item->id) }}">Edit</a>
                                        <a class="btn btn-danger btn-sm" href="{{ route('warehoues_delete', $item->id) }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            $('#table').DataTable();
        })
    </script>
@endsection
