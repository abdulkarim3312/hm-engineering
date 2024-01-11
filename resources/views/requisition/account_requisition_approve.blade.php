@extends('layouts.app')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style>
        select.form-control.product {
            width: 138px !important;
        }
        input.form-control.quantity {
            width: 90px;
        }
        input.form-control.unit_price,input.form-control.selling_price{
            width: 130px;
        }
        th {
            text-align: center;
        }
        select.form-control {
            min-width: 120px;
        }
        .cart-box-body{
            padding: 20px 30px;
        }
        .box-header.with-border{
            /* padding-left: 30px; */
        }
        .cart-box-footer{
            padding: 16px 30px;
            border-top: 1px solid #f5f5f5;
        }
        .card-sub-title{
            font-size: 18px;
        }
    </style>
@endsection
@section('title')
    Product Requisition Approve
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="card-sub-title">Requisition Product Approved Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('product_requisition_approve_account', [$requisition->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Requisition No.</th>
                                        <td>{{ $requisition->requisition_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Requisition Date</th>
                                        <td>{{ $requisition->date->format('d-m-Y, h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <th>
                                            @if ($requisition->status == 0)
                                                <span class="badge badge-warning text-white" style="background: #FFC107; color:#000000;">Pending</span>
                                            @elseif($requisition->status == 1)
                                                <span class="badge badge-info text-white" style="background: #31D2F2;">Approved By Admin</span>
                                            @elseif($requisition->status == 3)
                                                <span class="badge badge-success text-white" style="background: #04D89D;">Delivered</span>
                                            @elseif($requisition->status == 4)
                                                <span class="badge badge-success text-white" style="background: #04D89D;">Received</span>
                                            @endif
                                        </th>
                                    <tr>
                                        @if ($requisition->requisition_note)
                                    <tr>
                                        <th>Requisition Note</th>
                                        <td>{{ $requisition->requisition_note }}</td>
                                    </tr>
                                    @endif
                                    @if ($requisition->approved_note)
                                        <tr>
                                            <th>Requisition Approved Note</th>
                                            <td>{{ $requisition->approved_note }}</td>
                                        </tr>
                                    @endif
                                    @if ($requisition->delivered_note)
                                        <tr>
                                            <th>Requisition Delivered Note</th>
                                            <td>{{ $requisition->delivered_note }}</td>
                                        </tr>
                                    @endif
                                    @if ($requisition->received_note)
                                        <tr>
                                            <th>Requisition Received Note</th>
                                            <td>{{ $requisition->received_note }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2" class="text-center">Requisition Info</th>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td>{{ $requisition->project->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Segment Name</th>
                                        <td>{{ $requisition->segment->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $requisition->project->address ?? '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th width="20%">Approved Quantity</th>
                                        <th width="20%">Approved Unit Price</th>
                                        <th>Total Cost</th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                    @foreach ($requisition->requisitionDetails as $projectSegmentDetails)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group">
                                                    <input readonly type="text" name="product[]"
                                                        value="{{ $projectSegmentDetails->name }}" class="form-control">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" readonly
                                                        class="form-control quantity" name="quantity[]"
                                                        value="{{ $projectSegmentDetails->approved_quantity ?? '' }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control unit_price"
                                                        name="unit_price[]"
                                                        value="{{ $projectSegmentDetails->approved_unit_price ?? '' }}" readonly>
                                                </div>
                                            </td>

                                            <td class="total-cost">৳0.00</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td>

                                        </td>
                                        <th colspan="2" class="text-right">Total Amount</th>
                                        <th id="total-amount">৳0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="">
                                                <div class="form-group">
                                                    <label>Payment Type</label>
                                                    <select class="form-control" id="modal-pay-type" name="payment_type">
                                                        <option value="1"
                                                            {{ old('payment_type') == '1' ? 'selected' : '' }}>Cash
                                                        </option>
                                                        <option value="2"
                                                            {{ old('payment_type') == '2' ? 'selected' : '' }}>Bank
                                                        </option>
                                                        <option value="3"
                                                            {{ old('payment_type') == '3' ? 'selected' : '' }}>Mobile
                                                            Banking</option>
                                                    </select>
                                                </div>
                                                <div id="modal-bank-info">
                                                    <div class="form-group {{ $errors->has('bank') ? 'has-error' : '' }}">
                                                        <label>Bank</label>
                                                        <select class="form-control" id="modal-bank" name="bank">
                                                            <option value="">Select Bank</option>
                                                            @foreach ($banks as $bank)
                                                                <option value="{{ $bank->id }}"
                                                                    {{ old('bank') == $bank->id ? 'selected' : '' }}>
                                                                    {{ $bank->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div
                                                        class="form-group {{ $errors->has('branch') ? 'has-error' : '' }}">
                                                        <label>Branch</label>
                                                        <select class="form-control" id="modal-branch" name="branch">
                                                            <option value="">Select Branch</option>
                                                        </select>
                                                    </div>

                                                    <div
                                                        class="form-group {{ $errors->has('account') ? 'has-error' : '' }}">
                                                        <label>Account</label>
                                                        <select class="form-control" id="modal-account" name="account">
                                                            <option value="">Select Account</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Cheque No.</label>
                                                        <input class="form-control" type="text" name="cheque_no"
                                                            placeholder="Enter Cheque No."
                                                            value="{{ old('cheque_no') }}">
                                                    </div>

                                                    <div
                                                        class="form-group {{ $errors->has('cheque_image') ? 'has-error' : '' }}">
                                                        <label>Cheque Image</label>
                                                        <input class="form-control" name="cheque_image" type="file">
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group"> --}}
                                                {{-- <label>Amount</label> --}}
                                                {{-- <input class="form-control" type="text" name="amount" placeholder="Enter amount" value="{{ old('amount') }}"> --}}
                                                {{-- </div> --}}
                                                <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                                                    <label for="date">Date</label>

                                                    <div class="input-group date">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="date"
                                                            name="date"
                                                            value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    <!-- /.input group -->
                                                    @error('date')
                                                        <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
                                                <label>Approve Note</label>
                                                <textarea name="note" class="form-control" rows="3" placeholder="Enter approve note">{{ old('note') }}</textarea>
                                                <!-- /.input group -->

                                                @error('note')
                                                    <span class="help-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('body').on('keyup', '.quantity, .unit_price', function() {
                calculate();
            });

            initProduct();
            calculate();
        });

        var message = '{{ session('message') }}';

        if (!window.performance || window.performance.navigation.type != window.performance.navigation.TYPE_BACK_FORWARD) {
            if (message != '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message,
                });
            }
        }

        //payment
        $('#modal-pay-type').change(function() {
            if ($(this).val() == '1' || $(this).val() == '3' || $(this).val() == '4') {
                $('#modal-bank-info').hide();
            } else {
                $('#modal-bank-info').show();
            }
        });

        $('#modal-pay-type').trigger('change');

        var selectedBranch = '{{ old('branch') }}';
        var selectedAccount = '{{ old('account') }}';

        $('#modal-bank').change(function() {
            var bankId = $(this).val();
            $('#modal-branch').html('<option value="">Select Branch</option>');
            $('#modal-account').html('<option value="">Select Account</option>');

            if (bankId != '') {
                $.ajax({
                    method: "GET",
                    url: "{{ route('get_branch') }}",
                    data: {
                        bankId: bankId
                    }
                }).done(function(response) {
                    $.each(response, function(index, item) {
                        if (selectedBranch == item.id)
                            $('#modal-branch').append('<option value="' + item.id + '" selected>' +
                                item.name + '</option>');
                        else
                            $('#modal-branch').append('<option value="' + item.id + '">' + item
                                .name + '</option>');
                    });

                    $('#modal-branch').trigger('change');
                });
            }

            $('#modal-branch').trigger('change');
        });

        $('#modal-branch').change(function() {
            var branchId = $(this).val();
            $('#modal-account').html('<option value="">Select Account</option>');

            if (branchId != '') {
                $.ajax({
                    method: "GET",
                    url: "{{ route('get_bank_account') }}",
                    data: {
                        branchId: branchId
                    }
                }).done(function(response) {
                    $.each(response, function(index, item) {
                        if (selectedAccount == item.id)
                            $('#modal-account').append('<option value="' + item.id + '" selected>' +
                                item.account_no + '</option>');
                        else
                            $('#modal-account').append('<option value="' + item.id + '">' + item
                                .account_no + '</option>');
                    });
                });
            }
        });

        $('#modal-bank').trigger('change');

        function calculate() {
            var total = 0;

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq(' + i + ')').val();
                var unit_price = $('.unit_price:eq(' + i + ')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq(' + i + ')').html('৳ ' + (quantity * unit_price).toFixed(2));
                total += quantity * unit_price;
            });

            $('#total-amount').html('৳ ' + total.toFixed(2));
        }

        function initProduct() {

        }
    </script>
@endsection
