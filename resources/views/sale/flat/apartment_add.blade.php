@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Apartment/Flat Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Apartment/Flat Add</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('flat.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="25%">Project Name</th>
                                    <th width="15%">Floor</th>
                                    <th width="15%">Type</th>
                                    <th width="20%">Name</th>
                                    <th width="15%">Size</th>
                                    <th width="10%">Floor Quantity</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="project-container">
                                @if (old('project') != null && sizeof(old('project')) > 0)
                                    @foreach(old('project') as $item)
                                        <tr class="project-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('project.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 project" style="width: 100%;" name="project[]" data-placeholder="Select Project" required>
                                                        <option value="">Select Project</option>

                                                        @foreach($projects as $project)
                                                            <option value="{{ $project->id }}" {{ old('project.'.$loop->parent->index) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('floor_id.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control floor_id" style="width: 100%;" data-selected-floor-id="{{ old('floor_id.'.$loop->index) }}" name="floor_id[]" required>
                                                        <option value="">Select Floor</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('type.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control type" style="width: 100%;" data-selected-type="{{ old('type.'.$loop->index) }}" name="type[]" required>
                                                        <option value="">Select Type</option>
                                                        <option {{ old('type.'.$loop->index) == 1 ? 'selected' : '' }} value="1">Apartment</option>
                                                        <option {{ old('type.'.$loop->index) == 2 ? 'selected' : '' }} value="2">Shop</option>
                                                        <option {{ old('type.'.$loop->index) == 3 ? 'selected' : '' }} value="3">Commercial Space</option>
                                                        <option {{ old('type.'.$loop->index) == 4 ? 'selected' : '' }} value="4">Car Parking</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('name.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control name" name="name[]" value="{{ old('name.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('size.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control size" name="size[]" value="{{ old('size.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('floor_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" class="form-control floor_quantity" name="floor_quantity[]" value="{{ old('floor_quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="project-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 project" style="width: 100%;" name="project[]" data-placeholder="Select Project" required>
                                                    <option value="">Select Project</option>

                                                    @foreach($projects as $project)
                                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control floor_id" style="width: 100%;" name="floor_id[]">
                                                    <option value="">Select Floor</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control type" style="width: 100%;" name="type[]" required>
                                                    <option value="">Select Type</option>
                                                    <option {{ old('type') == 3 ? 'selected' : '' }} value="1">Apartment</option>
                                                    <option {{ old('type') == 3 ? 'selected' : '' }} value="2">Shop</option>
                                                    <option {{ old('type') == 3 ? 'selected' : '' }} value="3">Commercial Space</option>
                                                    <option {{ old('type') == 4 ? 'selected' : '' }} value="4">Car Parking</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any" class="form-control name" name="name[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control size" name="size[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" class="form-control floor_quantity" name="floor_quantity[]" value="1">
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-project">Add Flat/Apartment</a>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
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

    <template id="template-project">
        <tr class="project-item">
            <td>
                <div class="form-group">
                    <select class="form-control select2 project" style="width: 100%;" name="project[]" data-placeholder="Select Project" required>
                        <option value="">Select Project</option>

                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control floor_id" style="width: 100%;" name="floor_id[]">
                        <option value="">Select Floor</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <select class="form-control type" style="width: 100%;" name="type[]" required>
                        <option value="">Select Type</option>
                        <option {{ old('type') == 3 ? 'selected' : '' }} value="1">Apartment</option>
                        <option {{ old('type') == 3 ? 'selected' : '' }} value="2">Shop</option>
                        <option {{ old('type') == 3 ? 'selected' : '' }} value="3">Commercial Space</option>
                        <option {{ old('type') == 4 ? 'selected' : '' }} value="4">Car Parking</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" step="any" class="form-control name" name="name[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control size" name="size[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" class="form-control floor_quantity" name="floor_quantity[]" value="1">
                </div>
            </td>

            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            $('#btn-add-project').click(function () {
                var html = $('#template-project').html();
                var item = $(html);

                $('#project-container').append(item);


                initProject();

                if ($('.project-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.project-item').remove();
                calculate();

                if ($('.project-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('change','.project', function () {
                var projectId = $(this).val();
                var itemProduct = $(this);
                itemProduct.closest('tr').find('.floor_id').html('<option value="">Select floor</option>');
                var selected = itemProduct.closest('tr').find('.floor_id').attr("data-selected-floor-id");

                if (projectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('floor.get_floor') }}",
                        data: { projectId: projectId }
                    }).done(function( data ) {
                        console.log(data);
                        $.each(data, function( index, item ) {
                            if (selected == item.id)
                                itemProduct.closest('tr').find('.floor_id').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                itemProduct.closest('tr').find('.floor_id').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                       // itemProduct.closest('tr').find('.project').trigger('change');
                    });
                }
            });
            $('.project').trigger('change');


            {{--var floorSelected = '{{ old('floor_id') }}';--}}

            {{--$('.project').change(function () {--}}
            {{--    var projectId = $(this).val();--}}

            {{--    $('.floor_id').html('<option value="">Select floor</option>');--}}

            {{--    if (projectId != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('floor.get_floor') }}",--}}
            {{--            data: { projectId: projectId }--}}
            {{--        }).done(function( data ) {--}}
            {{--            $.each(data, function( index, item ) {--}}
            {{--                if (floorSelected == item.id)--}}
            {{--                    $('.floor_id').append('<option value="'+item.id+'" selected>'+item.name+'</option>');--}}
            {{--                else--}}
            {{--                    $('.floor_id').append('<option value="'+item.id+'">'+item.name+'</option>');--}}
            {{--            });--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}

            {{--$('.project').trigger('change');--}}


            $('body').on('keyup','.width,.height', function () {
                calculate();
            });

            if ($('.project-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProject();
            calculate();
        });

        function calculate() {
            var totalArea = 0;

            $('.project-item').each(function(i, obj) {
                var length = $('.length:eq('+i+')').val();
                var width = $('.width:eq('+i+')').val();
                var height = $('.height:eq('+i+')').val();

                if (length == '' || length < 0 || !$.isNumeric(length))
                    length = 0;

                if (width == '' || width < 0 || !$.isNumeric(width))
                    width = 0;

                if (height == '' || height < 0 || !$.isNumeric(height))
                    height = 0;

                $('.sub-total:eq('+i+')').html(parseFloat(((length * width) * height).toFixed(2)));

                totalArea += parseFloat(((length * width) * height).toFixed(2));
            });

            $('#total-area').html(totalArea.toFixed(2) +' '+'Cft');
        }

        function initProject() {
            $('.project').select2();
            $('.floor_id').select2();
        }
    </script>
@endsection
