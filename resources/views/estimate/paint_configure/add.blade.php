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
                                <div class="form-group">
                                    <label>Main Paint Type</label>
                                    <select class="form-control main_paint_type" id="main_paint_type" name="main_paint_type[]" data-placeholder="Select WAll Direction" required>
                                        <option>Select Paint</option>
                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Polish Work</option>
                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Inside Paint Work</option>
                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Outside Paint Work</option>
                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Putty Work</option>
                                    </select>
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

                        <div class="table-responsive" id="polish_work">
                            <h2>Polish Paint Work</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Unit Section Area</th>
                                    <th width="10%">Wall Direction</th>
                                    <th width="20%">Polish Paint Type</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Price</th>
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
                                                    <select class="form-control product" name="product[]" data-placeholder="Select Unit Section" required>
                                                        @foreach($unitSections as $unitSection)
                                                            <option value="{{ $unitSection->id }}" {{ old('product.'.$loop->parent->index) == $unitSection->id ? 'selected' : '' }}>{{ $unitSection->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('wall_direction.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control wall_direction" name="wall_direction[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td id="polish_work" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control polish_paint_type" width="100%" id="polish_paint_type" name="polish_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Polish</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Spirit</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Gala</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Markin Cloth</option>
                                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 Paper</option>
                                                        <option value="5" {{ old('paint_type') == 5 ? 'selected' : '' }}>1.5 Paper</option>
                                                        <option value="6" {{ old('paint_type') == 6 ? 'selected' : '' }}>Chalk Paper</option>
                                                        <option value="7" {{ old('paint_type') == 7 ? 'selected' : '' }}>Candle</option>
                                                        <option value="8" {{ old('paint_type') == 8 ? 'selected' : '' }}>Brown</option>
                                                        <option value="9" {{ old('paint_type') == 9 ? 'selected' : '' }}>Sidur</option>
                                                        <option value="10" {{ old('paint_type') == 10 ? 'selected' : '' }}>Elamati</option>
                                                        <option value="11" {{ old('paint_type') == 11? 'selected' : '' }}>Zink Oxaid</option>
                                                        <option value="12" {{ old('paint_type') == 12 ? 'selected' : '' }}>Woodkeeper</option>
                                                        <option value="13" {{ old('paint_type') == 13 ?'selected' : '' }}>T6 Thiner</option>
                                                        <option value="14" {{ old('paint_type') == 14 ? 'selected' : '' }}>NC Thiner</option>
                                                    </select>
                                                </div>
                                            </td>
                                            {{-- <td id="inside_paint_work" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control inside_paint_type" id="inside_paint_type" name="inside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Inside</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Plastic Paint</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Enamel</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Water Sealer</option>
                                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Snow Seen</option>


                                                    </select>
                                                </div>
                                            </td> --}}
                                            {{-- <td id="outside_paint_work" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control outside_paint_type" id="outside_paint_type" name="outside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Outside</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Coat</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>White Cement</option>
                                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 no Paper</option>
                                                    </select>
                                                </div>
                                            </td> --}}
                                            {{-- <td id="putty_paint_work" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control putty_paint_type" id="putty_paint_type" name="putty_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Putty</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Chack Powder</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Enamel Paint</option>
                                                    </select>
                                                </div>
                                            </td> --}}
                                            <td>
                                                <div class="form-group" {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 50px;" readonly type="text" step="any"  name="unit[]" class="form-control unit" value="{{ old('unit.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 70px;" readonly type="text" step="any"  name="quantity[]" class="form-control quantity" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('price.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input  style="width: 60px;" type="text" step="any"  name="price[]" class="form-control price" value="{{ old('price.'.$loop->index) }}">
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
                                                <select class="form-control wall_direction" name="wall_direction[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                    <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                    <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                    <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control polish_paint_type" width="100%" id="polish_paint_type" name="polish_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Polish</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Spirit</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Gala</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Markin Cloth</option>
                                                    <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 Paper</option>
                                                    <option value="5" {{ old('paint_type') == 5 ? 'selected' : '' }}>1.5 Paper</option>
                                                    <option value="6" {{ old('paint_type') == 6 ? 'selected' : '' }}>Chalk Paper</option>
                                                    <option value="7" {{ old('paint_type') == 7 ? 'selected' : '' }}>Candle</option>
                                                    <option value="8" {{ old('paint_type') == 8 ? 'selected' : '' }}>Brown</option>
                                                    <option value="9" {{ old('paint_type') == 9 ? 'selected' : '' }}>Sidur</option>
                                                    <option value="10" {{ old('paint_type') == 10 ? 'selected' : '' }}>Elamati</option>
                                                    <option value="11" {{ old('paint_type') == 11? 'selected' : '' }}>Zink Oxaid</option>
                                                    <option value="12" {{ old('paint_type') == 12 ? 'selected' : '' }}>Woodkeeper</option>
                                                    <option value="13" {{ old('paint_type') == 13 ?'selected' : '' }}>T6 Thiner</option>
                                                    <option value="14" {{ old('paint_type') == 14 ? 'selected' : '' }}>NC Thiner</option>
                                                </select>
                                            </div>
                                        </td>
                                        {{-- <td id="inside_paint_work" style="display: none;">
                                            <div class="form-group">
                                                <select class="form-control inside_paint_type" id="inside_paint_type" name="inside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Inside</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Plastic Paint</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Enamel</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Water Sealer</option>
                                                    <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Snow Seen</option>


                                                </select>
                                            </div>
                                        </td>
                                        <td id="outside_paint_work" style="display: none;">
                                            <div class="form-group">
                                                <select class="form-control outside_paint_type" id="outside_paint_type" name="outside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Outside</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Coat</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>White Cement</option>
                                                    <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 no Paper</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td id="putty_paint_work" style="display: none;">
                                            <div class="form-group">
                                                <select class="form-control putty_paint_type" id="putty_paint_type" name="putty_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Putty</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Chack Powder</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Enamel Paint</option>
                                                </select>
                                            </div>
                                        </td> --}}
                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 50px;" readonly type="text" step="any"  name="unit[]" class="form-control unit">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 70px;" readonly type="text" step="any"  name="quantity[]" class="form-control quantity">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="width: 60px;" type="text" step="any"  name="price[]" class="form-control price">
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
                        <div class="table-responsive" id="inside_paint_work">
                            <h2>Inside Paint Work</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Unit Section Area</th>
                                    <th width="10%">Wall Direction</th>
                                    <th width="20%">Inside Paint Type</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Price</th>
                                    <th width="10%">Length</th>
                                    <th width="10%">Height/Width</th>
                                    <th width="10%">Deduction Length(1)</th>
                                    <th width="10%">Deduction Height/Width(1)</th>
                                    <th width="10%">Deduction Length(2)</th>
                                    <th width="10%">Deduction Height/Width(2)</th>
                                    <th width="10%">Side</th>
                                    <th width="10%">Code Nos</th>
                                    <th>Total Area</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container-inside">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product" name="product[]" data-placeholder="Select Unit Section" required>
                                                        @foreach($unitSections as $unitSection)
                                                            <option value="{{ $unitSection->id }}" {{ old('product.'.$loop->parent->index) == $unitSection->id ? 'selected' : '' }}>{{ $unitSection->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('wall_direction.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control wall_direction" name="wall_direction_inside[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td id="inside_paint_work" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control inside_paint_type" id="inside_paint_type" name="inside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Inside</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Plastic Paint</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Enamel</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Water Sealer</option>
                                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Snow Seen</option>


                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group" {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 50px;" readonly type="text" step="any"  name="unit_inside[]" class="form-control unit" value="{{ old('unit.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 70px;" readonly type="text" step="any"  name="quantity_inside[]" class="form-control quantity" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('price.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input  style="width: 60px;" type="text" step="any"  name="price_inside[]" class="form-control price" value="{{ old('price.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="length_inside[]" class="form-control length" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control height" name="height_inside[]" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_one_inside[]" class="form-control deduction_length_one" value="0" value="{{ old('deduction_length_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_inside[]" value="0" value="{{ old('deduction_height_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_two_inside[]" class="form-control deduction_length_two" value="0" value="{{ old('deduction_length_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_inside[]" value="0" value="{{ old('deduction_height_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('side.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control side" name="side_inside[]" value="{{ old('side.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('code_nos.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control code_nos" name="code_nos_inside[]" value="{{ old('code_nos.'.$loop->index) }}">
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
                                                <select class="form-control select2 product" name="product_inside[]" data-placeholder="Select Unit Section" required>
                                                    @foreach($unitSections as $unitSection)
                                                        <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control wall_direction" name="wall_direction_inside[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                    <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                    <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                    <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td id="inside_paint_work">
                                            <div class="form-group">
                                                <select class="form-control inside_paint_type" id="inside_paint_type" name="inside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Inside</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Plastic Paint</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Enamel</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Water Sealer</option>
                                                    <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Snow Seen</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 50px;" readonly type="text" step="any"  name="unit_inside[]" class="form-control unit">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 70px;" readonly type="text" step="any"  name="quantity_inside[]" class="form-control quantity">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="width: 60px;" type="text" step="any"  name="price_inside[]" class="form-control price">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="length_inside[]" class="form-control length">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control height" name="height_inside[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_one_inside[]" class="form-control deduction_length_one" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_inside[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_two_inside[]" class="form-control deduction_length_two" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_inside[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control side" name="side_inside[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control code_nos" name="code_nos_inside[]" value="1">
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
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product-inside">Add More</a>
                                    </td>
                                    <th colspan="10" class="text-right">Total Area</th>
                                    <th id="total-area"> 0.00 </th>
                                    <th id="total-liter"> 0.00 </th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="table-responsive" id="outside_paint_work">
                            <h2>Outside Paint Work</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Unit Section Area</th>
                                    <th width="10%">Wall Direction</th>
                                    <th width="20%">Outside Work</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Price</th>
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

                                <tbody id="product-container-outside">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product" name="product_outside[]" data-placeholder="Select Unit Section" required>
                                                        @foreach($unitSections as $unitSection)
                                                            <option value="{{ $unitSection->id }}" {{ old('product.'.$loop->parent->index) == $unitSection->id ? 'selected' : '' }}>{{ $unitSection->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('wall_direction.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control wall_direction" name="wall_direction_outside[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td id="outside_paint_work">
                                                <div class="form-group">
                                                    <select class="form-control outside_paint_type" id="outside_paint_type" name="outside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Outside</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Coat</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>White Cement</option>
                                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 no Paper</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 50px;" readonly type="text" step="any"  name="unit_outside[]" class="form-control unit" value="{{ old('unit.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 70px;" readonly type="text" step="any"  name="quantity_outside[]" class="form-control quantity" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('price.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input  style="width: 60px;" type="text" step="any"  name="price_outside[]" class="form-control price" value="{{ old('price.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="length_outside[]" class="form-control length" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control height" name="height_outside[]" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_one_outside[]" class="form-control deduction_length_one" value="0" value="{{ old('deduction_length_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_outside[]" value="0" value="{{ old('deduction_height_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_two_outside[]" class="form-control deduction_length_two" value="0" value="{{ old('deduction_length_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_outside[]" value="0" value="{{ old('deduction_height_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('side.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control side" name="side_outside[]" value="{{ old('side.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('code_nos.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control code_nos" name="code_nos_outside[]" value="{{ old('code_nos.'.$loop->index) }}">
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
                                                <select class="form-control select2 product" name="product_outside[]" data-placeholder="Select Unit Section" required>
                                                    @foreach($unitSections as $unitSection)
                                                        <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control wall_direction" name="wall_direction_outside[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                    <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                    <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                    <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td id="outside_paint_work">
                                            <div class="form-group">
                                                <select class="form-control outside_paint_type" id="outside_paint_type" name="outside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Outside</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Coat</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>White Cement</option>
                                                    <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 no Paper</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 50px;" readonly type="text" step="any"  name="unit_outside[]" class="form-control unit">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 70px;" readonly type="text" step="any"  name="quantity_outside[]" class="form-control quantity">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="width: 60px;" type="text" step="any"  name="price_outside[]" class="form-control price">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="length_outside[]" class="form-control length">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control height" name="height_outside[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_one_outside[]" class="form-control deduction_length_one" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_outside[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_two_outside[]" class="form-control deduction_length_two" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_outside[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control side" name="side_outside[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control code_nos" name="code_nos_outside[]" value="1">
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
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product-outside">Add More</a>
                                    </td>
                                    <th colspan="10" class="text-right">Total Area</th>
                                    <th id="total-area"> 0.00 </th>
                                    <th id="total-liter"> 0.00 </th>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="table-responsive" id="putty_paint_work">
                            <h2>Putty Work</h2>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="10%">Unit Section Area</th>
                                    <th width="10%">Wall Direction</th>
                                    <th width="20%">Putty Work</th>
                                    <th width="10%">Unit</th>
                                    <th width="10%">Quantity</th>
                                    <th width="10%">Price</th>
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

                                <tbody id="product-container-putty">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product" name="product_putty[]" data-placeholder="Select Unit Section" required>
                                                        @foreach($unitSections as $unitSection)
                                                            <option value="{{ $unitSection->id }}" {{ old('product.'.$loop->parent->index) == $unitSection->id ? 'selected' : '' }}>{{ $unitSection->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('wall_direction.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control wall_direction" name="wall_direction_putty[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td id="putty_paint_work" style="display: none;">
                                                <div class="form-group">
                                                    <select class="form-control putty_paint_type" id="putty_paint_type" name="putty_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option value="null">Select Putty</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Chack Powder</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Enamel Paint</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 50px;" readonly type="text" step="any"  name="unit_putty[]" class="form-control unit" value="{{ old('unit.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input style="width: 70px;" readonly type="text" step="any"  name="quantity_putty[]" class="form-control quantity" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group" {{ $errors->has('price.'.$loop->index) ? 'has-error' :'' }}>
                                                    <input  style="width: 60px;" type="text" step="any"  name="price_putty[]" class="form-control price" value="{{ old('price.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="length_putty[]" class="form-control length" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control height" name="height_putty[]" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_one_putty[]" class="form-control deduction_length_one" value="0" value="{{ old('deduction_length_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_putty[]" value="0" value="{{ old('deduction_height_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_two_putty[]" class="form-control deduction_length_two" value="0" value="{{ old('deduction_length_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_putty[]" value="0" value="{{ old('deduction_height_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('side.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control side" name="side_putty[]" value="{{ old('side.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('code_nos.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control code_nos" name="code_nos_putty[]" value="{{ old('code_nos.'.$loop->index) }}">
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
                                                <select class="form-control select2 product" name="product_putty[]" data-placeholder="Select Unit Section" required>
                                                    @foreach($unitSections as $unitSection)
                                                        <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control wall_direction" name="wall_direction_putty[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                                                    <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                                                    <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                                                    <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                                                </select>
                                            </div>
                                        </td>


                                        <td id="putty_paint_work">
                                            <div class="form-group">
                                                <select class="form-control putty_paint_type" id="putty_paint_type" name="putty_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option value="null">Select Putty</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Chack Powder</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Enamel Paint</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 50px;" readonly type="text" step="any"  name="unit_putty[]" class="form-control unit">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input  style="width: 70px;" readonly type="text" step="any"  name="quantity_putty[]" class="form-control quantity">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="width: 60px;" type="text" step="any"  name="price_putty[]" class="form-control price">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="length_putty[]" class="form-control length">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control height" name="height_putty[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_one_putty[]" class="form-control deduction_length_one" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_putty[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_two_putty[]" class="form-control deduction_length_two" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_putty[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control side" name="side_putty[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control code_nos" name="code_nos_putty[]" value="1">
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
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product-putty">Add More</a>
                                    </td>
                                    <th colspan="10" class="text-right">Total Area</th>
                                    <th id="total-area-putty"> 0.00 </th>
                                    <th id="total-liter-putty"> 0.00 </th>
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
                    <select class="form-control product" name="product[]" data-placeholder="Select Unit Section" required>
                        @foreach($unitSections as $unitSection)
                            <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control wall_direction" name="wall_direction[]" data-placeholder="Select WAll Direction" required>
                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                    </select>
                </div>
            </td>
            <td id="polish_work">
                <div class="form-group">
                    <select class="form-control select2 polish_paint_type" width="100%" id="polish_paint_type" name="polish_paint_type[]" data-placeholder="Select WAll Direction" required>
                        <option value="null">Select Polish</option>
                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Spirit</option>
                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Gala</option>
                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Markin Cloth</option>
                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 Paper</option>
                        <option value="5" {{ old('paint_type') == 5 ? 'selected' : '' }}>1.5 Paper</option>
                        <option value="6" {{ old('paint_type') == 6 ? 'selected' : '' }}>Chalk Paper</option>
                        <option value="7" {{ old('paint_type') == 7 ? 'selected' : '' }}>Candle</option>
                        <option value="8" {{ old('paint_type') == 8 ? 'selected' : '' }}>Brown</option>
                        <option value="9" {{ old('paint_type') == 9 ? 'selected' : '' }}>Sidur</option>
                        <option value="10" {{ old('paint_type') == 10 ? 'selected' : '' }}>Elamati</option>
                        <option value="11" {{ old('paint_type') == 11? 'selected' : '' }}>Zink Oxaid</option>
                        <option value="12" {{ old('paint_type') == 12 ? 'selected' : '' }}>Woodkeeper</option>
                        <option value="13" {{ old('paint_type') == 13 ?'selected' : '' }}>T6 Thiner</option>
                        <option value="14" {{ old('paint_type') == 14 ? 'selected' : '' }}>NC Thiner</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input style="width: 50px;" type="text" step="any" readonly  name="unit[]" class="form-control unit">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 70px;" type="text" step="any" readonly  name="quantity[]" class="form-control quantity">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 60px;" type="text" step="any"  name="price[]" class="form-control price">
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
    <template id="template-product-inside">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product" name="product_inside[]" data-placeholder="Select Unit Section" required>
                        @foreach($unitSections as $unitSection)
                            <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control wall_direction" name="wall_direction_inside[]" data-placeholder="Select WAll Direction" required>
                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                    </select>
                </div>
            </td>

            <td id="inside_paint_work">
                <div class="form-group">
                    <select class="form-control select2 inside_paint_type" width="100%" id="inside_paint_type" name="inside_paint_type[]" data-placeholder="Select WAll Direction" required>
                        <option value="null">Select Inside</option>
                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Plastic Paint</option>
                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Enamel</option>
                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Water Sealer</option>
                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Snow Seen</option>


                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 50px;" type="text" step="any" readonly  name="unit_inside[]" class="form-control unit">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 70px;" type="text" step="any" readonly  name="quantity_inside[]" class="form-control quantity">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 60px;" type="text" step="any"  name="price_inside[]" class="form-control price">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="length_inside[]" class="form-control length">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control height" name="height_inside[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_one_inside[]" class="form-control deduction_length_one" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_inside[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_two_inside[]" class="form-control deduction_length_two" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_inside[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control side" name="side_inside[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control code_nos" name="code_nos_inside[]" value="1">
                </div>
            </td>

            <td class="sub-total-area">0.00</td>
            <td class="sub-total-liter">0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
    <template id="template-product-outside">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product" name="product_outside[]" data-placeholder="Select Unit Section" required>
                        @foreach($unitSections as $unitSection)
                            <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control wall_direction" name="wall_direction_outside[]" data-placeholder="Select WAll Direction" required>
                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                    </select>
                </div>
            </td>

            <td id="outside_paint_work">
                <div class="form-group">
                    <select class="form-control select2 outside_paint_type" id="outside_paint_type" name="outside_paint_type[]" data-placeholder="Select WAll Direction" required>
                        <option value="null">Select Outside</option>
                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Coat</option>
                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>White Cement</option>
                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 no Paper</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 50px;" type="text" step="any" readonly  name="unit_outside[]" class="form-control unit">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 70px;" type="text" step="any" readonly  name="quantity_outside[]" class="form-control quantity">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 60px;" type="text" step="any"  name="price_outside[]" class="form-control price">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="length_outside[]" class="form-control length">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control height" name="height_outside[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_one_outside[]" class="form-control deduction_length_one" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_outside[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_two_outside[]" class="form-control deduction_length_two" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_outside[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control side" name="side_outside[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control code_nos" name="code_nos_outside[]" value="1">
                </div>
            </td>

            <td class="sub-total-area">0.00</td>
            <td class="sub-total-liter">0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
    <template id="template-product-putty">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product" name="product_putty[]" data-placeholder="Select Unit Section" required>
                        @foreach($unitSections as $unitSection)
                            <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control wall_direction" name="wall_direction_putty[]" data-placeholder="Select WAll Direction" required>
                        <option value="1" {{ old('wall_direction') == 1 ? 'selected' : '' }}>East</option>
                        <option value="2" {{ old('wall_direction') == 2 ? 'selected' : '' }}>West</option>
                        <option value="3" {{ old('wall_direction') == 3 ? 'selected' : '' }}>North</option>
                        <option value="4" {{ old('wall_direction') == 4 ? 'selected' : '' }}>South</option>
                    </select>
                </div>
            </td>

            <td id="putty_paint_work">
                <div class="form-group">
                    <select class="form-control select2 putty_paint_type" id="putty_paint_type" name="putty_paint_type[]" data-placeholder="Select WAll Direction" required>
                        <option value="null">Select Putty</option>
                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Chack Powder</option>
                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Enamel Paint</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 50px;" type="text" step="any" readonly  name="unit_putty[]" class="form-control unit">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 70px;" type="text" step="any" readonly  name="quantity_putty[]" class="form-control quantity">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input style="width: 60px;" type="text" step="any"  name="price_putty[]" class="form-control price">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="length_putty[]" class="form-control length">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control height" name="height_putty[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_one_putty[]" class="form-control deduction_length_one" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one_putty[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_two_putty[]" class="form-control deduction_length_two" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two_putty[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control side" name="side_putty[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control code_nos" name="code_nos_putty[]" value="1">
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


            $('body').on('change','#main_paint_type', function () {
                var paintType = $(this).val();
                if (paintType == 1) {
                    $('#inside_paint_work').hide();
                    $('#outside_paint_work').hide();
                    $('#putty_paint_work').hide();
                    $('#polish_work').show();
                }else if(paintType == 2){
                    $('#inside_paint_work').show();
                    $('#outside_paint_work').hide();
                    $('#putty_paint_work').hide();
                    $('#polish_work').hide();
                }else if(paintType == 3){
                    $('#outside_paint_work').show();
                    $('#inside_paint_work').hide();
                    $('#putty_paint_work').hide();
                    $('#polish_work').hide();
                }else {
                    $('#putty_paint_work').show();
                    $('#inside_paint_work').hide();
                    $('#outside_paint_work').hide();
                    $('#polish_work').hide();
                }
            })
            $('#main_paint_type').trigger("change");


            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                $('#product-container').append(item);

                initProduct();
                calculate();
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

            $('#btn-add-product-inside').click(function () {
                var html = $('#template-product-inside').html();
                var item = $(html);

                $('#product-container-inside').append(item);

                initProduct();
                calculate();
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
            $('#btn-add-product-outside').click(function () {
                var html = $('#template-product-outside').html();
                var item = $(html);

                $('#product-container-outside').append(item);

                initProduct();
                calculate();
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
            $('#btn-add-product-putty').click(function () {
                var html = $('#template-product-putty').html();
                var item = $(html);

                $('#product-container-putty').append(item);

                initProduct();
                calculate();
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


            $('body').on('change','.polish_paint_type', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                var spirit_unit = 'ltr';
                var galan_unit = 'gm';
                var markin_unit = 'yrds';
                var papar_one_unit = 'pcs';
                var paper_two_unit = 'pcs';
                var chalk_unit = 'gram';
                var candle_unit = 'gram';
                var brown_unit = 'gram';
                var sidur_unit = 'gram';
                var elamati_unit = 'gram';
                var zink_unit = 'gram';
                var wook_unit = 'ltr';
                var t6_unit = 'ltr';
                var nc_unit = 'ltr';
                var spirit = 0.03;
                var gala = 2.5;
                var markin = 0.06;
                var papar_120 = 0.01;
                var papar_1 = 0.02;
                var chalk_powder = 2.5;
                var candle = 0.5;
                var brown = 0.1;
                var sidur = 0.1;
                var elamati = 0.05;
                var t6_thiner = 0.000375;
                var nc_thiner = 0.000375;
                var zink = 2;
                var woodkeeper = 0.005;

                if (productID == 1) {
                    var barValue = itemproductID.closest('tr').find('.unit').val(spirit_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(spirit);
                }else if(productID == 2){
                    var barValue = itemproductID.closest('tr').find('.unit').val(galan_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(gala);
                }else if(productID == 3){
                    var barValue = itemproductID.closest('tr').find('.unit').val(markin_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(markin);
                }else if(productID == 4){
                    var barValue = itemproductID.closest('tr').find('.unit').val(papar_one_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(papar_120);
                }else if(productID == 5){
                    var barValue = itemproductID.closest('tr').find('.unit').val(paper_two_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(papar_1);
                }else if(productID == 6){
                    var barValue = itemproductID.closest('tr').find('.unit').val(chalk_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(chalk_powder);
                }else if(productID == 7){
                    var barValue = itemproductID.closest('tr').find('.unit').val(candle_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(candle);
                }else if(productID == 8){
                    var barValue = itemproductID.closest('tr').find('.unit').val(brown_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(brown);
                }else if(productID == 9){
                    var barValue = itemproductID.closest('tr').find('.unit').val(sidur_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(sidur);
                }else if(productID == 10){
                    var barValue = itemproductID.closest('tr').find('.unit').val(elamati_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(elamati);
                }else if(productID == 11){
                    var barValue = itemproductID.closest('tr').find('.unit').val(zink_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(zink);
                }else if(productID == 12){
                    var barValue = itemproductID.closest('tr').find('.unit').val(wook_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(woodkeeper);
                }else if(productID == 13){
                    var barValue = itemproductID.closest('tr').find('.unit').val(t6_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(t6_thiner);
                }else {
                    var barValue = itemproductID.closest('tr').find('.unit').val(nc_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(nc_thiner);
                }
            })
            $('.polish_paint_type').trigger("change");

            $('body').on('change','.inside_paint_type', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                var plastic_unit = 'galan';
                var eanmel_unit = 'galan';
                var water_unit = 'galan';
                var snow_unit = 'galan';
                var plastic = 0.00333;
                var eanmel = 0.00312;
                var water = 0.002;
                var snow = 0.002;


                if (productID == 1) {
                    var barValue = itemproductID.closest('tr').find('.unit').val(plastic_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(plastic);
                }else if(productID == 2){
                    var barValue = itemproductID.closest('tr').find('.unit').val(eanmel_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(eanmel);
                }else if(productID == 3){
                    var barValue = itemproductID.closest('tr').find('.unit').val(water_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(water);
                }else {
                    var barValue = itemproductID.closest('tr').find('.unit').val(snow_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(snow);
                }
            })
            $('.inside_paint_type').trigger("change");

            $('body').on('change','.outside_paint_type', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                var wather_unit = 'galan';
                var plastic_unit = 'galan';
                var white_unit = 'kg';
                var paper_unit = 'nos';
                var plastic = 0.005;
                var wather = 0.001;
                var white = 0.0333;
                var paper = 0.005;

                if (productID == 1) {
                    var barValue = itemproductID.closest('tr').find('.unit').val(wather_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(wather);
                }else if(productID == 2){
                    var barValue = itemproductID.closest('tr').find('.unit').val(plastic_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(plastic);
                }else if(productID == 3){
                    var barValue = itemproductID.closest('tr').find('.unit').val(white_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(white);
                }else {
                    var barValue = itemproductID.closest('tr').find('.unit').val(paper_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(paper);
                }
            })
            $('.outside_paint_type').trigger("change");

            $('body').on('change','.putty_paint_type', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                var chalk_unit = 'bag';
                var plastic_unit = 'liter';
                var enamel_unit = 'liter';
                var chalk = 0.00125;
                var plastic = 0.00312;
                var enamel = 0.00375;

                if (productID == 1) {
                    var barValue = itemproductID.closest('tr').find('.unit').val(chalk_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(chalk);
                }else if(productID == 2){
                    var barValue = itemproductID.closest('tr').find('.unit').val(plastic_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(plastic);
                }else {
                    var barValue = itemproductID.closest('tr').find('.unit').val(enamel_unit);
                    var barValue = itemproductID.closest('tr').find('.quantity').val(enamel);
                }
            })
            $('.putty_paint_type').trigger("change");


            $('body').on('keyup','.length,.height,.side,.code_nos,.deduction_length_one' +
                '.deduction_height_one,.deduction_length_two,.deduction_height_two,#polish_work_data', function () {
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
            var unit_spirit = $('#polish_work_data').val();

            if (unit_spirit == '' || unit_spirit < 0 || !$.isNumeric(unit_spirit))
                unit_spirit = 0;
            $('.product-item').each(function(i, obj) {
                var length = $('.length:eq('+i+')').val();
                var height = $('.height:eq('+i+')').val();
                var quantity = $('.quantity:eq('+i+')').val();
                var price = $('.price:eq('+i+')').val();
                var deduction_length_one = $('.deduction_length_one:eq('+i+')').val();
                var deduction_height_one = $('.deduction_height_one:eq('+i+')').val();
                var deduction_length_two = $('.deduction_length_two:eq('+i+')').val();
                var deduction_height_two = $('.deduction_height_two:eq('+i+')').val();
                var side = $('.side:eq('+i+')').val();
                var code_nos = $('.code_nos:eq('+i+')').val();

                // console.log(quantity);

                if (length == '' || length < 0 || !$.isNumeric(length))
                    length = 0;

                if (height == '' || height < 0 || !$.isNumeric(height))
                    height = 0;

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (price == '' || price < 0 || !$.isNumeric(price))
                    price = 0;

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
                var item = (quantity * (length * height)) * price;
                console.log(item);

                $('.sub-total-area:eq('+i+')').html(parseFloat(((((length * height) - totalDeduction) * side ) * code_nos).toFixed(2)));
                $('.sub-total-liter:eq('+i+')').html(parseFloat((((((((length * height) * quantity) * price) - totalDeduction) * side) * code_nos )) .toFixed(2)));

                totalArea += parseFloat((((length * height) - totalDeduction) * side) * code_nos);
                totalLiter += parseFloat(((((((length * height) * quantity) * price) - totalDeduction) * side) * code_nos));
            });

            $('#total-area-putty').html(parseFloat(totalArea).toFixed(2));
            $('#total-liter-putty').html(parseFloat(totalLiter).toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
