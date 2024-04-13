@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Petty Cash Adjustment
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Petty Cash Adjustment Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('petty_cash_adjustment.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimate Project</option>

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
                                <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                                    <label>Project Address</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="address" step="any"
                                               name="address" value="" placeholder="Enter Address">
                                    </div>
                                    <!-- /.input group -->

                                    @error('address')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('month') ? 'has-error' :'' }}">
                                    <label>Month</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="month" step="any"
                                               name="month" value="" placeholder="Enter Month">
                                    </div>
                                    <!-- /.input group -->

                                    @error('month')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('acc_holder_name') ? 'has-error' :'' }}">
                                    <label>Account Holder Name</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="address" step="any"
                                               name="acc_holder_name" value="" placeholder="Enter Account Holder Name">
                                    </div>
                                    <!-- /.input group -->

                                    @error('acc_holder_name')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Note</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Enter Note" id="note" name="note" value="{{ old('note') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <h3>Petty Cash Adjustment</h3>
                            <hr>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="30%">Particulars</th>
                                    <th width="20%">Receive Amount(Tk)</th>
                                    <th width="20%">Expense Amount(Tk)</th>
                                    <th width="20%">Balance Amount(Tk)</th>
                                    <th width="10%"></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control product" name="product[]" value="{{ old('product.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('receive_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control receive_amount" name="receive_amount[]" value="{{ old('receive_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('expense_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="expense_amount[]" class="form-control expense_amount" value="{{ old('expense_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('balance_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control balance_amount" name="balance_amount[]" value="{{ old('balance_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total-area">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any"  name="product[]" class="form-control product">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="receive_amount[]" class="form-control receive_amount" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="expense_amount[]" class="form-control expense_amount" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control balance_amount" name="balance_amount[]" value="0">
                                            </div>
                                        </td>

                                        {{-- <td class="sub-total-area">0.00</td> --}}
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
                                    {{-- <th colspan="1" class="text-right">Total Amount</th>
                                    <th id="total-one"> 0.00 </th>
                                    <th id="total-two"> 0.00 </th>
                                    <th id="total-three"> 0.00 </th> --}}
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
                <div class="form-group">
                    <input type="text" step="any"  name="product[]" class="form-control product">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="receive_amount[]" class="form-control receive_amount" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="expense_amount[]" class="form-control expense_amount" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control balance_amount" name="balance_amount[]" value="0">
                </div>
            </td>

            {{-- <td class="sub-total-area">0.00</td> --}}
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

            $('body').on('change','#configure_type', function () {
                var configureType = $(this).val();

                if (configureType == 1) {
                    $('#grill_costing').show();
                    $('#tiles_glass_costing').hide();
                }else if(configureType == 2 || configureType == 3){
                    $('#grill_costing').hide();
                    $('#tiles_glass_costing').show();
                }else {

                }
            })
            $('#configure_type').trigger("change");

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

            $('body').on('keyup','.expense_amount,.quantity', function () {
                calculate();
            });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
            calculate();
        });

        function calculate() {
            var totalOne = 0;
            var totalTwo = 0;
            var totalThree = 0;

            $('.product-item').each(function(i, obj) {
                var receive_amount = $('.receive_amount:eq('+i+')').val();
                var expense_amount = $('.expense_amount:eq('+i+')').val();

                // alert(previous_balance);

                if (receive_amount == '' || receive_amount < 0 || !$.isNumeric(length))
                    receive_amount = 0;

                if (expense_amount == '' || expense_amount < 0 || !$.isNumeric(expense_amount))
                    expense_amount = 0;


                var item = receive_amount - expense_amount;

                $('.balance_amount:eq('+i+')').val(item);
            });

            // $('#total-one').html(parseFloat(totalOne).toFixed(2));
            // $('#total-two').html(parseFloat(totalTwo).toFixed(2));
            // $('#total-three').html(parseFloat(totalThree).toFixed(2));
        }

        function initProduct() {
            $('.product');
        }
    </script>
@endsection
