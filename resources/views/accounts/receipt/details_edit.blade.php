@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Payment Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Payment Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('receipt_details.edit', ['receiptPayment' => $receiptPayment->id]) }}">
                    @csrf

                    <div class="box-body">

                        <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Select Financial Year *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" style="width: 100%" name="financial_year" id="financial_year">
{{--                                    <option value="">Select Year</option>--}}
                                    @for($i=2022; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ old('financial_year',$receiptPayment->financial_year) == $i ? 'selected' : '' }}>{{ $i }}-{{ $i+1 }}</option>
                                    @endfor
                                </select>

                                @error('financial_year')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Client Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $receiptPayment->client->name) : old('name') }}">
                                <input type="hidden" name="client_id" value="{{ $receiptPayment->client_id }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('order_no') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Select Order No *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" style="width: 100%" id="modal-order" name="order_no">
                                    <option value="">Select Order</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_no',$receiptPayment->sales_order_id) == $order->id ? 'selected' : '' }}>{{ $order->order_no }}</option>
                                    @endforeach
                                </select>

                                @error('order_no')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div id="modal-order-info" style="background-color: lightgrey; padding: 10px; border-radius: 3px; margin-left: 191px;margin-bottom: 10px;"></div>


                        {{-- <div id="payment_step_area" class="form-group">
                            <label>Payment Step</label>
                            <select id="payment_step_show" name="payment_step"  class="form-control">
                                <option value="">Select Payment Step</option>
                                <option value="2">Down Payment</option>
                                <option value="3">Installment</option>
                            </select>

                        </div>
                        <div class="form-group payment-step-show" style="display: none">
                            <label>Installment Step</label>
                            <input class="form-control" type="text" {{ old('amount',$receiptPayment->installment_name) }} name="installment_name" placeholder="installment step name">
                        </div> --}}


                        <div class="form-group {{ $errors->has('payment_type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Payment Step</label>

                            <div class="col-sm-10">
                                <select id="payment_step_show" name="payment_step"  class="form-control">
                                    <option value="">Select Payment Step</option>
                                    <option value="1" {{ old('payment_step',$receiptPayment->payment_step) == '1' ? 'selected' : '' }}>Boking Money</option>
                                    <option value="2" {{ old('payment_step',$receiptPayment->payment_step) == '2' ? 'selected' : '' }}>Down Payment</option>
                                    <option value="3" {{ old('payment_step',$receiptPayment->payment_step) == '3' ? 'selected' : '' }}>Installment</option>
                                </select>

                                @error('payment_type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group payment-step-show {{ $errors->has('installment_name') ? 'has-error' :'' }}" style="display: none">
                            <label class="col-sm-2 control-label">Installment Step</label>

                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="installment_name" placeholder="installment step name" value="{{ empty(old('installment_name')) ? ($errors->has('installment_name') ? '' : $receiptPayment->installment_name) : old('installment_name') }}">

                                @error('installment_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('payment_type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Payment Type *</label>

                            <div class="col-sm-10">
                                <select class="form-control" id="payment_type" name="payment_type">
                                    <option value="">Select Payment Type</option>
                                    <option value="1" {{ old('payment_type',$receiptPayment->payment_type) == '1' ? 'selected' : '' }}>Cheque</option>
                                    <option value="2" {{ old('payment_type',$receiptPayment->payment_type) == '2' ? 'selected' : '' }}>Cash</option>
                                </select>

                                @error('payment_type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('account') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Cash/Bank Account *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" id="account" name="account">
                                    <option value=""> Select Cash/Bank Account </option>
                                    @foreach($accountHeads as $accountHead)
                                    <option value="{{$accountHead->id}}" {{ old('account',$receiptPayment->account_head_id) == $accountHead->id ? 'selected' : '' }}>
                                        Account:{{ $accountHead->name }}|Account Code:{{ $accountHead->account_code }}</option>
                                    @endforeach
                                </select>

                                @error('account')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group bank-area {{ $errors->has('cheque_no') ? 'has-error' :'' }}" style="display: none">
                            <label class="col-sm-2 control-label">Cheque No.</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Cheque No"
                                       name="cheque_no" value="{{ empty(old('cheque_no')) ? ($errors->has('cheque_no') ? '' : $receiptPayment->cheque_no) : old('cheque_no') }}">

                                @error('cheque_no')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group bank-area {{ $errors->has('cheque_date') ? 'has-error' :'' }}" style="display: none">
                            <label class="col-sm-2 control-label">Cheque Date</label>

                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="cheque_date" name="cheque_date"
                                           value="{{ empty(old('cheque_date')) ? ($errors->has('cheque_date') ? '' : $receiptPayment->cheque_date) : old('cheque_date') }}" autocomplete="off">
                                </div>
                                <!-- /.input group -->

                                @error('cheque_date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group bank-area {{ $errors->has('cheque_image') ? 'has-error' :'' }}" style="display: none">
                            <label class="col-sm-2 control-label">Cheque image</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="cheque_image" >

                                @error('cheque_image')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('amount') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Amount</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control pull-right" id="amount" name="amount"
                                       value="{{ old('amount',$receiptPayment->net_amount) }}" autocomplete="off">

                                @error('amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group bank-area {{ $errors->has('issuing_bank_name') ? 'has-error' :'' }}" style="display: none">
                            <label class="col-sm-2 control-label">Issuing Bank Name</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Issuing Bank Name" id="issuing_bank_name"
                                       name="issuing_bank_name" value="{{ empty(old('issuing_bank_name')) ? ($errors->has('issuing_bank_name') ? '' : $receiptPayment->issuing_bank_name) : old('issuing_bank_name') }}">

                                @error('issuing_bank_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group bank-area {{ $errors->has('issuing_branch_name') ? 'has-error' :'' }}" style="display: none">
                            <label class="col-sm-2 control-label">Issuing Branch Name</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Issuing Branch Name" id="issuing_branch_name"
                                       name="issuing_branch_name" value="{{ empty(old('issuing_branch_name')) ? ($errors->has('issuing_branch_name') ? '' : $receiptPayment->issuing_branch_name) : old('issuing_branch_name') }}">

                                @error('issuing_branch_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Date </label>

                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="date" name="date"
                                           value="{{old('date',date('d-m-Y',strtotime($receiptPayment->date))) }}" autocomplete="off">
                                </div>
                                <!-- /.input group -->

                                @error('date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                       {{-- <div class="form-group bank-area {{ $errors->has('next_date') ? 'has-error' :'' }}" style="display: none">--}}
{{--                            <label class="col-sm-2 control-label">Next Payment Date </label>--}}

{{--                            <div class="col-sm-10">--}}
{{--                                <div class="input-group date">--}}
{{--                                    <div class="input-group-addon">--}}
{{--                                        <i class="fa fa-calendar"></i>--}}
{{--                                    </div>--}}
{{--                                    <input type="text" class="form-control pull-right" id="next_date" name="next_date"--}}
{{--                                           value="{{ empty(old('next_date')) ? ($errors->has('next_date') ? '' : $receiptPayment->cheque_date) : old('next_date') }}" autocomplete="off">--}}
{{--                                </div>--}}
{{--                                <!-- /.input group -->--}}

{{--                                @error('next_date')--}}
{{--                                <span class="help-block">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div> --}}

                        <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Note</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Note" id="note"
                                       name="note" value="{{ empty(old('note')) ? ($errors->has('note') ? '' : $receiptPayment->notes) : old('note') }}">

                                @error('note')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            intSelect2
            //Date picker
            $('#cheque_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: 'bottom'
            });

            $('.date-picker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
            });

            $('#modal-order').change(function () {
                var orderId = $(this).val();
                $('#modal-order-info').hide();

                if (orderId != '') {

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

            $('#modal-order').trigger('change');

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

            $("#payment_step_show").trigger("change");
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
                        //console.log(response);
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
            $('#account').on('select2:select', function (e) {
                var data = e.params.data;
                alert(data);
                var index = $("#account").index(this);
                $('#account_name:eq('+index+')').val(data.text);
            });
        }
    </script>
@endsection
