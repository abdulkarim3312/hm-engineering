@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        table.table-bordered.dataTable th, table.table-bordered.dataTable td {
            text-align: center;
            vertical-align: middle;
        }
        .page-item.active .page-link {
            background-color: #009f4b;
            border-color: #009f4b;
        }
    </style>
@endsection

@section('title')
    Project Cash
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
                    {{-- <a class="btn btn-primary" href="{{ route('segment.add') }}">Add Product Segment</a> --}}

                    <hr>

                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Opening Balance</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projectCashes as $cash)
                                    <tr>
                                        <td>{{$cash->Project->name??''}}</td>
                                        <td>{{$cash->opening_balance}}</td>
                                        <td>{{$cash->amount}}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{route('project.cash.edit',['cash' => $cash->id])}}">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endsection
