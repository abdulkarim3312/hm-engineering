@extends('layouts.app')

@section('style')
    <style>
        .table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
            border-top: 0;
            white-space: nowrap;
        }
    </style>
@endsection

@section('title')
    Client Received
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Project</label>
                                <select class="form-control" id="project_name">
                                    <option value="all">Select Project Name</option>
                                    @foreach($projects as $project)
                                        <option data-client-id="{{$project->name}}">{{$project->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Project Name</th>
                                <th>Mobile</th>
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
                            <input type="hidden" id="client_id" name="client_id">
                        </div>

                        <div class="form-group">
                            <label>Order No</label>
                            <select class="form-control select2" style="width: 100%" id="modal-order" name="order_no">
                                <option value="">Select Order No.</option>
                            </select>
                        </div>

                        <div id="modal-order-info" style="background-color: lightgrey; padding: 10px; border-radius: 3px;"></div>

                        <div id="payment_step_area" style="display: none" class="form-group mt-3">
                            <label>Payment Step</label>
                            <select id="payment_step_show" name="payment_step"  class="form-control">
                                <option value="">Select Payment Step</option>
                                <option value="2">Down Payment</option>
                                <option value="3">Installment</option>
                            </select>
                        </div>
                        <div class="form-group payment-step-show" style="display: none">
                            <label>Installment Step</label>
                            <input class="form-control" type="text" name="installment_name" placeholder="installment step name">
                        </div>
                        <div id="installment_area" style="display: none" class="form-group">
                            <label>Per Installment Amount</label>
                            <input type="text" class="form-control" id="per_installment_amount" name="per_installment_amount">
                        </div>

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
                            <label>Next Payment Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="date" name="next_date" value="{{ date('Y-m-d') }}" autocomplete="off">
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
                    <form id="modal-form-refund" enctype="multipart/form-data" name="modal-form">
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
                            <input class="form-control" type="text" autocomplete="off" id="cheque_date" name="cheque_date" placeholder="Enter Cheque Date">
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
                                <input type="text" class="form-control pull-right" id="date-refund" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
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
                    <button type="button" class="btn btn-primary" id="modal-btn-refund">Pay</button>
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

            intSelect2();

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                pageLength:100,
                ajax: {
                    url: '{{ route('client_payment.datatable') }}',
                    data: function(d) {
                        // Add the client parameter to the request
                        d.project_name = $('#project_name').val();
                    }
                },
                {{--ajax: '{{ route('client_payment.datatable') }}',--}}
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'project', name: 'project'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    {data: 'discount', name: 'discount'},
                    //{data: 'refund', name: 'refund'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });

            //Date picker
            $('#date,#cheque_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
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
                    url: "{{ route('client_payment.get_orders') }}",
                    data: { clientId: clientId }
                }).done(function( response ) {
                    $.each(response, function( index, item ) {
                        $('#modal-order').append('<option value="'+item.id+'">'+item.order_no+'-'+item.floor.name+'-'+item.flat.name+'</option>');
                    });

                    $('#modal-pay').modal('show');
                });

            });

            $('#modal-btn-pay').click(function () {
                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('client_payment.make_payment') }}",
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



            // Refund
            $('body').on('click', '.btn-refund', function () {
                var customerId = $(this).data('id');
                var customerName = $(this).data('name');
                $('#modal-order-refund').html('<option value="">Select Order</option>');
                $('#modal-order-info-refund').hide();
                $('#modal-name-refund').val(customerName);

                $.ajax({
                    method: "GET",
                    url: "{{ route('customer_payment.get_refund_orders') }}",
                    data: { customerId: customerId }
                }).done(function( response ) {
                    $.each(response, function( index, item ) {
                        $('#modal-order-refund').append('<option value="'+item.id+'">'+item.order_no+'</option>');
                    });

                    $('#modal-refund').modal('show');
                });
            });
            $('#modal-order-refund').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info-refund').hide();

                if (orderId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_order_details') }}",
                        data: { orderId: orderId }
                    }).done(function( response ) {
                        due = response.due.toFixed(2);
                        $('#modal-order-info-refund').html('<strong>Total: </strong>৳'+response.total.toFixed(2)+' <strong>Paid: </strong>৳'+response.paid.toFixed(2)+' <strong>Due: </strong>৳'+response.due.toFixed(2)+' <strong>Refund: </strong>৳'+response.refund.toFixed(2));
                        $('#modal-order-info-refund').show();
                    });
                }
            });
            $('#modal-btn-refund').click(function () {
                var formData = new FormData($('#modal-form-refund')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('customer_payment.make_refund') }}",
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

            $("#payment_step_show").change(function (){
                var payType = $(this).val();

                if(payType != ''){
                    if(payType == 3){
                        $(".payment-step-show").show();
                    }else{
                        $(".payment-step-show").hide();
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
        });
        document.querySelector('#project_name').addEventListener('change', function() {
            // Get the selected project ID
            var selectedCompanyNameId = this.value;

            // Get all the rows in the table
            var rows = document.querySelectorAll('#table tbody tr');

            // Loop through each row
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];

                // Get the project name cell in the row
                var companyNameCell = row.querySelector('td:nth-child(2)');

                // If the selected project is "all" or the project name in the row matches the selected project, show the row
                if (selectedCompanyNameId === 'all' || companyNameCell.textContent === selectedCompanyNameId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
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
