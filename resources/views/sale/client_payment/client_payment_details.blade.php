@extends('layouts.app')

@section('title')
    Client Received Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Client Name</label>
                                    <input class="form-control" readonly type="text" value="{{$client->name}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project Name</label>
{{--                                    <input class="form-control" readonly type="text" value="{{$receipt_payment->project->flat->name??''}}">--}}
                                    <input class="form-control" readonly type="text" value="{{$receipt_payment->project->name??''}}">
                                </div>
                            </div>

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Flat Name</label>--}}
{{--                                    <input class="form-control" readonly type="text" value="{{$receipt_payment->project->flat->name??''}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>Floor Name</label>--}}
{{--                                    <input class="form-control" readonly type="text" value="{{$receipt_payment->project->floor->name??''}}">--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Payment</label>
                                    <input class="form-control" readonly type="text" value="{{$client->orderPaid}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Due</label>
                                    <input class="form-control" readonly type="text" value="{{$client->orderDue}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>FS Year</th>
                                        <th>Type</th>
                                        <th>Voucher/Receipt No</th>
                                        <th>Cash/Bank Account</th>
                                        <th>Client name</th>
                                        <th>Income Code</th>
                                        <th>Net Amount</th>
                                        <th>Installment</th>
                                        <th width="14%">Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
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
                            <select class="form-control select2" style="width: 100%" name="financial_year" id="financial_year">
                                <option value="">Select Year</option>
                                @for($i=2022; $i <= date('Y'); $i++)
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
                        {{--                        <div id="payment_step_area" style="display: none" class="form-group">--}}
                        {{--                            <label>Payment Step</label>--}}
                        {{--                            <input type="hidden" id="payment_step_no" name="payment_step_no">--}}
                        {{--                            <input type="text" readonly class="form-control" id="payment_step" name="payment_step">--}}
                        {{--                         </div>--}}
                        {{--                        <div id="installment_area" style="display: none" class="form-group">--}}
                        {{--                            <label>Per Installment Amount</label>--}}
                        {{--                            <input type="text" class="form-control" id="per_installment_amount" name="per_installment_amount">--}}
                        {{--                        </div>--}}
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


                        {{--                        <div class="form-group" id="payment_amount_area">--}}
                        <div class="form-group">
                            <label>Amount</label>
                            <input class="form-control" name="amount" placeholder="Enter Amount">
                        </div>
                        {{--                        <div style="display: none" class="form-group" id="installments_area">--}}
                        {{--                            <label>Total Installments</label>--}}
                        {{--                            <input class="form-control" value="36" name="installments" placeholder="Enter Installments">--}}
                        {{--                        </div>--}}
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
@endsection

@section('script')
    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('receipt_sale_payment.datatable') }}",
                    data: function (d) {
                        d.client_id = '{{ $client->id }}'
                    }
                },
                "pagingType": "full_numbers",
                "dom": 'T<"clear">lfrtip',
                "lengthMenu": [[10, 25, 50, -1],[10, 25, 50, "All"]
                ],
                columns: [
                    { data: 'date',name:'date'},
                    { data: 'financial_year',name:'financial_year'},
                    { data: 'transaction_type',name:'transaction_type'},
                    { data: 'receipt_payment_no',name:'receipt_payment_no'},
                    {data: 'account_head', name: 'account_head.name'},
                    {data: 'client_name', name: 'client.name'},
                    {data: 'expenses_code', name: 'expenses_code',searchable: false},
                    {data: 'net_amount', name: 'net_amount'},
                    {data: 'notes', name: 'notes'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'asc']],

                "responsive": true, "autoWidth": false,
            });

            $('body').on('click', '.btn-delete', function () {

                var receiptPaymentId = $(this).data('id');
              //  alert(orderId);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "Post",
                            url: "{{ route('receipt_details.delete') }}",
                            data: { receiptPaymentId: receiptPaymentId }
                        }).done(function( response ) {
                            if (response.success) {
                                Swal.fire(
                                    'Delete!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                    //window.location.href = response.redirect_url;
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


            $('body').on('click', '.btn-edit', function () {
                var receiptId = $(this).data('id');
                var clientId = $(this).data('client-id');
                var saleOrderId = $(this).data('sale-order-id');
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
                    console.log(response);
                    $.each(response, function( index, item ) {
                        if (item.id == saleOrderId)
                         $('#modal-order').append('<option value="'+item.id+'" selected>'+item.order_no+'</option>');
                        else
                         $('#modal-order').append('<option value="'+item.id+'">'+item.order_no+'</option>');
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

        })
    </script>
@endsection

