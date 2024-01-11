@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Balance Summary
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bank Details</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Bank Name</th>
                                        <th>Branch Name</th>
                                        <th>Account No.</th>
                                        <th>Balance</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($bankAccounts as $account)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $account->bank->name }}</td>
                                            <td>{{ $account->branch->name }}</td>
                                            <td>{{ $account->account_no }}</td>
                                            <td class="text-right"> {{ number_format($account->balance, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="3"></th>
                                            <th class="text-right">Total</th>
                                            <th class="text-right"> {{ number_format($bankAccounts->sum('balance'), 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Cash</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Type</th>
                                        <th>Balance</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Cash</td>
                                        <td class="text-right"> {{ number_format($cash->amount, 2) }}</td>
                                    </tr>
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right"> {{ number_format($cash->amount, 2) }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Mobile Banking</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Type</th>
                                        <th>Balance</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mobile Banking</td>
                                        <td class="text-right"> {{ number_format($mobile_banking->amount, 2) }}</td>
                                    </tr>
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right"> {{ number_format($mobile_banking->amount, 2) }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Client Payment</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered " id="customer-payment-table" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Mobile</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                    </tr>
                                    </thead>

                                    <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Total</th>
                                        <th class="text-right"> {{ number_format($customerTotal, 2) }}</th>
                                        <th class="text-right"> {{ number_format($customerTotalPaid, 2) }}</th>
                                        <th class="text-right"> {{ number_format($customerTotalDue, 2) }}</th>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Supplier Payment</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="supplier-payment-table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($suppliers as $supplier)
                                        <tr>
                                            <td>{{ $supplier->name }}</td>
                                            <td>{{ $supplier->mobile }}</td>
                                            <td> {{ number_format($supplier->order_total, 2) }}</td>
                                            <td> {{ number_format($supplier->order_paid, 2) }}</td>
                                            <td> {{ number_format($supplier->order_due, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Total</th>
                                        <th class="text-right"> {{ number_format($suppliers->sum('order_total'), 2) }}</th>
                                        <th class="text-right"> {{ number_format($suppliers->sum('order_paid'), 2) }}</th>
                                        <th class="text-right"> {{ number_format($suppliers->sum('order_due'), 2) }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Production Stock Summary</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Product</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-right">Total</th>
                                        <th class="text-left"> {{ number_format($purchaseStocktotal, 2) }}</th>
                                        <th class="text-left"> {{ number_format($purchaseStockQty, 2) }}</th>
                                        <th class="text-right"></th>

                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Sale Stock Summary</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="">
                                <table class="table table-bordered" >
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Project</th>
                                        <th>Floor</th>
                                        <th>Flat</th>
                                        <th>Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                        ?>
                                    @foreach($flatPrices as $row)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$row->project_name}}</td>
                                            <td>{{$row->floor_name}}</td>
                                            <td>{{$row->flat_name}}</td>
                                            <td>{{$row->total}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right"></th>
                                        <th class="text-left"> {{ $totalflatPrice }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Summary</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="inventory-table">
                                    <thead>
                                    <tr>
                                        <th>Total Capital:  {{ number_format($bankAccounts->sum('balance') + $cash->amount+$mobile_banking->amount+$totalSaleProductStock + $customerTotalDue + $totalflatPrice  - $suppliers->sum('due'), 2) }}</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
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
                processing: true,
                serverSide: true,
                ajax: '{{ route('purchase_inventory.datatable') }}',
                columns: [
                    {data: 'project', name: 'project.name'},
                    {data: 'product', name: 'product.name'},
                    {data: 'avg_unit_price', name: 'avg_unit_price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'action', name: 'action'},
                ],
                columnDefs: [
                    {
                        targets: 4,
                        className: 'text-right'
                    }
                ]
            });
            $('#customer-payment-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('client_payment.datatable') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid', orderable: false},
                    {data: 'due', name: 'due', orderable: false},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        className: 'text-left'
                    },
                    {
                        targets: 4,
                        className: 'text-right'
                    }
                ]
            });

            $('#supplier-payment-table').DataTable({
                columnDefs: [
                    {
                        targets: 3,
                        className: 'text-right'
                    },
                    {
                        targets: 4,
                        className: 'text-right'
                    }
                ]
            });

            $('#inventory-table').DataTable({
                // processing: true,
                serverSide: true,
                ajax: '{{ route('purchase_inventory.datatable') }}',
                columns: [
                    {data: 'purchase_order', name: 'purchase_order.order_no'},
                    {data: 'product', name: 'product.name'},
                    {data: 'total', name: 'total'},
                ],
                columnDefs: [
                    {
                        targets: 4,
                        className: 'text-right'
                    }
                ]
            });


            $('#sale-stock-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('sale_inventory.datatable') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'total', name: 'total'},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        className: 'text-right'
                    }
                ]

            });
        });
    </script>
@endsection
