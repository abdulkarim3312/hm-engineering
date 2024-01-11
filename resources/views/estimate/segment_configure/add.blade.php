@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Segment Configure
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Segment Configure Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('segment_configure.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('costing_segment') ? 'has-error' :'' }}">
                                    <label>Costing Segment</label>

                                    <select class="form-control select2" style="width: 100%;" name="costing_segment" data-placeholder="Select Estimating Segment">
                                        <option value="">Select Costing Segment</option>

                                        @foreach($costingSegments as $costingSegment)
                                            <option value="{{ $costingSegment->id }}" {{ old('costing_segment') == $costingSegment->id ? 'selected' : '' }}>{{ $costingSegment->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('costing_segment')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('segment_unit_type') ? 'has-error' :'' }}">
                                    <label>Segment Unit Type</label>

                                    <select class="form-control select2" style="width: 100%;" name="segment_unit_type" data-placeholder="Select Segment Unit Type">
                                        <option value="">Select Segment Unit Type</option>
                                        <option value="1" {{ old('segment_unit_type') ? 'selected' : '' }}>Meter/CM</option>
                                        <option value="2" {{ old('segment_unit_type') ? 'selected' : '' }}>Feet/Inch</option>
                                    </select>

                                    @error('segment_unit_type')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('segment_height') ? 'has-error' :'' }}">
                                    <label>Segment Minimum Height</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="segment_height"
                                               name="segment_height" value="{{ old('segment_height') }}" placeholder="Segment Quantity">
                                    </div>
                                    <!-- /.input group -->

                                    @error('segment_height')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('segment_width') ? 'has-error' :'' }}">
                                    <label>Segment Minimum Width</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="segment_width"
                                               name="segment_width" value="{{ old('segment_width') }}" placeholder="Segment Width">
                                    </div>
                                    <!-- /.input group -->

                                    @error('segment_width')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('segment_length') ? 'has-error' :'' }}">
                                    <label>Segment Minimum Length</label>

                                    <div class="form-group">
                                        <input type="number" class="form-control" id="segment_length"
                                               name="segment_length" value="{{ old('segment_length') }}" placeholder="Segment Length">
                                    </div>
                                    <!-- /.input group -->

                                    @error('segment_length')
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

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Estimate Product Name</th>
                                    <th width="15%">Unit</th>
                                    <th width="15%">Minimum Quantity</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach(old('product') as $item)
                                        <tr class="product-item">
                                            <td>
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control select2 product" style="width: 100%;" name="product[]" data-placeholder="Select Product" required>
                                                        <option value="">Select Product</option>
                                                            @foreach($estimateProducts as $estimateProduct)
                                                                <option value="{{ $estimateProduct->id }}" {{ old('product.'.$loop->parent->index) == $estimateProduct->id ? 'selected' : '' }}>{{ $estimateProduct->name }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('unit.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  name="unit[]" class="form-control unit" value="{{ old('unit.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('minimum_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control minimum_quantity" name="minimum_quantity[]" value="{{ old('minimum_quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product" style="width: 100%;" name="product[]" required>
                                                    <option value="">Select Product</option>

                                                    @foreach($estimateProducts as $estimateProduct)
                                                        <option value="{{ $estimateProduct->id }}">{{ $estimateProduct->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="unit[]" class="form-control unit" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control minimum_quantity" name="minimum_quantity[]">
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
                                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add More</a>
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

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product" style="width: 100%;" name="product[]" required>
                        <option value="">Select Product</option>

                        @foreach($estimateProducts as $estimateProduct)
                            <option value="{{ $estimateProduct->id }}">{{ $estimateProduct->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" name="unit[]" class="form-control unit" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control minimum_quantity" name="minimum_quantity[]">
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

            $('body').on('change','.product', function () {
                var productID = $(this).val();
                var itemproductID = $(this);
                //var selected = itemproductID.closest('tr').find('.unit').attr("data-selected-unit");

                if (productID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_estimate_product_unit') }}",
                        data: { productID: productID }
                    }).done(function( data ) {
                        itemproductID.closest('tr').find('.unit').val(data.name);
                    });
                }

            })

            $('body').on('keyup', '.quantity', function () {
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
            var total = 0;

            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit-price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;
                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html('৳ ' + parseFloat(quantity * unit_price).toFixed(2));
                total += parseFloat(quantity * unit_price);
            });

            $('#total-amount').html('৳ ' + total.toFixed(2));
        }

        function initProduct() {
            $('.product').select2();
            {{--$('.product').select2({--}}
            {{--    ajax: {--}}
            {{--        url: "{{ route('estimate_product.json') }}",--}}
            {{--        type: "get",--}}
            {{--        dataType: 'json',--}}
            {{--        delay: 250,--}}
            {{--        data: function (params) {--}}
            {{--            return {--}}
            {{--                searchTerm: params.term // search term--}}
            {{--            };--}}
            {{--        },--}}
            {{--        processResults: function (response) {--}}
            {{--            return {--}}
            {{--                results: response--}}
            {{--            };--}}
            {{--        },--}}
            {{--        cache: true--}}
            {{--    }--}}
            {{--});--}}

            {{--$('.product').on('select2:select', function (e) {--}}
            {{--    var data = e.params.data;--}}

            {{--    var index = $(".product").index(this);--}}
            {{--    $('.product-name:eq('+index+')').val(data.text);--}}
            {{--    $('.unit-price:eq('+index+')').val(data.unit_price);--}}
            {{--});--}}
        }
    </script>
@endsection
