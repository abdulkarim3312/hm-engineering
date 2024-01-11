@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Labour Employee Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Labour Employee Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('labour.edit', ['labour' => $labour->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $labour->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('labour_employee_id') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Labour Employee ID *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Employee ID"
                                       name="labour_employee_id" value="{{ $labour->labour_employee_id }}" readonly>

                                @error('labour_employee_id')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('project_id') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="project" >
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ empty(old('project')) ? ($errors->has('project') ? '' : ($labour->project_id == $project->id ? 'selected' : '')) :
                                            (old('project') == $project->id ? 'selected' : '') }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>

                                @error('project')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('joining_date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Joining Date </label>

                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right date-picker" name="joining_date"
                                           value="{{ empty(old('joining_date')) ? ($errors->has('joining_date') ? '' : $labour->joining_date) : old('joining_date') }}" autocomplete="off">
                                </div>
                                <!-- /.input group -->

                                @error('joining_date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('designation') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Designation *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="designation" id="designation">
                                    @foreach ($labourDesignations as $labourDesignation)
                                        <option value="{{ $labourDesignation->id }}" {{ empty(old('designation')) ? ($errors->has('designation') ? '' : ($labour->designation_id == $labourDesignation->id ? 'selected' : '')) :
                                            (old('designation') == $labourDesignation->id ? 'selected' : '') }}>{{ $labourDesignation->name }}</option>
                                    @endforeach
                                </select>

                                @error('designation')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('gender') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Gender *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="gender" >
                                    <option value="">Select Gender</option>
                                    <option value="1" {{ empty(old('gender')) ? ($errors->has('gender') ? '' : ($labour->gender == '1' ? 'selected' : '')) :
                                            (old('gender') == '1' ? 'selected' : '') }}>Male</option>
                                    <option value="2" {{ empty(old('gender')) ? ($errors->has('gender') ? '' : ($labour->gender == '2' ? 'selected' : '')) :
                                            (old('gender') == '2' ? 'selected' : '') }}>Female</option>
                                </select>

                                @error('gender')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Mobile No. </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Mobile No."
                                       name="mobile_no" value="{{ empty(old('mobile_no')) ? ($errors->has('mobile_no') ? '' : $labour->mobile_no) : old('mobile_no') }}">

                                @error('mobile_no')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('father_name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Father Name </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Father Name"
                                       name="father_name" value="{{ empty(old('father_name')) ? ($errors->has('father_name') ? '' : $labour->father_name) : old('father_name') }}">

                                @error('father_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('mother_name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Mother Name </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Mother Name"
                                       name="mother_name" value="{{ empty(old('mother_name')) ? ($errors->has('mother_name') ? '' : $labour->mother_name) : old('mother_name') }}">

                                @error('mother_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('photo') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Photo</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="photo">

                                @error('photo')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('present_address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Present Address *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Present Address"
                                       name="present_address" value="{{ empty(old('present_address')) ? ($errors->has('present_address') ? '' : $labour->present_address) : old('present_address') }}">

                                @error('present_address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('permanent_address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Permanent Address *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Permanent Address"
                                       name="permanent_address" value="{{ empty(old('permanent_address')) ? ($errors->has('permanent_address') ? '' : $labour->permanent_address) : old('permanent_address') }}">

                                @error('permanent_address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('religion') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Religion *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="religion" >
                                    <option value="">Select Religion</option>
                                    <option value="1" {{ empty(old('religion')) ? ($errors->has('religion') ? '' : ($labour->religion == '1' ? 'selected' : '')) :
                                            (old('religion') == '1' ? 'selected' : '') }}>Muslim</option>
                                    <option value="2" {{ empty(old('religion')) ? ($errors->has('religion') ? '' : ($labour->religion == '2' ? 'selected' : '')) :
                                            (old('religion') == '2' ? 'selected' : '') }}>Hindu</option>
                                    <option value="3" {{ empty(old('religion')) ? ($errors->has('religion') ? '' : ($labour->religion == '3' ? 'selected' : '')) :
                                            (old('religion') == '3' ? 'selected' : '') }}>Christian</option>
                                    <option value="4" {{ empty(old('religion')) ? ($errors->has('religion') ? '' : ($labour->religion == '4' ? 'selected' : '')) :
                                            (old('religion') == '4' ? 'selected' : '') }}>Other</option>
                                </select>

                                @error('religion')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('per_day_amount') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Per Day Amount *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Per Day Amount"
                                       name="per_day_amount" value="{{ empty(old('per_day_amount')) ? ($errors->has('per_day_amount') ? '' : $labour->per_day_amount) : old('per_day_amount') }}">

                                @error('per_day_amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($labour->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($labour->status == '0' ? 'checked' : '')) :
                                            (old('status') == '0' ? 'checked' : '') }}>
                                        Inactive
                                    </label>
                                </div>

                                @error('status')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
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
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Date picker
            $('#dob').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                orientation: 'bottom'
            });

            $('.date-picker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
            });
        });
    </script>
@endsection
