@extends('layouts.app')

@section('title')
    Vendor Payment
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
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                {{-- <th>Discount</th> --}}
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($vendors as $vendor)
                                <tr>
                                    <td>{{ $vendor->name }}</td>
                                    <td>{{ $vendor->mobile }}</td>
                                    <td> {{ number_format($vendor->total, 2) }}</td>
                                    <td> {{ number_format($vendor->paid, 2) }}</td>
                                    <td> {{ number_format($vendor->due, 2) }}</td>
                                    {{-- <td> {{ number_format($vendor->discount, 2) }}</td> --}}
                                    <td>
                                        <a class="btn btn-success btn-sm btn-pay" role="button" data-id="{{ $vendor->id }}" data-name="{{ $vendor->name }}">Pay</a>
                                        <a href="{{route('vendor_payment.details',['vendor'=>$vendor->id])}}" class="btn btn-primary btn-sm" >Details</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
                            <label>Name</label>
                            <input class="form-control" id="modal-name" disabled>
                            <input type="hidden" id="supplier_id" name="supplier">
                        </div>

                        <div class="form-group">
                            <label>Project</label>
                            <select class="form-control" id="project" name="project">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Payment Type</label>
                            <select class="form-control" id="payment_type" name="payment_type">
                                <option value="">Select Payment Type</option>
                                <option {{ old('payment_type') == 1 ? 'selected' : '' }} value="1">Cheque</option>
                                <option {{ old('payment_type') == 2 ? 'selected' : '' }} value="2">Cash</option>
                                <option {{ old('payment_type') == 3 ? 'selected' : '' }} value="3">BGM</option>
                                <option {{ old('payment_type') == 4 ? 'selected' : '' }} value="4">LC</option>
                                <option {{ old('payment_type') == 5 ? 'selected' : '' }} value="5">Chalan</option>
                                <option {{ old('payment_type') == 6 ? 'selected' : '' }} value="6">TT</option>
                                <option {{ old('payment_type') == 7 ? 'selected' : '' }} value="7">BFTN</option>
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
                            <input class="form-control date-picker" type="text" autocomplete="off"  name="cheque_date" placeholder="Enter Cheque Date">
                        </div>

                        <div class="form-group bank-area" style="display: none">
                            <label> Cheque image </label>
                            <input class="form-control" name="cheque_image" type="file">
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input class="form-control" name="amount" placeholder="Enter Amount">
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
    <!-- /.modal -->
    <div class="modal fade" id="modal-refund">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Refund Information</h4>
                </div>
                <div class="modal-body">
                    <form id="refund-form" enctype="multipart/form-data" name="refund-form">
                        <div class="form-group">
                            <label for="financial_year">Select Financial Year <span
                                    class="text-danger">*</span></label>
                            <select class="form-control select2" style="width: 100%" name="financial_year">
                                <option value="">Select Year</option>
                                @for($i=2022; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ old('financial_year') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" id="modal-name-refund" disabled>
                        </div>

                        <div class="form-group">
                            <label>Order</label>
                            <select class="form-control select2" style="width: 100%" id="modal-order-refund" name="order">
                                <option value="">Select Order</option>
                            </select>
                        </div>

                        <div id="modal-order-info-refund" style="background-color: lightgrey; padding: 10px; border-radius: 3px;"></div>

                        <div class="form-group">
                            <label>Payment Type</label>
                            <select class="form-control" id="payment_type_refund" name="payment_type">
                                <option value="">Payment Type</option>
                                <option value="1">Cheque</option>
                                <option value="2">Cash</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label> Account </label>
                            <select class="form-control select2" style="width: 100%" id="refund_account" name="account">
                                <option value=""> Select Cash/Bank Account </option>
                            </select>
                        </div>
                        <div class="form-group bank-area" style="display: none">
                            <label>Cheque No.</label>
                            <input class="form-control" type="text" name="cheque_no" placeholder="Cheque No.">
                        </div>
                        <div class="form-group bank-area" style="display: none">
                            <label>Cheque date</label>
                            <input class="form-control date-picker" type="text" autocomplete="off" id="cheque_date" name="cheque_date" placeholder="Enter Cheque Date">
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
                            <label>Amount</label>
                            <input class="form-control" name="amount" placeholder="Enter Amount">
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right date-picker" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
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
                    <button type="button" class="btn btn-primary" id="modal-btn-refund">Refund</button>
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
            $('#table').DataTable();
            intSelect2();
            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            {{--$('body').on('click', '.btn-pay',function () {--}}
            {{--    var supplierId = $(this).data('id');--}}
            {{--    var supplierName = $(this).data('name');--}}
            {{--    $('#modal-order').html('<option value="">Select Order</option>');--}}
            {{--    $('#modal-order-info').hide();--}}
            {{--    $('#modal-project-info').hide();--}}
            {{--    $('#modal-name').val(supplierName);--}}
            {{--    $('#supplier_id').val(supplierId);--}}

            {{--    $.ajax({--}}
            {{--        method: "GET",--}}
            {{--        url: "{{ route('supplier_payment.get_orders') }}",--}}
            {{--        data: { supplierId: supplierId }--}}
            {{--    }).done(function( response ) {--}}
            {{--        $.each(response, function( index, item ) {--}}
            {{--            $('#modal-order').append('<option value="'+item.id+'">'+item.order_no+'-'+item.project.name+'</option>');--}}
            {{--        });--}}

            {{--        $('#modal-pay').modal('show');--}}
            {{--    });--}}
            {{--});--}}

            $('body').on('click', '.btn-pay', function () {
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
                }).done(function (response) {
                    $.each(response, function (index, item) {
                        $('#modal-order').append('<option value="' + item.id + '">' + item.order_no  + '</option>');
                    });

                    // Show the payment modal without checking for projects
                    $('#modal-pay').modal('show');
                });


                $.ajax({
                    method: "GET",
                    url: "{{ route('supplier_payment.get_orders') }}",
                    data: { supplierId: supplierId }
                }).done(function (response) {
                    $.each(response, function (index, item) {
                        $('#modal-order').append('<option value="' + item.id + '">' + item.order_no + '-' + item.project.name + '</option>');
                    });

                    // Show the payment modal without checking for projects
                    $('#modal-pay').modal('show');
                });
            });


            $('#modal-btn-pay').click(function () {
                var formData = new FormData($('#modal-form')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('vendor_make_payment') }}",
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
                $('#modal-project-info').hide();

                if (orderId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('supplier_payment.order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        $('#modal-order-info').html('<strong>Total: </strong>'+parseFloat(response.total)+' <strong>Paid: </strong>'+parseFloat(response.paid)+' <strong>Due: </strong>'+parseFloat(response.due));
                        $('#modal-project-name').val(response.project.name);
                        $('#modal-project-id').val(response.project_id);
                        $('#modal-order-info').show();
                        $('#modal-project-info').show();
                    });
                }
            });
            // Refund
            $('body').on('click', '.btn-refund', function () {
                var supplierId = $(this).data('id');
                var supplierName = $(this).data('name');
                $('#modal-order-refund').html('<option value="">Select Order</option>');
                $('#modal-order-info-refund').hide();
                $('#modal-name-refund').val(supplierName);

                $.ajax({
                    method: "GET",
                    url: "{{ route('supplier_payment.get_refund_orders') }}",
                    data: { supplierId: supplierId }
                }).done(function( response ) {
                    $.each(response, function( index, item ) {
                        $('#modal-order-refund').append('<option value="'+item.id+'">'+item.order_no+'</option>');
                    });
                    $('#modal-refund').modal('show');
                });
            });

            $('#modal-pay-type-refund').change(function () {
                if ($(this).val() == '2') {
                    $('#modal-bank-info-refund').show();
                } else {
                    $('#modal-bank-info-refund').hide();
                }
            });

            $('#modal-order-refund').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info-refund').hide();

                if (orderId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('supplier_payment.order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        $('#modal-order-info-refund').html('<strong>Total: </strong>'+response.total.toFixed(2)+' <strong>Paid: </strong>'+response.paid.toFixed(2)+' <strong>Due: </strong>'+response.due.toFixed(2)+' <strong>Refund: </strong>'+response.refund.toFixed(2));
                        $('#modal-order-info-refund').show();
                    });
                }
            });

            $('#modal-btn-refund').click(function () {
                var formData = new FormData($('#refund-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('supplier_payment.make_refund') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-refund').modal('hide');
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

            $("#payment_type").trigger("change");
            $("#payment_type_refund").change(function (){
                var payType = $(this).val();

                if(payType != ''){
                    if(payType == 1){
                        $(".bank-area").show();
                    }else{
                        $(".bank-area").hide();
                    }
                }
            })
            $("#payment_type_refund").trigger("change");
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
    </script>
@endsection
