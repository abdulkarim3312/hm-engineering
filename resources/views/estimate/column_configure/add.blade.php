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
        select.form-control.tie_product {
            width: 120px;
        }
        input.form-control.tie_dia{
            width: 120px;
        }
        input.form-control.tie_kg_by_rft{
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
    Column Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Column Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('column_configure.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Estimate Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimating Project</option>
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
                                <div class="form-group {{ $errors->has('column_type') ? 'has-error' :'' }}">
                                    <label>Column Type</label>

                                    <select class="form-control select2" style="width: 100%;" name="column_type" data-placeholder="Select Column Type">
                                        <option value="">Select Column Type</option>
                                        @foreach($columnTypes as $columnType)
                                            <option value="{{ $columnType->id }}" {{ old('column_type') == $columnType->id ? 'selected' : '' }}>{{ $columnType->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('column_type')
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
                                <div class="form-group {{ $errors->has('ring_quantity') ? 'has-error' :'' }}">
                                    <label>Tie Ring Qty</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="ring_quantity" step="any"
                                               name="ring_quantity" value="{{ old('ring_quantity') }}" placeholder="Ring Quantity" readonly>
                                    </div>
                                    <!-- /.input group -->

                                    @error('ring_quantity')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('tie_interval') ? 'has-error' :'' }}">
                                    <label>Tie Interval(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="tie_interval" step="any"
                                               name="tie_interval" value="{{ old('tie_interval') }}" placeholder="Tie Interval">
                                    </div>
                                    <!-- /.input group -->

                                    @error('tie_interval')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('column_quantity') ? 'has-error' :'' }}">
                                    <label>Column Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="column_quantity" step="any"
                                               name="column_quantity" value="{{ old('column_quantity') }}" placeholder="Column Quantity">
                                    </div>
                                    <!-- /.input group -->

                                    @error('column_quantity')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('first_ratio') ? 'has-error' :'' }}">
                                    <label>Cement Ratio</label>

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
                                    <label>Sand Ratio</label>

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
                                    <label>Stone/Bricks Ratio</label>

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
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('column_length') ? 'has-error' :'' }}">
                                    <label>Column Length(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="column_length" step="any"
                                               name="column_length" value="{{ old('column_length') }}" placeholder="Column Length">
                                    </div>
                                    <!-- /.input group -->

                                    @error('column_length')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('tie_length_volume') ? 'has-error' :'' }}">
                                    <label style="font-size: 15px;">Column Height(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="tie_length_volume" step="any"
                                               name="tie_length_volume" value="{{ old('tie_length_volume') }}" placeholder="Tie Length for Volume">
                                    </div>
                                    <!-- /.input group -->

                                    @error('tie_length_volume')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('tie_width_volume') ? 'has-error' :'' }}">
                                    <label>Column Width(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="tie_width_volume" step="any"
                                               name="tie_width_volume" value="{{ old('tie_width_volume') }}" placeholder="Tie Width For Volume">
                                    </div>
                                    <!-- /.input group -->

                                    @error('tie_width_volume')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('total_volume') ? 'has-error' :'' }}">
                                    <label>Total Volume(Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="total_volume" readonly step="any"
                                               name="total_volume" value="{{ old('total_volume') }}" placeholder="Total Volume">
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
                                <div class="form-group {{ $errors->has('total_dry_volume') ? 'has-error' :'' }}">
                                    <label>Total Dry Volume(Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="total_dry_volume" readonly step="any"
                                               name="total_dry_volume" value="{{ old('total_dry_volume') }}" placeholder="Total Dry Volume">
                                    </div>
                                    <!-- /.input group -->

                                    @error('total_dry_volume')
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
                                <div class="form-group {{ $errors->has('column_bar_costing') ? 'has-error' :'' }}">
                                    <label>Bar Cost(Per Kg)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any"
                                               name="column_bar_costing" value="{{ $beamCost->beam_bar_per_cost??0 }}" placeholder="Enter Per Kg Bar Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('column_bar_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('column_cement_costing') ? 'has-error' :'' }}">
                                    <label>Cement Cost(Per Bag)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $beamCost->beam_cement_per_cost??0 }}"
                                               name="column_cement_costing"  placeholder="Enter Per Bag Cement Costing"
                                        >
                                    </div>
                                    <!-- /.input group -->

                                    @error('column_cement_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('column_sands_costing') ? 'has-error' :'' }}">
                                    <label>Sands Cost(Per Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $beamCost->beam_sands_per_cost??0 }}"
                                               name="column_sands_costing"  placeholder="Enter Per Cft Sands Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('column_sands_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('s_sands_costing') ? 'has-error' :'' }}">
                                    <label>S.Sands Cost(Per Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $pileCost->s_sands_costing??0 }}"
                                               name="s_sands_costing"  placeholder="Enter Per Cft S.Sands Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('s_sands_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div id="column_aggregate_costing">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('column_aggregate_costing') ? 'has-error' :'' }}">
                                        <label>Aggregate Cost(Cft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ $beamCost->beam_aggregate_per_cost??0 }}"
                                                   name="column_aggregate_costing"  placeholder="Enter Per Cft Aggregates Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('column_aggregate_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="column_picked_costing">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('column_picked_costing') ? 'has-error' :'' }}">
                                        <label>Picked Cost(Per Cft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ $beamCost->beam_picked_per_cost??0 }}"
                                                   name="column_picked_costing"  placeholder="Enter Per Pcs Picked Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('column_picked_costing')
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
                                    <th width="15%">Bar Type</th>
                                    <th width="10%">Dia</th>
                                    <th width="10%">Dia Square(D^2)</th>
                                    <th width="10%">Value of Bar</th>
                                    <th width="10%">Kg/Rft</th>
                                    <th width="10%">Kg/Ton</th>
                                    <th width="10%">Bar Nos.</th>
                                    <th width="10%">Lapping Length</th>
                                    <th width="10%">Lapping Nos.</th>
                                    <th width="10%">Kg</th>
                                    <th width="10%">Ton</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" style="width: 100%;" name="product[]" data-placeholder="Select Product" required>
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
                                                <div class="form-group {{ $errors->has('number_of_bar.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control number_of_bar" name="number_of_bar[]" value="{{ old('number_of_bar.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('lapping_length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control lapping_length" name="lapping_length[]" value="{{ old('lapping_length.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('lapping_nos.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control lapping_nos" name="lapping_nos[]" value="{{ old('lapping_nos.'.$loop->index) }}">
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
                                                <select class="form-control product" style="width: 100%;" name="product[]" required>
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
                                                <input type="number" class="form-control number_of_bar" name="number_of_bar[]">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control lapping_length" step="any" name="lapping_length[]">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control lapping_nos" step="any" name="lapping_nos[]">
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
                            <strong><h3>Calculation of Tie Bar</h3></strong>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Bar Type</th>
                                    <th>Dia</th>
                                    <th>Dia Square(D^2)</th>
                                    <th>Value of Bar</th>
                                    <th>Kg/Rft</th>
                                    <th>Kg/Ton</th>
                                    <th>Tie Length(Inch)</th>
                                    <th>Tie Width(Inch)</th>
                                    <th>Kg</th>
                                    <th>Ton</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="tie-container">
                                @if (old('tie_product') != null && sizeof(old('tie_product')) > 0)
                                    @foreach(old('tie_product') as $item)
                                        <tr class="tie-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('tie_product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 tie_product" name="tie_product[]" data-placeholder="Select Product" required>
                                                        <option value="6" {{ old('tie_product') == 6 ? 'selected' : '' }}>6mm</option>
                                                        <option value="8" {{ old('tie_product') == 8 ? 'selected' : '' }}>8mm</option>
                                                        <option value="10" {{ old('tie_product') == 10 ? 'selected' : '' }}>10mm</option>
                                                        <option value="12" {{ old('tie_product') == 12 ? 'selected' : '' }}>12mm</option>
                                                        <option value="16" {{ old('tie_product') == 16 ? 'selected' : '' }}>16mm</option>
                                                        <option value="18" {{ old('tie_product') == 18 ? 'selected' : '' }}>18mm</option>
                                                        <option value="20" {{ old('tie_product') == 20 ? 'selected' : '' }}>20mm</option>
                                                        <option value="22" {{ old('tie_product') == 22 ? 'selected' : '' }}>22mm</option>
                                                        <option value="25" {{ old('tie_product') == 25 ? 'selected' : '' }}>25mm</option>
                                                        <option value="28" {{ old('tie_product') == 28 ? 'selected' : '' }}>28mm</option>
                                                        <option value="32" {{ old('tie_product') == 32 ? 'selected' : '' }}>32mm</option>
                                                        <option value="36" {{ old('tie_product') == 36 ? 'selected' : '' }}>36mm</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('tie_dia.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="tie_dia[]" class="form-control tie_dia" value="{{ old('tie_dia.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('tie_dia_square.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control tie_dia_square" name="tie_dia_square[]" value="{{ old('tie_dia_square.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('tie_value_of_bar.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control tie_value_of_bar" name="tie_value_of_bar[]" value="{{ old('tie_value_of_bar.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('tie_kg_by_rft.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control tie_kg_by_rft" name="tie_kg_by_rft[]" value="{{ old('tie_kg_by_rft.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('tie_kg_by_ton.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" readonly class="form-control tie_kg_by_ton" name="tie_kg_by_ton[]" value="{{ old('tie_kg_by_ton.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('tie_length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number"  step="any" class="form-control tie_length" name="tie_length[]" value="{{ old('tie_length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('tie_width.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control tie_width" name="tie_width[]" value="{{ old('tie_width.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="tie-total-kg">0.00</td>
                                            <td class="tie-total-ton">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm tie-btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="tie-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control tie_product" style="width: 100%;" name="tie_product[]" required>
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
                                                <input type="text" name="tie_dia[]" class="form-control tie_dia" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control tie_dia_square" name="tie_dia_square[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control tie_value_of_bar" name="tie_value_of_bar[]" value="532.17">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control tie_kg_by_rft" name="tie_kg_by_rft[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control tie_kg_by_ton" name="tie_kg_by_ton[]" value="1000">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control tie_length" name="tie_length[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control tie_width" name="tie_width[]">
                                            </div>
                                        </td>
                                        <td class="tie-total-kg">0.00</td>
                                        <td class="tie-total-ton">0.00</td>

                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm tie-btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-tie">Add More</a>
                                    </td>
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
                    <select class="form-control product" style="width: 100%;" name="product[]" required>
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
                    <input type="number" class="form-control number_of_bar" name="number_of_bar[]">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control lapping_length" name="lapping_length[]">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control lapping_nos" name="lapping_nos[]">
                </div>
            </td>
            <td class="total-kg">0.00</td>
            <td class="total-ton">0.00</td>

            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>

    <template id="template-tie">
        <tr class="tie-item">
            <td>
                <div class="form-group">
                    <select class="form-control tie_product" style="width: 100%;" name="tie_product[]" required>
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
                    <input type="text" name="tie_dia[]" class="form-control tie_dia" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control tie_dia_square" name="tie_dia_square[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control tie_value_of_bar" name="tie_value_of_bar[]" value="532.17">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control tie_kg_by_rft" name="tie_kg_by_rft[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" readonly class="form-control tie_kg_by_ton"  name="tie_kg_by_ton[]" value="1000">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" class="form-control tie_length" step="any" name="tie_length[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control tie_width" step="any" name="tie_width[]">
                </div>
            </td>

            {{-- <td>
                <div class="form-group">
                    <input type="number" class="form-control tie_clear_cover" step="any" name="tie_clear_cover[]">
                </div>
            </td> --}}
            <td class="tie-total-kg">0.00</td>
            <td class="tie-total-ton">0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm tie-btn-remove">X</a>
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
            $('body').on('change','#course_aggregate_type', function () {
                var courseType = $(this).val();

                if (courseType == 1) {
                    $('#column_picked_costing').hide();
                    $('#column_aggregate_costing').show();
                }else if(courseType == 2){
                    $('#column_picked_costing').show();
                    $('#column_aggregate_costing').hide();
                }else {

                }
            })
            $('#course_aggregate_type').trigger("change");

            $('#btn-add-tie').click(function () {
                var html = $('#template-tie').html();
                var item = $(html);

                $('#tie-container').append(item);

                initProduct();

                if ($('.tie-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
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

            $('body').on('click', '.tie-btn-remove', function () {
                $(this).closest('.tie-item').remove();
                calculate();

                if ($('.tie-item').length <= 1 ) {
                    $('.tie-btn-remove').hide();
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

            $('body').on('change','.product', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                var barValue = itemproductID.closest('tr').find('.value_of_bar').val();


                if (productID != '') {
                    itemproductID.closest('tr').find('.dia').val(productID);
                    itemproductID.closest('tr').find('.dia_square').val(productID * productID);
                    itemproductID.closest('tr').find('.kg_by_rft').val(((productID * productID) / barValue).toFixed(2));
                }
            })
            $('.product').trigger("change");

            $('body').on('change','.tie_product', function () {
                var tieProductID = $(this).val();
                var itemproductID = $(this);
                var tieBarValue = itemproductID.closest('tr').find('.tie_value_of_bar').val();


                if (tieProductID != '') {
                    itemproductID.closest('tr').find('.tie_dia').val(tieProductID);
                    itemproductID.closest('tr').find('.tie_dia_square').val(tieProductID * tieProductID);
                    itemproductID.closest('tr').find('.tie_kg_by_rft').val(((tieProductID * tieProductID) / tieBarValue).toFixed(2));
                }
            })
            $('.tie_product').trigger("change");

            $('body').on('keyup','#column_length,#tie_length,#dry_volume,' +
                '.number_of_bar,.tie_length,.tie_width,#tie_interval,' +
                '.lapping_length,.lapping_nos,#tie_length_volume,#tie_width_volume', function () {
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
            var total_volume = 0;
            var column_length = $('#column_length').val();
            var dry_volume = $('#dry_volume').val();
            var tie_interval = $('#tie_interval').val();
            var tie_length_volume = $('#tie_length_volume').val();
            var tie_width_volume = $('#tie_width_volume').val();

            if (column_length == '' || column_length < 0 || !$.isNumeric(column_length))
                column_length = 0;

            if (dry_volume == '' || dry_volume < 0 || !$.isNumeric(dry_volume))
                dry_volume = 0;

            if (tie_interval == '' || tie_interval < 0 || !$.isNumeric(tie_interval))
                tie_interval = 0;

            if (tie_length_volume == '' || tie_length_volume < 0 || !$.isNumeric(tie_length_volume))
                tie_length_volume = 0;

            if (tie_width_volume == '' || tie_width_volume < 0 || !$.isNumeric(tie_width_volume))
                tie_width_volume = 0;


            $('.product-item').each(function(i, obj) {
                var number_of_bar = $('.number_of_bar:eq('+i+')').val();
                var kg_by_ton = $('.kg_by_ton:eq('+i+')').val();
                var kg_by_rft = $('.kg_by_rft:eq('+i+')').val();
                var lapping_length = $('.lapping_length:eq('+i+')').val();
                var lapping_nos = $('.lapping_nos:eq('+i+')').val();

                if (number_of_bar == '' || number_of_bar < 0 || !$.isNumeric(number_of_bar))
                    number_of_bar = 0;

                if (kg_by_ton == '' || kg_by_ton < 0 || !$.isNumeric(kg_by_ton))
                    kg_by_ton = 0;

                if (kg_by_rft == '' || kg_by_rft < 0 || !$.isNumeric(kg_by_rft))
                    kg_by_rft = 0;

                if (column_length == '' || column_length < 0 || !$.isNumeric(column_length))
                    column_length = 0;

                if (lapping_length == '' || lapping_length < 0 || !$.isNumeric(lapping_length))
                    lapping_length = 0;

                if (lapping_nos == '' || lapping_nos < 0 || !$.isNumeric(lapping_nos))
                    lapping_nos = 0;

               var lappingKg = ((lapping_length * lapping_nos) * kg_by_rft)

                $('.total-kg:eq('+i+')').html(parseFloat(((column_length * kg_by_rft) * number_of_bar) + lappingKg).toFixed(2));
                $('.total-ton:eq('+i+')').html(parseFloat(((((column_length * kg_by_rft) * number_of_bar) + lappingKg)/kg_by_ton)).toFixed(3));
                //total += rft_by_ton;
            });

            $('.tie-item').each(function(i, obj) {
                var tie_kg_by_ton = $('.tie_kg_by_ton:eq('+i+')').val();
                var tie_kg_by_rft = $('.tie_kg_by_rft:eq('+i+')').val();
                // var cover = $('.tie_clear_cover:eq('+i+')').val();
                var tie_length = $('.tie_length:eq('+i+')').val();
                var tie_width = $('.tie_width:eq('+i+')').val();
                var column_length = $('#column_length').val();
                var tie_interval = $('#tie_interval').val();
                
                var tieQuantity = (column_length / tie_interval) + 1;

                if (tie_kg_by_ton == '' || tie_kg_by_ton < 0 || !$.isNumeric(tie_kg_by_ton))
                    tie_kg_by_ton = 0;

                if (tie_kg_by_rft == '' || tie_kg_by_rft < 0 || !$.isNumeric(tie_kg_by_rft))
                    tie_kg_by_rft = 0;

                // if (cover == '' || cover < 0 || !$.isNumeric(cover))
                //     cover = 0;

                if (tie_length == '' || tie_length < 0 || !$.isNumeric(tie_length))
                    tie_length = 0;

                if (tie_width == '' || tie_width < 0 || !$.isNumeric(tie_width))
                    tie_width = 0;

                if (tieQuantity == '' || tieQuantity < 0 || !$.isNumeric(tieQuantity))
                    tieQuantity = 0;

                var length_tie_total = tie_length/12;
                var width_tie_total = tie_width/12;
                var pre_tie_bar = ((parseFloat(length_tie_total) + parseFloat(width_tie_total)) * 2) + 0.42;

                // console.log(pre_tie_bar);

                // console.log(length_tie_total);
                // var pre_tie_bar = ((length_tie_total + width_tie_total) * 2) + 0.42;
                // console.log(tie_width);

                $('.tie-total-kg:eq('+i+')').html(parseFloat((pre_tie_bar * tie_kg_by_rft)  * tieQuantity).toFixed(2));
                $('.tie-total-ton:eq('+i+')').html(parseFloat((((pre_tie_bar * tie_kg_by_rft)  * tieQuantity)/tie_kg_by_ton)).toFixed(3));
                //total += rft_by_ton;
            });

            var total_volume = column_length * tie_length_volume * tie_width_volume ;

           // var width_tie = tie_width - (2 * cover);
           // var length_tie = tie_length - (2 * cover);

            var interval = (column_length / tie_interval) + 1;

            //var pre_tie_bar = ((width_tie + length_tie) * 2) + 0.42;


            $('#total_volume').val(total_volume.toFixed(2));
            $('#total_dry_volume').val((total_volume * dry_volume).toFixed(2));
            $('#ring_quantity').val((interval).toFixed(2));

            //$('#total-ton').html(total);
        }

        function initProduct() {
            $('.product').select2();
            $('.tie_product').select2();
        }
    </script>
@endsection
