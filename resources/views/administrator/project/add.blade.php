@extends('layouts.app')

@section('title')
    Project Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Project Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('project.add') }}" enctype="multipart/form-data">

                @csrf

                    <div class="box-body">

                        <div class="form-group {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project Type <span class="text-danger">*</span></label>

                            <div class="col-sm-10">
                                <select name="type" class="form-control select2" >
                                    <option value="">Select Project Type</option>
                                    <option {{ old('type') == 1 ? 'selected' : '' }} value="1">Construction</option>
                                    <option {{ old('type') == 2 ? 'selected' : '' }} value="2">Consultancy</option>
                                </select>

                                @error('type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('client') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project Client </label>

                            <div class="col-sm-10">
                                <select name="client" class="form-control select2" >
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option {{ old('client') == $client->id ? 'selected' : '' }} value="{{$client->id}}">{{$client->name}}</option>
                                    @endforeach
                                </select>

                                @error('client')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project Name <span class="text-danger">*</span></label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Address </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Address"
                                       name="address" value="{{ old('address') }}">

                                @error('address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('phone_number') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Phone Number</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Phone Number"
                                       name="phone_number" value="{{ old('phone_number') }}">

                                @error('phone_number')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('amount') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Amount</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Amount"
                                       name="amount" value="{{ old('amount') }}">

                                @error('amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('duration_start') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Start Date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" placeholder="Enter Start Date"
                                       name="duration_start" value="{{ old('duration_start') }}">

                                @error('duration_start')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('duration_end') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">End Date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" placeholder="Enter End Date"
                                       name="duration_end" value="{{ old('duration_end') }}">

                                @error('duration_end')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('project_progress') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project Progress</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Project Progress"
                                       name="project_progress" value="{{ old('project_progress') }}">

                                @error('project_progress')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('attachment') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Attachment</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="attachment">
                                @error('attachment')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('attachment1') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Attachment 1</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="attachment1">
                                @error('attachment1')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('attachment2') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Attachment 2</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="attachment2">
                                @error('attachment2')
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
