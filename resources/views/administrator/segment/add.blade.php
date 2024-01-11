@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection

@section('title')
    Segment Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Segment Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('segment.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="project">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}" {{old('project') == $project->id ? 'selected':'' }}>
                                            {{$project->name}}</option>
                                    @endforeach
                                </select>

                                @error('project')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
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
                        <div class="form-group {{ $errors->has('segment_unit') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Segment Unit </label>

                            <div class="col-sm-10">
                                <input type="number" class="form-control" placeholder="Enter Segment Unit"
                                       name="segment_unit" value="{{ old('segment_unit') }}">

                                @error('segment_unit')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('segment_percent') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Segment Percent </label>

                            <div class="col-sm-10">
                                <input type="number" class="form-control" placeholder="Enter Segment Percent"
                                       name="segment_percent" value="{{ old('segment_percent') }}">

                                @error('segment_percent')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('description') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Description </label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Description"
                                       name="description" value="{{ old('description') }}">

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
@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>
@endsection
