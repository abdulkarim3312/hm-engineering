@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style>
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            white-space: nowrap;
        }
        select.form-control.product{
            width: 373.486px;
        }
        input.form-control.plaster_side{
            width: 100px;
        }
    </style>

@endsection

@section('title')
    Plaster Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Plaster Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('plaster_configure.add') }}">
                    @csrf

                    <div class="box-body">

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('first_ratio') ? 'has-error' :'' }}">
                                    <label>First Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="first_ratio" step="any"
                                               name="first_ratio" value="{{ old('first_ratio') }}" placeholder="First Ratio">
                                    </div>
                                    <!-- /.input group -->

                                    @error('first_ratio')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('second_ratio') ? 'has-error' :'' }}">
                                    <label>Second Ratio</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="second_ratio" step="any"
                                               name="second_ratio" value="{{ old('second_ratio') }}" placeholder="Second Ratio">
                                    </div>
                                    <!-- /.input group -->

                                    @error('second_ratio')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('floor_number') ? 'has-error' :'' }}">
                                    <label>Floor Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="floor_number" step="any"
                                               name="floor_number" value="{{ old('floor_number',1) }}" placeholder="Floor Number">
                                    </div>
                                    <!-- /.input group -->

                                    @error('floor_number')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('dry_morter') ? 'has-error' :'' }}">
                                    <label>Dry Morter</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="dry_morter" step="any"
                                               name="dry_morter" value="{{ old('dry_morter',1.35) }}" placeholder="Dry Morter">
                                    </div>
                                    <!-- /.input group -->

                                    @error('dry_morter')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                                    <label>Note</label>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="note" name="note" value="{{ old('note') }}">
                                    </div>
                                    <!-- /.input group -->

                                    @error('note')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <u><i><h3>Costing Area</h3></i></u>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('plaster_cement_costing') ? 'has-error' :'' }}">
                                    <label>Cement Cost(Per Bag)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $bricksCost->bricks_cement_costing??0 }}"
                                               name="plaster_cement_costing"  placeholder="Enter Per Bag Cement Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('plaster_cement_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group {{ $errors->has('plaster_sands_costing') ? 'has-error' :'' }}">
                                    <label>Sands Cost(Per Cft)</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" step="any" value="{{ $bricksCost->bricks_sands_costing??0 }}"
                                               name="plaster_sands_costing"  placeholder="Enter Per Cft Sands Costing">
                                    </div>
                                    <!-- /.input group -->

                                    @error('plaster_sands_costing')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Bricks Wall Direction</th>
                                    <th>Plaster Area</th>
                                    <th>Deduction Length(1)</th>
                                    <th>Deduction Height/Width(1)</th>
                                    <th>Deduction Length(2)</th>
                                    <th>Deduction Height/Width(2)</th>
                                    <th>Side</th>
                                    <th>Plaster Thickness</th>
                                    <th>Total Plaster Area(Wet)</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" name="product[]" data-placeholder="Select WAll Direction" required>
                                                        <option>Select Wall Direction</option>
                                                        @foreach($brickConfigureProducts as $brickConfigureProduct)
                                                            <option value="{{ $brickConfigureProduct->id }}" {{ old('product.'.$loop->parent->index) == $brickConfigureProduct->id ? 'selected' : '' }}>
                                                                @if($brickConfigureProduct->wall_direction == 1)
                                                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-East
                                                                @elseif($brickConfigureProduct->wall_direction == 2)
                                                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-West
                                                                @elseif($brickConfigureProduct->wall_direction == 3)
                                                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-North
                                                                @else
                                                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-South
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('.plaster_area'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="plaster_area[]" readonly class="form-control plaster_area" value="{{ old('plaster_area.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_one[]" class="form-control deduction_length_one" value="0" value="{{ old('deduction_length_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_one.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0" value="{{ old('deduction_height_one.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_length_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="deduction_length_two[]" class="form-control deduction_length_two" value="0" value="{{ old('deduction_length_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('deduction_height_two.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0" value="{{ old('deduction_height_two.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('plaster_side.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control plaster_side" name="plaster_side[]" value="{{ old('plaster_side.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('plaster_thickness.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control plaster_thickness" name="plaster_thickness[]" value="{{ old('plaster_thickness.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total-plaster-morters">0.00 Wet(Cft)</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product" name="product[]" data-placeholder="Select WAll Direction" required>
                                                    <option>Select Wall Direction</option>
                                                    @foreach($brickConfigureProducts as $brickConfigureProduct)
                                                        <option value="{{ $brickConfigureProduct->id }}">
                                                            @if($brickConfigureProduct->wall_direction == 1)
                                                                {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-East
                                                            @elseif($brickConfigureProduct->wall_direction == 2)
                                                                {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-West
                                                            @elseif($brickConfigureProduct->wall_direction == 3)
                                                                {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-North
                                                            @else
                                                                {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-South
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="plaster_area[]" readonly class="form-control plaster_area">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_one[]" class="form-control deduction_length_one" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any"  name="deduction_length_two[]" class="form-control deduction_length_two" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control plaster_side" name="plaster_side[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control plaster_thickness" name="plaster_thickness[]">
                                            </div>
                                        </td>

                                        <td class="sub-total-plaster-morters">0.00 Wet(Cft)</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>

                                <tfoot>
                                <tr>
                                    <td>
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add More</a>
                                    </td>
                                    <th colspan="7" class="text-right">Total Plaster</th>
                                    <th id="total-plaster-morters"> 0.00 Wet(Cft)</th>
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

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control select2 product" name="product[]" data-placeholder="Select WAll Direction" required>
                        <option>Select Wall Direction</option>
                        @foreach($brickConfigureProducts as $brickConfigureProduct)
                            <option value="{{ $brickConfigureProduct->id }}">
                                @if($brickConfigureProduct->wall_direction == 1)
                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-East
                                @elseif($brickConfigureProduct->wall_direction == 2)
                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-West
                                @elseif($brickConfigureProduct->wall_direction == 3)
                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-North
                                @else
                                    {{$brickConfigureProduct->project->name}}-{{$brickConfigureProduct->estimateFloor->name}}-{{$brickConfigureProduct->estimateFloorUnit->name}} - {{$brickConfigureProduct->unitSection->name}}-South
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="plaster_area[]" readonly class="form-control plaster_area">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_one[]" class="form-control deduction_length_one" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_one" name="deduction_height_one[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any"  name="deduction_length_two[]" class="form-control deduction_length_two" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control deduction_height_two" name="deduction_height_two[]" value="0">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control plaster_side" name="plaster_side[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control plaster_thickness" name="plaster_thickness[]">
                </div>
            </td>

            <td class="sub-total-plaster-morters">0.00</td>
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

            var selectedFloor = '{{ old('estimate_floor') }}';

            $('body').on('change', '#estimate_project', function () {
                var estimateProjectId = $(this).val();
                $('#estimate_floor').html('<option value="">Select Estimate Floor</option>');

                if (estimateProjectId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_estimate_floor') }}",
                        data: { estimateProjectId:estimateProjectId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedFloor == item.id)
                                $('#estimate_floor').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#estimate_floor').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                    });
                }
            });
            $('#estimate_project').trigger('change');

            $('body').on('change','.product', function () {
                var productID = $(this).val();
                var itemproductID = $(this);

                itemproductID.closest('tr').find('.plaster_area').val();


                if (productID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bricks_area') }}",
                        data: { productID:productID }
                    }).done(function( response ) {
                        itemproductID.closest('tr').find('.plaster_area').val(response.sub_total_area);
                    });

                }
            })
            $('.product').trigger("change");

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                $('#product-container').append(item);

                //$('#unit_section').trigger('change');

                initProduct();

                if ($('.product-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup','.plaster_side,.plaster_thickness,.deduction_length_one' +
                '.deduction_height_one,.deduction_length_two,.deduction_height_two', function () {
                calculate();
            });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            initProduct();
            calculate();
        });

        function calculate() {
            var totalPlasterMorters = 0;

            $('.product-item').each(function(i, obj) {
                var plaster_area = $('.plaster_area:eq('+i+')').val();
                var deduction_length_one = $('.deduction_length_one:eq('+i+')').val();
                var deduction_height_one = $('.deduction_height_one:eq('+i+')').val();
                var deduction_length_two = $('.deduction_length_two:eq('+i+')').val();
                var deduction_height_two = $('.deduction_height_two:eq('+i+')').val();
                var plaster_side = $('.plaster_side:eq('+i+')').val();
                var plaster_thickness = $('.plaster_thickness:eq('+i+')').val();

                if (plaster_area == '' || plaster_area < 0 || !$.isNumeric(plaster_area))
                    plaster_area = 0;

                if (deduction_length_one == '' || deduction_length_one < 0 || !$.isNumeric(deduction_length_one))
                    deduction_length_one = 0;

                if (deduction_height_one == '' || deduction_height_one < 0 || !$.isNumeric(deduction_height_one))
                    deduction_height_one = 0;

                if (deduction_length_two == '' || deduction_length_two < 0 || !$.isNumeric(deduction_length_two))
                    deduction_length_two = 0;

                if (deduction_height_two == '' || deduction_height_two < 0 || !$.isNumeric(deduction_height_two))
                    deduction_height_two = 0;

                if (plaster_side == '' || plaster_side < 0 || !$.isNumeric(plaster_side))
                    plaster_side = 0;

                if (plaster_thickness == '' || plaster_thickness < 0 || !$.isNumeric(plaster_thickness))
                    plaster_thickness = 0;

                var deduction_one = parseFloat(deduction_length_one * deduction_height_one);
                var deduction_two = parseFloat(deduction_length_two * deduction_height_two);

                var totalDeduction = deduction_one + deduction_two;

                $('.sub-total-plaster-morters:eq('+i+')').html(parseFloat((((plaster_area - totalDeduction) * plaster_side) * plaster_thickness).toFixed(2)));

                totalPlasterMorters += parseFloat((((plaster_area - totalDeduction) * plaster_side) * plaster_thickness).toFixed(2));
            });

            $('#total-plaster-morters').html(totalPlasterMorters.toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
