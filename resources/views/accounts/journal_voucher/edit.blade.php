@extends('layouts.app')
@section('title')
    Edit Journal Voucher(JV) # <b>{{ $journalVoucher->jv_no }}</b>
@endsection
@section('style')
    <style>
        .row{
            margin: 0;
        }
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

        .table td .form-group {
            margin-bottom: 0 !important;
        }

        label:not(.form-check-label):not(.custom-file-label) {
            font-size: 14px;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .table td, .table th {
            text-align: center;
        }

        .table td .form-group input {
            text-align: center;

        }

        .table.other th, .table.other td {
            text-align: left;
        }

        .bank_account_area > .select2 {
            width: 100% !important;
            max-width: 430px !important;
        }

        #select_area > .select2 {
            width: 100% !important;
            max-width: 430px !important;
        }

        .table td .form-group {
            text-align: left;
        }
    </style>
@endsection
@section('content')

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- jquery validation -->
            <div class="box box-outline box-primary">
                <div class="box-header">
                    <h3 class="box-title">Journal Voucher(JV) Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form enctype="multipart/form-data" action="{{ route('journal_voucher.edit',['journalVoucher'=>$journalVoucher->id]) }}"
                      method="post">
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
                                                    <input type="text" name="financial_year" id="financial_year" readonly value="{{ $journalVoucher->financial_year }}" class="form-control">

                                                    @error('financial_year')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                                    <label for="date">Date <span class="text-danger">*</span></label>
                                                    <input type="text" autocomplete="off" id="date" value="{{ old('date',$journalVoucher->date->format('d-m-Y')) }}" name="date" class="form-control date-picker" placeholder="Enter Date">
                                                    @error('date')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend>Description:</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="client_type">Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="client_type" name="client_type">
                                                        <option {{ old('client_type') == 1 ? 'selected' : '' }} value="1">Existing</option>
                                                        <option {{ old('client_type') == 2 ? 'selected' : '' }} value="2">New</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" id="payee_select_area">
                                                <div
                                                    class="form-group {{ $errors->has('employee_party') ? 'has-error' :'' }}">
                                                    <label for="employee_party">Employee/Party <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control select2" id="employee_party"
                                                            style="width: 100%;" name="employee_party"
                                                            data-placeholder="Search Employee/Party">
                                                        <option value="">Search Employee/Party</option>
                                                        @if (old('employee_party',$journalVoucher->client_id) != '')
                                                            <option value="{{ old('employee_party',$journalVoucher->client_id) }}"
                                                                    selected>{{ old('select_name',($journalVoucher->client->id_no ?? '').'-'.($journalVoucher->client->name ?? '')) }}</option>
                                                        @endif
                                                    </select>
                                                    @error('employee_party')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                    <input type="hidden" name="select_name" id="select_name"
                                                           value="{{ old('select_name',$journalVoucher->employee_party) }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row hide client_name_area">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                                                    <label for="name">Party Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" value="{{ old('name',$journalVoucher->employee_party) }}" id="name"
                                                           class="form-control" placeholder="Enter Name">
                                                    @error('name')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group {{ $errors->has('designation') ? 'has-error' :'' }}">
                                                    <label for="designation">Employee/Party Designation</label>
                                                    <input type="text" name="designation"
                                                           value="{{ old('designation',$journalVoucher->designation) }}" id="designation"
                                                           class="form-control" placeholder="Enter Designation">
                                                    @error('designation')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                                                    <label for="address">Employee/Party Address</label>
                                                    <input type="text" name="address" value="{{ old('address',$journalVoucher->address) }}"
                                                           id="address" class="form-control"
                                                           placeholder="Enter Address">
                                                    @error('address')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group {{ $errors->has('mobile_no') ? 'has-error' :'' }}">
                                                    <label for="mobile_no">Employee/Party Mobile No.</label>
                                                    <input type="text" name="mobile_no" id="mobile_no"
                                                           value="{{ old('mobile_no',$journalVoucher->mobile_no) }}" class="form-control"
                                                           placeholder="Enter Mobile No">
                                                    @error('mobile_no')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
                                                    <label for="email">Email</label>
                                                    <input type="text" name="email" value="{{ old('email',$journalVoucher->email) }}"
                                                           id="email" class="form-control" placeholder="Enter Email">
                                                    @error('email')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('e_tin') ? 'has-error' :'' }}">
                                                    <label for="e_tin">Employee/Party eTIN</label>
                                                    <input type="text" name="e_tin" id="e_tin"
                                                           value="{{ old('e_tin',$journalVoucher->e_tin) }}" class="form-control"
                                                           placeholder="Enter eTin">
                                                    @error('e_tin')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group {{ $errors->has('nature_of_organization') ? 'has-error' :'' }}">
                                                    <label for="nature_of_organization">Nature of Organization</label>
                                                    <input type="text" name="nature_of_organization"
                                                           value="{{ old('nature_of_organization',$journalVoucher->nature_of_organization) }}"
                                                           id="nature_of_organization" class="form-control"
                                                           placeholder="Enter Nature of Organization">
                                                    @error('nature_of_organization')
                                                    <span class="help-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-md-12 mt-3 mb-2">
                                    <div class="table-responsive">
                                        <fieldset>
                                            <legend>Journal Voucher Details:</legend>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th width="60%" class="text-left">Debit Account Head <span
                                                            class="text-danger">*</span></th>
                                                    <th width="">Debit Amount <span class="text-danger">*</span></th>
                                                    <th colspan="8%"></th>

                                                </tr>
                                                </thead>
                                                <tbody id="receipt-payment-container">
                                                @if (old('account_head_code') != null && sizeof(old('account_head_code')) > 0)
                                                    @foreach(old('account_head_code') as $item)
                                                        <tr class="receipt-payment-item">
                                                            <td>
                                                                <div
                                                                    class="form-group account_head_code_area {{ $errors->has('account_head_code.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <select
                                                                        class="form-control select2 account_head_code"
                                                                        name="account_head_code[]"
                                                                        data-placeholder="Search Account Head Code">
                                                                        <option value="">Select Account Head Code
                                                                        </option>
                                                                        @if (old('account_head_code.'.$loop->index) != '')
                                                                            <option
                                                                                value="{{ old('account_head_code.'.$loop->index) }}"
                                                                                selected>{{ old('account_head_code_name.'.$loop->index) }}</option>
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden" name="account_head_code_name[]"
                                                                           class="account_head_code_name"
                                                                           value="{{ old('account_head_code_name.'.$loop->index) }}">

                                                                </div>

                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="form-group {{ $errors->has('debit_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <input type="text"
                                                                           value="{{ old('debit_amount.'.$loop->index) }}"
                                                                           name="debit_amount[]"
                                                                           class="form-control debit_amount">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a role="button" style="display: none"
                                                                   class="btn btn-danger btn-sm btn-remove"><i
                                                                        class="fa fa-times"></i></a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @foreach($journalVoucher->journalVoucherDebitDetails as $journalVoucherDebitDetail)
                                                    <tr class="receipt-payment-item">
                                                        <td>
                                                            <div class="form-group account_head_code_area">
                                                                <select class="form-control select2 account_head_code" style="width: 100%;" name="account_head_code[]" data-placeholder="Search Account Head Code" required>
                                                                    <option value="">Search Account Head Code</option>
                                                                    <option value="{{ $journalVoucherDebitDetail->account_head_id }}" selected>{{ $journalVoucherDebitDetail->accountHead->name }}-{{ $journalVoucherDebitDetail->accountHead->existing_account_code }}</option>
                                                                </select>
                                                                <input type="hidden" name="account_head_code_name[]" value="{{ $journalVoucherDebitDetail->accountHead->name }}" class="account_head_code_name">

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" value="{{ $journalVoucherDebitDetail->amount }}" name="debit_amount[]"
                                                                       class="form-control debit_amount">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a role="button" style="display: none"
                                                               class="btn btn-danger btn-sm btn-remove"><i
                                                                    class="fa fa-times"></i></a>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="1" class="text-right">
                                                        Total
                                                    </th>
                                                    <th class="text-center" id="debit-total-amount">0.00</th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-left" style="text-align: left">
                                                        <a role="button" class="btn btn-primary btn-sm"
                                                           id="btn-add-voucher"><i class="fa fa-plus"></i></a>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <table class="table other table-bordered">
                                                <thead>
                                                <tr>
                                                    <th width="60%" class="text-left">Credit Account Head <span
                                                            class="text-danger">*</span></th>
                                                    <th width="" class="text-center">Credit Amount <span
                                                            class="text-danger">*</span></th>
                                                    <th width="5%" class="text-center"></th>
                                                </tr>
                                                </thead>
                                                <tbody id="receipt-payment-other-container">
                                                @if (old('other_account_head_code') != null && sizeof(old('other_account_head_code')) > 0)
                                                    @foreach(old('other_account_head_code') as $item)
                                                        <tr class="receipt-payment-other-item">
                                                            <td>
                                                                <div
                                                                    class="form-group other_account_head_code_area {{ $errors->has('other_account_head_code.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <select
                                                                        class="form-control select2 other_account_head_code"
                                                                        name="other_account_head_code[]"
                                                                        data-placeholder="Search Other Account Head Code">
                                                                        <option value="">Select Account Head Code
                                                                        </option>
                                                                        @if (old('other_account_head_code.'.$loop->index) != '')
                                                                            <option
                                                                                value="{{ old('other_account_head_code.'.$loop->index) }}"
                                                                                selected>{{ old('other_account_head_code_name.'.$loop->index) }}</option>
                                                                        @endif
                                                                    </select>
                                                                    <input type="hidden"
                                                                           name="other_account_head_code_name[]"
                                                                           class="other_account_head_code_name"
                                                                           value="{{ old('other_account_head_code_name.'.$loop->index) }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="form-group {{ $errors->has('credit_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                                    <input type="text"
                                                                           value="{{ old('credit_amount.'.$loop->index) }}"
                                                                           name="credit_amount[]"
                                                                           class="form-control credit_amount">
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <a role="button" style="display: none"
                                                                   class="btn btn-danger btn-sm btn-other-remove"><i
                                                                        class="fa fa-times"></i></a>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @foreach($journalVoucher->journalVoucherCreditDetails as $journalVoucherCreditDetail)
                                                    <tr class="receipt-payment-other-item">
                                                        <td>
                                                            <div class="form-group other_account_head_code_area">
                                                                <select class="form-control select2 other_account_head_code" name="other_account_head_code[]">
                                                                    <option value="">Search Other Account Head Code</option>
                                                                    <option value="{{ $journalVoucherCreditDetail->account_head_id }}" selected>{{ $journalVoucherCreditDetail->accountHead->name }}-{{ $journalVoucherCreditDetail->accountHead->existing_account_code }}</option>
                                                                </select>
                                                                <input type="hidden" name="other_account_head_code_name[]" value="{{ $journalVoucherCreditDetail->accountHead->name }}" class="other_account_head_code_name">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" value="{{ $journalVoucherCreditDetail->amount }}" name="credit_amount[]"
                                                                       class="form-control credit_amount">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a role="button" style="display: none"
                                                               class="btn btn-danger btn-sm btn-other-remove"><i
                                                                    class="fa fa-times"></i></a>

                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="1" class="text-right">Total</th>
                                                    <th class="text-center" id="total-credit-amount">0.00</th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-left" style="text-align: left">
                                                        <a role="button" class="btn btn-primary btn-sm"
                                                           id="btn-add-other-voucher"><i class="fa fa-plus"></i></a>
                                                    </th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th width="70%" class="text-right">Debit Total</th>
                                                    <th class="text-right" id="debit-total">0.00</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-right">Credit Total</th>
                                                    <th class="text-right" id="credit-total">0.00</th>
                                                    <th></th>
                                                </tr>
                                            </table>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Journal Voucher Details (Narration)</label>
                                                    <div
                                                        class="form-group {{ $errors->has('notes') ? 'has-error' :'' }}">
                                                        <input type="text" value="{{ old('notes',$journalVoucher->notes) }}" name="notes"
                                                               class="form-control"
                                                               placeholder="Enter Payment Details (Narration)...">
                                                        @error('notes')
                                                        <span class="help-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label>Supporting Documents</label>
                                                    <div
                                                        class="form-group {{ $errors->has('supporting_document') ? 'has-error' :'' }}">
                                                        <input type="file" name="supporting_document[]" multiple
                                                               class="form-control">
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
                        <a href="{{ route('journal_voucher') }}" class="btn btn-default float-right">Cancel</a>
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
                    <select class="form-control select2 account_head_code" style="width: 100%;"
                            name="account_head_code[]" data-placeholder="Search Account Head Code">
                        <option value="">Search Account Head Code</option>
                    </select>
                    <input type="hidden" name="account_head_code_name[]" class="account_head_code_name">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="debit_amount[]" class="form-control debit_amount">
                </div>
            </td>
            <td>
                <a role="button" style="display: none" class="btn btn-danger btn-sm btn-remove"><i
                        class="fa fa-times"></i></a>
            </td>

        </tr>
    </template>
    <template id="receipt-other-payment-template">
        <tr class="receipt-payment-other-item">
            <td>
                <div class="form-group other_account_head_code_area">
                    <select class="form-control select2 other_account_head_code" name="other_account_head_code[]">
                        <option value="">Search Account Head Code</option>
                    </select>
                    <input type="hidden" name="other_account_head_code_name[]" class="other_account_head_code_name">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="credit_amount[]" class="form-control credit_amount">
                </div>
            </td>

            <td class="text-center">
                <a role="button" style="display: none" class="btn btn-danger btn-sm btn-other-remove"><i
                        class="fa fa-times"></i></a>

            </td>
        </tr>
    </template>
@endsection
@section('script')
    <script>
        $(function () {
            intSelect2();

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

                if ($('.receipt-payment-item').length >= 1) {
                    $('.btn-remove').show();
                }

                calculate();
            });
            $('#btn-add-other-voucher').click(function () {
                var html2 = $('#receipt-other-payment-template').html();
                var item2 = $(html2);
                $('#receipt-payment-other-container').append(item2);
                intSelect2();
                if ($('.receipt-payment-other-item').length >= 1) {
                    $('.btn-other-remove').show();
                }

                calculate();
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.receipt-payment-item').remove();
                if ($('.receipt-payment-item').length <= 1) {
                    $('.btn-remove').hide();
                }
                calculate();
            });
            $('body').on('click', '.btn-other-remove', function () {
                $(this).closest('.receipt-payment-other-item').remove();
                if ($('.receipt-payment-other-item').length <= 1) {
                    $('.btn-other-remove').hide();
                }
                calculate();
            });

            if ($('.receipt-payment-item').length <= 1) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            if ($('.receipt-payment-other-item').length <= 1) {
                $('.btn-other-remove').hide();
            } else {
                $('.btn-other-remove').show();
            }


            $('body').on('keyup', '.debit_amount,.credit_amount', function () {
                calculate();
            });
            calculate();
        });

        function calculate() {

            var totalDebitAmount = 0;
            var totalCreditAmount = 0;

            $('.receipt-payment-item').each(function (i, obj) {
                var debitAmount = $('.debit_amount:eq(' + i + ')').val();


                if (debitAmount == '' || debitAmount < 0 || !$.isNumeric(debitAmount))
                    debitAmount = 0;


                totalDebitAmount += parseFloat(debitAmount);

            });

            $('.receipt-payment-other-item').each(function (i, obj) {
                var creditAmount = $('.credit_amount:eq(' + i + ')').val();

                if (creditAmount == '' || creditAmount < 0 || !$.isNumeric(creditAmount))
                    creditAmount = 0;

                totalCreditAmount += parseFloat(creditAmount);

            });
            $('#total-credit-amount').html(jsNumberFormat(totalCreditAmount));

            $('#debit-total-amount').html(jsNumberFormat(totalDebitAmount));

            $('#debit-total').html(jsNumberFormat(totalDebitAmount));

            $('#credit-total').html(jsNumberFormat(totalCreditAmount));

        }

        function intSelect2() {
            $('.date-picker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
            $('.select2').select2()
            $('#employee_party').select2({
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
            $('#employee_party').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#payee").index(this);
                $('#select_name:eq(' + index + ')').val(data.text);
            });
            $('.account_head_code').select2({
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
            $('.account_head_code').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $(".account_head_code").index(this);
                $('.account_head_code_name:eq(' + index + ')').val(data.text);
            });

            $('.other_account_head_code').select2({
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
            $('.other_account_head_code').on('select2:select', function (e) {
                var data2 = e.params.data;
                var index2 = $(".other_account_head_code").index(this);
                $('.other_account_head_code_name:eq(' + index2 + ')').val(data2.text);
            });

        }
    </script>
@endsection
