@extends('layouts.app')
@section('title','Journal Vouchers(JV)')
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
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Data Filter</h3>
                </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div  class="row">
                            <div class="col-md-5 ">
                                <div class="form-group">
                                    <input type="text" required id="start_date" autocomplete="off"
                                           name="start_date" class="form-control date-picker"
                                           placeholder="Enter Start Date" value="{{ request()->get('start_date')  }}">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" required id="end_date" autocomplete="off"
                                           name="end_date" class="form-control date-picker"
                                           placeholder="Enter Start Date" value="{{ request()->get('end_date')  }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button id="date_range_search" class="btn btn-default bg-gradient-primary pull-right"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-outline box-primary">
                <div class="box-header">
                    <a href="{{ route('journal_voucher.create') }}" class="btn btn-primary bg-gradient-primary">Journal Voucher Create</a>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive-sm">
                        <table id="table" class="table table-bordered">
                            <thead>
                            <tr>

                                <th>Date</th>
                                <th>JV No</th>
                                <th>Party name</th>
                                <th>Debit Codes</th>
                                <th>Credit Codes</th>
                                <th>Amount</th>
                                <th>Action</th>
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
           var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('journal_voucher.datatable') }}",
                    data: function (d) {
                        d.start_date = $('#start_date').val()
                        d.end_date = $('#end_date').val()
                    }
                },

                "pagingType": "full_numbers",
                "dom": 'T<"clear">lfrtip',
                "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]
                ],
                columns: [
                    { data: 'date',name:'date'},
                    { data: 'jv_no',name:'jv_no'},
                    {data: 'client_name', name: 'client.name'},
                    {data: 'debit_codes', name: 'debit_codes', orderable: false},
                    {data: 'credit_codes', name: 'credit_codes', orderable: false},
                    {data: 'credit_total', name: 'credit_total'},
                    {data: 'action', name: 'action', orderable: false},
                ],  order: [[0, 'asc']],
                "responsive": true, "autoWidth": false,
            });

            $('#start_date,#end_date').change(function () {
                table.ajax.reload();
            });
            $('#date_range_search').click(function () {
                table.ajax.reload();
            });
        })
    </script>
@endsection
