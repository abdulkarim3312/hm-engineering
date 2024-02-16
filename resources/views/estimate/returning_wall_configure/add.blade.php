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
        input.form-control.dia{
            width: 120px;
        }
        input.form-control.kg_by_rft{
            width: 100px;
        }
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
    Returning Wall Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Returning Wall Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('returning_wall_configure.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Estimate Projects</label>

                                    <select class="form-control select2" style="width: 100%;" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Costing Common</option>

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
                                <div class="form-group {{ $errors->has('costing_segment') ? 'has-error' :'' }}">
                                    <label>Costing Segments</label>

                                    <select class="form-control select2" style="width: 100%;" name="costing_segment" data-placeholder="Select Costing Segment">
                                        <option value="">Select Costing Segment</option>

                                        @foreach($costingSegments as $costingSegment)
                                            <option value="{{ $costingSegment->id }}" {{ old('estimate_project') == $costingSegment->id ? 'selected' : '' }}>{{ $costingSegment->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('costing_segment')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('costing_segment_quantity') ? 'has-error' :'' }}">
                                    <label>Costing Segment Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="costing_segment_quantity" step="any"
                                               name="costing_segment_quantity" value="{{ old('costing_segment_quantity') }}" placeholder="Costing Segment Quantity">
                                    </div>
                                    <!-- /.input group -->

                                    @error('costing_segment_quantity')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('course_aggregate_type') ? 'has-error' :'' }}">
                                    <label>Course Aggregate Type</label>

                                    <select class="form-control select2" style="width: 100%;" name="course_aggregate_type" id="course_aggregate_type"
                                            data-placeholder="Select Course Aggregate Type">
{{--                                        <option value="">Select Course Aggregate Type</option>--}}
                                        <option value="1" {{ old('course_aggregate_type') == 1 ? 'selected' : '' }}>Stone</option>
                                        <option value="2" {{ old('course_aggregate_type') == 2 ? 'selected' : '' }}>Brick Chips</option>
                                    </select>

                                    @error('course_aggregate_type')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('segment_length') ? 'has-error' :'' }}">
                                    <label>Segment Length(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="segment_length" step="any"
                                               name="segment_length" value="{{ old('segment_length') }}" placeholder="Segment Length">
                                    </div>
                                    <!-- /.input group -->

                                    @error('segment_length')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('segment_width') ? 'has-error' :'' }}">
                                    <label>Segment Width(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="segment_width" step="any"
                                               name="segment_width" value="{{ old('segment_width') }}" placeholder="Segment Width">
                                    </div>
                                    <!-- /.input group -->

                                    @error('segment_width')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('segment_thickness') ? 'has-error' :'' }}">
                                    <label style="font-size: 15px;">Segment Thickness(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="segment_thickness" step="any"
                                               name="segment_thickness" value="{{ old('segment_thickness') }}" placeholder="Segment Thickness">
                                    </div>
                                    <!-- /.input group -->

                                    @error('segment_thickness')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('total_volume') ? 'has-error' :'' }}">
                                    <label>Total Volume(Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="total_volume" readonly step="0.01"
                                               name="total_volume" value="{{ old('total_volume', 0.00) }}" placeholder="Total Volume">
                                    </div>
                                    <!-- /.input group -->

                                    @error('total_volume')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('dry_volume') ? 'has-error' :'' }}">
                                    <label>Dry Volume(Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="dry_volume" step="any"
                                               name="dry_volume" value="1.5" placeholder="Enter Dry Volume">
                                    </div>
                                    <!-- /.input group -->

                                    @error('dry_volume')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('dry_volume') ? 'has-error' :'' }}">
                                    <label>Total Dry Volume(Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="total_dry_volume" step="any"
                                               name="total_dry_volume" value="{{ old('total_dry_volume',1.5) }}" placeholder="total_dry_volume Volume" readonly>
                                    </div>
                                    <!-- /.input group -->

                                    @error('total_dry_volume')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('first_ratio') ? 'has-error' :'' }}">
                                    <label> First Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="first_ratio" step="any"
                                               name="first_ratio" value="{{ old('first_ratio') }}" placeholder="First(R)">
                                    </div>
                                    <!-- /.input group -->

                                    @error('first_ratio')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('second_ratio') ? 'has-error' :'' }}">
                                    <label>Second Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="second_ratio" step="any"
                                               name="second_ratio" value="{{ old('second_ratio') }}" placeholder="Second(S)">
                                    </div>
                                    <!-- /.input group -->

                                    @error('second_ratio')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('third_ratio') ? 'has-error' :'' }}">
                                    <label>Third Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="third_ratio" step="any"
                                               name="third_ratio" value="{{ old('third_ratio') }}" placeholder="Third(Th)">
                                    </div>
                                    <!-- /.input group -->

                                    @error('third_ratio')
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
                            <div class="col-md-4">
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
                                <div class="form-group {{ $errors->has('common_bar_costing') ? 'has-error' :'' }}">
                                    <label>Bar Cost(Per Kg)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any"
                                               name="common_bar_costing" value="{{ $columnCost->column_bar_per_cost??0 }}" placeholder="Enter Per Kg Bar Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('common_bar_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('common_cement_costing') ? 'has-error' :'' }}">
                                    <label>Cement Cost(Per Bag)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $columnCost->column_cement_per_cost??0 }}"
                                               name="common_cement_costing"  placeholder="Enter Per Bag Cement Costing"
                                        >
                                    </div>
                                    <!-- /.input group -->

                                    @error('common_cement_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('common_sands_costing') ? 'has-error' :'' }}">
                                    <label>Sands Cost(Per Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $columnCost->column_sands_per_cost??0 }}"
                                               name="common_sands_costing"  placeholder="Enter Per Cft Sands Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('common_sands_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div id="common_aggregate_costing">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('common_aggregate_costing') ? 'has-error' :'' }}">
                                        <label>Aggregate Cost(Cft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ $columnCost->column_aggregate_per_cost??0 }}"
                                                   name="common_aggregate_costing"  placeholder="Enter Per Cft Aggregates Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('common_aggregate_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="common_picked_costing">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('common_picked_costing') ? 'has-error' :'' }}">
                                        <label>Picked Cost(Per Cft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ $columnCost->column_picked_per_cost??0 }}"
                                                   name="common_picked_costing"  placeholder="Enter Per Pcs Picked Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('common_picked_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <strong><h3>Calculation of Main Bar</h3></strong>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Bar Type</th>
                                    <th>Dia</th>
                                    <th>Dia Square(D^2)</th>
                                    <th>Value of Bar</th>
                                    <th>Kg/Rft</th>
                                    <th>Kg/Ton</th>
                                    <th>Length Type</th>
                                    <th>Length</th>
                                    <th>Spacing</th>
                                    <th>Type Length</th>
                                    <th>Layer</th>
                                    <th>Kg</th>
                                    <th>Ton</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" name="product[]" data-placeholder="Select Product" required>
                                                            <option value="6" {{ old('product') == 6 ? 'selected' : '' }}>6mm</option>
                                                            <option value="8" {{ old('product') == 8 ? 'selected' : '' }}>8mm</option>
                                                            <option value="10" {{ old('product') == 10 ? 'selected' : '' }}>10mm</option>
                                                            <option value="12" {{ old('product') == 12 ? 'selected' : '' }}>12mm</option>
                                                            <option value="16" {{ old('product') == 16 ? 'selected' : '' }}>16mm</option>
                                                            <option value="18" {{ old('product') == 18 ? 'selected' : '' }}>18mm</option>
                                                            <option value="20" {{ old('product') == 20 ? 'selected' : '' }}>20mm</option>
                                                            <option value="22" {{ old('product') == 22 ? 'selected' : '' }}>22mm</option>
                                                            <option value="25" {{ old('product') == 25 ? 'selected' : '' }}>25mm</option>
                                                            <option value="28" {{ old('product') == 28 ? 'selected' : '' }}>28mm</option>
                                                            <option value="32" {{ old('product') == 32 ? 'selected' : '' }}>32mm</option>
                                                            <option value="36" {{ old('product') == 36 ? 'selected' : '' }}>36mm</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('dia.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="dia[]" class="form-control dia" value="{{ old('dia.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('dia_square.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control dia_square" name="dia_square[]" value="{{ old('dia_square.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('value_of_bar.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control value_of_bar" name="value_of_bar[]" value="{{ old('value_of_bar.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('kg_by_rft.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control kg_by_rft" name="kg_by_rft[]" value="{{ old('kg_by_rft.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('kg_by_ton.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control kg_by_ton" name="kg_by_ton[]" value="{{ old('kg_by_ton.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('length_type.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 length_type" style="width: 100%;" name="length_type[]" data-placeholder="Select Length Type" required>
                                                        <option value="1" {{ old('length_type') == 1 ? 'selected' : '' }}>Horizontal</option>
                                                        <option value="2" {{ old('length_type') == 2 ? 'selected' : '' }}>Vertical</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control length" name="length[]" step="any" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('spacing.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control spacing" name="spacing[]" step="any" value="{{ old('spacing.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('type_length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control type_length" name="type_length[]" step="any" value="{{ old('type_length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('layer.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control layer" name="layer[]" value="{{ old('layer.'.$loop->index) }}" step="any">
                                                </div>
                                            </td>

                                            <td class="total-kg">0.00</td>
                                            <td class="total-ton">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product" name="product[]" required>
                                                    <option value="6">6mm</option>
                                                    <option value="8">8mm</option>
                                                    <option value="10">10mm</option>
                                                    <option value="12">12mm</option>
                                                    <option value="16">16mm</option>
                                                    <option value="18">18mm</option>
                                                    <option value="20">20mm</option>
                                                    <option value="22">22mm</option>
                                                    <option value="25">25mm</option>
                                                    <option value="28">28mm</option>
                                                    <option value="32">32mm</option>
                                                    <option value="36">36mm</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="dia[]" class="form-control dia" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control dia_square" name="dia_square[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control value_of_bar" name="value_of_bar[]" value="532.17">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control kg_by_rft" name="kg_by_rft[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control kg_by_ton" name="kg_by_ton[]" value="1000">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 length_type" style="width: 100%;" name="length_type[]" data-placeholder="Select Length Type" required>
                                                    <option value="1">Horizontal</option>
                                                    <option value="2">Vertcal</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control length" name="length[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control spacing" name="spacing[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control type_length" name="type_length[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control layer" name="layer[]" value="1" step="any">
                                            </div>
                                        </td>
                                        <td class="total-kg">0.00</td>
                                        <td class="total-ton">0.00</td>
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
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="table-responsive">
                            <strong><h3>Calculation of Extra Bar</h3></strong>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Bar Type</th>
                                    <th>Dia</th>
                                    <th>Dia Square(D^2)</th>
                                    <th>Value of Bar</th>
                                    <th>Kg/Rft</th>
                                    <th>Kg/Ton</th>
                                    <th>Length Type</th>
                                    <th>Length</th>
                                    <th>Spacing</th>
                                    <th>Type Length</th>
                                    <th>Layer</th>
                                    <th>Kg</th>
                                    <th>Ton</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container-extra">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" name="product[]" data-placeholder="Select Product" required>
                                                            <option value="6" {{ old('product') == 6 ? 'selected' : '' }}>6mm</option>
                                                            <option value="8" {{ old('product') == 8 ? 'selected' : '' }}>8mm</option>
                                                            <option value="10" {{ old('product') == 10 ? 'selected' : '' }}>10mm</option>
                                                            <option value="12" {{ old('product') == 12 ? 'selected' : '' }}>12mm</option>
                                                            <option value="16" {{ old('product') == 16 ? 'selected' : '' }}>16mm</option>
                                                            <option value="18" {{ old('product') == 18 ? 'selected' : '' }}>18mm</option>
                                                            <option value="20" {{ old('product') == 20 ? 'selected' : '' }}>20mm</option>
                                                            <option value="22" {{ old('product') == 22 ? 'selected' : '' }}>22mm</option>
                                                            <option value="25" {{ old('product') == 25 ? 'selected' : '' }}>25mm</option>
                                                            <option value="28" {{ old('product') == 28 ? 'selected' : '' }}>28mm</option>
                                                            <option value="32" {{ old('product') == 32 ? 'selected' : '' }}>32mm</option>
                                                            <option value="36" {{ old('product') == 36 ? 'selected' : '' }}>36mm</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('dia.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="dia[]" class="form-control dia" value="{{ old('dia.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('dia_square.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control dia_square" name="dia_square[]" value="{{ old('dia_square.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('value_of_bar.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control value_of_bar" name="value_of_bar[]" value="{{ old('value_of_bar.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('kg_by_rft.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control kg_by_rft" name="kg_by_rft[]" value="{{ old('kg_by_rft.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('kg_by_ton.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control kg_by_ton" name="kg_by_ton[]" value="{{ old('kg_by_ton.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('length_type.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 length_type" style="width: 100%;" name="length_type[]" data-placeholder="Select Length Type" required>
                                                        <option value="1" {{ old('length_type') == 1 ? 'selected' : '' }}>Horizontal</option>
                                                        <option value="2" {{ old('length_type') == 2 ? 'selected' : '' }}>Vertical</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control length" name="length[]" step="any" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('spacing.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control spacing" name="spacing[]" step="any" value="{{ old('spacing.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('type_length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control type_length" name="type_length[]" step="any" value="{{ old('type_length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('layer.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control layer" name="layer[]" value="{{ old('layer.'.$loop->index) }}" step="any">
                                                </div>
                                            </td>

                                            <td class="total-kg">0.00</td>
                                            <td class="total-ton">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product" name="product[]" required>
                                                    <option value="6">6mm</option>
                                                    <option value="8">8mm</option>
                                                    <option value="10">10mm</option>
                                                    <option value="12">12mm</option>
                                                    <option value="16">16mm</option>
                                                    <option value="18">18mm</option>
                                                    <option value="20">20mm</option>
                                                    <option value="22">22mm</option>
                                                    <option value="25">25mm</option>
                                                    <option value="28">28mm</option>
                                                    <option value="32">32mm</option>
                                                    <option value="36">36mm</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="dia[]" class="form-control dia" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control dia_square" name="dia_square[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control value_of_bar" name="value_of_bar[]" value="532.17">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control kg_by_rft" name="kg_by_rft[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control kg_by_ton" name="kg_by_ton[]" value="1000">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 length_type" style="width: 100%;" name="length_type[]" data-placeholder="Select Length Type" required>
                                                    <option value="1">Horizontal</option>
                                                    <option value="2">Vertcal</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control length" name="length[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control spacing" name="spacing[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control type_length" name="type_length[]" step="any">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control layer" name="layer[]" value="1" step="any">
                                            </div>
                                        </td>
                                        <td class="total-kg">0.00</td>
                                        <td class="total-ton">0.00</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product-extra">Add More</a>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        {{-- <div class="table-responsive">
                            <strong><h3>Calculation of Extra Bar</h3></strong>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="15%">Bar Type</th>
                                    <th width="10%">Dia</th>
                                    <th width="10%">Dia Square(D^2)</th>
                                    <th width="10%">Value of Bar</th>
                                    <th width="10%">Kg/Rft</th>
                                    <th width="10%">Kg/Ton</th>
                                    <th width="10%">Bar Nos.</th>
                                    <th width="10%">Length</th>
                                    <th>Kg</th>
                                    <th>Ton</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="extra-container">
                                @if (old('extra_product') != null && sizeof(old('extra_product')) > 0)
                                    @foreach(old('extra_product') as $item)
                                        <tr class="extra-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('extra_product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 extra_product" name="extra_product[]" data-placeholder="Select Product" required>
                                                        <option value="6" {{ old('extra_product') == 6 ? 'selected' : '' }}>6mm</option>
                                                        <option value="8" {{ old('extra_product') == 8 ? 'selected' : '' }}>8mm</option>
                                                        <option value="10" {{ old('extra_product') == 10 ? 'selected' : '' }}>10mm</option>
                                                        <option value="12" {{ old('extra_product') == 12 ? 'selected' : '' }}>12mm</option>
                                                        <option value="16" {{ old('extra_product') == 16 ? 'selected' : '' }}>16mm</option>
                                                        <option value="18" {{ old('extra_product') == 18 ? 'selected' : '' }}>18mm</option>
                                                        <option value="20" {{ old('extra_product') == 20 ? 'selected' : '' }}>20mm</option>
                                                        <option value="22" {{ old('extra_product') == 22 ? 'selected' : '' }}>22mm</option>
                                                        <option value="25" {{ old('extra_product') == 25 ? 'selected' : '' }}>25mm</option>
                                                        <option value="28" {{ old('extra_product') == 28 ? 'selected' : '' }}>28mm</option>
                                                        <option value="32" {{ old('extra_product') == 32 ? 'selected' : '' }}>32mm</option>
                                                        <option value="36" {{ old('extra_product') == 36 ? 'selected' : '' }}>36mm</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('extra_dia.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="extra_dia[]" class="form-control extra_dia" value="{{ old('extra_dia.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('extra_dia_square.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control extra_dia_square" name="extra_dia_square[]" value="{{ old('extra_dia_square.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('extra_value_of_bar.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control extra_value_of_bar" name="extra_value_of_bar[]" value="{{ old('extra_value_of_bar.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('extra_kg_by_rft.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control extra_kg_by_rft" name="extra_kg_by_rft[]" value="{{ old('extra_kg_by_rft.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('extra_kg_by_ton.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control extra_kg_by_ton" name="extra_kg_by_ton[]" value="{{ old('extra_kg_by_ton.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('extra_number_of_bar.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control extra_number_of_bar" name="extra_number_of_bar[]" value="{{ old('extra_number_of_bar.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('extra_length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number"  step="any" class="form-control extra_length" name="extra_length[]" value="{{ old('extra_length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="extra-total-kg">0.00</td>
                                            <td class="extra-total-ton">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm extra-btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="extra-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control extra_product" style="width: 100%;" name="extra_product[]" required>
                                                    <option value="6">6mm</option>
                                                    <option value="8">8mm</option>
                                                    <option value="10">10mm</option>
                                                    <option value="12">12mm</option>
                                                    <option value="16">16mm</option>
                                                    <option value="18">18mm</option>
                                                    <option value="20">20mm</option>
                                                    <option value="22">22mm</option>
                                                    <option value="25">25mm</option>
                                                    <option value="28">28mm</option>
                                                    <option value="32">32mm</option>
                                                    <option value="36">36mm</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="extra_dia[]" class="form-control extra_dia" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control extra_dia_square" name="extra_dia_square[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control extra_value_of_bar" name="extra_value_of_bar[]" value="532.17">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control extra_kg_by_rft" name="extra_kg_by_rft[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control extra_kg_by_ton" name="extra_kg_by_ton[]" value="1000">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control extra_number_of_bar" name="extra_number_of_bar[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number"  step="any" class="form-control extra_length" name="extra_length[]" value="0">
                                            </div>
                                        </td>
                                        <td class="extra-total-kg">0.00</td>
                                        <td class="extra-total-ton">0.00</td>

                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm extra-btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-extra-product">Add More</a>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div> --}}
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
                    <select class="form-control product" name="product[]" required>
                        <option value="6">6mm</option>
                        <option value="8">8mm</option>
                        <option value="10">10mm</option>
                        <option value="12">12mm</option>
                        <option value="16">16mm</option>
                        <option value="18">18mm</option>
                        <option value="20">20mm</option>
                        <option value="22">22mm</option>
                        <option value="25">25mm</option>
                        <option value="28">28mm</option>
                        <option value="32">32mm</option>
                        <option value="36">36mm</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="dia[]" class="form-control dia" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control dia_square" name="dia_square[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control value_of_bar" name="value_of_bar[]" value="532.17">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control kg_by_rft" name="kg_by_rft[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control kg_by_ton" name="kg_by_ton[]" value="1000">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select class="form-control select2 length_type" style="width: 100%;" name="length_type[]" data-placeholder="Select Length Type" required>
                        <option value="1">Horizontal</option>
                        <option value="2">Vertical</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control length" name="length[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control spacing" name="spacing[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control type_length" name="type_length[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control layer" name="layer[]" value="1" step="any">
                </div>
            </td>
            <td class="total-kg">0.00</td>
            <td class="total-ton">0.00</td>

            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
    <template id="template-product-extra">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product" name="product[]" required>
                        <option value="6">6mm</option>
                        <option value="8">8mm</option>
                        <option value="10">10mm</option>
                        <option value="12">12mm</option>
                        <option value="16">16mm</option>
                        <option value="18">18mm</option>
                        <option value="20">20mm</option>
                        <option value="22">22mm</option>
                        <option value="25">25mm</option>
                        <option value="28">28mm</option>
                        <option value="32">32mm</option>
                        <option value="36">36mm</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="dia[]" class="form-control dia" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control dia_square" name="dia_square[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control value_of_bar" name="value_of_bar[]" value="532.17">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control kg_by_rft" name="kg_by_rft[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control kg_by_ton" name="kg_by_ton[]" value="1000">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select class="form-control select2 length_type" style="width: 100%;" name="length_type[]" data-placeholder="Select Length Type" required>
                        <option value="1">Horizontal</option>
                        <option value="2">Vertical</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control length" name="length[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control spacing" name="spacing[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control type_length" name="type_length[]" step="any">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control layer" name="layer[]" value="1" step="any">
                </div>
            </td>
            <td class="total-kg">0.00</td>
            <td class="total-ton">0.00</td>

            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
    {{-- <template id="template-extra-product">
        <tr class="extra-item">
            <td>
                <div class="form-group">
                    <select class="form-control extra_product" style="width: 100%;" name="extra_product[]" required>
                        <option value="6">6mm</option>
                        <option value="8">8mm</option>
                        <option value="10">10mm</option>
                        <option value="12">12mm</option>
                        <option value="16">16mm</option>
                        <option value="18">18mm</option>
                        <option value="20">20mm</option>
                        <option value="22">22mm</option>
                        <option value="25">25mm</option>
                        <option value="28">28mm</option>
                        <option value="32">32mm</option>
                        <option value="36">36mm</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" name="extra_dia[]" class="form-control extra_dia" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control extra_dia_square" name="extra_dia_square[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control extra_value_of_bar" name="extra_value_of_bar[]" value="532.17">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control extra_kg_by_rft" name="extra_kg_by_rft[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control extra_kg_by_ton" name="extra_kg_by_ton[]" value="1000">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" class="form-control extra_number_of_bar" name="extra_number_of_bar[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number"  step="any" class="form-control extra_length" name="extra_length[]" value="0">
                </div>
            </td>
            <td class="extra-total-kg">0.00</td>
            <td class="extra-total-ton">0.00</td>

            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm extra-btn-remove">X</a>
            </td>
        </tr>
    </template> --}}
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

            $('body').on('change','#course_aggregate_type', function () {
                var courseType = $(this).val();

                if (courseType == 1) {
                    $('#common_picked_costing').hide();
                    $('#common_aggregate_costing').show();
                }else if(courseType == 2){
                    $('#common_picked_costing').show();
                    $('#common_aggregate_costing').hide();
                }else {

                }
            })
            $('#course_aggregate_type').trigger("change");

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                $('#product-container').append(item);

                initProduct();

                if ($('.product-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });
            $('#btn-add-product-extra').click(function () {
                var html = $('#template-product-extra').html();
                var item = $(html);

                $('#product-container-extra').append(item);

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

            $('#btn-add-extra-product').click(function () {
                var html = $('#template-extra-product').html();
                var item = $(html);

                $('#extra-container').append(item);

                initProduct();

                if ($('.extra-item').length >= 1 ) {
                    $('.extra-btn-remove').show();
                }
            });

            $('body').on('click', '.extra-btn-remove', function () {
                $(this).closest('.extra-item').remove();
                calculate();

                if ($('.extra-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('change','.product', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                var barValue = itemproductID.closest('tr').find('.value_of_bar').val();
                var kgTon = itemproductID.closest('tr').find('.kg_by_ton').val();


                if (productID != '') {
                    itemproductID.closest('tr').find('.dia').val(productID);
                    itemproductID.closest('tr').find('.dia_square').val(productID * productID);
                    itemproductID.closest('tr').find('.kg_by_rft').val(((productID * productID) / barValue).toFixed(2));
                }
            })
            $('.product').trigger("change");

            $('body').on('change','.extra_product', function () {
                var extraProductID = $(this).val();
                var itemProductID = $(this);
                var extraBarValue = itemProductID.closest('tr').find('.extra_value_of_bar').val();


                if (extraProductID != '') {
                    itemProductID.closest('tr').find('.extra_dia').val(extraProductID);
                    itemProductID.closest('tr').find('.extra_dia_square').val(extraProductID * extraProductID);
                    itemProductID.closest('tr').find('.extra_kg_by_rft').val(((extraProductID * extraProductID) / extraBarValue).toFixed(2));
                }
            })
            $('.extra_product').trigger("change");

            $('body').on('keyup','.length,.spacing,.type_length,.layer, #segment_length, #segment_width, #segment_thickness', function () {
                calculate();
            });
            $('body').on('keyup','.extra_length,.extra_number_of_bar', function () {
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
            var segment_length = $('#segment_length').val();
            var segment_width = $('#segment_width').val();
            var segment_thickness = $('#segment_thickness').val();

            var total_dry = segment_length * segment_width * segment_thickness;
            $('#total_volume').val(total_dry);

            var total_dry_volume = total_dry * 1.5;

            $('#total_dry_volume').val(total_dry_volume);

            console.log(total_dry_volume);


            $('.product-item').each(function(i, obj) {
                var kg_by_ton = $('.kg_by_ton:eq('+i+')').val();
                var kg_by_rft = $('.kg_by_rft:eq('+i+')').val();
                var length = $('.length:eq('+i+')').val();
                var spacing = $('.spacing:eq('+i+')').val();
                var type_length = $('.type_length:eq('+i+')').val();
                var layer = $('.layer:eq('+i+')').val();

                if (kg_by_ton == '' || kg_by_ton < 0 || !$.isNumeric(kg_by_ton))
                    kg_by_ton = 0;

                if (kg_by_rft == '' || kg_by_rft < 0 || !$.isNumeric(kg_by_rft))
                    kg_by_rft = 0;

                if (length == '' || length < 0 || !$.isNumeric(length))
                    length = 0;

                if (spacing == '' || spacing < 0 || !$.isNumeric(spacing))
                    spacing = 0;

                if (type_length == '' || type_length < 0 || !$.isNumeric(type_length))
                    type_length = 0;

                if (layer == '' || layer < 0 || !$.isNumeric(layer))
                    layer = 0;

                var rft = ((length / spacing) * type_length * layer);

                $('.total-kg:eq('+i+')').html(parseFloat((rft * kg_by_rft)).toFixed(2));
                $('.total-ton:eq('+i+')').html(parseFloat(((rft * kg_by_rft)/kg_by_ton)).toFixed(3));
                //total += rft_by_ton;
            });

            $('.extra-item').each(function(i, obj) {
                var extra_number_of_bar = $('.extra_number_of_bar:eq('+i+')').val();
                var extra_kg_by_ton = $('.extra_kg_by_ton:eq('+i+')').val();
                var extra_kg_by_rft = $('.extra_kg_by_rft:eq('+i+')').val();
                var extra_length = $('.extra_length:eq('+i+')').val();

                if (extra_number_of_bar == '' || extra_number_of_bar < 0 || !$.isNumeric(extra_number_of_bar))
                    extra_number_of_bar = 0;

                if (extra_kg_by_ton == '' || extra_kg_by_ton < 0 || !$.isNumeric(extra_kg_by_ton))
                    extra_kg_by_ton = 0;

                if (extra_kg_by_rft == '' || extra_kg_by_rft < 0 || !$.isNumeric(extra_kg_by_rft))
                    extra_kg_by_rft = 0;

                if (extra_length == '' || extra_length < 0 || !$.isNumeric(extra_length))
                    extra_length = 0;

                $('.extra-total-kg:eq('+i+')').html(parseFloat((extra_length * extra_kg_by_rft) * extra_number_of_bar).toFixed(2));
                $('.extra-total-ton:eq('+i+')').html(parseFloat((((extra_length * extra_kg_by_rft) * extra_number_of_bar)/extra_kg_by_ton)).toFixed(3));
            });
            //$('#total-ton').html(total);
        }

        function initProduct() {
            $('.product').select2();
            {{--$('.product').select2({--}}
            {{--    ajax: {--}}
            {{--        url: "{{ route('estimate_product.json') }}",--}}
            {{--        type: "get",--}}
            {{--        dataType: 'json',--}}
            {{--        delay: 250,--}}
            {{--        data: function (params) {--}}
            {{--            return {--}}
            {{--                searchTerm: params.term // search term--}}
            {{--            };--}}
            {{--        },--}}
            {{--        processResults: function (response) {--}}
            {{--            return {--}}
            {{--                results: response--}}
            {{--            };--}}
            {{--        },--}}
            {{--        cache: true--}}
            {{--    }--}}
            {{--});--}}

            {{--$('.product').on('select2:select', function (e) {--}}
            {{--    var data = e.params.data;--}}

            {{--    var index = $(".product").index(this);--}}
            {{--    $('.product-name:eq('+index+')').val(data.text);--}}
            {{--    $('.dia-price:eq('+index+')').val(data.dia_price);--}}
            {{--});--}}
        }
    </script>
@endsection
