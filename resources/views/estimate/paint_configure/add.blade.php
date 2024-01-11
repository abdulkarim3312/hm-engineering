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
        select.form-control.product{
            width: 120px;
        }
        select.form-control.paint_type{
            width: 120px;
        }
        input.form-control.side{
            width: 100px;
        }
        input.form-control.code_nos{
            width: 100px;
        }
        /*input.form-control.deduction_length_one{*/
        /*    width: 100px;*/
        /*}*/
        /*input.form-control.deduction_height_one{*/
        /*    width: 100px;*/
        /*}*/
        /*input.form-control.deduction_length_two{*/
        /*    width: 100px;*/
        /*}*/
        /*input.form-control.deduction_height_two{*/
        /*    width: 100px;*/
        /*}*/
        input.form-control.length{
            width: 100px;
        }
        input.form-control.spacing{
            width: 100px;
        }
        input.form-control.layer{
            width: 100px;
        }
    </style>
@endsection


@section('title')
    Paint Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Paint Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('paint_configure.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Estimate Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimate Project</option>

                                        @foreach($estimateProjects as $estimateProject)
                                            <option value="{{ $estimateProject->id }}" {{ old('estimate_project') == $estimateProject->id ? 'selected' : '' }}>{{ $estimateProject->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('estimate_project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_floor') ? 'has-error' :'' }}">
                                    <label>Estimate Floor</label>

                                    <select class="form-control select2" style="width: 100%;" name="estimate_floor" id="estimate_floor" data-placeholder="Select Estimate Floor">
                                        <option value="">Select Estimate Floor</option>
                                    </select>

                                    @error('estimate_floor')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_floor_unit') ? 'has-error' :'' }}">
                                    <label>Estimate Floor Unit</label>

                                    <select class="form-control select2" style="width: 100%;" name="estimate_floor_unit" data-placeholder="Select Estimate Floor Unit">
                                        <option value="">Select Estimate Floor Unit</option>
                                        @foreach($estimateFloorUnits as $estimateFloorUnit)
                                            <option value="{{ $estimateFloorUnit->id }}" {{ old('estimate_floor_unit') == $estimateFloorUnit->id ? 'selected' : '' }}>{{ $estimateFloorUnit->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('estimate_floor_unit')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('floor_number') ? 'has-error' :'' }}">
                                    <label>Floor Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="floor_number" step="any"
                                               name="floor_number" value="{{ old('floor_number',1) }}" >
                                    </div>
                                    <!-- /.input group -->

                                    @error('floor_number')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('color_paint_per_cft') ? 'has-error' :'' }}">
                                    <label>Color Paint Per Cft</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="color_paint_per_cft" step="any"
                                               name="color_paint_per_cft" value="{{ old('color_paint_per_cft',0.01111) }}" readonly>
                                    </div>
                                    <!-- /.input group -->

                                    @error('color_paint_per_cft')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('seller_paint_per_cft') ? 'has-error' :'' }}">
                                    <label>Seller Paint Per Cft</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="seller_paint_per_cft" step="any"
                                               name="seller_paint_per_cft" value="{{ old('seller_paint_per_cft',0.01111) }}" readonly>
                                    </div>
                                    <!-- /.input group -->

                                    @error('seller_paint_per_cft')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
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

                        <u><i><h3>Costing Area</h3></i></u>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('paint_costing') ? 'has-error' :'' }}">
                                    <label>Paint Costing(Per Liter)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any"
                                               name="paint_costing"  placeholder="Enter Per Liter Costing">
                                    </div>
{{--                                    value="{{ $grillGlassTilesCost->grill_costing??0 }}"--}}
                                    <!-- /.input group -->

                                    @error('paint_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('seller_costing') ? 'has-error' :'' }}">
                                    <label>Seller Costing(Per Liter)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any"
                                               name="seller_costing"  placeholder="Enter Per Liter Costing">
                                    </div>
                                    <!-- /.input group -->
{{--                                    value="{{ $grillGlassTilesCost->tiles_glass_costing??0 }}"--}}

                                    @error('seller_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Unit Section</th>
                                    <th width="10%">Wall Direction</th>
                                    <th width="10%">Paint Type</th>
                                    <th width="10%">Length</th>
                                    <th width="10%">Height/Width</th>
                                    <th width="10%">Deduction Length(1)</th>
                                    <th width="10%">Deduction Height/Width(1)</th>
                                    <th width="10%">Deduction Length(2)</th>
                                    <th width="10%">Deduction Height/Width(2)</th>
                                    <th width="10%">Side</th>
                                    <th width="10%">Code Nos</th>
                                    <th>Total Area</th>
                                    <th>Total Liter</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" name="product[]" data-placeholder="Select Unit Section" required>
                                                        @foreach($unitSections as $unitSection)
                                                            <option value="{{ $unitSection->id }}" {{ old('product.'.$loop->parent->index) == $unitSection->id ? 'selected' : '' }}>{{ $unitSection->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('wall_direction.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 wall_direction" name="wall_direction[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('paint_type.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 paint_type" name="paint_type[]" data-placeholder="Select Paint Type" required>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Code</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Dis-Temper</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="length[]" class="form-control length" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control height" name="height[]" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_one[]" class="form-control deduction_length_one" value="0" value="{{ old('deduction_length_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0" value="{{ old('deduction_height_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_two[]" class="form-control deduction_length_two" value="0" value="{{ old('deduction_length_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0" value="{{ old('deduction_height_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('side.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control side" name="side[]" value="{{ old('side.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('code_nos.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control code_nos" name="code_nos[]" value="{{ old('code_nos.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total-area">0.00</td>
                                            <td class="sub-total-liter">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product" name="product[]" data-placeholder="Select Unit Section" required>
                                                    @foreach($unitSections as $unitSection)
                                                        <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 wall_direction" name="wall_direction[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                    <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                    <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                    <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 paint_type" name="paint_type[]" data-placeholder="Select Paint Type" required>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Code</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Dis-Temper</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="length[]" class="form-control length">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control height" name="height[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_one[]" class="form-control deduction_length_one" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_two[]" class="form-control deduction_length_two" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control side" name="side[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control code_nos" name="code_nos[]" value="1">
                                            </div>
                                        </td>

                                        <td class="sub-total-area">0.00</td>
                                        <td class="sub-total-liter">0.00</td>
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
                                    <th colspan="10" class="text-right">Total Area</th>
                                    <th id="total-area"> 0.00 </th>
                                    <th id="total-liter"> 0.00 </th>
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
                    <select class="form-control select2 product" name="product[]" data-placeholder="Select Unit Section" required>
                        @foreach($unitSections as $unitSection)
                            <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control select2 wall_direction" name="wall_direction[]" data-placeholder="Select WAll Direction" required>
                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control select2 paint_type" name="paint_type[]" data-placeholder="Select Paint Type" required>
                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Code</option>
                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Dis-Temper</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="length[]" class="form-control length">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control height" name="height[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_one[]" class="form-control deduction_length_one" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_two[]" class="form-control deduction_length_two" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control side" name="side[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control code_nos" name="code_nos[]" value="1">
                </div>
            </td>

            <td class="sub-total-area">0.00</td>
            <td class="sub-total-liter">0.00</td>
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

            $('body').on('keyup','.length,.height,.side,.code_nos,.deduction_length_one' +
                '.deduction_height_one,.deduction_length_two,.deduction_height_two', function () {
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
            var totalLiter = 0;
            var color_paint_per_cft = $('#color_paint_per_cft').val();

            $('.product-item').each(function(i, obj) {
                var length = $('.length:eq('+i+')').val();
                var height = $('.height:eq('+i+')').val();
                var deduction_length_one = $('.deduction_length_one:eq('+i+')').val();
                var deduction_height_one = $('.deduction_height_one:eq('+i+')').val();
                var deduction_length_two = $('.deduction_length_two:eq('+i+')').val();
                var deduction_height_two = $('.deduction_height_two:eq('+i+')').val();
                var side = $('.side:eq('+i+')').val();
                var code_nos = $('.code_nos:eq('+i+')').val();

                //alert(code_nos);

                if (length == '' || length < 0 || !$.isNumeric(length))
                    length = 0;

                if (height == '' || height < 0 || !$.isNumeric(height))
                    height = 0;

                if (deduction_length_one == '' || deduction_length_one < 0 || !$.isNumeric(deduction_length_one))
                    deduction_length_one = 0;

                if (deduction_height_one == '' || deduction_height_one < 0 || !$.isNumeric(deduction_height_one))
                    deduction_height_one = 0;

                if (deduction_length_two == '' || deduction_length_two < 0 || !$.isNumeric(deduction_length_two))
                    deduction_length_two = 0;

                if (deduction_height_two == '' || deduction_height_two < 0 || !$.isNumeric(deduction_height_two))
                    deduction_height_two = 0;

                if (side == '' || side < 0 || !$.isNumeric(side))
                    side = 0;

                if (code_nos == '' || code_nos < 0 || !$.isNumeric(code_nos))
                    code_nos = 0;

                var deduction_one = parseFloat(deduction_length_one * deduction_height_one);
                var deduction_two = parseFloat(deduction_length_two * deduction_height_two);

                var totalDeduction = deduction_one + deduction_two;

                $('.sub-total-area:eq('+i+')').html(parseFloat(((((length * height) - totalDeduction) * side ) * code_nos).toFixed(2)));
                $('.sub-total-liter:eq('+i+')').html(parseFloat((((((length * height) - totalDeduction) * side) * code_nos ) * color_paint_per_cft) .toFixed(2)));

                totalArea += parseFloat((((length * height) - totalDeduction) * side) * code_nos);
                totalLiter += parseFloat(((((length * height) - totalDeduction) * side) * code_nos) * color_paint_per_cft);
            });

            $('#total-area').html(parseFloat(totalArea).toFixed(2));
            $('#total-liter').html(parseFloat(totalLiter).toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
