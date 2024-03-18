@extends('layouts.app')

@section('title')
    Supplier Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Supplier Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('contractor.edit', ['contractor' => $contractor->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ old('name', $contractor->name ?? '') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Contractor ID</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter ID"
                                       name="contractor_id" value="{{ old('contractor_id', $contractor->contractor_id ?? '') }}">

                                @error('contractor_id')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('project_id') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project <span class="text-danger">*</span></label>

                            <div class="col-sm-10">
                                <select name="project_id" class="form-control" >
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" {{ $project->id == $contractor->project_id ? 'selected' : '' }}>{{ $project->name ?? '' }}</option>
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
                                    <option value="1" {{ $contractor->trade == 1 ? 'selected' : '' }}>Civil Contractor</option>
                                    <option value="2" {{ $contractor->trade == 2 ? 'selected' : '' }}>Paint Contractor</option>
                                    <option value="3" {{ $contractor->trade == 3 ? 'selected' : '' }}>Sanitary Contractor</option>
                                    <option value="4" {{ $contractor->trade == 4 ? 'selected' : '' }}>Tiles Contractor</option>
                                    <option value="5" {{ $contractor->trade == 5 ? 'selected' : '' }}>MS Contractor</option>
                                    <option value="6" {{ $contractor->trade == 6 ? 'selected' : '' }}>Wood Contractor</option>
                                    <option value="7" {{ $contractor->trade == 7 ? 'selected' : '' }}>Electric Contractor</option>
                                    <option value="8" {{ $contractor->trade == 8 ? 'selected' : '' }}>Thai Contractor</option>
                                    <option value="9" {{ $contractor->trade == 9 ? 'selected' : '' }}>Other Contractor</option>
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
                                       name="mobile" value="{{ old('mobile', $contractor->mobile ?? '') }}">

                                @error('mobile')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('nid') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">NID</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter NID"
                                       name="nid" value="{{ old('nid', $contractor->nid ?? '') }}">

                                @error('nid')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Address *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter address"
                                       name="address" value="{{ old('address', $contractor->address ?? '') }}">

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
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($contractor->status == '1' ? 'checked' : '')) :
                                        (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($contractor->status == '0' ? 'checked' : '')) :
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
