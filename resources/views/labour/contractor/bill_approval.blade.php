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
    Contractor Bill Approval
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
                <form method="POST" action="{{ route('bill.approved_store',['billStatement' => $billStatement->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Project">
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ $billStatement->estimate_project == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('estimate_project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('trade') ? 'has-error' :'' }}">
                                    <label>Type Of Work(Trade)</label>

                                    <select name="trade" readonly class="form-control" >
                                        <option value="">Select Trade</option>
                                        <option value="1" {{ $billStatement->trade == 1 ? 'selected': '' }}>Civil Contractor</option>
                                        <option value="2" {{ $billStatement->trade == 2 ? 'selected': '' }}>Paint Contractor</option>
                                        <option value="3" {{ $billStatement->trade == 3 ? 'selected': '' }}>Sanitary Contractor</option>
                                        <option value="4" {{ $billStatement->trade == 4 ? 'selected': '' }}>Tiles Contractor</option>
                                        <option value="5" {{ $billStatement->trade == 5 ? 'selected': '' }}>MS Contractor</option>
                                        <option value="6" {{ $billStatement->trade == 6 ? 'selected': '' }}>Wood Contractor</option>
                                        <option value="7" {{ $billStatement->trade == 7 ? 'selected': '' }}>Electric Contractor</option>
                                        <option value="8" {{ $billStatement->trade == 8 ? 'selected': '' }}>Thai Contractor</option>
                                        <option value="9" {{ $billStatement->trade == 9 ? 'selected': '' }}>Other Contractor</option>
                                    </select>

                                    @error('trade')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('contractor') ? 'has-error' :'' }}">
                                    <label>Contractor Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" readonly id="contractor" name="contractor" readonly value="{{ $billStatement->contractor ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('contractor') ? 'has-error' :'' }}">
                                    <label>Approved Note</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Enter Approve Note" id="approved_note" name="approved_note" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                                    <label>Address</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" readonly id="address" name="address" value="{{ $billStatement->address ?? '' }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('address')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Approval Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" readonly id="date" name="approved_date" value="{{ $billStatement->approved_date ?? '' }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('cheque_holder_name') ? 'has-error' :'' }}">
                                    <label>Cheque Holder Name</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" readonly id="cheque_holder_name" name="cheque_holder_name" value="{{ $billStatement->cheque_holder_name ?? '' }}">
                                    </div>

                                    @error('cheque_holder_name')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <h3>Contractor Bill Statement</h3>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Item Code</th>
                                    <th width="20%">Description of work</th>
                                    <th width="10%">Bill No</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">App. Quantity</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Rate</th>
                                    <th width="10%">Amount</th>
                                    <th width="10%">Payable(%)</th>
                                    <th width="10%">App.Payable(%)</th>
                                    <th width="10%">Payable Amount</th>
                                    <th width="10%">Deduct SD M(%)</th>
                                    <th width="10%">Net Amount</th>
                                    <th width="10%">A. Amount</th>
                                    <th width="10%">Approval Amount</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                    @foreach ($billStatement->billStatementDescription as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group">
                                                    <div class="form-group {{ $errors->has('item_code.') ? 'has-error' :'' }}">
                                                        <input type="text" readonly name="item_code[]" class="form-control item_code" value="{{ $item->item_code ?? '' }}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <div class="form-group {{ $errors->has('work_description.') ? 'has-error' :'' }}">
                                                        <input style="width: 220px;" readonly type="text" step="any"  name="work_description[]" class="form-control work_description" value="{{ $item->work_description ?? '' }}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="width:70px;" readonly type="text" step="any"  name="bill_no[]" class="form-control bill_no" value="{{ $item->bill_no ?? '' }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ $item->quantity - $item->app_quantity ?? '' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control approved_quantity" name="approved_quantity[]" value="{{$item->quantity - $item->app_quantity}}"
                                                    max="{{$item->quantity - $item->app_quantity}}">
                                                </div>
                                                @error('approved_quantity')
                                                <span class="help-block">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" step="any" readonly class="form-control unit" name="unit[]" value="{{ $item->unit ?? '' }}" style="width:70px;">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" readonly class="form-control rate" name="rate[]" value="{{ $item->rate ?? '' }}" style="width:70px;">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" readonly class="form-control t_amount" name="t_amount[]" value="{{ $item->t_amount ?? '' }}" style="width:80px;">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" readonly class="form-control payable" name="payable[]" value="{{ $item->payable - $item->app_payable ?? '' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control app_payable" name="app_payable[]" value="{{$item->payable - $item->app_payable}}"
                                                    max="{{$item->payable - $item->app_payable}}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" readonly class="form-control payable_a" name="payable_a[]" value="{{ $item->payable_a ?? '' }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control deduct_money" readonly name="deduct_money[]" value="{{ $item->deduct_money ?? '' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="number"  step="any" readonly class="form-control n_amount" name="n_amount[]" value="{{ $item->n_amount ?? '' }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" class="form-control advance_amount" name="advance_amount[]" value="{{ $item->advance_amount ?? '' }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="any" readonly class="form-control approve_amount" name="approve_amount[]" value="{{ $item->approve_amount ?? '' }}">
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                               
                                </tbody>

                                <tfoot>
                                <tr>
                                    {{-- <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add More</a>
                                    </td> --}}
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

            $('body').on('keyup','#brick_size,#morter,#dry_morter,.quantity,.rate,.t_amount,.payable,.deduct_amount,.n_amount,.advance_amount,.approved_quantity,.app_payable', function () {
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
                var approved_quantity = $('.approved_quantity:eq('+i+')').val();
                var rate = $('.rate:eq('+i+')').val();
                var payable = $('.payable:eq('+i+')').val();
                var app_payable = $('.app_payable:eq('+i+')').val();
                var deduct_money = $('.deduct_money:eq('+i+')').val();
                var n_amount = $('.n_amount:eq('+i+')').val();
                var advance_amount = $('.advance_amount:eq('+i+')').val();
                var deduction_length_two = $('.deduction_length_two:eq('+i+')').val();
                var deduction_height_two = $('.deduction_height_two:eq('+i+')').val();
                var deduction_length_three = $('.deduction_length_three:eq('+i+')').val();
                var deduction_height_three = $('.deduction_height_three:eq('+i+')').val();
                // alert(app_payable);
                if (quantity == '' || length < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (approved_quantity == '' || length < 0 || !$.isNumeric(approved_quantity))
                    approved_quantity = 0;

                if (rate == '' || rate < 0 || !$.isNumeric(rate))
                    rate = 0;

                if (payable == '' || payable < 0 || !$.isNumeric(payable))
                    payable = 0;

                if (app_payable == '' || app_payable < 0 || !$.isNumeric(app_payable))
                    app_payable = 0;

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
                var total_amount = approved_quantity * rate;
                var payable_amount = (total_amount * app_payable) / 100;
                var deduct_amount = (payable_amount * 5) / 100;
                var net_amount = payable_amount - deduct_amount;
                
                if(advance_amount >= 0){
                    var approval_amount = net_amount - advance_amount;
                }else{
                    var approval_amount = net_amount - advance_amount;
                }
                console.log(total_amount);
                $('.t_amount:eq('+i+')').val(total_amount);
                $('.payable_a:eq('+i+')').val(payable_amount);
                $('.deduct_money:eq('+i+')').val(deduct_amount);
                $('.n_amount:eq('+i+')').val(net_amount);
                $('.approve_amount:eq('+i+')').val(approval_amount);
            });

            $('#total-bricks').html(totalBricks.toFixed(2));
            $('#total-morters').html(totalMorters.toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
