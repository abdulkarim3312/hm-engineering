@extends('layouts.app')

@section('title')
    Bill Form
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
                    <a class="btn btn-primary" href="{{ route('bill_form.add') }}">Add Bill Form</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>SL No</th>
                                <th>Date</th>
                                <th>Project Name</th>
                                <th>Month</th>
                                <th>Bill No</th>
                                <th>Trade</th>
                                <th>Acc Holder Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($billAdjustment as $item)
                                    <tr>
                                        <td>{{$loop->iteration }}</td>
                                        <td>{{$item->date ?? ''}}</td>
                                        <td>{{$item->project->name ?? ''}}</td>
                                        <td>{{$item->for_the_month}}</td>
                                        <td>{{$item->trade}}</td>
                                        <td>{{$item->bill_no}}</td>
                                        <td>{{$item->acc_holder_name ?? ''}}</td>
                                        <td>
                                            <a href="{{ route('bill_adjustment.details', $item->id) }}" class="btn btn-primary btn-sm">Details</a>
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
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table').DataTable({

            });
        })
    </script>
@endsection
