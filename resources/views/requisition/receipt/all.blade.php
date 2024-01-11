@extends('layouts.app')
@section('title')
    Requisition List
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
                    <a class="btn btn-primary" href="{{ route('requisition.add') }}">Add Requisition</a>
                </div>
                <div class="box-body">
                  <div class="table-responsive">
                      <table id="table" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                              <th>Date</th>
                              <th>Requisition No</th>
                              <th>Project</th>
{{--                              <th>Segment</th>--}}
                              <th>Total Quantity</th>
                              <th>Total Approved QTY</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                          </thead>
                      </table>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-delivery">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delivery Product</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form" name="modal-form">
                        <table class="table table-bordered">
                            <tr>
                                <th width="50%">Total Quantity</th>
                                <td width="50%" id="total_qty"></td>
                            </tr>
                            <tr>
                                <th>Delivery Quantity</th>
                                <td id="delivery_qty"></td>
                            </tr>
                            <tr>
                                <th>Remain Quantity</th>
                                <td id="remain_qty"></td>
                            </tr>

                        </table>
                        <input type="hidden" id="sales_id" name="sales_id">
                        <div class="form-group">
                            <label>Date</label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="date" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>

                            <input type="text" class="form-control" name="quantity" placeholder="Quantity">
                            <!-- /.input group -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-delivery-btn-save">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('script')

    <script>
        $(function () {
            var selectedOrderId;

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('requisition.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'requisition_no', name: 'requisition_no'},
                    {data: 'project_name', name: 'project.name'},
                    //{data: 'segment_name', name: 'segment.name'},
                    {data: 'total_quantity', name: 'total_quantity'},
                    {data: 'total_approved_quantity', name: 'total_approved_quantity'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 0, "desc" ]],
            });

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('click', '.btn-delivery', function () {
                var orderId = $(this).data('id');
                selectedOrderId = orderId;
                $("#sales_id").val(orderId);

                $.ajax({
                    method: "GET",
                    url: "{{ route('sale_delivery_info') }}",
                    data: { orderId: orderId }
                }).done(function( response ) {

                    $("#total_qty").html(response.total_quantity)
                    $("#delivery_qty").html(response.delivery_quantity)
                    $("#remain_qty").html(response.total_quantity - response.delivery_quantity)

                    $('#modal-delivery').modal('show');
                });



            });

            $('#modal-delivery-btn-save').click(function () {

                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('sales_delivery_log') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-delivery').modal('show');
                            Swal.fire(
                                'Delivered!',
                                response.message,
                                'success'
                            ).then((result) => {
                                //location.reload();
                                window.location.href = response.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    }
                });
            });
            $('body').on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var orderId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "Post",
                            url: "{{ route('sales_order_delete') }}",
                            data: { orderId: orderId }
                        }).done(function( response ) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    //location.reload();
                                    window.location.href = response.redirect_url;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });

                    }
                })

            });

        });
    </script>
@endsection
