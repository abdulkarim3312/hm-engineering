@extends('layouts.app')
@section('title')
    Supplier Payment Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>FS Year</th>
                                        <th>Type</th>
                                        <th>Voucher/Receipt No</th>
                                        <th>Cash/Bank Account</th>
                                        <th>Client name</th>
                                        <th>Incomes Code</th>
                                        <th>Net Amount</th>
                                        <th width="14%">Action</th>
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
    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('purchase_payment.datatable') }}",
                    data: function (d) {
                        d.client_id = '{{ $client->id }}'
                    }
                },
                "pagingType": "full_numbers",
                "dom": 'T<"clear">lfrtip',
                "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]
                ],
                columns: [
                    { data: 'date',name:'date'},
                    { data: 'financial_year',name:'financial_year'},
                    { data: 'transaction_type',name:'transaction_type'},
                    { data: 'receipt_payment_no',name:'receipt_payment_no'},
                    {data: 'account_head', name: 'account_head.name'},
                    {data: 'client_name', name: 'client.name'},
                    {data: 'expenses_code', name: 'expenses_code',searchable: false},
                    {data: 'net_amount', name: 'net_amount'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'asc']],

                "responsive": true, "autoWidth": false,
            });
        })
    </script>
@endsection
