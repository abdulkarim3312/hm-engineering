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
    Bricks Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bricks Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('bricks_configure.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Bricks Wall Type</label>
                                    <select class="form-control select2" style="width: 100%;" id="wall_type" name="wall_type" data-placeholder="Select Wall Type">
                                        <option value="">Select Wall Type</option>
                                        <option value="1" {{ old('wall_type') == 1 ? 'selected' : '' }}>3 Inch wall</option>
                                        <option value="2" {{ old('wall_type') == 2 ? 'selected' : '' }}>5 Inch wall</option>
                                        <option value="3" {{ old('wall_type') == 3 ? 'selected' : '' }}>10 Inch wall</option>
                                    </select>

                                    @error('estimate_project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-md-2">
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

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('brick_size') ? 'has-error' :'' }}">
                                    <label>Brick Size</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="brick_size" step="any"
                                               name="brick_size" value="{{ old('brick_size') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('brick_size')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('morter') ? 'has-error' :'' }}">
                                    <label>Morter</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="morter" step="any"
                                               name="morter" value="{{ old('morter',0.02557) }}" readonly>
                                    </div>
                                    <!-- /.input group -->

                                    @error('morter')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('dry_morter') ? 'has-error' :'' }}">
                                    <label>Dry Morter</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="dry_morter" step="any"
                                               name="dry_morter" value="{{ old('dry_morter',1.35) }}" placeholder="Dry Morter">
                                    </div>
                                    <!-- /.input group -->

                                    @error('dry_morter')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('first_ratio') ? 'has-error' :'' }}">
                                    <label>Cement Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="first_ratio" step="any"
                                               name="first_ratio" value="{{ old('first_ratio') }}" placeholder="First Ratio">
                                    </div>
                                    <!-- /.input group -->

                                    @error('first_ratio')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('second_ratio') ? 'has-error' :'' }}">
                                    <label>Sand Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="second_ratio" step="any"
                                               name="second_ratio" value="{{ old('second_ratio') }}" placeholder="Second Ratio">
                                    </div>
                                    <!-- /.input group -->

                                    @error('second_ratio')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
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
                            <div class="col-md-8">
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
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('bricks_costing') ? 'has-error' :'' }}">
                                    <label>Bricks Cost(Per Pcs)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="0"
                                               name="bricks_costing"  placeholder="Enter Bricks Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('bricks_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('bricks_cement_costing') ? 'has-error' :'' }}">
                                    <label>Cement Cost(Per Bag)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ 0 }}"
                                               name="bricks_cement_costing"  placeholder="Enter Per Bag Cement Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('bricks_cement_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('bricks_sands_costing') ? 'has-error' :'' }}">
                                    <label>Sands Cost(Per Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $commonCost->column_sands_per_cost??0 }}"
                                               name="bricks_sands_costing"  placeholder="Enter Per Cft Sands Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('bricks_sands_costing')
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
                                    <th width="10%">Length</th>
                                    <th width="10%">Height</th>
                                    <th width="10%">Wall Number</th>
                                    <th width="10%">D.Length(1)</th>
                                    <th width="10%">D.height(1)</th>
                                    <th width="10%">D.Length(2)</th>
                                    <th width="10%">D.height(2)</th>
                                    <th width="10%">D.Length(3)</th>
                                    <th width="10%">D.height(3)</th>
                                    <th width="10%">Total Bricks</th>
                                    <th width="10%">Total Morters</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td class="col-md-3">
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
                                                <div class="form-group {{ $errors->has('wall_number.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control wall_number" name="wall_number[]" value="{{ old('wall_number.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_length_one" name="deduction_length_one[]" value="{{ old('deduction_length_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="{{ old('deduction_height_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_length_two" name="deduction_length_two[]" value="{{ old('deduction_length_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="{{ old('deduction_height_two.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_three.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_length_three" name="deduction_length_three[]" value="{{ old('deduction_length_three.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_three.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_three" name="deduction_height_three[]" value="{{ old('deduction_height_three.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total-bricks">0.00</td>
                                            <td class="sub-total-morter">0.00</td>
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
                                                <input type="number" step="any"  name="length[]" class="form-control length" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control height" name="height[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control wall_number" name="wall_number[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_length_one" name="deduction_length_one[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_length_two" name="deduction_length_two[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number"  step="any" class="form-control deduction_length_three" name="deduction_length_three[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_three" name="deduction_height_three[]" value="0">
                                            </div>
                                        </td>

                                        <td class="sub-total-bricks">0.00</td>
                                        <td class="sub-total-morter">0.00</td>
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
                                    <th colspan="10" class="text-right">Total Bricks</th>
                                    <th id="total-bricks"> 0.00 </th>
                                    <th id="total-morters"> 0.00 </th>
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
                    <input type="number" step="any"  name="length[]" class="form-control length" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control height" name="height[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control wall_number" name="wall_number[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_length_one" name="deduction_length_one[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_length_two" name="deduction_length_two[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number"  step="any" class="form-control deduction_length_three" name="deduction_length_three[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_three" name="deduction_height_three[]" value="0">
                </div>
            </td>

            <td class="sub-total-bricks">0.00</td>
            <td class="sub-total-morter">0.00</td>
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

            // $('body').on('change','.product', function () {
            //     var productID = $(this).val();
            //     var itemproductID = $(this);
            //     var barValue = itemproductID.closest('tr').find('.value_of_bar').val();
            //     var kgTon = itemproductID.closest('tr').find('.kg_by_ton').val();
            //     //var NumBar = itemproductID.closest('tr').find('.number_of_bar').val();
            //
            //
            //     if (productID != '') {
            //         itemproductID.closest('tr').find('.dia').val(productID);
            //         itemproductID.closest('tr').find('.dia_square').val(productID * productID);
            //         itemproductID.closest('tr').find('.kg_by_rft').val(((productID * productID) / barValue).toFixed(2));
            //         //var kgRft = itemproductID.closest('tr').find('.kg_by_rft').val();
            //         //itemproductID.closest('tr').find('.rft_by_ton').html((kgTon/kgRft).toFixed(0));
            //         //itemproductID.closest('tr').find('.rft_by_ton').val((NumBar * ton).toFixed(0));
            //     }
            // })
            // $('.product').trigger("change");

            $('body').on('keyup','#brick_size,#morter,#dry_morter,.length,.height,.wall_number,' +
                '.deduction_length_one,.deduction_height_one,'+
                '.deduction_length_two,.deduction_height_two,'+
                '.deduction_length_three,.deduction_height_three', function () {
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
                var length = $('.length:eq('+i+')').val();
                var height = $('.height:eq('+i+')').val();
                var wall_number = $('.wall_number:eq('+i+')').val();
                var deduction_length_one = $('.deduction_length_one:eq('+i+')').val();
                var deduction_height_one = $('.deduction_height_one:eq('+i+')').val();
                var deduction_length_two = $('.deduction_length_two:eq('+i+')').val();
                var deduction_height_two = $('.deduction_height_two:eq('+i+')').val();
                var deduction_length_three = $('.deduction_length_three:eq('+i+')').val();
                var deduction_height_three = $('.deduction_height_three:eq('+i+')').val();

                if (length == '' || length < 0 || !$.isNumeric(length))
                    length = 0;

                if (height == '' || height < 0 || !$.isNumeric(height))
                    height = 0;

                if (wall_number == '' || wall_number < 0 || !$.isNumeric(wall_number))
                    wall_number = 0;

                if (deduction_length_one == '' || deduction_length_one < 0 || !$.isNumeric(deduction_length_one))
                    deduction_length_one = 0;

                if (deduction_height_one == '' || deduction_height_one < 0 || !$.isNumeric(deduction_height_one))
                    deduction_height_one = 0;

                if (deduction_length_two == '' || deduction_length_two < 0 || !$.isNumeric(deduction_length_two))
                    deduction_length_two = 0;

                if (deduction_height_two == '' || deduction_height_two < 0 || !$.isNumeric(deduction_height_two))
                    deduction_height_two = 0;

                if (deduction_length_three == '' || deduction_length_three < 0 || !$.isNumeric(deduction_length_three))
                    deduction_length_three = 0;

                if (deduction_height_three == '' || deduction_height_three < 0 || !$.isNumeric(deduction_height_three))
                    deduction_height_three = 0;

                var deduction_one = parseFloat((deduction_length_one) * (deduction_height_one));
                var deduction_two = parseFloat((deduction_length_two) * (deduction_height_two));
                var deduction_three = parseFloat((deduction_length_three) * (deduction_height_three));

                var totalDeduction = deduction_one + deduction_two + deduction_three;

                //console.log(totalDeduction);

                $('.sub-total-bricks:eq('+i+')').html(parseFloat(((((length * height) * wall_number) - totalDeduction)/brick_size) .toFixed(2)));
                $('.sub-total-morter:eq('+i+')').html(parseFloat(((((((length * height) * wall_number) - totalDeduction)/brick_size) * morter) * dry_morter).toFixed(2)));

                totalBricks += parseFloat(((((length * height) * wall_number) - totalDeduction)/brick_size).toFixed(2));
                totalMorters += parseFloat(((((((length * height) * wall_number) - totalDeduction)/brick_size) * morter) * dry_morter).toFixed(2));
            });

            $('#total-bricks').html(totalBricks.toFixed(2));
            $('#total-morters').html(totalMorters.toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
