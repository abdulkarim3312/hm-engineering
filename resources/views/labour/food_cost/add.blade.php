@extends('layouts.app')
@section('title')
    Add Advance Amount
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Advance Payment Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('labour.food_cost.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('financial_year') ? 'has-error' :'' }}">
                                    <label for="financial_year">Select Financial Year <span
                                            class="text-danger">*</span></label>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Payment Type </label>
                                    <select class="form-control select2" id="payment_type" name="payment_type">
                                        <option value="">Select Payment Type</option>
                                        <option value="1">Bank</option>
                                        <option value="2">Cash</option>

                                    </select>
                                </div>
                                @error('payment_type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div  class="col-md-3">
                                <div class="form-group {{ $errors->has('account') ? 'has-error' :'' }}">
                                    <label>Bank/Cash Account <span class="text-danger">*</span></label>
                                    <select class="form-control select2" id="account" name="account">
                                        <option value="">Select Bank/Cash Account</option>
                                        @if (old('account') != '')
                                            <option value="{{ old('account') }}" selected>{{ old('account_name') }}</option>
                                        @endif
                                    </select>
                                    @error('account')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 bank-area" style="display: none">
                                <div class="form-group {{ $errors->has('cheque_date') ? 'has-error' :'' }}">
                                    <label>Cheque Date <span class="text-danger">*</span></label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right date-picker"
                                               id="cheque_date" name="cheque_date" value="{{ old('cheque_date',date('Y-m-d'))  }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->
                                    @error('cheque_date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 bank-area" style="display: none">
                                <div class="form-group {{ $errors->has('cheque_no') ? 'has-error' :'' }}">
                                    <label>Cheque No. <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control"
                                           id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}">

                                    @error('cheque_no')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Note</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Labour Employee Name</th>
                                     <th width="20%">Advance Amount</th>
                                    <th width="20%">Food Cost</th>
                                    <th width="20%">Receive By</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="employee-container">
                                @if (old('employee') != null && sizeof(old('employee')) > 0)
                                    @foreach(old('employee') as $item)
                                        <tr class="employee-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('employee.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control employee select2" style="width: 100%;" name="employee[]" data-placeholder="Select Employee">
                                                        <option value="">Select Employee</option>
                                                        @foreach($labours as $labour)
                                                            <option value="{{ $labour->id }}" {{ old('labour.'.$loop->parent->index) == $labour->id ? 'selected' : '' }}>{{ $labour->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('advance.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="advance[]" class="form-control advance" value="{{ old('advance.'.$loop->index) }}">
                                                </div>
                                            </td>

                                             <td>
                                                <div class="form-group {{ $errors->has('food_cost.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="food_cost[]" class="form-control food_cost" value="{{ old('food_cost.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('received_by.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control received_by" name="received_by[]" value="{{ old('received_by.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost">৳ 0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="employee-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control employee select2" style="width: 100%;" name="employee[]" data-placeholder="Select Employee">
                                                    <option value="">Select Employee</option>
                                                @foreach($labours as $labour)
                                                    <option value="{{ $labour->id }}">{{ $labour->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </td>

                                         <td>
                                            <div class="form-group">
                                                <input type="text" name="advance[]" class="form-control advance">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="food_cost[]" class="form-control food_cost">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control received_by" name="received_by[]">
                                            </div>
                                        </td>

                                        <td class="total-cost">৳ 0.00</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-employee">Add More</a>
                                    </td>
                                    <th class="text-right" colspan="2" >Total Amount</th>
                                    <th id="total-amount"> ৳ 0.00 </th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
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

    <template id="template-employee">
        <tr class="employee-item">
            <td>
                <div class="form-group">
                    <select class="form-control employee select2" style="width: 100%;" name="employee[]" data-placeholder="Select Employee">
                        <option value="">Select Employee</option>
                        @foreach($labours as $labour)
                            <option value="{{ $labour->id }}">{{ $labour->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

             <td>
                <div class="form-group">
                    <input type="text" name="advance[]" class="form-control advance">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" name="food_cost[]" class="form-control food_cost">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control received_by" name="received_by[]">
                </div>
            </td>

            <td class="total-cost">৳0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')

    <script>
        $(function () {
            intSelect2();

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

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#btn-add-employee').click(function () {
                var html = $('#template-employee').html();
                var item = $(html);

                $('#employee-container').append(item);

                //initEmployee();

                if ($('.employee-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.employee-item').remove();
                calculate();

                if ($('.employee-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup', '.food_cost', function () {
                calculate();
            });
             $('body').on('keyup', '.advance', function () {
                calculate();
            });

            if ($('.employee-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            //initEmployee();
            calculate();

        });

        function calculate() {
            var total = 0;

            $('.employee-item').each(function(i, obj) {
                var food_cost = $('.food_cost:eq('+i+')').val();
                var advance = $('.advance:eq('+i+')').val();

                if (food_cost == '' || food_cost < 0 || !$.isNumeric(food_cost))
                    food_cost = 0;

                // $('.total-cost:eq('+i+')').html(' ' + parseFloat(food_cost).toFixed(2));

                 if (advance == '' || advance < 0 || !$.isNumeric(advance))
                    advance = 0;

                   //var sub_total= (food_cost+advance);
                $('.total-cost:eq('+i+')').html( (parseFloat(food_cost)+parseFloat(advance)).toFixed(2) );

                total += parseFloat(food_cost)+parseFloat(advance);
            });

            $('#total-amount').html( '=  ' +total.toFixed(2));
        }
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

        }
    </script>
@endsection
