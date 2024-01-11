@extends('layouts.app')
@section('title')
    Balance Transfer
@endsection
@section('style')
    <style>
        .row{
            margin-left: 0;
            margin-right: 0;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-outline box-primary">
                <div class="box-header">
                    <h3 class="box-title">Balance Transfer Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form  method="POST" enctype="multipart/form-data" action="{{ route('balance_transfer.add') }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group row {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="financial_year">Financial Year <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="financial_year" id="financial_year">
                                <option value="">Select Year</option>
                                @for($i=2022; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ old('financial_year') == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                @endfor
                            </select>
                                @error('financial_year')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="type">Transfer Type <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control select2" name="type" id="type">
                                    <option value="">Select Transfer Type</option>
                                    <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Bank To Cash</option>
                                    <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Cash To Bank</option>
                                    <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Bank To Bank</option>
                                    <option value="4" {{ old('type') == '4' ? 'selected' : '' }}>Cash To Cash</option>
                                </select>
                                @error('type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div id="source_area" style="display: none">
                            <div class="form-group row {{ $errors->has('source_account_head_code') ? 'has-error' :'' }}">
                                <label class="col-sm-3 col-form-label" for="source_account_head_code"><span id="source_label_title">Source Account Head </span> <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select style="width: 100%" class="form-control select2" name="source_account_head_code" id="source_account_head_code">
                                        <option value="">Search Source Account Head</option>
                                        @if (old('source_account_head_code') != '')
                                            <option value="{{ old('source_account_head_code') }}" selected>{{ old('source_account_head_code_name') }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="source_account_head_code_name" id="source_account_head_code_name">
                                    @error('source_account_head_code')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div id="if_source_bank" style="display: none">
                                <div class="form-group row {{ $errors->has('source_bank_cheque_no') ? 'has-error' :'' }}">
                                    <label class="col-sm-3 col-form-label" for="source_bank_cheque_no">Source Bank Cheque No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="source_bank_cheque_no" name="source_bank_cheque_no" placeholder="Enter Source Bank Cheque No.">
                                        @error('source_bank_cheque_no')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('source_bank_cheque_date') ? 'has-error' :'' }}">
                                    <label class="col-sm-3 col-form-label" for="source_bank_cheque_date">Source Bank Cheque Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control pull-right date-picker" placeholder="Enter Source Bank Cheque Date" id="source_bank_cheque_date" name="source_bank_cheque_date" value="{{ empty(old('source_bank_cheque_date')) ? ($errors->has('source_bank_cheque_date') ? '' : '') : old('source_bank_cheque_date') }}" autocomplete="off">
                                        @error('source_bank_cheque_date')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('source_bank_cheque_image') ? 'has-error' :'' }}">
                                    <label class="col-sm-3 col-form-label">Source Bank Cheque Image</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="source_bank_cheque_image">
                                        @error('source_bank_cheque_image')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="target_area" style="display: none">
                            <div class="form-group row {{ $errors->has('target_account_head_code') ? 'has-error' :'' }}">
                                <label class="col-sm-3 col-form-label" for="target_account_head_code"><span id="target_label_title">Target Account Head</span>  <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select style="width: 100%" class="form-control select2" name="target_account_head_code" id="target_account_head_code">
                                        <option value="">Search Target Account Head Code</option>
                                        @if (old('target_account_head_code') != '')
                                            <option value="{{ old('target_account_head_code') }}" selected>{{ old('target_account_head_code_name') }}</option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="target_account_head_code_name" id="target_account_head_code_name">
                                    @error('target_account_head_code')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('amount') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="amount">Amount <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount" value="{{ old('amount') }}">
                                <span style="font-weight: bold" id="amount-show"></span>
                                @error('amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="date">Date <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control pull-right date-picker" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                                @error('date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('note') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="note">Note</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="note" name="note" placeholder="Enter Note" value="{{ old('note') }}">
                                @error('note')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('balance_transfer') }}" class="btn btn-default pull-right">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            intSelect2();
            $('body').on('keyup', '#amount', function () {
                calculate();
            });
            calculate();

            $('#type').change(function () {
                var type = $(this).val();
                if (type != '') {
                    if (type == '1') {
                        $("#source_label_title").html('Source Bank Account Head');
                        if('{{ old('source_account_head_code') }}' == '')
                            $("#source_account_head_code").html('<option value="">Search Source Bank Account Head</option>');

                        $("#target_label_title").html('Target Cash In Hand Head');

                        if('{{ old('target_account_head_code') }}' == '')
                            $("#target_account_head_code").html('<option value="">Search Target Cash In Hand Head</option>');

                        $('#source_area').show();
                        $('#target_area').show();
                        $("#if_source_bank").show();
                    } else if (type == '2') {
                        $("#source_label_title").html('Source Cash In Hand Head');

                        if('{{ old('source_account_head_code') }}' == '')
                            $("#source_account_head_code").html('<option value="">Search Source Cash In Hand Head</option>');

                        $("#target_label_title").html('Target Bank Account Head');

                        if('{{ old('target_account_head_code') }}' == '')
                            $("#target_account_head_code").html('<option value="">Search Target Bank Account Head</option>');

                        $('#source_area').show();
                        $('#target_area').show();
                        $("#if_source_bank").hide();
                    } else if (type == '3') {
                        $("#source_label_title").html('Source Bank Account Head');
                        if('{{ old('source_account_head_code') }}' == '')
                            $("#source_account_head_code").html('<option value="">Search Source Bank Account Head</option>');

                        $("#target_label_title").html('Target Bank Account Head');
                        if('{{ old('target_account_head_code') }}' == '')
                            $("#target_account_head_code").html('<option value="">Search Target Bank Account Head</option>');

                        $('#source_area').show();
                        $('#target_area').show();
                        $("#if_source_bank").show();
                    }else if (type == '4') {
                        $("#source_label_title").html('Source Cash In Hand Head');
                        if('{{ old('source_account_head_code') }}' == '')
                            $("#source_account_head_code").html('<option value="">Search Source Cash In Hand Head</option>');

                        $("#target_label_title").html('Target Cash In Hand Head');

                        if('{{ old('target_account_head_code') }}' == '')
                            $("#target_account_head_code").html('<option value="">Search Target Cash In Hand Head</option>');

                        $('#source_area').show();
                        $('#target_area').show();
                        $("#if_source_bank").hide();
                    }
                } else {
                    $('#source_area').hide();
                    $('#target_area').hide();
                    $("#if_source_bank").hide();
                }
            });

            $('#type').trigger('change');

        });

        function calculate(){

            var amount = $("#amount").val();

            if (amount == '' || amount < 0 || !$.isNumeric(amount))
                amount = 0;

            $("#amount-show").html(jsNumberFormat(amount));
        }
        function intSelect2(){
            $('.date-picker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
            $('.select2').select2()
            $('#source_account_head_code').select2({
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
            $('#source_account_head_code').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#source_account_head_code").index(this);
                $('#source_account_head_code_name:eq('+index+')').val(data.text);
            });

            $('#target_account_head_code').select2({
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

            $('#target_account_head_code').on('select2:select', function (e) {
                var data = e.params.data;
                var index = $("#target_account_head_code").index(this);
                $('#target_account_head_code_name:eq('+index+')').val(data.text);
            });

        }
    </script>
@endsection
