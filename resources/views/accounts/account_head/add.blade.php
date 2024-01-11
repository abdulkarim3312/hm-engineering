@extends('layouts.app')

@section('title')
    Account Head Sub Type Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Account Head Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('account_head.add') }}">
                    @csrf

                    <div class="box-body">

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-3 control-label">Head of Income/Expense/Bank <span class="text-danger">*</span></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter Account Head Name"
                                       name="name" value="{{ old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('account_code') ? 'has-error' :'' }}">
                            <label class="col-sm-3 control-label">Account Code</label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter Account Code"
                                       name="account_code" value="{{ old('account_code') }}">

                                @error('account_code')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('opening_balance') ? 'has-error' :'' }}">
                            <label class="col-sm-3 control-label">Opening Balance <span class="text-danger">*</span></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter Opening Balance"
                                       name="opening_balance" value="{{ old('opening_balance',0) }}">

                                @error('opening_balance')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-3 control-label">Type <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="type" id="type">
                                    <option value="">Select Type</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('type')
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

