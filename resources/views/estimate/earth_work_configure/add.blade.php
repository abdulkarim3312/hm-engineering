@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Earth Work Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Earth Work Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('earth_work_configure.add') }}">
                    @csrf

                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="25%">Estimate Project</th>
                                    <th width="15%">Length</th>
                                    <th width="15%">Width</th>
                                    <th width="15%">Height</th>
                                    <th width="15%">Unit Price(Per Cft)</th>
                                    <th width="15%">Total Area</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="project-container">
                                @if (old('project') != null && sizeof(old('project')) > 0)
                                    @foreach(old('project') as $item)
                                        <tr class="project-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('project.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 project" style="width: 100%;" name="project[]" data-placeholder="Select Estimate Projects" required>
                                                        <option>Select Estimate Project</option>
                                                        @foreach($estimateProjects as $estimateProject)
                                                            <option value="{{ $estimateProject->id }}" {{ old('project.'.$loop->parent->index) == $brickConfigureProduct->id ? 'selected' : '' }}>{{ $estimateProject->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('.length'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="length[]" class="form-control length" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('width.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control width" name="width[]" value="{{ old('width.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control height" name="height[]" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="project-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 project" style="width: 100%;" name="project[]" data-placeholder="Select Estimate Project" required>
                                                    <option>Select Estimate Project</option>
                                                    @foreach($estimateProjects as $estimateProject)
                                                        <option value="{{ $estimateProject->id }}" >{{ $estimateProject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="length[]" class="form-control length">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control width" name="width[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control height" name="height[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control unit_price" name="unit_price[]" value="0">
                                            </div>
                                        </td>

                                        <td class="sub-total">0.00 (Cft)</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-project">Add More</a>
                                    </td>
                                    <th colspan="4" class="text-right">Total Volume</th>
                                    <th id="total-area"> 0.00 (Cft)</th>
                                    <td></td>
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
                    <select class="form-control select2 project" style="width: 100%;" name="project[]" data-placeholder="Select Estimate Project" required>
                        <option>Select Estimate Project</option>
                        @foreach($estimateProjects as $estimateProject)
                            <option value="{{ $estimateProject->id }}" >{{ $estimateProject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="length[]" class="form-control length">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control width" name="width[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control height" name="height[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control unit_price" name="unit_price[]" value="0">
                </div>
            </td>

            <td class="sub-total">0.00</td>
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

            {{--var selectedFloor = '{{ old('estimate_floor') }}';--}}

            {{--$('body').on('change', '#estimate_project', function () {--}}
            {{--    var estimateProjectId = $(this).val();--}}
            {{--    $('#estimate_floor').html('<option value="">Select Estimate Floor</option>');--}}

            {{--    if (estimateProjectId != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('get_estimate_floor') }}",--}}
            {{--            data: { estimateProjectId:estimateProjectId }--}}
            {{--        }).done(function( response ) {--}}
            {{--            $.each(response, function( index, item ) {--}}
            {{--                if (selectedFloor == item.id)--}}
            {{--                    $('#estimate_floor').append('<option value="'+item.id+'" selected>'+item.name+'</option>');--}}
            {{--                else--}}
            {{--                    $('#estimate_floor').append('<option value="'+item.id+'">'+item.name+'</option>');--}}
            {{--            });--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}
            {{--$('#estimate_project').trigger('change');--}}

            {{--$('body').on('change','.project', function () {--}}
            {{--    var projectID = $(this).val();--}}
            {{--    var itemprojectID = $(this);--}}

            {{--    itemprojectID.closest('tr').find('.length').val();--}}


            {{--    if (projectID != '') {--}}
            {{--        $.ajax({--}}
            {{--            method: "GET",--}}
            {{--            url: "{{ route('get_bricks_area') }}",--}}
            {{--            data: { projectID:projectID }--}}
            {{--        }).done(function( response ) {--}}
            {{--            itemprojectID.closest('tr').find('.length').val(response.sub_total_area);--}}
            {{--        });--}}

            {{--    }--}}
            {{--})--}}
            {{--$('.project').trigger("change");--}}

            $('#btn-add-project').click(function () {
                var html = $('#template-project').html();
                var item = $(html);

                $('#project-container').append(item);


                initProduct();

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

            $('body').on('keyup','.width,.height', function () {
                calculate();
            });

            if ($('.project-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
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

        function initProduct() {
            $('.project').select2();
        }
    </script>
@endsection
