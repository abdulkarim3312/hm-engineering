@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
@endsection
@section('title')
    Flat/Shop Edit
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
                <form class="form-horizontal" method="POST" action="{{ route('flat.edit', ['flat' => $flat->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('project') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Project *</label>

                            <div class="col-sm-10">
                                <select style="width: 100%" class="form-group select2" name="project_id" id="project">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}" {{ $flat->project_id == $project->id ? 'selected' : ''}}>{{$project->name}}</option>
                                    @endforeach
                                </select>
                                @error('Project')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('floor_id') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Floor *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="floor_id" id="floor">
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
                                    <option {{ $flat->type == 1  ? 'selected' : ''}} value="1">Apartment</option>
                                    <option {{ $flat->type == 2  ? 'selected' : ''}} value="2">Shop</option>
                                    <option {{ $flat->type == 3  ? 'selected' : ''}} value="3">Commercial Space</option>
                                    <option {{ $flat->type == 4  ? 'selected' : ''}} value="4">Car Parking</option>
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
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $flat->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('size') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Size</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Size"
                                       name="size" value="{{ empty(old('size')) ? ($errors->has('size') ? '' : $flat->size) : old('size') }}">

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
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($flat->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($flat->status == '0' ? 'checked' : '')) :
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

    <script>
        $(function () {
            var floorSelected = '{{ empty(old('floor_id')) ? ($errors->has('floor_id') ? '' : $flat->floor_id) : old('floor_id') }}';

            $('#project').change(function () {
                var projectId = $(this).val();
                $('#floor').html('<option value="">Select Floor</option>');

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
