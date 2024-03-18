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
                            {{-- <div id="putty_paint_work">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Putty Paint Type</label>
                                        <select class="form-control select2 putty_paint_type" id="putty_paint_type" name="putty_paint_type[]" data-placeholder="Select WAll Direction" required>
                                            <option value="null">Select Putty</option>
                                            <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Chack Powder</option>
                                            <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                            <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Enamel Paint</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div id="polish_work">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Polish Paint Type</label>
                                        <select class="form-control select2 polish_paint_type" id="polish_paint_type" name="polish_paint_type[]" data-placeholder="Select WAll Direction" required>
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
                                </div>
                            </div> --}}
                            {{-- <div id="inside_paint_work">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Inside Paint Type</label>
                                        <select class="form-control select2 inside_paint_type" id="inside_paint_type" name="inside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                            <option value="null">Select Inside</option>
                                            <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Plastic Paint</option>
                                            <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Enamel</option>
                                            <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Water Sealer</option>
                                            <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Snow Seen</option>


                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="outside_paint_work">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Outside Paint Type</label>
                                        <select class="form-control select2 outside_paint_type" id="outside_paint_type" name="outside_paint_type[]" data-placeholder="Select WAll Direction" required>
                                            <option value="null">Select Outside</option>
                                            <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Weather Coat</option>
                                            <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Plastic Paint</option>
                                            <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>White Cement</option>
                                            <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>120 no Paper</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('floor_number') ? 'has-error' :'' }}">
                                    <label>Unit</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" readonly  id="unit_spirit" step="any"
                                               name="floor_number" value="" >
                                    </div>
                                    <!-- /.input group -->

                                    @error('floor_number')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('floor_number') ? 'has-error' :'' }}">
                                    <label>Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="polish_work_data" step="any"
                                               name="floor_number" value="{{ old('floor_number') }}" >
                                    </div>
                                    <!-- /.input group -->

                                    @error('floor_number')
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

                        <u><i><h3>Costing Area</h3></i></u>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('paint_costing') ? 'has-error' :'' }}">
                                    <label>Paint Costing(Per Liter)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any"
                                               name="paint_costing"  placeholder="Enter Per Liter Costing">
                                    </div>
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
                                    <th width="10%">Unit Section Area</th>
                                    <th width="10%">Wall Direction</th>
                                    <th width="10%">Main Paint</th>
                                    <th id="polish_work_td" width="20%">Polish Paint Type</th>
                                    <th id="inside_paint_work_td" width="20%">Inside Paint Type</th>
                                    <th id="outside_paint_work_td" width="20%">Outside Paint Type</th>
                                    <th id="putty_paint_work_td" width="20%">Putty Paint Type</th>
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
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control main_paint_type" id="main_paint_type" name="main_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                        <option>Select Paint</option>
                                                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Polish Work</option>
                                                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Inside Paint Work</option>
                                                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Outside Paint Work</option>
                                                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Putty Work</option>
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
                                                <select class="form-control main_paint_type" id="main_paint_type" name="main_paint_type[]" data-placeholder="Select WAll Direction" required>
                                                    <option>Select Paint</option>
                                                    <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Polish Work</option>
                                                    <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Inside Paint Work</option>
                                                    <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Outside Paint Work</option>
                                                    <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Putty Work</option>
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
            <td>
                <div class="form-group">
                    <select class="form-control main_paint_type" id="main_paint_type" name="main_paint_type[]" data-placeholder="Select WAll Direction" required>
                        <option>Select Paint</option>
                        <option value="1" {{ old('paint_type') == 1 ? 'selected' : '' }}>Polish Work</option>
                        <option value="2" {{ old('paint_type') == 2 ? 'selected' : '' }}>Inside Paint Work</option>
                        <option value="3" {{ old('paint_type') == 3 ? 'selected' : '' }}>Outside Paint Work</option>
                        <option value="4" {{ old('paint_type') == 4 ? 'selected' : '' }}>Putty Work</option>
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


            $('body').on('change','#main_paint_type', function () {
                var paintType = $(this).val();
                if (paintType == 1) {
                    $('#inside_paint_work').hide();
                    $('#inside_paint_work_td').hide();
                    $('#outside_paint_work').hide();
                    $('#outside_paint_work_td').hide();
                    $('#putty_paint_work').hide();
                    $('#putty_paint_work_td').hide();
                    $('#polish_work').show();
                    $('#polish_work_td').show();
                }else if(paintType == 2){
                    $('#inside_paint_work').show();
                    $('#inside_paint_work_td').show();
                    $('#outside_paint_work').hide();
                    $('#outside_paint_work_td').hide();
                    $('#putty_paint_work').hide();
                    $('#putty_paint_work_td').hide();
                    $('#polish_work').hide();
                    $('#polish_work_td').hide();
                }else if(paintType == 3){
                    $('#outside_paint_work').show();
                    $('#outside_paint_work_td').show();
                    $('#inside_paint_work').hide();
                    $('#inside_paint_work_td').hide();
                    $('#putty_paint_work').hide();
                    $('#putty_paint_work_td').hide();
                    $('#polish_work').hide();
                    $('#polish_work_td').hide();
                }else {
                    $('#putty_paint_work').show();
                    $('#putty_paint_work_td').show();
                    $('#inside_paint_work').hide();
                    $('#inside_paint_work_td').hide();
                    $('#outside_paint_work').hide();
                    $('#outside_paint_work_td').hide();
                    $('#polish_work').hide();
                    $('#polish_work_td').hide();
                }
            })
            $('#main_paint_type').trigger("change");

            $('body').on('change','#polish_paint_type', function () {
                var subPaintType = $(this).val();
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

                if (subPaintType == 1) {
                    $('#polish_work_data').val(spirit);
                    $('#unit_spirit').val(spirit_unit);

                }else if(subPaintType == 2){
                    $('#polish_work_data').val(gala);
                    $('#unit_spirit').val(galan_unit);
                }else if(subPaintType == 3){
                    $('#polish_work_data').val(markin);
                    $('#markin_unit').val(galan_unit);
                }else if(subPaintType == 4){
                    $('#polish_work_data').val(papar_120);
                    $('#unit_spirit').val(papar_one_unit);
                }else if(subPaintType == 5){
                    $('#polish_work_data').val(papar_1);
                    $('#unit_spirit').val(paper_two_unit);
                }else if(subPaintType == 6){
                    $('#polish_work_data').val(chalk_powder);
                    $('#unit_spirit').val(chalk_unit);
                }else if(subPaintType == 7){
                    $('#polish_work_data').val(candle);
                    $('#unit_spirit').val(candle_unit);
                }else if(subPaintType == 8){
                    $('#polish_work_data').val(brown);
                    $('#unit_spirit').val(brown_unit);
                }else if(subPaintType == 9){
                    $('#polish_work_data').val(sidur);
                    $('#unit_spirit').val(sidur_unit);
                }else if(subPaintType == 10){
                    $('#polish_work_data').val(elamati);
                    $('#unit_spirit').val(elamati_unit);
                }else if(subPaintType == 11){
                    $('#polish_work_data').val(zink);
                    $('#unit_spirit').val(zink_unit);
                }else if(subPaintType == 12){
                    $('#polish_work_data').val(woodkeeper);
                    $('#unit_spirit').val(wook_unit);
                }else if(subPaintType == 13){
                    $('#polish_work_data').val(t6_thiner);
                    $('#unit_spirit').val(t6_unit);
                }else {
                    $('#polish_work_data').val(nc_thiner);
                    $('#unit_spirit').val(nc_unit);
                }
            })
            $('#polish_paint_type').trigger("change");


            $('body').on('change','#inside_paint_type', function () {
                var insidePaintType = $(this).val();
                var plastic_unit = 'galan';
                var eanmel_unit = 'galan';
                var water_unit = 'galan';
                var snow_unit = 'galan';
                var plastic = 0.00333;
                var eanmel = 0.00312;
                var water = 0.002;
                var snow = 0.002;


                if (insidePaintType == 1) {
                    $('#polish_work_data').val(plastic);
                    $('#unit_spirit').val(plastic_unit);

                }else if(insidePaintType == 2){
                    $('#polish_work_data').val(eanmel);
                    $('#unit_spirit').val(eanmel_unit);
                }else if(insidePaintType == 3){
                    $('#polish_work_data').val(water);
                    $('#markin_unit').val(water_unit);
                }else {
                    $('#polish_work_data').val(snow);
                    $('#unit_spirit').val(snow_unit);
                }
            })
            $('#inside_paint_type').trigger("change");

            $('body').on('change','#outside_paint_type', function () {
                var insidePaintType = $(this).val();
                var wather_unit = 'galan';
                var plastic_unit = 'galan';
                var white_unit = 'kg';
                var paper_unit = 'nos';
                var plastic = 0.005;
                var wather = 0.001;
                var white = 0.0333;
                var paper = 0.005;

                if (insidePaintType == 1) {
                    $('#polish_work_data').val(plastic);
                    $('#unit_spirit').val(plastic_unit);

                }else if(insidePaintType == 2){
                    $('#polish_work_data').val(wather);
                    $('#unit_spirit').val(wather_unit);
                }else if(insidePaintType == 3){
                    $('#polish_work_data').val(white);
                    $('#unit_spirit').val(white_unit);
                }else {
                    $('#polish_work_data').val(paper);
                    $('#unit_spirit').val(paper_unit);
                }
            })
            $('#outside_paint_type').trigger("change");

            $('body').on('change','#putty_paint_type', function () {
                var puttyPaintType = $(this).val();
                var chalk_unit = 'bag';
                var plastic_unit = 'liter';
                var enamel_unit = 'liter';
                var chalk = 0.00125;
                var plastic = 0.00312;
                var enamel = 0.00375;


                if (puttyPaintType == 1) {
                    $('#polish_work_data').val(chalk);
                    $('#unit_spirit').val(chalk_unit);

                }else if(puttyPaintType == 2){
                    $('#polish_work_data').val(plastic);
                    $('#unit_spirit').val(plastic_unit);
                }else {
                    $('#polish_work_data').val(enamel);
                    $('#unit_spirit').val(enamel_unit);
                }
            })
            $('#putty_paint_type').trigger("change");

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
                var item = unit_spirit * (length * height)
                console.log(item);

                $('.sub-total-area:eq('+i+')').html(parseFloat(((((length * height) - totalDeduction) * side ) * code_nos).toFixed(2)));
                $('.sub-total-liter:eq('+i+')').html(parseFloat(((((((length * height) * unit_spirit) - totalDeduction) * side) * code_nos )) .toFixed(2)));

                totalArea += parseFloat((((length * height) - totalDeduction) * side) * code_nos);
                totalLiter += parseFloat((((((length * height) * unit_spirit) - totalDeduction) * side) * code_nos));
            });

            $('#total-area').html(parseFloat(totalArea).toFixed(2));
            $('#total-liter').html(parseFloat(totalLiter).toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
