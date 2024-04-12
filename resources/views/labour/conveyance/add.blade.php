@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Conveyance Form
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Conveyance Form Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('conveyance.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('project_id') ? 'has-error' :'' }}">
                                    <label>Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="project_id" name="project_id" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimate Project</option>

                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('project_id')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                                    <label>Name</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" step="any"
                                               name="name" value="" placeholder="Enter Name">
                                    </div>
                                    <!-- /.input group -->

                                    @error('name')
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
                            <div class="col-md-6">
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

                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('designation') ? 'has-error' :'' }}">
                                    <label>Designation</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Enter Designation" id="designation" name="designation" value="{{ old('designation') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('designation')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <h3>Conveyance Form</h3>
                            <hr>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Start From</th>
                                    <th>End To</th>
                                    <th>Media</th>
                                    <th>Purpose</th>
                                    <th>Amount(Tk)</th>
                                    <th width="10%"></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="date" class="form-control product" name="product[]" value="{{ old('product.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('start_from.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="date" step="any" class="form-control" name="start_from[]" value="{{ old('start_from.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('end_to.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="date" step="any" class="form-control" name="end_to[]" value="{{ old('end_to.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('media.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control" name="media[]" value="{{ old('media.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control" name="purpose[]" value="{{ old('amount.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('amount.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control amount" name="amount[]" value="{{ old('amount.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total-amount">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group input-group date">
                                                <input type="date" step="any"  name="product[]" class="form-control product">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="date" step="any"  name="start_from[]" class="form-control">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="date" step="any"  name="end_to[]" class="form-control">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any"  name="media[]" class="form-control">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any"  name="purpose[]" class="form-control">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="amount[]" class="form-control amount">
                                            </div>
                                        </td>

                                        <td class="sub-total-amount">0.00</td>
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
                                    <th colspan="5" class="text-right">Total Amount</th>
                                    <th id="total-one"> 0.00 </th>
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
                    <input type="date" name="product[]" class="form-control product">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="date" name="start_from[]" class="form-control">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="date" name="end_to[]" class="form-control">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="media[]" class="form-control">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="purpose[]" class="form-control">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="amount[]" class="form-control amount">
                </div>
            </td>
            <td class="sub-total-amount">0.00</td>
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

            $('body').on('keyup','.amount', function () {
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
            var totalAmount = 0;

            $('.product-item').each(function(i, obj) {
                var amount = $('.amount:eq('+i+')').val();
                // alert(amount);
                if (amount == '' || amount < 0 || !$.isNumeric(amount))
                    amount = 0;


                $('.sub-total-amount:eq('+i+')').html(parseFloat(amount));

                totalAmount += parseFloat(amount);
            });

            $('#total-one').html(parseFloat(totalAmount).toFixed(2));
        }

        function initProduct() {
            $('.product');
        }
    </script>
@endsection
