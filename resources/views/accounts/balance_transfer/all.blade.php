@extends('layouts.app')
@section('title','Balance Transfer')
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
        <div class="box box-outline box-primary">
            <div class="box-header">
                <a href="{{ route('balance_transfer.add') }}" class="btn btn-primary bg-gradient-primary">Add Balance Transfer</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive-sm">
                    <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>FS Year</th>
                            <th>Type</th>
                            <th>Voucher</th>
                            <th>Receipt</th>
                            <th>Source</th>
                            <th>Target</th>
                            <th>Amount</th>
                            <th width="18.5%">Action</th>
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
                ajax: '{{ route('balance_transfer.datatable') }}',

                "pagingType": "full_numbers",
                "dom": 'T<"clear">lfrtip',
                "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]
                ],
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'financial_year', name: 'financial_year'},
                    {data: 'type', name: 'type'},
                    {data: 'voucher_no', name: 'voucher_no'},
                    {data: 'receipt_no', name: 'receipt_no'},
                    {data: 'source_account_head_name', name: 'sourceAccountHead.name'},
                    {data: 'target_account_head_name', name: 'targetAccountHead.name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'action', name: 'action'},
                ],
                order: [[0, 'desc']],
                "responsive": true, "autoWidth": false,
            });
        });
    </script>
@endsection
