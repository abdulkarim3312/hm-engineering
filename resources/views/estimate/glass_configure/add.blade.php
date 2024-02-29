@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Thai & Glass Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Thai & Glass Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('glass_configure.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            {{-- <div class="col-md-2">
                                <div class="form-group {{ $errors->has('glass_type') ? 'has-error' :'' }}">
                                    <label>Glass Type</label>

                                    <select class="form-control select2" style="width: 100%;" id="glass_type" name="glass_type" data-placeholder="Select Glass Type">

                                        <option value="1" {{ old('glass_type') == 1 ? 'selected' : '' }}>Glass</option>
                                        <option value="2" {{ old('glass_type') == 2 ? 'selected' : '' }}>Thai</option>
                                    </select>

                                    @error('estimate_floor_unit')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('configure_type') ? 'has-error' :'' }}">
                                    <label>Configure Type Name</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="configure_type" value="{{ old('configure_type') }}">
                                    </div>
                                    @error('configure_type')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_project') ? 'has-error' :'' }}">
                                    <label>Estimate Projects</label>

                                    <select class="form-control select2" style="width: 100%;" id="estimate_project" name="estimate_project" data-placeholder="Select Estimating Project">
                                        <option value="">Select Estimate Project</option>

                                        @foreach($estimateProjects as $estimateProject)
                                            <option value="{{ $estimateProject->id }}" {{ old('estimate_project') == $estimateProject->id ? 'selected' : '' }}>{{ $estimateProject->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('estimate_project')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_floor') ? 'has-error' :'' }}">
                                    <label>Estimate Floor</label>

                                    <select class="form-control select2" style="width: 100%;" name="estimate_floor" id="estimate_floor" data-placeholder="Select Estimate Floor">
                                        <option value="">Select Estimate Floor</option>
                                    </select>

                                    @error('estimate_floor')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('estimate_floor_unit') ? 'has-error' :'' }}">
                                    <label>Estimate Floor Unit</label>

                                    <select class="form-control select2" style="width: 100%;" name="estimate_floor_unit" data-placeholder="Select Estimate Floor Unit">
                                        <option value="">Select Estimate Floor Unit</option>
                                        @foreach($estimateFloorUnits as $estimateFloorUnit)
                                            <option value="{{ $estimateFloorUnit->id }}" {{ old('estimate_floor_unit') == $estimateFloorUnit->id ? 'selected' : '' }}>{{ $estimateFloorUnit->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('estimate_floor_unit')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('floor_number') ? 'has-error' :'' }}">
                                    <label>Floor Quantity</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="floor_number" step="any"
                                               name="floor_number" value="{{ old('floor_number',1) }}" >
                                    </div>
                                    <!-- /.input group -->

                                    @error('floor_number')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
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
                            <div class="col-md-6">
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
                            <div id="grill_costing">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('grill_costing') ? 'has-error' :'' }}">
                                        <label>Glass Costing(Per Sft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ 0 }}"
                                                   name="grill_costing"  placeholder="Enter Per Kg Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('grill_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="thai_costing">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('thai_costing') ? 'has-error' :'' }}">
                                        <label>Thai Costing(Per Rft)</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value="{{ 0 }}"
                                                   name="thai_costing"  placeholder="Enter Per Sft Costing">
                                        </div>
                                        <!-- /.input group -->

                                        @error('thai_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- <div id="thai_length" style="display: none;">
                                <div class="col-md-3">
                                    <div class="form-group {{ $errors->has('tiles_glass_costing') ? 'has-error' :'' }}">
                                        <label>Thai Length</label>

                                        <div class="form-group">
                                            <input type="number" class="form-control" step="any" value=""
                                                   name="tiles_glass_costing"  placeholder="Enter Thai Length">
                                        </div>
                                        <!-- /.input group -->

                                        @error('tiles_glass_costing')
                                        <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        <div class="table-responsive" id="glass_table">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="30%">Unit Section</th>
                                    <th width="15%">Length</th>
                                    <th width="15%">Height/Width</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%" style="font-size: 15px;">Total Area(Glass Sft)</th>
                                    <th width="15%" style="font-size: 15px;">Total Thai Rft</th>
                                    <th width="10%"></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" style="width: 70%;" name="product[]" data-placeholder="Select Unit Section" required>
                                                        @foreach($unitSections as $unitSection)
                                                            <option value="{{ $unitSection->id }}" {{ old('product.'.$loop->parent->index) == $unitSection->id ? 'selected' : '' }}>{{ $unitSection->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('length.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any"  name="length[]" class="form-control length" value="{{ old('length.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('height.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control height" name="height[]" value="{{ old('height.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="sub-total-area">0.00</td>
                                            <td class="sub-total-rft">0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 product" style="width: 70%;" name="product[]" data-placeholder="Select Unit Section" required>
                                                    @foreach($unitSections as $unitSection)
                                                        <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
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
                                                <input type="number" step="any" class="form-control height" name="height[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity" name="quantity[]">
                                            </div>
                                        </td>

                                        <td class="sub-total-area">0.00</td>
                                        <td class="sub-total-rft">0.00</td>
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
                                    <th colspan="3" class="text-right">Total Glass Area & Rft</th>
                                    <th id="total-area"> 0.00 </th>
                                    <th id="total-area-rft"> 0.00 </th>
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
                    <select class="form-control select2 product" style="width: 70%;" name="product[]" data-placeholder="Select Unit Section" required>
                        @foreach($unitSections as $unitSection)
                            <option value="{{ $unitSection->id }}">{{ $unitSection->name }}</option>
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
                    <input type="number" step="any" class="form-control height" name="height[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control quantity" name="quantity[]">
                </div>
            </td>

            <td class="sub-total-area">0.00</td>
            <td class="sub-total-rft">0.00</td>
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

            $('body').on('change','#glass_type', function () {
                var glassType = $(this).val();

                if (glassType == 1) {
                    $('#grill_costing').show();
                    $('#glass_table').show();
                    $('#thai_costing').hide();
                }else if(glassType == 2){
                    $('#grill_costing').hide();
                    $('#thai_costing').show();
                    $('#thai_length').show();
                    $('#glass_table').hide();
                }else {

                }
            })
            $('#configure_type').trigger("change");

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                $('#product-container').append(item);

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

            $('body').on('keyup','.length,.height,.quantity', function () {
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
            var totalArea = 0;
            var totalAreaRft = 0;


            $('.product-item').each(function(i, obj) {
                var length = $('.length:eq('+i+')').val();
                var height = $('.height:eq('+i+')').val();
                var quantity = $('.quantity:eq('+i+')').val();
                // console.log(height);
                if (length == '' || length < 0 || !$.isNumeric(length))
                    length = 0;

                if (height == '' || height < 0 || !$.isNumeric(height))
                    height = 0;

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                var len = parseFloat(length);
                var hei = parseFloat(height)
                var rft = (parseFloat(len) + parseFloat(hei)) * 2;
                // console.log(rft);

                $('.sub-total-area:eq('+i+')').html(parseFloat((length * height) * quantity).toFixed(2));
                $('.sub-total-rft:eq('+i+')').html(parseFloat(((rft) * quantity).toFixed(2)));

                totalArea += parseFloat(((length * height) * quantity).toFixed(2));
                totalAreaRft += parseFloat((((rft) * quantity).toFixed(2)));
            });

            $('#total-area').html(parseFloat(totalArea).toFixed(2));
            $('#total-area-rft').html(parseFloat(totalAreaRft).toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
