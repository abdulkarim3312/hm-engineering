@extends('layouts.app')
@section('title')
    Flat/Shop Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Flat Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('flat.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="project_id" id="project">
                                    <option value="">Select Project</option>

                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>

                                @error('project_id')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('floor') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Floor *</label>

                            <div class="col-sm-10">
                                <select class="form-control select2" name="floor_id" id="floor">
                                    <option value="">Select Floor</option>
                                </select>

                                @error('floor_id')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Type *</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="type" id="type">
                                    <option value="">Select Type</option>
                                    <option {{ old('type') == 1  ? 'selected' : ''}} value="1">Apartment</option>
                                    <option {{ old('type') == 2  ? 'selected' : ''}} value="2">Shop</option>
                                    <option {{ old('type') == 3  ? 'selected' : ''}} value="2">Commercial Space</option>
                                </select>

                                @error('type')
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
                        <div class="form-group {{ $errors->has('size') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Size</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Size"
                                       name="size" value="{{ old('size') }}">

                                @error('size')
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
    <script>
        $(function () {
            var floorSelected = '{{ old('floor_id') }}';

            $('#project').change(function () {
                var projectId = $(this).val();

                $('#floor').html('<option value="">Select floor</option>');

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('floor.get_floor') }}",
                        data: { projectId: projectId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (floorSelected == item.id)
                                $('#floor').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#floor').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });

            $('#project').trigger('change');
        });
    </script>
@endsection
