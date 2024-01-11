@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>
        .cart-box-body{
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
            background: #fff;
        }
        #receipt-content{
            font-size: 18px;
        }

        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid black !important;
        }
    </style>
@endsection

@section('title')
    Requisition  Payment Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-body cart-box-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('requisition_receipt.payment_print', ['payment' => $payment->id]) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-4">
                            <img src="{{ asset('img/logo.jpeg') }}" height="50px" style="float: left">
                            <h2 style="margin: 0px; float: left">RECEIPT</h2>
                        </div>

                        <div class="col-sm-4 text-center">
                            <b>Date: </b> {{ $payment->date->format('j F, Y') }}
                        </div>

                        <div class="col-sm-4 text-right">
                            <b>No: </b> {{ $payment->id + 1000 }}
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="20%">
                                        @if($payment->type == 1)
                                            To
                                        @elseif($payment->type == 2)
                                            From
                                        @endif
                                    </th>
{{--                                    <td>{{ $payment->purchaseOrder->supplier->name }}</td>--}}
                                    <th width="10%">Amount</th>
                                    <td width="15%">৳{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Requisition No.</th>
                                    <td colspan="3">{{ $payment->requisition->requisition_no ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Project & Segment</th>
                                    <td colspan="3">{{ $payment->requisition->project->name?? '' }} project,{{ $payment->requisition->segment->name?? '' }} segment</td>
                                </tr>

                                <tr>
                                    <th>Amount (In Word)</th>
                                    <td colspan="3">{{ $payment->amount_in_word }}</td>
                                </tr>

                                <tr>
                                    <th>For Payment of</th>
                                    <td colspan="3">Requisition No. {{ $payment->requisition->requisition_no }}</td>
                                </tr>

                                <tr>
                                    <th>Paid By</th>
                                    <td colspan="3">
                                        @if($payment->transaction_method == 1)
                                            Cash
                                        @elseif($payment->transaction_method == 3)
                                            Mobile Banking
                                        @else
                                            Bank - {{ $payment->bank->name.' - '.$payment->branch->name.' - '.$payment->account->account_no }}
                                        @endif
                                    </td>
                                </tr>

                                @if($payment->transaction_method == 2)
                                    <tr>
                                        <th>Cheque No.</th>
                                        <td colspan="3">{{ $payment->cheque_no }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <th>Note</th>
                                    <td colspan="3">{{ $payment->note ?? '' }}</td>
                                </tr>

                                @if($payment->transaction_method == 2)
                                    <tr>
                                        <th>Cheque Image</th>
                                        <td colspan="3" class="text-center">
                                            <img src="{{ asset($payment->cheque_image) }}" height="300px">
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

