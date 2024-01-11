@extends('layouts.app')

@section('title')
   Edit Grade Of Concrete Type
@endsection
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Grade Of Concrete Type Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('grade_of_concrete_type.edit',['gradeOfConcrete'=>$gradeOfConcrete->id]) }}">
                    @csrf

                    <div class="box-body">

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $gradeOfConcrete->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('first_ratio') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">First Ratio *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter First Ratio"
                                       name="first_ratio" value="{{ empty(old('first_ratio')) ? ($errors->has('first_ratio') ? '' : $gradeOfConcrete->first_ratio) : old('first_ratio') }}">

                                @error('first_ratio')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('second_ratio') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Second Ratio *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Second Ratio"
                                       name="second_ratio" value="{{ empty(old('second_ratio')) ? ($errors->has('second_ratio') ? '' : $gradeOfConcrete->second_ratio) : old('second_ratio') }}">

                                @error('second_ratio')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('third_ratio') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Third Ratio *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Third Ratio"
                                       name="third_ratio" value="{{ empty(old('third_ratio')) ? ($errors->has('third_ratio') ? '' : $gradeOfConcrete->third_ratio) : old('third_ratio') }}">

                                @error('third_ratio')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Description *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Description"
                                       name="description" value="{{ empty(old('description')) ? ($errors->has('description') ? '' : $gradeOfConcrete->description) : old('description') }}">

                                @error('description')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($gradeOfConcrete->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($gradeOfConcrete->status == '0' ? 'checked' : '')) :
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
            $('.select2').select2();
        });
    </script>
@endsection
