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
    </style>
@endsection

@section('title')
    Requisition Receipt Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-body cart-box-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                           <a target="_blank" href="" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Requisition No.</th>
                                    <td>{{ $requisition->requisition_no }}</td>
                                </tr>
                                <tr>
                                    <th>Requisition Date</th>
                                    <td>{{ $requisition->date }}</td>
                                </tr>
                                @if($requisition->approved_at)
                                    <tr>
                                        <th>Approve Date</th>
                                        <td>{{ $requisition->approved_at }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th> Status</th>
                                    @if($requisition->status == 0)
                                        <td><span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span></td>
                                    @else
                                        <td><span class="badge badge-success" style="background: #04D89D;">Approved by Admin</span></td>
                                    @endif
                                </tr>
                                @if($requisition->approve_note)
                                    <tr>
                                        <th>Approve Note</th>
                                        <td>{{ $requisition->approve_note }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-center">Project Info</th>
                                </tr>
                                <tr>
                                    <th>Project Name</th>
                                    <td>{{ $requisition->project->name ?? ''}}</td>
                                </tr>
                                <tr>
                                    <th>Segment Name</th>
                                    <td>{{ $requisition->segment->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td style="white-space: break-spaces;">{{ $requisition->project->address ?? ''}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Product</th>
                                        <th>Unit</th>
                                        <th>Requisition Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Approved Quantity</th>
                                        <th>Approve unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($requisition->requisitionDetails as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->product->unit->name ??'' }}</td>
                                            <td class="text-right">{{ $product->quantity }}</td>
                                            <td class="text-right">৳{{ number_format($product->unit_price, 2) }}</td>
                                            <td class="text-right">{{ $product->approved_quantity ?? '' }}</td>
                                            <td class="text-right">৳{{ number_format($product->approved_unit_price, 2) }}</td>
                                            <td class="text-right">৳{{ number_format($product->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 offset-md-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Sub Total Amount</th>
                                    <th class="text-right">৳{{ number_format($requisition->sub_total, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Vat ({{ $requisition->vat_percentage }}%)</th>
                                    <th class="text-right">৳{{ number_format($requisition->vat, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Discount ({{ $requisition->discount_percentage }}%)</th>
                                    <th class="text-right">৳{{ number_format($requisition->discount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <th class="text-right">৳{{ number_format($requisition->total, 2) }}</th>
                                </tr>
                            </table>
                        </div>
                        {{-- <div class="offset-md-8 col-md-4 float-end">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Sub Total Amount</th>
                                    <th class="text-right">৳{{ number_format($requisition->sub_total, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Vat ({{ $requisition->vat_percentage }}%)</th>
                                    <th class="text-right">৳{{ number_format($requisition->vat, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Discount ({{ $requisition->discount_percentage }}%)</th>
                                    <th class="text-right">৳{{ number_format($requisition->discount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <th class="text-right">৳{{ number_format($requisition->total, 2) }}</th>
                                </tr>
                            </table>
                        </div> --}}

                    </div>
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
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endsection
