@extends('layouts.app')
@section('title')
    Edit {{ $receiptPayment->payment_type == 1 ? 'Cheque Receipt' : 'Cash Receipt' }} # <b>{{ $receiptPayment->receipt_payment_no }}</b>
@endsection
@section('style')
    <style>
        .form-control {
            height: calc(1.9rem + 2px);
            font-size: .8rem;
            border-radius: 0;
        }
        .select2-container--default .select2-selection--single {
            height: calc(1.9rem + 2px);
            font-size: .8rem;
            border-radius: 0;
            padding: 0.26875rem 0.75rem;
        }
        .form-control::placeholder {
            color: #000000;
            opacity: 1; /* Firefox */
        }

        .form-control:-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: #000000;
        }

        .form-control::-ms-input-placeholder { /* Microsoft Edge */
            color: #000000;
        }
        .form-group {
            margin-bottom: 0.6rem;
        }

        legend {
            font-size: 1.4rem;
            font-weight: bold;
        }
        legend {
            color: #33b26f;
        }
        fieldset {
            border-width: 1.4px;
            margin-top: 10px;
            border-color: #33b26f;
        }
        .box-title {
            font-size: 1.5rem;
        }
        .table-bordered thead td, .table-bordered thead th {
            white-space: nowrap;
            font-size: 14px;
        }
        .table td, .table th {
            padding: 5px;
        }
        .table td .form-group{
            margin-bottom: 0!important;
        }
        label:not(.form-check-label):not(.custom-file-label) {
            font-size: 14px;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .account_head_code_area > .select2{
            width: 100% !important;
            /*max-width: 200px;*/
        }
        .table td, .table th {
            text-align: center;
        }
        .table td .form-group input{
            text-align: center;

        }
        .table.other th,.table.other td{
            text-align: left;
        }
        input.form-control.amount {
            /*width: 100px;*/
        }
        .bank_account_area > .select2 {
            width: 100% !important;
            max-width: 503px !important;
        }
        #payee_select_area > .select2 {
            width: 100% !important;
            max-width: 430px !important;
        }

    </style>
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="alert alert-danger">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- jquery validation -->
            <div class="box box-outline box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ $receiptPayment->payment_type == 1 ? 'Cheque Receipt' : 'Cash Receipt)' }} Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form enctype="multipart/form-data" action="{{ route('receipt.edit',['receiptPayment'=>$receiptPayment->id]) }}"  method="post">
                    @csrf
                    <div class="box-body">
                        <div class="receipt-payment-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Basic Information:</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                                                    <label for="financial_year">Select Financial Year <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="financial_year" id="financial_year" readonly value="{{ $receiptPayment->financial_year }}" class="form-control">
                                                    @error('financial_year')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                                    <label for="date">Date <span class="text-danger">*</span></label>
                                                    <input type="text" autocomplete="off" id="date" value="{{ old('date',$receiptPayment->date->format('d-m-Y')) }}" name="date" class="form-control date-picker" placeholder="Enter Date">
                                                    @error('date')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <input type="hidden" name="payment_type" value="{{ $receiptPayment->payment_type }}">
                                            @if($receiptPayment->payment_type == 1)
                                                <div class="col-md-12">
                                                    <div class="form-group bank_account_area {{ $errors->has('bank_cash_account_head') ? 'has-error' :'' }}">
                                                        <label for="bank_cash_account_head">Bank Account Code <span class="text-danger">*</span></label>
                                                        <input type="hidden" name="bank_cash_account_head" value="{{ $receiptPayment->account_head_id }}">
                                                        <input readonly id="bank_cash_account_head" value="{{ $receiptPayment->accountHead->name }}|{{ $receiptPayment->accountHead->account_code }}" type="text" class="form-control">

                                                        @error('bank_cash_account_head')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12">
                                                    <div class="form-group bank_account_area {{ $errors->has('bank_cash_account_head') ? 'has-error' :'' }}">
                                                        <label for="bank_cash_account_head">Cash In Hand Code <span class="text-danger">*</span></label>
                                                        <input type="hidden" name="bank_cash_account_head" value="{{ $receiptPayment->account_head_id }}">
                                                        <input readonly id="bank_cash_account_head" value="{{ $receiptPayment->accountHead->name }}|{{ $receiptPayment->accountHead->account_code }}" type="text" class="form-control">
                                                        @error('bank_cash_account_head')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                                                    <label for="payment_type">Project</label>
                                                    <select class="form-control select2 project" name="project" id="project">
                                                        <option value="">Select Project type</option>
                                                        @foreach($projects as $project)
                                                            <option value="{{ $project->id }}" {{ $project->id == $receiptPayment->project_id ? 'selected' : '' }}>{{ $project->name }}</option>
                                                        @endforeach

                                                    </select>
                                                    @error('project')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Depositor's Information:</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="client_type">Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="client_type" name="client_type">
                                                        <option {{ old('client_type',1) == 1 ? 'selected' : '' }} value="1">Existing</option>
                                                        <option {{ old('client_type') == 2 ? 'selected' : '' }} value="2">New</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" id="payee_select_area">
                                                <div class="form-group {{ $errors->has('payee') ? 'has-error' :'' }}">
                                                    <label for="payee">Depositor's <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="payee" style="width: 100%;" name="payee">
                                                        <option value="{{ $receiptPayment->client_id }}" selected>{{ $receiptPayment->client->id_no ?? '' }} - {{ $receiptPayment->client->name ?? '' }}</option>
                                                    </select>
                                                    @error('payee')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                    <input type="hidden" name="payee_select_name" id="payee_select_name" value="{{ $receiptPayment->client->name ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row hide client_name_area">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('payee_name') ? 'has-error' :'' }}">
                                                    <label for="payee_name">Depositor's Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="payee_name" value="{{ old('payee_name',$receiptPayment->client->name ?? '') }}" id="payee_name" class="form-control">
                                                    @error('payee_name')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('designation') ? 'has-error' :'' }}">
                                                    <label for="designation">Designation</label>
                                                    <input type="text" name="designation" id="designation" value="{{ old('designation',$receiptPayment->designation) }}" class="form-control" placeholder="Enter Designation">
                                                    @error('designation')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" >
                                                <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' :'' }}">
                                                    <label for="mobile_no">Depositor's Mobile No.</label>
                                                    <input type="text" name="mobile_no" id="mobile_no" value="{{ old('mobile_no',$receiptPayment->mobile_no) }}" class="form-control" placeholder="Enter Mobile No">
                                                    @error('mobile_no')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
                                                    <label for="email">Email</label>
                                                    <input type="text" name="email" value="{{ old('email',$receiptPayment->email) }}" id="email" class="form-control" placeholder="Enter Email">
                                                    @error('email')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" >
                                                <div class="form-group {{ $errors->has('e_tin') ? 'has-error' :'' }}">
                                                    <label for="e_tin">Depositor's eTIN</label>
                                                    <input type="text" name="e_tin" id="e_tin" value="{{ old('e_tin',$receiptPayment->e_tin) }}" class="form-control" placeholder="Enter eTin">
                                                    @error('e_tin')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('nature_of_organization') ? 'has-error' :'' }}">
                                                    <label for="nature_of_organization"> Nature of Organization</label>
                                                    <input type="text" name="nature_of_organization" value="{{ old('nature_of_organization',$receiptPayment->nature_of_organization) }}" id="nature_of_organization" class="form-control" placeholder="Enter Nature of Organization">
                                                    @error('nature_of_organization')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                @if($receiptPayment->payment_type == 1)
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend>Received Cheque Details Information:</legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('cheque_no') ? 'has-error' :'' }}">
                                                    <label for="cheque_no">Cheque No <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" value="{{ old('cheque_no',$receiptPayment->cheque_no) }}" id="cheque_no" name="cheque_no" class="form-control" placeholder="Enter Cheque no.">
                                                    @error('cheque_no')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('cheque_date') ? 'has-error' :'' }}">
                                                    <label for="cheque_date">Cheque Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" value="{{ old('cheque_date',$receiptPayment->cheque_date->format('d-m-Y')) }}" id="cheque_date" name="cheque_date" class="form-control date-picker" placeholder="Enter Cheque Date">
                                                    @error('cheque_date')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('issuing_bank_name') ? 'has-error' :'' }}">
                                                    <label for="issuing_bank_name">Issuing Bank Name</label>
                                                    <input type="text" value="{{ old('issuing_bank_name',$receiptPayment->issuing_bank_name) }}" id="issuing_bank_name" name="issuing_bank_name" class="form-control" placeholder="Enter Issuing Bank Name">
                                                    @error('issuing_bank_name')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('issuing_branch_name') ? 'has-error' :'' }}">
                                                    <label for="issuing_branch_name">Issuing Branch Name </label>
                                                    <input type="text" value="{{ old('issuing_branch_name',$receiptPayment->issuing_branch_name) }}" id="issuing_branch_name" name="issuing_branch_name" class="form-control" placeholder="Enter Issuing Bank Branch Name">
                                                    @error('issuing_branch_name')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </fieldset>
                                </div>
                                @endif
                                <div class="col-md-12 mt-3 mb-2">
                                    <div class="table-responsive">
                                        <fieldset>
                                            <legend>Received Details:</legend>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th width="60%">Account Head <span class="text-danger">*</span></th>
                                                    <th>Narration</th>
                                                    <th>Receive Amount <span class="text-danger">*</span></th>
                                                    <th></th>

                                                </tr>
                                                </thead>
                                                <tbody id="receipt-payment-container">
                                                @if (old('account_head_code') != null && sizeof(old('account_head_code')) > 0)
                                                    @foreach(old('account_head_code') as $item)
                                                        <tr class="receipt-payment-item">
                                                            <td>
                                                                <div class="form-group account_head_code_area {{ $errors->has('account_head_code.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <select class="form-control select2 account_head_code" name="account_head_code[]" data-placeholder="Search Account Head Code">
                                                                        <option value="">Select Account Head Code</option>
                                                                        @if (old('account_head_code.'.$loop->index) != '')
                                                                            <option value="{{ old('account_head_code.'.$loop->index) }}" selected>{{ old('account_head_code_name.'.$loop->index) }}</option>
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" name="account_head_code_name[]" class="account_head_code_name" value="{{ old('account_head_code_name.'.$loop->index) }}">

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group {{ $errors->has('narration.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <input type="text" value="{{ old('narration.'.$loop->index) }}" name="narration[]" class="form-control">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group {{ $errors->has('amount.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <input type="text" value="{{ old('amount.'.$loop->index) }}" name="amount[]" class="form-control amount">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a role="button" style="display: none" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-times"></i></a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @foreach($receiptPayment->receiptPaymentDetails as $receiptPaymentDetail)
                                                    <tr class="receipt-payment-item">

                                                        <td>
                                                            <div class="form-group account_head_code_area">
                                                                <select class="form-control select2 account_head_code" style="width: 100%;" name="account_head_code[]" data-placeholder="Search Account Head Code" required>
                                                                    <option value="">Search Account Head Code</option>
                                                                   <option value="{{ $receiptPaymentDetail->account_head_id }}" selected>{{ $receiptPaymentDetail->accountHead->account_code }}|{{ $receiptPaymentDetail->accountHead->name }}</option>
                                                                </select>
                                                                <input type="hidden" name="account_head_code_name[]" value="{{ $receiptPaymentDetail->accountHead->name }}" class="account_head_code_name">

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" value="{{ $receiptPaymentDetail->narration }}" name="narration[]"  class="form-control">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" value="{{ $receiptPaymentDetail->amount }}" name="amount[]"  class="form-control amount">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a role="button" style="display: none" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-times"></i></a>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-right">
                                                        Total
                                                    </th>
                                                    <th class="text-center" id="total-amount">0.00</th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-left" style="text-align: left">
                                                        <a role="button" class="btn btn-primary btn-sm" id="btn-add-voucher"><i class="fa fa-plus"></i></a>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                            <div class="row">
                                                <div class="col-md-6 col-md-offset-6">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th class="text-right">Net Receive Amount</th>
                                                            <th class="text-right" id="grand-total">0.00</th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Payment Details (Narration)</label>
                                                    <div class="form-group {{ $errors->has('notes') ? 'has-error' :'' }}">
                                                        <input type="text" value="{{ old('notes',$receiptPayment->notes) }}" name="notes" class="form-control" placeholder="Enter Payment Details (Narration)...">
                                                        @error('notes')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label>Supporting Documents</label>
                                                    <div class="form-group {{ $errors->has('supporting_document') ? 'has-error' :'' }}">
                                                        <input type="file" name="supporting_document[]" multiple class="form-control">
                                                        @error('supporting_document')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('receipt') }}" class="btn btn-default pull-right">Cancel</a>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->
    </div>
    <template id="receipt-payment-template">
        <tr class="receipt-payment-item">
            <td>
                <div class="form-group account_head_code_area">
                    <select class="form-control select2 account_head_code" style="width: 100%;" name="account_head_code[]" data-placeholder="Search Account Head Code">
                        <option value="">Search Account Head Code</option>
                    </select>
                    <input type="hidden" name="account_head_code_name[]" class="account_head_code_name">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="narration[]" class="form-control">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="amount[]" class="form-control amount">
                </div>
            </td>
            <td class="text-center">
                <a role="button" style="display: none" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-times"></i></a>
            </td>

        </tr>
    </template>
@endsection
@section('script')
    <script>
        $(function (){
            intSelect2();
            calculate();

            $('#client_type').change(function (){
                var clientType = $(this).val();
                if(clientType != ''){
                    if(clientType == 1){
                        $("#payee_select_area").show();
                        $(".client_name_area").hide();
                    }else{
                        $("#payee_select_area").hide();
                        $(".client_name_area").show();
                    }
                }
            })
            $('#client_type').trigger("change");


            $('#btn-add-voucher').click(function () {
                var html = $('#receipt-payment-template').html();
                var item = $(html);

                $('#receipt-payment-container').append(item);

                intSelect2();

                if ($('.receipt-payment-item').length >= 1 ) {
                    $('.btn-remove').show();
                }

                calculate();
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.receipt-payment-item').remove();
                if ($('.receipt-payment-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
                calculate();
            });


            if ($('.receipt-payment-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            $('body').on('keyup', '.amount', function () {
                calculate();
            });
            $('body').on('change', '.amount', function () {
                calculate();
            });

        });
        function calculate() {

            var totalAmount = 0;

            $('.receipt-payment-item').each(function(i, obj) {
                var amount = $('.amount:eq('+i+')').val();
                if (amount == '' || amount < 0 || !$.isNumeric(amount))
                    amount = 0;
                totalAmount += parseFloat(amount);

            });


            $('#total-amount').html(jsNumberFormat(totalAmount));


            var grandTotal = parseFloat(totalAmount);

            $('#grand-total').html(jsNumberFormat(grandTotal));
        }

        function intSelect2(){
            $('.date-picker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
            $('.select2').select2()

            $('#payee').select2({
                ajax: {
                    url: "{{ route('payee_json') }}",
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
            $('#payee').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#payee").index(this);
                $('#payee_select_name:eq('+index+')').val(data.text);
            });

            $('.account_head_code').select2({
                ajax: {
                    url: "{{ route('account_head_code_income.json') }}",
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
            $('.account_head_code').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $(".account_head_code").index(this);
                $('.account_head_code_name:eq('+index+')').val(data.text);
            });

        }
    </script>
@endsection
