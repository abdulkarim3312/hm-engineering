@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Loan
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
                    <a class="btn btn-primary" href="{{ route('loan.add') }}">Add Loan</a>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Loan Type</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- /.modal -->
    <div class="modal fade" id="modal-pay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Payment Information</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-form" enctype="multipart/form-data" name="modal-form">
                        <div class="form-group">
                            <label>Name </label>
                            <input class="form-control" id="modal-name" disabled>
                            <input type="hidden" id="client_id" name="client_id">
                            <input type="hidden" id="loan_type_id" name="loan_type_id">
                        </div>

                        <div class="form-group">
                            <label> Loan Number <span style="color: red">*</span> </label>
                            <select class="form-control" id="modal-order" name="loan_id">
                                <option value=""> Select Loan Number </option>
                            </select>
                        </div>

                        <div id="modal-order-info" style="background-color: lightgrey; padding: 10px; border-radius: 3px;"></div>


                        <div class="form-group">
                            <label> Payment Method <span style="color: red">*</span></label>
                            <select class="form-control" id="modal-pay-type" name="payment_type">
                                <option value="1">Cash</option>
                                <option value="2">Bank</option>
                            </select>
                        </div>

                        <div id="modal-bank-info">
                            <div class="form-group">
                                <label>Bank</label>
                                <select class="form-control" id="modal-bank" name="bank">
                                    <option value="">Select Bank </option>

                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label> Branch</label>
                                <select class="form-control" id="modal-branch" name="branch">
                                    <option value=""> Select Branch</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label> Account </label>
                                <select class="form-control" id="modal-account" name="account">
                                    <option value=""> Select Account </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label> Cheque Number </label>
                                <input class="form-control" type="text" name="cheque_no" placeholder="Cheque Number ">
                            </div>

                            <div class="form-group">
                                <label> Cheque Image </label>
                                <input class="form-control" name="cheque_image" type="file">
                            </div>
                        </div>

                        <div class="form-group">
                            <label> Ammount <span style="color: red"> *</span> </label>
                            <input class="form-control" name="amount" placeholder="Enter Amount">
                        </div>

                        <div class="form-group">
                            <label>Date </label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="date" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->
                        </div>

                        <div class="form-group">
                            <label> Note </label>
                            <input class="form-control" name="note" placeholder="Note">
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
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('client_loan.datatable') }}',
                columns: [
                    {data: 'client', name: 'client'},
                    {data: 'loan_type', name: 'loan_type'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due', name: 'due'},
                    {data: 'action', name: 'action', orderable: false},
                ],
            });

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('click', '.btn-pay', function () {
                var clientId = $(this).data('id');
                var loanType = $(this).data('type-id');
                var holderName = $(this).data('name');

                $('#modal-order-info').hide();
                $('#modal-order').html('<option value="">Select Loan Number.</option>');
                $('#modal-name').val(holderName);
                $('#client_id').val(clientId);
                $('#loan_type_id').val(loanType);
                $('#modal-pay').modal('show');
                $.ajax({
                    method: "GET",
                    url: "{{ route('client_loan_payment.get_number') }}",
                    data: { clientId: clientId,loanType: loanType }
                }).done(function( response ) {
                    console.log(response);
                    $.each(response, function( index, item ) {
                        $('#modal-order').append('<option value="'+item.id+'">'+item.loan_number+'</option>');
                    });

                    $('#modal-pay').modal('show');
                });
            });

            $('#modal-btn-pay').click(function () {
                var formData = new FormData($('#modal-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('client_loan_payment.make_payment') }}",
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

            $('#modal-pay-type').trigger('change');

            $('#modal-order').change(function () {
                var loanId = $(this).val();
                $('#modal-order-info').hide();

                if (loanId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('client_loan_payment.order_details') }}",
                        data: { loanId: loanId }
                    }).done(function( response ) {
                        $('#modal-order-info').html('<strong>Total: </strong>৳ '+parseFloat(response.total)+' <strong>Paid: </strong>৳ '+parseFloat(response.paid)+' <strong>Due: </strong>৳ '+parseFloat(response.due));
                        $('#modal-order-info').show();
                    });
                }
            });

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '1') {
                    $('#modal-bank-info').hide();
                } else {
                    $('#modal-bank-info').show();
                }
            });

            $('#modal-pay-type').trigger('change');
            {{--$('#modal-btn-pay').click(function () {--}}
            {{--    var formData = new FormData($('#modal-form')[0]);--}}

            {{--    $.ajax({--}}
            {{--        type: "POST",--}}
            {{--        url: "{{ route('supplier_payment.make_payment') }}",--}}
            {{--        data: formData,--}}
            {{--        processData: false,--}}
            {{--        contentType: false,--}}
            {{--        success: function(response) {--}}
            {{--            if (response.success) {--}}
            {{--                $('#modal-pay').modal('hide');--}}
            {{--                Swal.fire(--}}
            {{--                    'Paid!',--}}
            {{--                    response.message,--}}
            {{--                    'success'--}}
            {{--                ).then((result) => {--}}
            {{--                    //location.reload();--}}
            {{--                    window.location.href = response.redirect_url;--}}
            {{--                });--}}
            {{--            } else {--}}
            {{--                Swal.fire({--}}
            {{--                    icon: 'error',--}}
            {{--                    title: 'Oops...',--}}
            {{--                    text: response.message,--}}
            {{--                });--}}
            {{--            }--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

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


        });
    </script>
@endsection
