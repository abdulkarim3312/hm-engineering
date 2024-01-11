@extends('layouts.app')
@section('title')
    Balance Transfer Edit
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
                <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('balance_transfer.edit',['balanceTransfer'=>$balanceTransfer->id]) }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group row {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="financial_year">Financial Year <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="financial_year" readonly value="{{ $balanceTransfer->financial_year }}" class="form-control">

                            </div>
                            @error('financial_year')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="type">Transfer Type <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="hidden" name="type" value="{{ $balanceTransfer->type }}">
                                @if($balanceTransfer->type == 1)
                                <input type="hidden" readonly id="type" class="form-control" value="1">
                                <input type="text" readonly  class="form-control" value="Bank To Cash">
                                @elseif($balanceTransfer->type == 2)
                                    <input type="hidden" readonly id="type" class="form-control" value="2">
                                    <input type="text" readonly  class="form-control" value="Cash To Bank">
                                @elseif($balanceTransfer->type == 3)
                                    <input type="hidden" readonly id="type" class="form-control" value="3">
                                    <input type="text" readonly  class="form-control" value="Bank To Bank">
                                @else
                                    <input type="hidden" readonly id="type" class="form-control" value="4">
                                    <input type="text" readonly  class="form-control" value="Cash To Cash">
                                @endif
                                @error('type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div id="source_area" style="display: none">
                            <div class="form-group row {{ $errors->has('source_account_head_code') ? 'has-error' :'' }}">
                                <label class="col-sm-3 col-form-label" for="source_account_head_code"><span id="source_label_title">Source Account Head </span> <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control" value="{{ $balanceTransfer->sourceAccountHead->name ?? '' }}">
                                    <input type="hidden" name="source_account_head_code" class="form-control" value="{{ $balanceTransfer->sourceAccountHead->id ?? '' }}">
                                    @error('source_account_head_code')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div id="if_source_bank" style="display: none">
                                <div class="form-group row {{ $errors->has('source_bank_cheque_no') ? 'has-error' :'' }}">
                                    <label class="col-sm-3 col-form-label" for="source_bank_cheque_no">Source Bank Cheque No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" value="{{ $balanceTransfer->source_cheque_no }}" id="source_bank_cheque_no" name="source_bank_cheque_no" placeholder="Enter Source Bank Cheque No.">
                                        @error('source_bank_cheque_no')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('source_bank_cheque_date') ? 'has-error' :'' }}">
                                    <label class="col-sm-3 col-form-label" for="source_bank_cheque_date">Source Bank Cheque Date</label>
                                    <div class="col-sm-9">
                                        <input type="text"  class="form-control pull-right date-picker" placeholder="Enter Source Bank Cheque Date" id="source_bank_cheque_date" name="source_bank_cheque_date" value="{{ date('Y-m-d',strtotime($balanceTransfer->source_cheque_date)) }}" autocomplete="off">
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
                                    <input type="text" readonly class="form-control" value="{{ $balanceTransfer->targetAccountHead->name ?? '' }}">
                                    <input type="hidden" name="target_account_head_code" class="form-control" value="{{ $balanceTransfer->targetAccountHead->id ?? '' }}">
                                    @error('target_account_head_code')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('amount') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="amount">Amount <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount" value="{{ old('amount',$balanceTransfer->amount) }}">
                                <span style="font-weight: bold" id="amount-show"></span>
                                @error('amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('date') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="date">Date <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control pull-right date-picker" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : $balanceTransfer->date->format('d-m-Y')) : old('date') }}" autocomplete="off">
                                @error('date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('note') ? 'has-error' :'' }}">
                            <label class="col-sm-3 col-form-label" for="note">Note</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="note" name="note" placeholder="Enter Note" value="{{ old('note',$balanceTransfer->note) }}">
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

            $('body').on('keyup', '#amount', function () {
                calculate();
            });
            calculate();


            var type = $('#type').val();

            if (type != '') {
                if (type == '1') {
                    $("#source_label_title").html('Source Bank Account Head');

                    $("#target_label_title").html('Target Cash In Hand Head');

                    $('#source_area').show();
                    $('#target_area').show();
                    $("#if_source_bank").show();
                } else if (type == '2') {
                    $("#source_label_title").html('Source Cash In Hand Head');

                    $("#target_label_title").html('Target Bank Account Head');

                    $('#source_area').show();
                    $('#target_area').show();
                    $("#if_source_bank").hide();
                } else if (type == '3') {
                    $("#source_label_title").html('Source Bank Account Head');
                    $("#target_label_title").html('Target Bank Account Head');
                    $('#source_area').show();
                    $('#target_area').show();
                    $("#if_source_bank").show();
                }else if (type == '4') {
                    $("#source_label_title").html('Source Cash In Hand Head');
                    $("#target_label_title").html('Target Cash In Hand Head');
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

        function calculate(){

            var amount = $("#amount").val();

            if (amount == '' || amount < 0 || !$.isNumeric(amount))
                amount = 0;

            $("#amount-show").html(jsNumberFormat(amount));
        }
    </script>
@endsection
