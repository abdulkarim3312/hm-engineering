@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style>
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            white-space: nowrap;
        }
        input.form-control.length{
            width: 100px;
        }
        input.form-control.height{
            width: 100px;
        }
    </style>
@endsection

@section('title')
    Contractor Bill Statement
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bill Statement Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('bill_statement.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Project">
                                        <option value="">Select Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('estimate_project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('estimate_project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('estimate_floor') ? 'has-error' :'' }}">
                                    <label>Type Of Work(Trade)</label>

                                    <select name="trade" class="form-control" >
                                        <option>Select Trade</option>
                                        <option value="1">Civil Contractor</option>
                                        <option value="2">Paint Contractor</option>
                                        <option value="3">Sanitary Contractor</option>
                                        <option value="4">Tiles Contractor</option>
                                        <option value="5">MS Contractor</option>
                                        <option value="6">Wood Contractor</option>
                                        <option value="7">Electric Contractor</option>
                                        <option value="8">Thai Contractor</option>
                                        <option value="9">Other Contractor</option>
                                    </select>

                                    @error('estimate_floor')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('estimate_floor') ? 'has-error' :'' }}">
                                    <label>Contractor Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="contractor" name="contractor" readonly value="{{ $contractor->name ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Address</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('estimate_project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('estimate_floor') ? 'has-error' :'' }}">
                                    <label>Cheque Holder Name</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">
                                    </div>

                                    @error('estimate_floor')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Item Code</th>
                                    <th width="20%">Description of work</th>
                                    <th width="10%">Bill No</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Rate</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Payable(%)</th>
                                    <th width="10%">Payable Amount</th>
                                    <th width="10%">Deduct SD M(%)</th>
                                    <th width="10%">Net Amount</th>
                                    <th width="10%">A. Amount</th>
                                    <th width="10%">Approval Amount</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td class="col-md-3">
                                                <div class="form-group {{ $errors->has('item_code') ? 'has-error' :'' }}">
                                                    <div class="form-group {{ $errors->has('item_code') ? 'has-error' :'' }}">
                                                        <input type="text"  name="item_code[]" class="form-control item_code" value="{{ old('item_code') }}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('work_description') ? 'has-error' :'' }}">
                                                    <input style="width: 220px;" type="text" step="any"  name="work_description[]" class="form-control work_description" value="{{ old('work_description') }}">
                                                
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('bill_no.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input style="width:70px;" type="text" step="any"  name="bill_no[]" class="form-control bill_no" value="{{ old('bill_no.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input style="width:70px;" type="text" step="any" class="form-control unit" name="unit[]" value="{{ old('unit.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('rate.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input style="width:70px;" type="number" step="any" class="form-control rate" name="rate[]" value="{{ old('rate.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('t_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input style="width:80px;" type="number" step="any" class="form-control t_amount" name="t_amount[]" value="{{ old('t_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('payable.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control payable" name="payable[]" value="{{ old('payable.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('payable_a.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control payable_a" name="payable_a[]" value="{{ old('payable_a.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduct_money.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduct_money" name="deduct_money[]" value="{{ old('deduct_money.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('n_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control n_amount" name="n_amount[]" value="{{ old('n_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('advance_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control advance_amount" readonly name="advance_amount[]" value="{{ old('advance_amount.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('approve_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control approve_amount" name="approve_amount[]" value="{{ old('approve_amount.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            {{-- <td class="sub-total-bricks">0.00</td>
                                            <td class="sub-total-morter">0.00</td> --}}
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <div class="form-group {{ $errors->has('item_code.') ? 'has-error' :'' }}">
                                                    <input type="text" name="item_code[]" class="form-control item_code" value="{{ old('item_code') }}">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-group {{ $errors->has('work_description.') ? 'has-error' :'' }}">
                                                    <input style="width: 220px;" type="text" step="any"  name="work_description[]" class="form-control work_description" value="{{ old('work_description') }}">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="width:70px;" type="text" step="any"  name="bill_no[]" class="form-control bill_no" value="">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity" name="quantity[]" value="0">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any" class="form-control unit" name="unit[]" value="" style="width:70px;">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control rate" name="rate[]" value="0" style="width:70px;">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control t_amount" name="t_amount[]" value="0" style="width:80px;">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control payable" name="payable[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control payable_a" name="payable_a[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduct_money" name="deduct_money[]" value="0">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number"  step="any" class="form-control n_amount" name="n_amount[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control advance_amount" readonly name="advance_amount[]" value="0">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control approve_amount" name="approve_amount[]" value="0">
                                            </div>
                                        </td>

                                        {{-- <td class="sub-total-bricks">0.00</td>
                                        <td class="sub-total-morter">0.00</td> --}}
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add More</a>
                                    </td>
                                    {{-- <th colspan="10" class="text-right">Total Bricks</th>
                                    <th id="total-bricks"> 0.00 </th>
                                    <th id="total-morters"> 0.00 </th> --}}
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

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group {{ $errors->has('item_code') ? 'has-error' :'' }}">
                    <input type="text" step="any"  name="item_code[]" class="form-control item_code" value="{{ old('item_code') }}">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <div class="form-group {{ $errors->has('work_description.') ? 'has-error' :'' }}">
                        <input style="width: 220px;" type="text" step="any"  name="work_description[]" class="form-control work_description" value="{{ old('work_description') }}">
                    </div>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width:70px;" type="text" step="any"  name="bill_no[]" class="form-control bill_no" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="0">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" step="any" class="form-control unit" name="unit[]" value="" style="width:70px;">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control rate" name="rate[]" value="0" style="width:70px;">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control t_amount" name="t_amount[]" value="0" style="width:80px;">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control payable" name="payable[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control payable_a" name="payable_a[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduct_money" name="deduct_money[]" value="0">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number"  step="any" class="form-control n_amount" name="n_amount[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control advance_amount" readonly name="advance_amount[]" value="0">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control approve_amount" name="approve_amount[]" value="0">
                </div>
            </td>

            {{-- <td class="sub-total-bricks">0.00</td>
            <td class="sub-total-morter">0.00</td> --}}
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                $('#product-container').append(item);

                initProduct();

                if ($('.product-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });


            $('body').on('change','#wall_type', function () {
                var wallType = $(this).val();
                var threeInch = 0.334;
                var fiveInch = 0.20;
                var tenInch = 0.087;

                if (wallType == 1) {
                    $('#brick_size').val(threeInch);
                }else if(wallType == 2){
                    $('#brick_size').val(fiveInch);
                }else {
                    $('#brick_size').val(tenInch);
                }
            })



            var selectedFloor = '{{ old('estimate_floor') }}';

            $('body').on('change', '#estimate_project', function () {
                var estimateProjectId = $(this).val();
                $('#estimate_floor').html('<option value="">Select Estimate Floor</option>');

                if (estimateProjectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_estimate_floor') }}",
                        data: { estimateProjectId:estimateProjectId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedFloor == item.id)
                                $('#estimate_floor').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#estimate_floor').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });
            $('#estimate_project').trigger('change');

            $('body').on('keyup','#brick_size,#morter,#dry_morter,.quantity,.rate,.t_amount,.payable,.deduct_amount,.n_amount,.advance_amount', function () {
                calculate();
            });

            // $('body').on('keyup','#brick_size,#morter,#dry_morter,.quantity,.rate,.t_amount' +
            //     '.payable,.deduct_amount,.n_amount'+
            //     '.deduction_height_two,'+
            //     '.advance_amount,.deduction_length_three,.deduction_height_three', function () {
            //     calculate();
            // });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
            calculate();
        });

        function calculate() {
            var totalBricks = 0;
            var totalMorters = 0;
            var brick_size = $('#brick_size').val();
            var morter = $('#morter').val();
            var dry_morter = $('#dry_morter').val();

            if (brick_size == '' || brick_size < 0 || !$.isNumeric(brick_size))
                brick_size = 0;

            if (morter == '' || morter < 0 || !$.isNumeric(morter))
                morter = 0;

            if (dry_morter == '' || dry_morter < 0 || !$.isNumeric(dry_morter))
                dry_morter = 0;

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var rate = $('.rate:eq('+i+')').val();
                var payable = $('.payable:eq('+i+')').val();
                var deduct_money = $('.deduct_money:eq('+i+')').val();
                var n_amount = $('.n_amount:eq('+i+')').val();
                var advance_amount = $('.advance_amount:eq('+i+')').val();
                var deduction_length_two = $('.deduction_length_two:eq('+i+')').val();
                var deduction_height_two = $('.deduction_height_two:eq('+i+')').val();
                var deduction_length_three = $('.deduction_length_three:eq('+i+')').val();
                var deduction_height_three = $('.deduction_height_three:eq('+i+')').val();
                // alert(advance_amount);
                if (quantity == '' || length < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (rate == '' || rate < 0 || !$.isNumeric(rate))
                    rate = 0;

                if (payable == '' || payable < 0 || !$.isNumeric(payable))
                    payable = 0;

                if (deduct_money == '' || deduct_money < 0 || !$.isNumeric(deduct_money))
                    deduct_money = 0;

                if (n_amount == '' || n_amount < 0 || !$.isNumeric(n_amount))
                    n_amount = 0;

                if (advance_amount == '' || advance_amount < 0 || !$.isNumeric(advance_amount))
                    advance_amount = 0;

                if (deduction_height_two == '' || deduction_height_two < 0 || !$.isNumeric(deduction_height_two))
                    deduction_height_two = 0;

                if (deduction_length_three == '' || deduction_length_three < 0 || !$.isNumeric(deduction_length_three))
                    deduction_length_three = 0;

                if (deduction_height_three == '' || deduction_height_three < 0 || !$.isNumeric(deduction_height_three))
                    deduction_height_three = 0;

             

                //console.log(totalDeduction);
                var total_amount = quantity * rate;
                var payable_amount = (total_amount * payable) / 100;
                var deduct_amount = (payable_amount * 5) / 100;
                var net_amount = payable_amount - deduct_amount;
                var approval_amount = net_amount - advance_amount;
                console.log(approval_amount);
                $('.t_amount:eq('+i+')').val(total_amount);
                $('.payable_a:eq('+i+')').val(payable_amount);
                $('.deduct_money:eq('+i+')').val(deduct_amount);
                $('.n_amount:eq('+i+')').val(net_amount);
                $('.approve_amount:eq('+i+')').val(approval_amount);
                // $('.sub-total-bricks:eq('+i+')').html(parseFloat(((((length * height) * wall_number) - totalDeduction)/brick_size) .toFixed(2)));
                // $('.sub-total-morter:eq('+i+')').html(parseFloat(((((((length * height) * wall_number) - totalDeduction)/brick_size) * morter) * dry_morter).toFixed(2)));

                // totalBricks += parseFloat(((((length * height) * wall_number) - totalDeduction)/brick_size).toFixed(2));
                // totalMorters += parseFloat(((((((length * height) * wall_number) - totalDeduction)/brick_size) * morter) * dry_morter).toFixed(2));
            });

            $('#total-bricks').html(totalBricks.toFixed(2));
            $('#total-morters').html(totalMorters.toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
