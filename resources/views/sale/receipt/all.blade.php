@extends('layouts.app')

@section('title')
    Sales Order List
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
                <div class="box-body">
                    <div class="row">
{{--                        <div class="col-md-4">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Client</label>--}}

{{--                                <select class="form-control client-select" id="client">--}}
{{--                                    <option value="all">All Clients</option>--}}
{{--                                    @foreach($clients as $client)--}}
{{--                                        <option data-client-id="{{$client->id}}">{{$client->name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}

{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Project</label>

                                <select class="form-control project-select" id="project">
                                    <option value="all">All Projects</option>
                                    @foreach($projects as $project)
                                        <option data-project-id="{{$project->id}}">{{$project->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Project Name</th>
                            <th>Client Name</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Discount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
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
                pageLength: 100,
                ajax: '{{ route('sale_receipt.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'project', name: 'project.name'},
                    {data: 'client', name: 'client.name'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    {data: 'discount', name: 'discount'},
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


        document.querySelector('.project-select').addEventListener('change', function() {
            // Get the selected project ID
            var selectedProjectId = this.value;

            // Get all the rows in the table
            var rows = document.querySelectorAll('#table tbody tr');

            // Loop through each row
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];

                // Get the project name cell in the row
                var projectCell = row.querySelector('td:nth-child(2)');

                // If the selected project is "all" or the project name in the row matches the selected project, show the row
                if (selectedProjectId === 'all' || projectCell.textContent === selectedProjectId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
@endsection
