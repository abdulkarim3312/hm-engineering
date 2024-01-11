@extends('layouts.app')

@section('title')
    Project Edit
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
                <form class="form-horizontal" method="POST" action="{{ route('project.edit',['project' => $project->id]) }}" enctype="multipart/form-data">

                    @csrf

                    <div class="box-body">
                        <div class="form-group row {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label"> Type <span class="text-danger">*</span></label>

                            <div class="col-sm-10">
                                <select name="type" class="form-control select2" >
                                    <option value="">Select Project Type</option>

                                    <option value="1" {{ 1 == $project->type? 'selected' :''}}>Interior</option>

                                    <option value="2" {{ 2 == $project->type? 'selected' :''}}>Building</option>
                                </select>

                                @error('type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row{{ $errors->has('client') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label"> Client</label>

                            <div class="col-sm-10">
                                <select name="client" class="form-control select2" >
                                    <option value="">Select Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}" {{$client->id==$project->client_id? 'selected' :''}}>{{$client->name}}</option>
                                    @endforeach
                                </select>

                                @error('client')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $project->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Address </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Address"
                                       name="address" value="{{ empty(old('address')) ? ($errors->has('address') ? '' : $project->address) : old('address') }}">

                                @error('address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('phone_number') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Phone Number</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Phone Number"
                                       name="phone_number" value="{{ empty(old('phone_number')) ? ($errors->has('phone_number') ? '' : $project->phone_number) : old('phone_number') }}">

                                @error('phone_number')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('amount') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Amount</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Amount"
                                       name="amount" value="{{ empty(old('amount')) ? ($errors->has('amount') ? '' : $project->amount) : old('amount') }}">

                                @error('amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('duration_start') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Start Date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" placeholder="Enter Start Date"
                                       name="duration_start" value="{{ empty(old('duration_start')) ? ($errors->has('duration_start') ? '' : $project->duration_start) : old('duration_start') }}">
                                @error('duration_start')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('duration_end') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">End Date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" placeholder="Enter End Date"
                                       name="duration_end" value="{{ empty(old('duration_end')) ? ($errors->has('duration_end') ? '' : $project->duration_end) : old('duration_end') }}">

                                @error('duration_end')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('project_progress') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project Progress</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Project Progress"
                                       name="project_progress" value="{{ empty(old('project_progress')) ? ($errors->has('project_progress') ? '' : $project->project_progress) : old('project_progress') }}">

                                @error('project_progress')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('attachment') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Attachment</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control"  name="attachment">
                                @error('attachment')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div><div class="form-group row {{ $errors->has('attachment1') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Attachment1</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control"  name="attachment1">
                                @error('attachment1')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('attachment2') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Attachment2</label>

                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="attachment">
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
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($project->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($project->status == '0' ? 'checked' : '')) :
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
