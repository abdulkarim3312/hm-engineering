@extends('layouts.app')

@section('title')
    Contractor Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Contractor Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {{-- <form method="POST" action="{{ route('mobilization_work.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('project_id') ? 'has-error' :'' }}">
                                    <label>Name</label>

                                    <input type="text" class="form-control" placeholder="Enter Name"
                                    name="name" value="{{ old('name') }}">

                                    @error('name')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Contractor ID</label>

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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('project_id') ? 'has-error' :'' }}">
                                    <label>Project</label>

                                    <select name="project_type" class="form-control select2" >
                                        <option value="">Select Project Type</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project') == 1 ? 'selected' : '' }}>{{ $project->name ?? '' }}</option>
                                        @endforeach
                                    </select>

                                    @error('project_type')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Contractor ID</label>

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

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form> --}}
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('contractor.add') }}">
                    @csrf

                    <div class="box-body">

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Contractor ID</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter ID"
                                       name="contractor_id" value="{{ old('contractor_id') }}">

                                @error('contractor_id')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('project_id') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project <span class="text-danger">*</span></label>

                            <div class="col-sm-10">
                                <select name="project_id" class="form-control" >
                                    <option value="">Select Project Type</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == 1 ? 'selected' : '' }}>{{ $project->name ?? '' }}</option>
                                    @endforeach
                                </select>

                                @error('project_id')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('trade') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Trade <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select name="trade" class="form-control" >
                                    <option>Select Trade</option>
                                    <option value="1">Civil Contractor</option>
                                    <option value="2">Paint Contractor</option>
                                    <option value="3">Sanitary Contractor</option>
                                    <option value="4">Tiles Contractor</option>
                                    <option value="5">MS Contractor</option>
                                    <option value="6">Wood Contractor</option>
                                    <option value="7">Electric Contractor</option>
                                    <option value="8">Thai Contractor</option>
                                    <option value="8">Other Contractor</option>
                                </select>

                                @error('trade')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('mobile') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Mobile No. *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Mobile No."
                                       name="mobile" value="{{ old('mobile') }}">

                                @error('mobile')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('nid') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">NID</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter NID"
                                       name="nid" value="{{ old('nid') }}">

                                @error('nid')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Address *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter address"
                                       name="address" value="{{ old('address') }}">

                                @error('address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('image') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="image">
                                @error('image')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ old('status') == '1' ? 'checked' : '' }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}>
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
