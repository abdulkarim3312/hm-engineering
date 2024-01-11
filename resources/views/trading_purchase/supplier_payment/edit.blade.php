@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Supplier Payment Edit
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box">
                <div class="box-body">
                    <div class="card " id="modal-pay">
                        <div class="card-header">
                            <h3 class="card-title text-center">Payment Information</h3>
                        </div>
                        <div class="card-body">
                            <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control" value="{{$payment->supplier->name}}" id="modal-name" disabled>
                                    <input type="hidden" id="supplier_id" value="{{$payment->supplier_id}}" name="supplier">
                                </div>

                                <div class="form-group">
                                    <label>Order</label>
                                    <select class="form-control" id="modal-order" name="order">
                                        <option value="">Select Order</option>
                                        @foreach($orders as $order)
                                            <option value="{{$order->id}}" {{$payment->purchase_order_id==$order->id?"selected":''}}>{{$order->order_no}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="modal-order-info" style="background-color: lightgrey; padding: 10px; border-radius: 3px;"></div>
                                <div class="form-group" id="modal-project-info">
                                    <label>Project</label>
                                    <input class="form-control" id="modal-project-name" disabled>
                                    <input type="hidden" id="modal-project-id" name="project">
                                </div>

                                <div class="form-group">
                                    <label>Payment Type</label>
                                    <select class="form-control" id="modal-pay-type" name="payment_type">
                                        <option value="1" {{$payment->transaction_method==1?"selected":''}}>Cash</option>
                                        <option value="2" {{$payment->transaction_method==2?"selected":''}}>Bank</option>
                                        <option value="3" {{$payment->transaction_method==3?"selected":''}}>Discount</option>
                                    </select>
                                </div>

                                <div id="modal-bank-info">
                                    <div class="form-group">
                                        <label>Bank</label>
                                        <select class="form-control" id="modal-bank" name="bank">
                                            <option value="">Select Bank</option>

                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="form-control" id="modal-branch" name="branch">
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Account</label>
                                        <select class="form-control" id="modal-account" name="account">
                                            <option value="">Select Account</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Cheque No.</label>
                                        <input class="form-control" type="text" name="cheque_no" placeholder="Enter Cheque No.">
                                    </div>

                                    <div class="form-group">
                                        <label>Cheque Image</label>
                                        <input class="form-control" name="cheque_image" type="file">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Amount</label>
                                    <input class="form-control" value="{{$payment->amount}}" name="amount" placeholder="Enter Amount">
                                </div>

                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ date('Y-m-d',strtotime($payment->date)) }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <div class="form-group">
                                    <label>Note</label>
                                    <input class="form-control" value="{{$payment->note}}" name="note" placeholder="Enter Note">
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary" id="modal-btn-pay">Pay</button>
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- /.modal -->
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            $('#table').DataTable();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('.btn-pay').click(function () {
                var supplierId = $(this).data('id');
                var supplierName = $(this).data('name');
                $('#modal-order').html('<option value="">Select Order</option>');
                $('#modal-order-info').hide();
                $('#modal-project-info').hide();
                $('#modal-name').val(supplierName);
                $('#supplier_id').val(supplierId);

                $.ajax({
                    method: "GET",
                    url: "{{ route('supplier_payment.get_orders') }}",
                    data: { supplierId: supplierId }
                }).done(function( response ) {
                    $.each(response, function( index, item ) {
                        $('#modal-order').append('<option value="'+item.id+'">'+item.order_no+'</option>');
                    });

                    $('#modal-pay').modal('show');
                });
            });

            $('#modal-btn-pay').click(function () {
                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('supplier_payment_edit',['payment'=>$payment->id]) }}",
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

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '2') {
                    $('#modal-bank-info').show();
                } else {
                    $('#modal-bank-info').hide();
                }
            });

            $('#modal-pay-type').trigger('change');

            $('#modal-order').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info').hide();
                $('#modal-project-info').hide();

                if (orderId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('supplier_payment.order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        $('#modal-order-info').html('<strong>Total: </strong>৳'+parseFloat(response.total)+' <strong>Paid: </strong>৳'+parseFloat(response.paid)+' <strong>Due: </strong>৳'+parseFloat(response.due));
                        $('#modal-project-name').val(response.project.name);
                        $('#modal-project-id').val(response.project_id);
                        $('#modal-order-info').show();
                        $('#modal-project-info').show();
                    });
                }
            });
            $('#modal-order').trigger('change');

            $('#modal-bank').change(function () {
                var bankId = $(this).val();
                $('#modal-branch').html('<option value="">Select Branch</option>');
                $('#modal-account').html('<option value="">Select Account</option>');

                if (bankId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_branch') }}",
                        data: { bankId: bankId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('#modal-branch').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#modal-branch').trigger('change');
                    });
                }

                $('#modal-branch').trigger('change');
            });

            $('#modal-branch').change(function () {
                var branchId = $(this).val();
                $('#modal-account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            $('#modal-account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });
        })
    </script>
@endsection
