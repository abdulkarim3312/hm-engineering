@extends('layouts.app')

@section('title')
    Scrap Sales Orders
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
                    <a class="btn btn-primary" href="{{ route('scrap_sale.create') }}">Scrap Sale Add</a>

                </div>
                <div class="box-body">
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            {{-- <th>Discount</th> --}}
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Payment Information</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form" enctype="multipart/form-data" name="modal-form">

                        <div class="form-group">
                            <label for="financial_year">Select Financial Year <span
                                    class="text-danger">*</span></label>
                            <select class="form-control select2" style="width: 100%" name="financial_year">
                                <option value="">Select Year</option>
                                @for($i=2017; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ old('financial_year') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            {{-- <label>Name</label> --}}
                            {{-- <input type="hidden" class="form-control" id="projects_id" name="projects_id"> --}}
                            <input type="hidden" id="client_id" name="client">
                        </div>
                        <div class="form-group">
                            {{-- <label>Project ID</label> --}}
                            <input type="hidden" class="form-control" id="projects_id" name="projects_id">
                        </div>

                        {{-- <div class="form-group">
                            <label>Order No</label>
                            <select class="form-control select2" style="width: 100%" id="modal-order" name="order_no">
                                <option value="">Select Order No.</option>
                            </select>
                        </div> --}}

                        <div id="modal-order-info" style="background-color: lightgrey; padding: 10px; border-radius: 3px;"></div>
                        <div class="form-group">
                            <label>Payment Type</label>
                            <select class="form-control" id="payment_type" name="payment_type">
                                <option value="">Select Payment Type</option>
                                <option value="1">Cheque</option>
                                <option value="2">Cash</option>
                            </select>
                        </div>

                            <div class="form-group">
                                <label> Account </label>
                                <select class="form-control select2" style="width: 100%" id="account" name="account">
                                    <option value=""> Select Cash/Bank Account </option>
                                </select>
                            </div>

                            <div class="form-group bank-area" style="display: none">
                                <label>Cheque No.</label>
                                <input class="form-control" type="text" name="cheque_no" placeholder="Cheque No.">
                            </div>

                            <div class="form-group bank-area" style="display: none">
                                <label>Cheque date</label>
                                <input class="form-control" type="text" autocomplete="off" id="cheque_date" name="cheque_date" placeholder="Enter Cheque Date">
                            </div>

                            <div class="form-group bank-area" style="display: none">
                                <label> Cheque image </label>
                                <input class="form-control" name="cheque_image" type="file">
                            </div>

                            <div class="form-group bank-area" style="display: none">
                                <label for="issuing_bank_name">Issuing Bank Name</label>
                                <input type="text"  id="issuing_bank_name" name="issuing_bank_name" class="form-control" placeholder="Enter Issuing Bank Name">
                            </div>
                            <div class="form-group bank-area" style="display: none">
                                <label for="issuing_branch_name">Issuing Branch Name </label>
                                <input type="text" value="" id="issuing_branch_name" name="issuing_branch_name" class="form-control" placeholder="Enter Issuing Bank Branch Name">
                            </div>

                        <div class="form-group">
                            <label>Total Amount</label>
                            <input class="form-control" name="total_amount" id="total_amount" readonly placeholder="Total Amount">
                        </div>
                        <div class="form-group">
                            <label>Paid Amount</label>
                            <input class="form-control" name="paid_amount" id="paid_amount" readonly placeholder="Paid Amount">
                        </div>
                        <div class="form-group">
                            <label>Due Amount</label>
                            <input class="form-control" id="due_amount" name="due_amount" readonly placeholder="Due Amount">
                        </div>
                        <div class="form-group">
                            <label>Pay Amount</label>
                            <input class="form-control" name="amount" placeholder="Pay Amount">
                        </div>

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
                            <label>Note</label>
                            <input class="form-control" name="note" placeholder="Enter Note">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-pay">Pay</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- <div class="modal fade" id="modal-delivery">
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
    </div> --}}
    <!-- /.modal -->
@endsection

@section('script')

    <script>
        $(function () {

            intSelect2();

            var selectedOrderId;

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('scrap_sale_receipt.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    // {data: 'discount', name: 'discount'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 0, "desc" ]],
            });

            $('body').on('click', '.btn-pay', function () {
                var clientId = $(this).data('id');
                var clientName = $(this).data('name');

                $('#modal-order-info').hide();
                $('#modal-order').html('<option value="">Select Order No.</option>');
                $('#payment_step_area').hide();
                $('#modal-name').val(clientName);
                $('#client_id').val(clientId);
                $('#modal-pay').modal('show');

                $.ajax({
                    method: "GET",
                    url: "{{ route('get_scrap_data') }}",
                    data: { clientId: clientId }
                }).done(function( response ) {
                    $('#total_amount').val(response.total)
                    $('#paid_amount').val(response.paid)
                    $('#due_amount').val(response.due)
                    $('#projects_id').val(response.project_id)

                    // $.each(response, function( index, item ) {
                    //     console.log(item);
                    //     $('#projects_id').append('<option value="'+item.id+'">'+item[0]+'</option>');
                    // });

                    $('#modal-pay').modal('show');
                });

            });


            $('#modal-btn-pay').click(function () {
                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('get_scrap_data_post') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-pay').modal('hide');
                            Swal.fire(
                                'Paid!',
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



            $('#modal-order').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info').hide();

                if (orderId != '') {

                    $.ajax({
                        method: "GET",
                        url: "{{ route('client_payment.payment_step') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        $('#payment_step').val(response.payment_step);
                        $('#payment_step_no').val(response.payment_step_status);
                        if (response.payment_step_status == 3){
                            $("#installments_area").show();
                        }else{
                            $("#installments_area").hide();
                        }
                        if (response.payment_step_status == 4){
                            $('#payment_amount_area').hide();
                            $('#installment_area').show();
                            $('#per_installment_amount').val(response.per_installment_amount);
                        }else{
                            $('#installment_area').hide();
                            $('#payment_amount_area').show();
                        }
                        $('#payment_step_area').show();
                    });

                    $.ajax({
                        method: "GET",
                        url: "{{ route('client_payment.order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        $('#modal-order-info').html('<strong>Total: </strong>৳ '+parseFloat(response.total)+' <strong>Paid: </strong>৳ '+parseFloat(response.paid)+' <strong>Due: </strong>৳ '+parseFloat(response.due));
                        $('#modal-order-info').show();
                    });
                }
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


            $("#payment_type").change(function (){
                var payType = $(this).val();

                if(payType != ''){
                    if(payType == 1){
                        $(".bank-area").show();
                    }else{
                        $(".bank-area").hide();
                    }
                }
            })


            function intSelect2(){
            $('.select2').select2()
            $('#account').select2({
                ajax: {
                    url: "{{ route('account_head_code.json') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $('#account').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#account").index(this);
                $('#account_name:eq('+index+')').val(data.text);
            });

            $('#refund_account').select2({
                ajax: {
                    url: "{{ route('account_head_code.json') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $('#refund_account').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#account").index(this);
                $('#refund_account_name:eq('+index+')').val(data.text);
            });

        }


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
