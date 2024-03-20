@extends('layouts.app')

@section('title')
    Payment Check
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
                    <a class="btn btn-primary" href="{{ route('payment_check.add') }}">Add Payment Check</a>

                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Customer Name</th>
                            <th>Bank Name</th>
                            <th>Cheque No</th>
                            <th>Cheque Amount</th>
                            <th>Cheque Date</th>
                            <th>Submitted Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($paymentCheque as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->customer_name }}</td>
                                <td>{{ $item->bank_name }}</td>
                                <td>{{ $item->check_no }}</td>
                                <td>{{ $item->check_amount }}</td>
                                <td>{{ $item->check_date }}</td>
                                <td>{{ $item->submitted_date }}</td>
                                <td>
                                    @if ($item->status == 1)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('payment_check.edit', ['cheque' => $item->id]) }}">Edit</a>
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
