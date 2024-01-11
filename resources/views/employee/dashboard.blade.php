@extends('layouts.app')
{{--@section('title','Password Change')--}}

@section('content')
    <div class="col-md-12">
        <!-- jquery validation -->
        <div class="box box-outline card-primary">
            <div class="box-header">
                <h3 class="box-title">Password Change Information</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form enctype="multipart/form-data" action="{{ route('employee_password_change') }}" class="form-horizontal" method="post">
                @csrf
                <div class="box-body">
                    <div class="form-group row {{ $errors->has('old_password') ? 'has-error' :'' }}">
                        <label for="old_password" class="col-sm-3 col-form-label">Old Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password"  name="old_password" class="form-control" id="old_password" placeholder="Enter Old Password">
                            @error('old_password')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('new_password') ? 'has-error' :'' }}">
                        <label for="new_password" class="col-sm-3 col-form-label">New Password <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter New Password">
                            @error('new_password')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('password_confirmation') ? 'has-error' :'' }}">
                        <label for="password_confirmation" class="col-sm-3 col-form-label">Password Confirmation <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="password"  name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter Password Confirmation">
                            @error('password_confirmation')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
{{--                    <a href="{{ route('employee_profile') }}" class="btn btn-default float-right">Cancel</a>--}}
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
    </div>
@endsection
