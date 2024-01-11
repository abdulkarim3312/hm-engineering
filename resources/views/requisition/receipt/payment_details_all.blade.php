@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    {{-- <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}"> --}}

@endsection

@section('title')
    Requisition Payment Details 
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <a class="btn btn-primary" href="{{ route('purchase_product.add') }}">Add Product</a>

                    <hr>

                    <table id="table-payments" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction Method</th>
                                <th>Bank</th>
                                <th>Branch</th>
                                <th>Account</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($requisition->payments as $payment)
                                <tr>
                                    <td>{{ $payment->date->format('Y-m-d') }}</td>
                                    <td>
                                        @if($payment->transaction_method == 1)
                                            Cash
                                        @else
                                            Bank
                                        @endif
                                    </td>
                                    <td>{{ $payment->bank ? $payment->bank->name : '' }}</td>
                                    <td>{{ $payment->branch ? $payment->branch->name : '' }}</td>
                                    <td>{{ $payment->account ? $payment->account->account_no : '' }}</td>
                                    <td>৳ {{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->note }}</td>
                                    <td>
                                        <a href="{{ route('requisition_receipt.payment_details', ['payment' => $payment->id]) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
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

    {{-- <script>
        $(function () {
            $('#table').DataTable();
        })
    </script> --}}
    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": [[ 0, "desc" ]],
            });

        });
    </script>
    <!-- DataTables -->
    {{-- <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script> --}}
@endsection
