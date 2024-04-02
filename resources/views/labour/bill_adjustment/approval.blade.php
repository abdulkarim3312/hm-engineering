@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Petty Cash Requisition Approve
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Petty Cash Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('petty_cash.approval',['pettyCash' => $pettyCash->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimate Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ $pettyCash->estimate_project == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
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
                                               name="address" value="{{ $pettyCash->address ?? '' }}" placeholder="Enter Address">
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
                                               name="month" value="{{ $pettyCash->month ?? '' }}" placeholder="Enter Month">
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
                                               name="acc_holder_name" value="{{ $pettyCash->acc_holder_name ?? '' }}" placeholder="Enter Account Holder Name">
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
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ $pettyCash->date ?? '' }}" autocomplete="off">
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
                                        <input type="text" class="form-control" placeholder="Enter Note" id="note" name="note" value="{{ $pettyCash->note ?? ''  }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <h3>Petty Cash Form</h3>
                            <hr>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="30%">Particulars</th>
                                    <th width="20%">Previous Balance(Tk)</th>
                                    <th width="20%">Budget Amount(Tk)</th>
                                    <th width="20%">Required Amount(Tk)</th>
                                    <th width="20%">Recommended Amount(Tk)</th>
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
                                                <div class="form-group {{ $errors->has('previous_balance.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control previous_balance" name="previous_balance[]" value="{{ old('previous_balance.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('budget_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control budget_amount" name="budget_amount[]" value="{{ old('budget_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('required_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="required_amount[]" class="form-control required_amount" value="{{ old('required_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('recommended_amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control recommended_amount" name="recommended_amount[]" value="{{ old('recommended_amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            {{-- <td class="sub-total-area">0.00</td> --}}
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                  @foreach ($pettyCash->pettyCashProduct as $product)
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any"  name="product[]" class="form-control product" value="{{ $product->product ?? '' }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="previous_balance[]" class="form-control previous_balance" value="{{ $product->previous_balance ?? '' }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="budget_amount[]" class="form-control budget_amount" value="{{ $product->budget_amount ?? '' }}">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="required_amount[]" class="form-control required_amount" value="{{ $product->required_amount ?? '' }}">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control recommended_amount" name="recommended_amount[]" value="{{ $product->recommended_amount ?? '' }}">
                                            </div>
                                        </td>

                                        {{-- <td class="sub-total-area">0.00</td> --}}
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                  @endforeach
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add More</a>
                                    </td>
                                    {{-- <th colspan="2" class="text-right">Total Amount</th>
                                    <th id="total-area"> 0.00 </th> --}}
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

            $('body').on('keyup','.budget_amount,.quantity', function () {
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
            var totalArea = 0;
            var totalTwo = 0;
            var totalThree = 0;

            $('.product-item').each(function(i, obj) {
                var previous_balance = $('.previous_balance:eq('+i+')').val();
                var budget_amount = $('.budget_amount:eq('+i+')').val();

                // alert(previous_balance);

                if (previous_balance == '' || previous_balance < 0 || !$.isNumeric(length))
                    previous_balance = 0;

                if (budget_amount == '' || budget_amount < 0 || !$.isNumeric(budget_amount))
                    budget_amount = 0;

                
                var item = budget_amount - previous_balance;
                console.log(item);
            
                // totalThree += parseFloat(((previous_balance * budget_amount)).toFixed(2));
                $('.required_amount:eq('+i+')').val(item);
                $('.recommended_amount:eq('+i+')').val(item);
            });
        }

        function initProduct() {
            $('.product');
        }
    </script>
@endsection
