@extends('layouts.app')

@section('title')
    Receive Cheque Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Receive Cheque Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('receive_cheque.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('customer_name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Customer Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       name="customer_name" value="{{ old('customer_name') }}">

                                @error('customer_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('bank_name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Bank Name</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       name="bank_name" value="{{ old('bank_name') }}">

                                @error('bank_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('check_no') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Cheque Number </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       name="check_no" value="{{ old('check_no') }}">

                                @error('check_no')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('check_amount') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Cheque Amount</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       name="check_amount" value="{{ old('check_amount') }}">

                                @error('check_amount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('check_date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Cheque Date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control"
                                       name="check_date" value="{{ old('check_date') }}">

                                @error('check_date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('submitted_date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Submitted Date</label>

                            <div class="col-sm-10">
                                <input type="date" class="form-control"
                                       name="submitted_date" value="{{ old('submitted_date') }}">

                                @error('submitted_date')
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
