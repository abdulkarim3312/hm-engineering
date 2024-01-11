@extends('layouts.app')
@section('title','Cash/Bank Vouchers')
@section('style')
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
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">

                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('voucher.create') }}" class="btn btn-primary bg-gradient-primary pull-left">Cash/Bank Voucher Create</a>

                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive-sm">
                        <table id="table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>FS Year</th>
                                <th>Voucher No</th>
                                <th>Cash/Bank Account</th>
                                <th>Head of Expenditure</th>
                                <th>Narration</th>
                                <th>Net Amount</th>
                                <th width="14%">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
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
                ajax: '{{ route('voucher.datatable') }}',

                "pagingType": "full_numbers",
                "dom": 'T<"clear">lfrtip',
                "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]],
                rowCallback: function(row, data, index) {
                    if (data.date == '{{ date('d-m-Y') }}') {
                        $(row).addClass('bg-green-custom');
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(7)').addClass('bg-white-custom');
                },
                columns: [
                    { data: 'date',name:'date'},
                    { data: 'financial_year',name:'financial_year'},
                    { data: 'receipt_payment_no',name:'receipt_payment_no'},
                    {data: 'account_head', name: 'account_head'},
                    {data: 'expenses_head', name: 'expenses_head',searchable: false},
                    {data: 'narrations', name: 'narrations',searchable: false},
                    {data: 'net_amount', name: 'net_amount'},
                    {data: 'action', name: 'action', orderable: false},
                ], order: [[0, 'desc']],
                "responsive": true, "autoWidth": false,
            });
        })
    </script>
@endsection
