@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Grade Beam Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Grade Beam Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('grade_beam_type_configure.add') }}">
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
                                <div class="form-group {{ $errors->has('beam_type') ? 'has-error' :'' }}">
                                    <label>Grade Beam Type</label>

                                    <select class="form-control select2" style="width: 100%;" name="beam_type" data-placeholder="Select Grade Beam Type">
                                        <option value="">Select Grade Beam Type</option>
                                        @foreach($grateBeamTypes as $grateBeamType)
                                            <option value="{{ $grateBeamType->id }}" {{ old('beam_type') == $grateBeamType->id ? 'selected' : '' }}>{{ $grateBeamType->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('beam_type')
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
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="ring_quantity" step="any"
                                       name="ring_quantity" value="{{ old('ring_quantity') }}" placeholder="Ring Quantity" readonly>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('tie_bar') ? 'has-error' :'' }}">
                                    <label>Stirrup(Rft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="tie_bar" step="any"
                                               name="tie_bar" value="{{ old('tie_bar') }}" placeholder="Stirrup" readonly>
                                    </div>
                                    <!-- /.input group -->

                                    @error('tie_bar')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                           
                            {{-- <div class="col-md-2">
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
                            </div> --}}
                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('tie_interval') ? 'has-error' :'' }}">
                                    <label>Stirrup Interval(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="tie_interval" step="any"
                                               name="tie_interval" value="{{ old('tie_interval') }}" placeholder="Grade Beam Interval">
                                    </div>
                                    <!-- /.input group -->

                                    @error('tie_interval')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('beam_quantity') ? 'has-error' :'' }}">
                                    <label>Grade Beam Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="beam_quantity" step="any"
                                               name="beam_quantity" value="{{ old('beam_quantity') }}" placeholder="Grade Beam Quantity">
                                    </div>
                                    <!-- /.input group -->

                                    @error('beam_quantity')
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
                                    <label>Ag. Ratio</label>

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
                                <div class="form-group {{ $errors->has('beam_length') ? 'has-error' :'' }}">
                                    <label style="font-size: 14px;">Grade Beam Length(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="beam_length" step="any"
                                               name="beam_length" value="{{ old('beam_length') }}" placeholder="Grade Beam Length">
                                    </div>
                                    <!-- /.input group -->

                                    @error('beam_length')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('grade_beam_length') ? 'has-error' :'' }}">
                                    <label style="font-size: 14px;">Grade Beam Height(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="grade_beam_length" step="any"
                                               name="grade_beam_length" value="{{ old('grade_beam_length') }}" placeholder="Grade Beam Height">
                                    </div>
                                    <!-- /.input group -->

                                    @error('beam_length')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('grade_beam_width') ? 'has-error' :'' }}">
                                    <label style="font-size: 14px;">Grade Beam Width(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="grade_beam_width" step="any"
                                               name="grade_beam_width" value="{{ old('grade_beam_width') }}" placeholder="Grade Beam Width">
                                    </div>
                                    <!-- /.input group -->

                                    @error('tie_width')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('cover') ? 'has-error' :'' }}">
                                    <label>Clear Cover(Ft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="cover" step="any"
                                               name="cover" value="{{ old('cover') }}" placeholder="Clear Cover">
                                    </div>
                                    <!-- /.input group -->

                                    @error('cover')
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

                            <div class="col-md-5">
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
                            <div class="col-md-5">
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
                                <div class="form-group {{ $errors->has('beam_bar_costing') ? 'has-error' :'' }}">
                                    <label>Bar Cost(Per Kg)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any"
                                               name="beam_bar_costing" value="{{ $pileCost->pile_bar_per_cost??0 }}" placeholder="Enter Per Kg Bar Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('beam_bar_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('beam_cement_costing') ? 'has-error' :'' }}">
                                    <label>Cement Cost(Per Bag)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $pileCost->pile_cement_per_cost??0 }}"
                                               name="beam_cement_costing"  placeholder="Enter Per Bag Cement Costing"
                                        >
                                    </div>
                                    <!-- /.input group -->

                                    @error('beam_cement_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('beam_sands_costing') ? 'has-error' :'' }}">
                                    <label>Sands Cost(Per Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $pileCost->pile_sands_per_cost??0 }}"
                                               name="beam_sands_costing"  placeholder="Enter Per Cft Sands Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('beam_sands_costing')
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
                            <div id="beam_aggregate_costing">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('beam_aggregate_costing') ? 'has-error' :'' }}">
                                        <label>Aggregate Cost(Cft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ $pileCost->pile_aggregate_per_cost??0 }}"
                                                   name="beam_aggregate_costing"  placeholder="Enter Per Cft Aggregates Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('beam_aggregate_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="beam_picked_costing">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('beam_picked_costing') ? 'has-error' :'' }}">
                                        <label>Picked Cost(Per Cft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ $pileCost->pile_picked_per_cost??0 }}"
                                                   name="beam_picked_costing"  placeholder="Enter Per Pcs Picked Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('beam_picked_costing')
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
    <template id="template-extra-product">
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
                    $('#beam_picked_costing').hide();
                    $('#beam_aggregate_costing').show();
                }else if(courseType == 2){
                    $('#beam_picked_costing').show();
                    $('#beam_aggregate_costing').hide();
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

            $('#btn-add-tie').click(function () {
                var html = $('#template-tie').html();
                var item = $(html);

                $('#tie-container').append(item);

                initProduct();

                if ($('.tie-item').length >= 1 ) {
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

            $('body').on('keyup','#beam_length,#tie_length,#dry_volume,.grade_beam_length,.grade_beam_width' +
                '.number_of_bar,#cover,.tie_length,.tie_width,.lapping_length,.lapping_nos, #tie_interval', function () {
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
            var total_volume = 0;
            var beam_length = $('#beam_length').val();
            var dry_volume = $('#dry_volume').val();
            var tie_interval = $('#tie_interval').val();
            var cover = $('#cover').val();
            var grade_beam_length = $('#grade_beam_length').val();
            var grade_beam_width = $('#grade_beam_width').val();
            

            if (beam_length == '' || beam_length < 0 || !$.isNumeric(beam_length))
                beam_length = 0;

            if (grade_beam_length == '' || grade_beam_length < 0 || !$.isNumeric(grade_beam_length))
                grade_beam_length = 0;

            if (dry_volume == '' || dry_volume < 0 || !$.isNumeric(dry_volume))
                dry_volume = 0;

            if (tie_interval == '' || tie_interval < 0 || !$.isNumeric(tie_interval))
                tie_interval = 0;

            if (cover == '' || cover < 0 || !$.isNumeric(cover))
                cover = 0;


            if (grade_beam_width == '' || grade_beam_width < 0 || !$.isNumeric(grade_beam_width))
                grade_beam_width = 0;

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

                if (beam_length == '' || beam_length < 0 || !$.isNumeric(beam_length))
                    beam_length = 0;

                if (lapping_length == '' || lapping_length < 0 || !$.isNumeric(lapping_length))
                    lapping_length = 0;

                if (lapping_nos == '' || lapping_nos < 0 || !$.isNumeric(lapping_nos))
                    lapping_nos = 0;

                var lappingKg = ((lapping_length * lapping_nos) * kg_by_rft)

                $('.total-kg:eq('+i+')').html(parseFloat(((beam_length * kg_by_rft) * number_of_bar) + lappingKg).toFixed(2));
                $('.total-ton:eq('+i+')').html(parseFloat(((((beam_length * kg_by_rft) * number_of_bar) + lappingKg)/kg_by_ton)).toFixed(3));
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

            $('.tie-item').each(function(i, obj) {
                var tie_kg_by_ton = $('.tie_kg_by_ton:eq('+i+')').val();
                var tie_kg_by_rft = $('.tie_kg_by_rft:eq('+i+')').val();
                // var cover = $('.tie_clear_cover:eq('+i+')').val();
                var tie_length = $('.tie_length:eq('+i+')').val();
                var tie_width = $('.tie_width:eq('+i+')').val();
                var beam_length = $('#beam_length').val();
                var tie_interval = $('#tie_interval').val();

                
                var tieQuantity = (beam_length / tie_interval) + 1;
                
                if (tie_kg_by_ton == '' || tie_kg_by_ton < 0 || !$.isNumeric(tie_kg_by_ton))
                    tie_kg_by_ton = 0;

                if (tie_kg_by_rft == '' || tie_kg_by_rft < 0 || !$.isNumeric(tie_kg_by_rft))
                    tie_kg_by_rft = 0;

              
                if (tie_length == '' || tie_length < 0 || !$.isNumeric(tie_length))
                    tie_length = 0;

                if (tie_width == '' || tie_width < 0 || !$.isNumeric(tie_width))
                    tie_width = 0;

                if (tieQuantity == '' || tieQuantity < 0 || !$.isNumeric(tieQuantity))
                    tieQuantity = 0;

                if (tie_interval == '' || tie_interval < 0 || !$.isNumeric(tie_interval))
                    tie_interval = 0;

                var length_tie_total = tie_length/12;
                console.log(length_tie_total);
                var width_tie_total = tie_width/12;
                console.log(width_tie_total);
                var pre_tie_bar = ((parseFloat(length_tie_total) + parseFloat(width_tie_total)) * 2) + 0.42;
                var item = (pre_tie_bar * tie_kg_by_rft)  * tieQuantity;

                console.log(item);

                $('.tie-total-kg:eq('+i+')').html(parseFloat((pre_tie_bar * tie_kg_by_rft)  * tieQuantity).toFixed(2));
                $('.tie-total-ton:eq('+i+')').html(parseFloat((((pre_tie_bar * tie_kg_by_rft)  * tieQuantity)/tie_kg_by_ton)).toFixed(3));
                //total += rft_by_ton;
            });

            total_volume = beam_length * grade_beam_width * grade_beam_length ;

            var width_tie = grade_beam_width - (2 * cover);
            var length_tie = grade_beam_length - (2 * cover);

            var interval = ((beam_length / tie_interval) + 1);

            var pre_tie_bar = ((width_tie + length_tie) * 2) + 0.42;


            $('#total_volume').val(total_volume.toFixed(2));
            $('#total_dry_volume').val((total_volume * dry_volume).toFixed(2));
            $('#tie_bar').val((interval * pre_tie_bar).toFixed(2));
            $('#ring_quantity').val((interval).toFixed(2));

            //$('#total-ton').html(total);
        }

        function initProduct() {
            $('.product').select2();
            //$('.extra_product').select2();
        }
    </script>
@endsection
