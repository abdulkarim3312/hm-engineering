@extends('layouts.app')
@section('title')
    Stock Inventory Details - {{ $product->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="date" autocomplete="off">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>

                                <select class="form-control" id="type">
                                    <option value="">All Type</option>
                                    <option value="1">In</option>
                                    <option value="2">Out</option>
                                </select>
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
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Project</th>
{{--                                <th>Segment</th>--}}
                                <th>Purchase Order No.</th>
                                <th>Requisition No.</th>
                                <th>Type</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Supplier</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- date-range-picker -->
    <script src="{{ asset('themes/backend/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script>
        $(function () {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('purchase_inventory.details.datatable') }}",
                    data: function (d) {
                        d.product_id = {{ $product->id }}
                        d.project_id = {{ $project->id }}
                        d.date = $('#date').val()
                        d.type = $('#type').val()
                    }
                },
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'warehouse_name', name: 'warehouse.name'},
                    {data: 'project', name: 'project.name'},
                    //{data: 'segment_name', name: 'segment.name'},
                    {data: 'purchase_order', name: 'purchase_order.order_no'},
                    {data: 'requisition_no', name: 'requisition.requisition_no'},
                    {data: 'type', name: 'type'},
                    {data: 'unit_price', name: 'unit_price'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'supplier', name: 'supplier.name'},
                ],
                order: [[ 0, "desc" ]],
            });

            //Date range picker
            $('#date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                table.ajax.reload();
            });

            $('#date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                table.ajax.reload();
            });

            $('#date, #type').change(function () {
                table.ajax.reload();
            });
        })
    </script>
@endsection
