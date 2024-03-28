@extends('layouts.app')

@section('title')
    Petty Cash
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
                    <a class="btn btn-primary" href="{{ route('petty_cash.add') }}">Add Petty Cash</a>

                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>SL No</th>
                                <th>Project Name</th>
                                <th>Month</th>
                                <th>Acc Holder Name</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($pettyCash as $item)
                                    <tr>
                                        <td>{{$loop->iteration }}</td>
                                        <td>{{$item->project->name ?? ''}}</td>
                                        <td>{{$item->month}}</td>
                                        <td>{{$item->acc_holder_name ?? ''}}</td>
                                        <td>{{$item->date ?? ''}}</td>
                                        <td>
                                            @if ($item->status == 0)
                                                <span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>
                                            @else
                                                <span class="badge badge-success" style="background: #04D89D;">Approved</span>    
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 0)
                                                <a href="{{ route('petty_cash.details', $item->id) }}" class="btn btn-primary btn-sm">Details</a>
                                                <a href="{{ route('petty_cash.approval', $item->id) }}" class="btn btn-warning btn-sm">Approve</a>
                                            @else
                                                <a href="{{ route('petty_cash.details', $item->id) }}" class="btn btn-primary btn-sm">Details</a>
                                            @endif
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
