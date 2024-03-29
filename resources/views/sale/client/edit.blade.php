@extends('layouts.app')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection
@section('title')
    Client Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Client Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('client.edit', ['client' => $client->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $client->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Profession</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Profession"
                                       name="company_name" value="{{ empty(old('company_name')) ? ($errors->has('company_name') ? '' : $client->company_name) : old('company_name') }}">

                                @error('company_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('nid_number') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">NID Number.</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter NID Number."
                                       name="nid_number" value="{{empty(old('nid_number')) ? ($errors->has('nid_number') ? '' : $client->nid_number) : old('nid_number') }}">

                                @error('nid_number')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('blood_group') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Blood Group.</label>

                            <div class="col-sm-10">
                                <select style="width: 100%" class="form-control" name="blood_group">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ $client->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ $client->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ $client->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ $client->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="O+" {{ $client->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ $client->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="AB+" {{ $client->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ $client->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                                @error('blood_group')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('country') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Country.</label>
                            <div class="col-sm-10">
                                <select style="width: 100%" class="form-control" name="country">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $country->id == $client->country_id ? 'selected' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('country_code') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Country Code.</label>
                            <div class="input-group">
                                <select style="width: 100%; margin-left: 15px" class="form-control" name="country_code">
                                    <option value="">Select Code</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $country->id == $client->country_id ? 'selected' : '' }}>{{ $country->country_code }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn" style="width:15px;"></span>
                                <input type="text" name="mobile_no" class="form-control" placeholder="Enter Mobile No." value="{{ empty(old('mobile_no')) ? ($errors->has('mobile_no') ? '' : $client->mobile_no) : old('mobile_no') }}">
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Address</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Address"
                                       name="address" value="{{ empty(old('address')) ? ($errors->has('address') ? '' : $client->address) : old('address') }}">

                                @error('address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Email"
                                       name="email" value="{{ empty(old('email')) ? ($errors->has('email') ? '' : $client->email) : old('email') }}">

                                @error('email')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('gender') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Gender *</label>

                            <div class="col-sm-10">
                                <select style="width: 100%" class="form-group select2" name="gender">
                                    @if($client->gender == 0)
                                        <option value="0">Male</option>
                                        <option value="1">Female</option>
                                    @else
                                        <option value="1">Female</option>
                                        <option value="0">Male</option>
                                    @endif
                                </select>
                                @error('gender')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('birthday') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Date of Birth</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" placeholder="Date of birth"
                                       name="birthday" value="{{ $client->birthday }}">
                                @error('birthday')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('marrige_day') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Marriage anniversary date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control" placeholder="Anniversary date"
                                       name="marrige_day" value="{{ $client->marrige_day }}">

                                @error('marrige_day')
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
                                @if($client->image)
                                    <img width="100px" height="100px" src="{{ asset($client->image) }}" alt="">
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($client->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($client->status == '0' ? 'checked' : '')) :
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
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
        });
    </script>
@endsection
